<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo khóa học</title>
</head>
<body>

<h1>Tạo khóa học mới</h1>

<form method="POST" action="{{ route('teacher.courses.store') }}">
    @csrf

    <div>
        <label>Tên khóa học</label><br>
        <input type="text" name="title" value="{{ old('title') }}">
        @error('title')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <br>

    <div>
        <label>Mô tả</label><br>
        <textarea name="description">{{ old('description') }}</textarea>
    </div>

    <br>

    <div>
        <label>Giá (VNĐ)</label><br>
        <input type="number" name="price" value="{{ old('price', 0) }}">
    </div>

    <br>

    <button type="submit">Lưu khóa học</button>
</form>

</body>
</html>
