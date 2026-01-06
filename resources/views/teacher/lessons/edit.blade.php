@extends('layouts.teacher')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Sửa bài học</h3>

    <form method="POST"
          action="{{ route('teacher.lessons.update', [$course, $lesson]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- TIÊU ĐỀ --}}
        <div class="mb-3">
            <label class="form-label">Tiêu đề bài học</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title', $lesson->title) }}"
                   required>
        </div>

        {{-- MÔ TẢ --}}
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description"
                      class="form-control"
                      rows="4">{{ old('description', $lesson->description) }}</textarea>
        </div>

        {{-- YOUTUBE --}}
        <div class="mb-3">
            <label class="form-label">YouTube ID</label>
            <input type="text"
                   name="youtube_id"
                   class="form-control"
                   value="{{ old('youtube_id', $lesson->youtube_id) }}">
            <small class="text-muted">VD: dQw4w9WgXcQ</small>
        </div>

        {{-- PDF --}}
        <div class="mb-3">
            <label class="form-label">Tài liệu PDF</label>
            <input type="file" name="pdf" class="form-control">

            @if($lesson->pdf_path)
                <p class="mt-2">
                    📄 File hiện tại:
                    <a href="{{ asset('storage/'.$lesson->pdf_path) }}"
                       target="_blank">
                        Xem PDF
                    </a>
                </p>
            @endif
        </div>

        {{-- BUTTON --}}
        <button class="btn btn-primary">
            💾 Lưu bài học
        </button>

        <a href="{{ route('teacher.courses.edit', $course) }}"
           class="btn btn-secondary ms-2">
            ← Quay lại
        </a>
    </form>
</div>
@endsection
