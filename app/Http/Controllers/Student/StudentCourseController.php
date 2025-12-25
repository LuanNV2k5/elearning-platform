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
        $lessons = $course->lessons()->orderBy('order')->get();

        return view('student.courses.show', compact('course', 'lessons'));
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
}
