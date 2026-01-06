@extends('layouts.teacher')

@section('content')
    <h3>🧪 Bài kiểm tra của khóa học</h3>

    @if(!$quiz)
        <p>Khóa học chưa có bài kiểm tra.</p>

        <a href="{{ route('teacher.courses.quiz.create', $course) }}"
           class="btn btn-primary">
            ➕ Tạo bài kiểm tra
        </a>
    @else
        <p><strong>Tên:</strong> {{ $quiz->title }}</p>
        <p><strong>Thời gian:</strong> {{ $quiz->duration }} phút</p>
        <p><strong>Điểm đạt:</strong> {{ $quiz->pass_score }}</p>

        <a href="{{ route('teacher.courses.quiz.edit', $course) }}"
           class="btn btn-warning">
            ✏️ Chỉnh sửa
        </a>
        <a href="{{ route('teacher.courses.quiz.questions.index', $course) }}"
   class="btn btn-info">
    ❓ Quản lý câu hỏi
</a>

    @endif
@endsection
