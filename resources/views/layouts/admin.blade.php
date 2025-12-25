<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        {{-- SIDEBAR --}}
        <div class="col-2 bg-primary text-white p-0">
            <x-sidebar.admin />
        </div>

        {{-- CONTENT --}}
        <div class="col-10 p-4">
            @yield('content')
        </div>

    </div>
</div>

</body>
</html>
