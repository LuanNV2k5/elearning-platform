<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Teacher\LessonController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\Student\StudentLessonController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\DashboardController;

/*
|-------------------------------------------------------------------------- 
| DASHBOARD (redirect theo role)
|-------------------------------------------------------------------------- 
*/
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        default   => redirect()->route('student.dashboard'),
    };
})->middleware('auth')->name('dashboard');

/*
|-------------------------------------------------------------------------- 
| ROOT
|-------------------------------------------------------------------------- 
*/
Route::get('/', fn () => redirect()->route('dashboard'));

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

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Khóa học đã đăng ký
        Route::get('/courses', [StudentCourseController::class, 'index'])
            ->name('courses.index');

        // Xem chi tiết khóa học
        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->name('courses.show');

        // Khám phá khóa học (CHÍNH LÀ CÁI BỊ THIẾU)
        Route::get('/explore', [StudentCourseController::class, 'explore'])
            ->name('explore');

        // Đăng ký khóa học
        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
            ->name('courses.enroll');

        // Xem bài học
        Route::get('/courses/{course}/lessons/{lesson}',
            [StudentLessonController::class, 'show'])
            ->name('lessons.show');
    });


/*
|-------------------------------------------------------------------------- 
| GOOGLE LOGIN
|-------------------------------------------------------------------------- 
*/
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

require __DIR__.'/auth.php';
