<div class="p-3">
    <h5 class="mb-4">👑 ADMIN</h5>

    <div class="list-group list-group-flush">

        <a href="{{ route('admin.dashboard') }}"
           class="list-group-item bg-primary text-white">
            🏠 Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="list-group-item bg-primary text-white">
            👥 Quản lý người dùng
        </a>

        <a href="{{ route('admin.courses.index') }}"
           class="list-group-item bg-primary text-white">
            📚 Quản lý khóa học
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="list-group-item bg-primary text-white border-0 text-start">
                🚪 Đăng xuất
            </button>
        </form>

    </div>
</div>
