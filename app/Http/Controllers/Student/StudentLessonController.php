<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;

class StudentLessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        // ✅ KIỂM TRA ĐÃ ĐĂNG KÝ KHOÁ HỌC CHƯA
        if (!$user->enrolledCourses->contains($course->id)) {
            abort(403, 'Bạn chưa đăng ký khóa học này');
        }

        // ✅ ĐẢM BẢO LESSON THUỘC COURSE (FIX LỖI PDF KHÔNG HIỆN)
        $lesson = $course->lessons()
            ->where('lessons.id', $lesson->id)
            ->firstOrFail();

        // ✅ ĐÁNH DẤU BÀI HỌC ĐÃ HOÀN THÀNH (CHỈ CẦN CLICK)
        $user->completedLessons()->syncWithoutDetaching([
            $lesson->id => [
                'completed'    => true,
                'completed_at' => now(),
            ]
        ]);

        // ✅ TÍNH PROGRESS CHUNG KHOÁ HỌC
        $lessonIds = $course->lessons()->pluck('id');

        $totalLessons = $lessonIds->count();

        $completedLessons = $user->completedLessons()
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        $progress = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        // ✅ CẬP NHẬT PROGRESS VÀO course_user
        $user->enrolledCourses()
            ->updateExistingPivot($course->id, [
                'progress' => $progress
            ]);

        return view('student.lessons.show', compact('course', 'lesson'));
    }

    // ⚠️ KHÔNG BẮT BUỘC DÙNG NỮA (GIỮ LẠI CŨNG KHÔNG SAO)
    public function complete(Lesson $lesson)
    {
        $user = auth()->user();

        $user->completedLessons()->syncWithoutDetaching([
            $lesson->id => [
                'completed' => true,
                'completed_at' => now(),
            ]
        ]);

        return back()->with('success', 'Đã hoàn thành bài học');
    }
}
