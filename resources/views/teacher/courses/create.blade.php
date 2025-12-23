<x-app-layout>
    <div class="container">
        <h3 class="mb-4">Tạo khóa học</h3>

        <form method="POST" action="{{ route('teacher.courses.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên khóa học</label>
                <input name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" name="price" class="form-control" value="0">
            </div>

            <button class="btn btn-primary">Tạo khóa học</button>
        </form>
    </div>
</x-app-layout>
