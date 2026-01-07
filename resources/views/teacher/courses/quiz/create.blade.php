@extends('layouts.teacher')

@section('content')
<h3>➕ Tạo bài kiểm tra</h3>

<form method="POST"
      action="{{ route('teacher.courses.quiz.store', $course) }}">
    @csrf

    <div class="mb-3">
        <label>Tên bài kiểm tra</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Thời gian (phút)</label>
        <input type="number" name="duration" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Điểm đạt</label>
        <input type="number" name="pass_score" class="form-control" required>
    </div>

    <button class="btn btn-success">Lưu</button>
</form>
@endsection
