@extends('layouts.teacher')

@section('content')
<h3>➕ Thêm bài học cho: {{ $course->title }}</h3>

<form action="{{ route('teacher.courses.lessons.store', $course) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Tiêu đề bài học</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Thứ tự bài</label>
        <input type="number" name="order" class="form-control" value="1">
    </div>

    <div class="mb-3">
        <label class="form-label">Link video (YouTube)</label>
        <input type="url" name="video_url" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">File PDF</label>
        <input type="file" name="pdf" class="form-control">
    </div>

    <button class="btn btn-success">💾 Lưu bài học</button>
    <a href="{{ route('teacher.courses.lessons.index', $course) }}"
       class="btn btn-secondary">⬅ Quay lại</a>
</form>
@endsection
