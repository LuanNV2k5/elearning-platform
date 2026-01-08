<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLessonController extends Controller
{
    /**
     * Hiển thị bài học + tiến độ hiện tại
     * (PHẦN D)
     */
    public function show(Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        // 1️⃣ Kiểm tra đã đăng ký khóa học
        if (!$user->enrolledCourses->contains($course->id)) {
            abort(403, 'Bạn chưa đăng ký khóa học này');
        }

        // 2️⃣ Đảm bảo lesson thuộc course
        $lesson = $course->lessons()
            ->where('lessons.id', $lesson->id)
            ->firstOrFail();

        // 3️⃣ Tạo bản ghi lesson_user nếu chưa có (bắt đầu học)
        $lesson->students()->syncWithoutDetaching([
            $user->id => [
                'progress'  => 100,
                'completed' => true,
            ]
        ]);

        // 4️⃣ Lấy progress hiện tại
        $progress = $user->lessonProgress($lesson);

        return view('student.lessons.show', compact(
            'course',
            'lesson',
            'progress'
        ));
    }

    /**
     * Cập nhật tiến độ bài học (%)
     * (PHẦN C)
     */
    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        // Kiểm tra lesson thuộc course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $progress = min(100, max(0, (int) $request->progress));

        $lesson->students()->updateExistingPivot($user->id, [
            'progress'  => $progress,
            'completed' => $progress === 100,
        ]);

        return response()->json([
            'success'  => true,
            'progress' => $progress,
        ]);
    }

    /**
     * Đánh dấu hoàn thành bài học (nút bấm)
     * (PHẦN C)
     */
    public function complete(Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->students()->updateExistingPivot($user->id, [
            'progress'  => 100,
            'completed' => true,
        ]);

        return back()->with('success', '✅ Đã hoàn thành bài học');
    }
}
