<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentLessonController extends Controller
{
    /**
     * Ghi hành vi user vào user_events (chống spam trong X phút)
     */
    private function logEvent(
        string $eventType,
        ?int $courseId = null,
        ?int $lessonId = null,
        ?int $watchSeconds = null,
        int $dedupeMinutes = 5
    ): void {
        $userId = Auth::id();
        if (!$userId) return;

        $exists = DB::table('user_events')
            ->where('user_id', $userId)
            ->where('event_type', $eventType)
            ->when($courseId !== null, fn($q) => $q->where('course_id', $courseId), fn($q) => $q->whereNull('course_id'))
            ->when($lessonId !== null, fn($q) => $q->where('lesson_id', $lessonId), fn($q) => $q->whereNull('lesson_id'))
            ->where('created_at', '>=', now()->subMinutes($dedupeMinutes))
            ->exists();

        if ($exists) return;

        DB::table('user_events')->insert([
            'user_id' => $userId,
            'event_type' => $eventType,
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'watch_seconds' => $watchSeconds,
            'created_at' => now(),
        ]);
    }

    /**
     * GET: xem bài học
     * - đảm bảo lesson thuộc course
     * - upsert lesson_user để ghi nhận "đã mở"
     * - log view_lesson
     */
    public function show(Course $course, Lesson $lesson)
    {
        if (($course->status ?? null) !== 'published') abort(404);
        if ($lesson->course_id != $course->id) abort(404);

        $userId = Auth::id();

        // ✅ upsert vào lesson_user (đánh dấu đã mở bài)
        // Nếu đã có thì chỉ update updated_at
        $existing = DB::table('lesson_user')
            ->where('user_id', $userId)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($existing) {
            DB::table('lesson_user')
                ->where('id', $existing->id)
                ->update([
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('lesson_user')->insert([
                'user_id' => $userId,
                'lesson_id' => $lesson->id,
                'progress' => 0,
                'completed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ log hành vi view_lesson
        $this->logEvent('view_lesson', $course->id, $lesson->id);

        // (tuỳ chọn) lấy lesson kế tiếp để nút Next
        $nextLesson = DB::table('lessons')
            ->where('course_id', $course->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();

        return view('student.lessons.show', compact('course', 'lesson', 'nextLesson'));
    }

    /**
     * POST: hoàn thành bài học
     * - update lesson_user.completed = 1, progress = 100
     * - log complete_lesson
     * - (tuỳ chọn) update course_user.progress
     */
    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        if (($course->status ?? null) !== 'published') abort(404);
        if ($lesson->course_id != $course->id) abort(404);

        $userId = Auth::id();

        /**
         * ===== UPDATE lesson_user =====
         */
        DB::table('lesson_user')->updateOrInsert(
            [
                'user_id' => $userId,
                'lesson_id' => $lesson->id,
            ],
            [
                'progress' => 100,
                'completed' => 1,
                'updated_at' => now(),
            ]
        );

        // log ML
        $this->logEvent('complete_lesson', $course->id, $lesson->id);

        /**
         * ===== TÍNH PROGRESS KHÓA HỌC =====
         */
        $totalLessons = DB::table('lessons')
            ->where('course_id', $course->id)
            ->count();

        $completedLessons = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->where('lesson_user.user_id', $userId)
            ->where('lessons.course_id', $course->id)
            ->where('lesson_user.completed', 1)
            ->count();

        $progress = 0;
        if ($totalLessons > 0) {
            $progress = (int) round(($completedLessons / $totalLessons) * 100);
        }

        if ($progress > 100) $progress = 100;

        /**
         * ===== UPDATE course_user.progress =====
         */
        DB::table('course_user')->updateOrInsert(
            [
                'user_id' => $userId,
                'course_id' => $course->id,
            ],
            [
                'progress' => $progress,
                'updated_at' => now(),
            ]
        );

        return redirect()
            ->route('student.lessons.show', [$course->id, $lesson->id])
            ->with('success', '✅ Đã hoàn thành bài học!');
    }
}
