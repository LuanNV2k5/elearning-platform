@extends('layouts.teacher')

@section('content')
<h3>➕ Thêm câu hỏi</h3>

<form method="POST"
      action="{{ route('teacher.courses.quiz.questions.store', $course) }}">
    @csrf

    <div class="mb-3">
        <label>Câu hỏi</label>
        <textarea name="content" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Điểm</label>
        <input type="number" name="score" class="form-control" value="1" required>
    </div>

    <hr>

    <h5>Đáp án</h5>

    @for($i = 0; $i < 4; $i++)
        <div class="mb-2 d-flex align-items-center">
            <input type="radio" name="correct_option" value="{{ $i }}" required>
            <input type="text"
                   name="options[]"
                   class="form-control ms-2"
                   placeholder="Đáp án {{ $i + 1 }}"
                   required>
        </div>
    @endfor

    <button class="btn btn-primary mt-3">Lưu câu hỏi</button>
</form>
@endsection
