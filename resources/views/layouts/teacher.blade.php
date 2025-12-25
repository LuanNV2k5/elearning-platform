<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Teacher - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex">
    {{-- Sidebar --}}
    <div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh">
        <h5 class="mb-4">🎓 Teacher Panel</h5>

        <a href="{{ route('teacher.dashboard') }}" class="d-block text-white mb-2">🏠 Dashboard</a>
        <a href="{{ route('teacher.courses.index') }}" class="d-block text-white mb-2">📚 Khóa học</a>

        <hr>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm w-100">Logout</button>
        </form>
    </div>

    {{-- Content --}}
    <div class="flex-fill p-4">
        @yield('content')
    </div>
</div>

</body>
</html>
