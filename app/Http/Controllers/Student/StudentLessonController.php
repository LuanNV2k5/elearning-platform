<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;

class StudentLessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        if (!auth()->user()->enrolledCourses->contains($course->id)) {
            abort(403, 'Bạn chưa đăng ký khóa học này');
        }
    
        return view('student.lessons.show', compact('course', 'lesson'));
    }

}
