<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Hiển thị bài kiểm tra
    public function show(Course $course)
    {
        $quiz = $course->quiz()->with('questions.options')->first();

        if (!$quiz) {
            abort(404, 'Khóa học chưa có bài kiểm tra');
        }

        return view('student.quiz.show', compact('course', 'quiz'));
    }

    // Nộp bài
    public function submit(Request $request, Course $course)
    {
        $quiz = $course->quiz()->with('questions.options')->first();

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

        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->user()->id,
            'score' => $score,
            'status' => $score >= $quiz->pass_score ? 'passed' : 'failed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Bạn đã hoàn thành bài kiểm tra');
    }
}
