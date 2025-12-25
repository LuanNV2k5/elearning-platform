@extends('layouts.teacher')

@section('content')
    <h3>➕ Tạo khóa học</h3>

    <form method="POST" action="{{ route('teacher.courses.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên khóa học</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="0">
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
