@extends('layouts.teacher')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>📚 Khóa học của tôi</h3>
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
            ➕ Tạo khóa học
        </a>
    </div>

    @if($courses->isEmpty())
        <p>Chưa có khóa học nào.</p>
    @else
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Tên khóa học</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $course->title }}</td>
                    <td>{{ number_format($course->price) }} đ</td>
                    <td>
                        <a href="{{ route('teacher.courses.lessons.index', $course) }}"
                           class="btn btn-sm btn-primary">
                            📚 Bài học
                        </a>
                    
                        <a href="{{ route('teacher.courses.edit', $course) }}"
                           class="btn btn-sm btn-warning">
                            Sửa
                        </a>
                    
                        <form action="{{ route('teacher.courses.destroy', $course) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Xóa khóa học?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
