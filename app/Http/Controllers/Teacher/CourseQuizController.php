<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CourseQuizController extends Controller
{
    public function show(Course $course)
{
    // kiểm tra quyền sở hữu
    if ($course->teacher_id !== auth()->id()) {
        abort(403);
    }

    $quiz = $course->quiz; // sẽ tạo relation ở bước sau

    return view('teacher.courses.quiz.show', compact('course', 'quiz'));
}
    public function create(Course $course)
{
    return view('teacher.courses.quiz.create', compact('course'));
}

public function store(Request $request, Course $course)
{
    $request->validate([
        'title' => 'required',
        'duration' => 'required|integer',
        'pass_score' => 'required|integer',
    ]);

    Quiz::create([
        'course_id' => $course->id,
        'title' => $request->title,
        'duration' => $request->duration,
        'pass_score' => $request->pass_score,
    ]);

    return redirect()
        ->route('teacher.courses.quiz.show', $course);
}

}
