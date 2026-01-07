<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>E-Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container vh-100 d-flex align-items-center">
    <div class="row w-100">
        <div class="col-md-6">
            <h1 class="fw-bold">Nền tảng học trực tuyến thông minh</h1>
            <p class="mt-3">
                Cá nhân hóa khóa học bằng AI, học mọi lúc mọi nơi.
            </p>

            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary me-2">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    Đăng ký
                </a>
            </div>
        </div>

        <div class="col-md-6 text-center">
            <img src="https://illustrations.popsy.co/gray/student-learning.svg"
                 class="img-fluid"
                 alt="E-learning">
        </div>
    </div>
</div>

</body>
</html>
