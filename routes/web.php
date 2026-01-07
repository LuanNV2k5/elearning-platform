<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\Teacher\LessonController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\Student\StudentLessonController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\CourseController as AdminCourseController;

use App\Http\Controllers\Teacher\CourseQuizController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Student\QuizController;

use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;

/*
|--------------------------------------------------------------------------
| ROOT – LANDING PAGE (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user()->load('role');

        return match ($user->role->name ?? null) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => abort(403),
        };
    }

    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('courses', CourseController::class);
    });
/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class);
    });
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('courses', AdminCourseController::class)
            ->only(['index', 'destroy']);
    });


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
    });
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/courses', [AdminCourseController::class, 'index'])
            ->name('courses.index');

        Route::get('/courses/{course}/students', [AdminCourseController::class, 'students'])
            ->name('courses.students');
    });

/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')
    ->name('teacher.')
    ->middleware(['auth', 'role:teacher'])
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboard::class, 'index'])
            ->name('dashboard');

        Route::resource('courses', CourseController::class);

        Route::resource('courses.lessons', LessonController::class)
            ->except(['show']);
            Route::get('courses/{course}/quiz', [CourseQuizController::class, 'show'])
    ->name('courses.quiz.show');

        Route::get('courses/{course}/quiz/create', [CourseQuizController::class, 'create'])
            ->name('courses.quiz.create');

        Route::post('courses/{course}/quiz', [CourseQuizController::class, 'store'])
            ->name('courses.quiz.store');

        Route::get('courses/{course}/quiz/edit', [CourseQuizController::class, 'edit'])
            ->name('courses.quiz.edit');

        Route::put('courses/{course}/quiz', [CourseQuizController::class, 'update'])
            ->name('courses.quiz.update');
        Route::get('courses/{course}/quiz/questions', [QuestionController::class, 'index'])
    ->name('courses.quiz.questions.index');

Route::get('courses/{course}/quiz/questions/create', [QuestionController::class, 'create'])
    ->name('courses.quiz.questions.create');

Route::post('courses/{course}/quiz/questions', [QuestionController::class, 'store'])
    ->name('courses.quiz.questions.store');


    });
Route::get('/dashboard', function () {
    return match (auth()->user()->role_id) {
        1 => redirect()->route('admin.dashboard'),
        2 => redirect()->route('teacher.dashboard'),
        3 => redirect()->route('student.dashboard'),
        default => abort(403),
    };
})->middleware('auth')->name('dashboard');
Route::put(
    '/teacher/courses/{course}',
    [CourseController::class, 'update']
)->name('teacher.courses.update');
Route::prefix('teacher')->name('teacher.')->middleware('auth')->group(function () {
    Route::get(
        '/courses/{course}/lessons/{lesson}/edit',
        [TeacherLessonController::class, 'edit']
    )->name('lessons.edit');

    Route::put(
        '/courses/{course}/lessons/{lesson}',
        [TeacherLessonController::class, 'update']
    )->name('lessons.update');
});
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // ✏️ SỬA BÀI HỌC
        Route::get(
            '/courses/{course}/lessons/{lesson}/edit',
            [TeacherLessonController::class, 'edit']
        )->name('lessons.edit');

        // 💾 CẬP NHẬT BÀI HỌC
        Route::put(
            '/courses/{course}/lessons/{lesson}',
            [TeacherLessonController::class, 'update']
        )->name('lessons.update');
    });
/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:student'])
    ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/courses', [StudentCourseController::class, 'index'])
            ->name('courses.index');

        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->name('courses.show');

        Route::get('/explore', [StudentCourseController::class, 'explore'])
            ->name('explore');

        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
            ->name('courses.enroll');

        Route::get(
            '/courses/{course}/lessons/{lesson}',
            [StudentLessonController::class, 'show']
        )->name('lessons.show');
        Route::get('courses/{course}/quiz', [QuizController::class, 'show'])
    ->name('courses.quiz.show');

Route::post('courses/{course}/quiz/submit', [QuizController::class, 'submit'])
    ->name('courses.quiz.submit');

    });

Route::post(
    '/lessons/{lesson}/complete',
    [StudentLessonController::class, 'complete']
)->name('lessons.complete');

Route::post(
    '/student/courses/{course}/complete',
    [StudentCourseController::class, 'complete']
)->name('student.courses.complete');

/*
|--------------------------------------------------------------------------
| GOOGLE LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.login');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
});

require __DIR__ . '/auth.php';
