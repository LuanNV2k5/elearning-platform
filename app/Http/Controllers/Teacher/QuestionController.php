<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Danh sách câu hỏi
    public function index(Course $course)
    {
        $quiz = $course->quiz;

        $questions = $quiz->questions()->with('options')->get();

        return view('teacher.courses.quiz.questions.index', compact(
            'course', 'quiz', 'questions'
        ));
    }

    // Form tạo câu hỏi
    public function create(Course $course)
    {
        $quiz = $course->quiz;

        return view('teacher.courses.quiz.questions.create', compact(
            'course', 'quiz'
        ));
    }

    // Lưu câu hỏi + đáp án
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'content' => 'required',
            'score' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'correct_option' => 'required'
        ]);

        $quiz = $course->quiz;

        // tạo câu hỏi
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'content' => $request->input('content'),
            'score' => $request->score,
            'order' => $quiz->questions()->count() + 1,
        ]);

        // tạo đáp án
        foreach ($request->options as $index => $content) {
            Option::create([
                'question_id' => $question->id,
                'content' => $content,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        return redirect()
            ->route('teacher.courses.quiz.questions.index', $course);
    }
}
