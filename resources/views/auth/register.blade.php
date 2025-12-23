<x-guest-layout>
    {{-- Google button CSS --}}
    <link rel="stylesheet" href="{{ asset('css/google-btn.css') }}">

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h3 class="text-center mb-4 text-secondary">Đăng ký tài khoản</h3>

        {{-- NAME --}}
        <div class="mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input
                id="name"
                class="form-control @error('name') is-invalid @enderror"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Nhập họ tên của bạn"
            />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                class="form-control @error('email') is-invalid @enderror"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="name@example.com"
            />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ROLE --}}
        <div class="mb-3">
            <label class="form-label">Bạn là:</label>

            <div class="d-flex gap-4 mt-1">
                <div class="form-check">
                    <input class="form-check-input"
                           type="radio"
                           name="role"
                           id="role_student"
                           value="student"
                           {{ old('role') == 'student' ? 'checked' : '' }}
                           required>
                    <label class="form-check-label" for="role_student">
                        Học sinh
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                           type="radio"
                           name="role"
                           id="role_teacher"
                           value="teacher"
                           {{ old('role') == 'teacher' ? 'checked' : '' }}>
                    <label class="form-check-label" for="role_teacher">
                        Giáo viên
                    </label>
                </div>
            </div>

            @error('role')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
            <input
                id="password_confirmation"
                class="form-control"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-between align-items-center">
            <a class="text-decoration-none small" href="{{ route('login') }}">
                Đã có tài khoản?
            </a>

            <button type="submit" class="btn btn-primary px-4">
                Đăng ký
            </button>
        </div>
    </form>

    {{-- DIVIDER --}}
    <div class="position-relative my-4 text-center">
        <hr class="text-secondary opacity-25">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">
            Hoặc tiếp tục với
        </span>
    </div>

    {{-- GOOGLE REGISTER --}}
    <a href="{{ route('google.login') }}" class="google-btn-blue">
        <div class="google-icon-wrapper">
            <img class="google-icon"
                 src="https://developers.google.com/identity/images/g-logo.png"
                 alt="Google logo"/>
        </div>
        <span class="btn-text">Sign up with Google</span>
    </a>

</x-guest-layout>
