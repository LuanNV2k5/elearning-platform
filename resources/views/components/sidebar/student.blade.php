<div class="list-group list-group-flush rounded-0">

    <a href="{{ route('student.dashboard') }}"
       class="list-group-item list-group-item-action bg-dark text-white">
        🏠 Dashboard
    </a>

    <a href="{{ route('student.courses.index') }}"
       class="list-group-item list-group-item-action bg-dark text-white">
        🎓 Khóa học của tôi
    </a>

    <a href="{{ route('student.explore') }}"
       class="list-group-item list-group-item-action bg-dark text-white">
        🔍 Khám phá khóa học
    </a>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="list-group-item list-group-item-action bg-dark text-white border-0 text-start w-100">
            🚪 Đăng xuất
        </button>
    </form>

</div>
