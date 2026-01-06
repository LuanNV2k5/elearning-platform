<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->enrolledCourses;

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
         $user = auth()->user();

    // 👉 THÊM DÒNG NÀY
    $lessons = $course->lessons()->orderBy('order')->get();

    $progress = $user->enrolledCourses()
        ->where('course_id', $course->id)
        ->first()
        ->pivot
        ->progress ?? 0;

    // 👉 THÊM 'lessons' VÀO VIEW
    return view('student.courses.show', compact(
        'course',
        'lessons',
        'progress'
    ));
    }

    public function explore()
    {
        $courses = Course::all();

        return view('student.courses.explore', compact('courses'));
    }

    public function enroll(Course $course)
    {
        Auth::user()
            ->enrolledCourses()
            ->syncWithoutDetaching($course->id);

        return redirect()
            ->route('student.courses.index')
            ->with('success', 'Đăng ký khóa học thành công');
    }
    public function complete(Course $course)
{
    auth()->user()
        ->enrolledCourses()
        ->updateExistingPivot($course->id, [
            'progress' => 100
        ]);

    return redirect()
        ->route('student.courses.show', $course)
        ->with('success', '🎉 Bạn đã hoàn thành khoá học!');
}

}
