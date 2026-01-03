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

/*
|--------------------------------------------------------------------------
| ROOT – REDIRECT THEO ROLE (CHỈ 1 NƠI DUY NHẤT)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user()->load('role');

    return match ($user->role->name) {
        'admin'   => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->_registration_or_route('student.dashboard'),
    };

})->middleware('auth');

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

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboard::class, 'index'])
            ->name('dashboard');

        Route::resource('courses', CourseController::class);

        Route::resource('courses.lessons', LessonController::class)
            ->except(['show']);
    });

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
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
    });

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
