@extends('layouts.teacher')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Chỉnh sửa khóa học</h3>

    {{-- THÔNG BÁO THÀNH CÔNG --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= FORM EDIT COURSE ================= --}}
    <form method="POST"
          action="{{ route('teacher.courses.update', $course) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- TÊN KHÓA HỌC --}}
        <div class="mb-3">
            <label class="form-label">Tên khóa học</label>
            <input type="text"
                   name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $course->title) }}"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- MÔ TẢ --}}
        <div class="mb-3">
            <label class="form-label">Mô tả khóa học</label>
            <textarea name="description"
                      rows="4"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện (thumbnail)</label>
            <input type="file"
                   name="thumbnail"
                   class="form-control @error('thumbnail') is-invalid @enderror">

            @error('thumbnail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if($course->thumbnail)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                         alt="Thumbnail"
                         style="max-height: 120px;">
                </div>
            @endif
        </div>

        {{-- TRẠNG THÁI --}}
        <div class="mb-4">
            <label class="form-label">Trạng thái</label>
            <select name="status"
                    class="form-select @error('status') is-invalid @enderror">
                <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>
                    Bản nháp
                </option>
                <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>
                    Công khai
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- BUTTON --}}
        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-primary">
                💾 Lưu thay đổi
            </button>

            <a href="{{ route('teacher.courses.index') }}"
               class="btn btn-secondary">
                ← Quay lại
            </a>
        </div>
    </form>

    {{-- ================= DANH SÁCH BÀI HỌC ================= --}}
    <hr>
    <h4 class="mb-3">📚 Danh sách bài học</h4>

    @if($course->lessons->count())
        <ul class="list-group">
            @foreach($course->lessons as $lesson)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $lesson->title }}</strong>

                        @if($lesson->pdf_path)
                            <span class="badge bg-info ms-2">PDF</span>
                        @endif

                        @if($lesson->youtube_id)
                            <span class="badge bg-danger ms-1">YouTube</span>
                        @endif
                    </div>

                    <a href="{{ route('teacher.lessons.edit', [$course, $lesson]) }}"
                       class="btn btn-sm btn-outline-primary">
                        ✏️ Sửa bài học
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">Chưa có bài học nào trong khóa học này.</p>
    @endif
</div>
@endsection
