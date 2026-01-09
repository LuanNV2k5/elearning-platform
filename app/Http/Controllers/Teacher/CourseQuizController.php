<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CourseQuizController extends Controller
{
    /**
     * GET /teacher/courses/{course}/quiz
     * Hiển thị quiz (nếu có)
     */
    public function show(Course $course)
    {
        // kiểm tra quyền sở hữu course
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz = Quiz::where('course_id', $course->id)->first();

        return view('teacher.courses.quiz.show', compact('course', 'quiz'));
    }

    /**
     * GET /teacher/courses/{course}/quiz/create
     * Form tạo quiz (nếu đã có quiz thì chuyển sang edit)
     */
    public function create(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz = Quiz::where('course_id', $course->id)->first();
        if ($quiz) {
            return redirect()->route('teacher.courses.quiz.edit', $course);
        }

        return view('teacher.courses.quiz.create', compact('course'));
    }

    /**
     * POST /teacher/courses/{course}/quiz
     * Lưu quiz mới
     */
    public function store(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        // chống tạo trùng quiz cho 1 course
        $exists = Quiz::where('course_id', $course->id)->exists();
        if ($exists) {
            return redirect()
                ->route('teacher.courses.quiz.edit', $course)
                ->with('error', 'Khóa học này đã có bài kiểm tra. Vui lòng chỉnh sửa.');
        }

        Quiz::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'duration' => $request->duration,
            'pass_score' => $request->pass_score,
        ]);

        return redirect()
            ->route('teacher.courses.quiz.show', $course)
            ->with('success', 'Tạo bài kiểm tra thành công!');
    }

    /**
     * GET /teacher/courses/{course}/quiz/edit
     * Form sửa quiz
     */
    public function edit(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz = Quiz::where('course_id', $course->id)->first();

        if (!$quiz) {
            // chưa có quiz thì chuyển sang create
            return redirect()
                ->route('teacher.courses.quiz.create', $course)
                ->with('error', 'Khóa học chưa có bài kiểm tra. Vui lòng tạo mới.');
        }

        return view('teacher.courses.quiz.edit', compact('course', 'quiz'));
    }

    /**
     * PUT/PATCH /teacher/courses/{course}/quiz
     * Cập nhật quiz
     */
    public function update(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz = Quiz::where('course_id', $course->id)->first();
        if (!$quiz) {
            return redirect()
                ->route('teacher.courses.quiz.create', $course)
                ->with('error', 'Không tìm thấy bài kiểm tra để cập nhật.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'pass_score' => $request->pass_score,
        ]);

        return redirect()
            ->route('teacher.courses.quiz.show', $course)
            ->with('success', 'Cập nhật bài kiểm tra thành công!');
    }
}
