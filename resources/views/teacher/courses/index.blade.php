<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khóa học của tôi</title>
</head>
<body>

<h1>Danh sách khóa học</h1>

<a href="{{ route('teacher.courses.create') }}">
    ➕ Tạo khóa học mới
</a>

<hr>

@if ($courses->isEmpty())
    <p>Chưa có khóa học nào.</p>
@else
    <ul>
        @foreach ($courses as $course)
            <li>
                <strong>{{ $course->title }}</strong>
                @if($course->price > 0)
                    - {{ number_format($course->price) }} VNĐ
                @else
                    - Miễn phí
                @endif
            </li>
        @endforeach
    </ul>
@endif

</body>
</html>
