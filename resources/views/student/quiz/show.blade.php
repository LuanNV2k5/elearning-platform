@extends('layouts.student')

@section('content')
<h3>🧪 Bài kiểm tra: {{ $quiz->title }}</h3>

<form method="POST"
      action="{{ route('student.courses.quiz.submit', $course) }}">
    @csrf

    @foreach($quiz->questions as $index => $question)
        <div class="mb-4">
            <strong>
                Câu {{ $index + 1 }}:
                {{ $question->content }}
                ({{ $question->score }} điểm)
            </strong>

            @foreach($question->options as $option)
                <div class="form-check">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $option->id }}"
                           class="form-check-input"
                           required>
                    <label class="form-check-label">
                        {{ $option->content }}
                    </label>
                </div>
            @endforeach
        </div>
    @endforeach

    <button class="btn btn-success">
        📝 Nộp bài
    </button>
</form>
@endsection
