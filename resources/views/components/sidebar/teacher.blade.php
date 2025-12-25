<h5 class="mb-3">Teacher</h5>

<ul class="nav flex-column gap-2">
    <li>
        <a href="{{ route('teacher.dashboard') }}" class="text-white text-decoration-none">
            Dashboard
        </a>
    </li>

    <li>
        <a href="{{ route('teacher.courses.index') }}" class="text-white text-decoration-none">
            Khóa học
        </a>
    </li>

    <li class="mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-danger w-100">Logout</button>
        </form>
    </li>
</ul>
