@extends('layouts.teacher')

@section('content')
<div class="container">
    <h3 class="mb-3">✏️ Chỉnh sửa Quiz - {{ $course->title }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <b>Lỗi nhập liệu:</b>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.courses.quiz.update', $course) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tiêu đề bài kiểm tra</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $quiz->title ?? '') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Thời lượng (phút)</label>
                    <input type="number"
                           name="duration"
                           class="form-control"
                           value="{{ old('duration', $quiz->duration ?? 10) }}"
                           min="1"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Điểm đạt (%)</label>
                    <input type="number"
                           name="pass_score"
                           class="form-control"
                           value="{{ old('pass_score', $quiz->pass_score ?? 50) }}"
                           min="0"
                           max="100"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        💾 Lưu thay đổi
                    </button>

                    <a href="{{ route('teacher.courses.quiz.show', $course) }}"
                       class="btn btn-secondary">
                        ↩️ Quay lại
                    </a>

                    @if(Route::has('teacher.courses.quiz.questions.index'))
                        <a href="{{ route('teacher.courses.quiz.questions.index', $course) }}"
                           class="btn btn-outline-dark">
                            📝 Câu hỏi
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
