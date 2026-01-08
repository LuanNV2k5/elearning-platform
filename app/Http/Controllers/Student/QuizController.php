<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Hiển thị bài kiểm tra
     * 👉 CHỈ CHO PHÉP khi hoàn thành 100% khóa học
     */
    public function show(Course $course)
    {
        $user = auth()->user();

        // 1️⃣ TÍNH TIẾN ĐỘ KHÓA HỌC
        $totalLessons = $course->lessons()->count();

        $completedLessons = $course->lessons()
            ->whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id)
                  ->where('completed', true);
            })
            ->count();

        $courseProgress = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        // 2️⃣ CHẶN NẾU CHƯA ĐỦ 100%
        if ($courseProgress < 100) {
            abort(403, 'Bạn phải hoàn thành 100% khóa học trước khi làm bài kiểm tra');
        }

        // 3️⃣ LẤY QUIZ
        $quiz = $course->quiz()->with('questions.options')->first();

        if (!$quiz) {
            abort(404, 'Khóa học chưa có bài kiểm tra');
        }

        return view('student.quiz.show', compact('course', 'quiz'));
    }

    /**
     * Nộp bài kiểm tra
     * 👉 ≥ 50% điểm → HOÀN THÀNH KHÓA HỌC
     */
    public function submit(Request $request, Course $course)
    {
        $user = auth()->user();

        $quiz = $course->quiz()->with('questions.options')->first();

        if (!$quiz) {
            abort(404);
        }

        // 1️⃣ TÍNH TỔNG ĐIỂM
        $score = 0;
        $totalScore = $quiz->questions->sum('score');

        foreach ($quiz->questions as $question) {
            $selected = $request->input("answers.{$question->id}");

            $correct = $question->options
                ->where('is_correct', true)
                ->first();

            if ($correct && $selected == $correct->id) {
                $score += $question->score;
            }
        }

        // 2️⃣ TÍNH % ĐIỂM
        $percent = $totalScore > 0
            ? round(($score / $totalScore) * 100)
            : 0;

        // 3️⃣ LƯU KẾT QUẢ QUIZ
        QuizAttempt::create([
            'quiz_id'      => $quiz->id,
            'user_id'      => $user->id,
            'score'        => $percent,
            'status'       => $percent >= 50 ? 'passed' : 'failed',
            'completed_at' => now(),
        ]);

        // 4️⃣ ĐẠT ≥ 50% → HOÀN THÀNH KHÓA HỌC
        if ($percent >= 50) {
            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', '🎉 Chúc mừng! Bạn đã hoàn thành khóa học');
        }

        return redirect()
            ->route('student.courses.show', $course)
            ->with('error', '❌ Bạn chưa đạt 50% để hoàn thành khóa học');
    }
}
