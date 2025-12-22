<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/google-btn.css') }}">

    <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email') }}" 
                required 
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
            <label class="form-check-label text-secondary" for="remember_me">
                Ghi nhớ đăng nhập
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a class="text-decoration-none small" href="{{ route('register') }}">
                Chưa có tài khoản?
            </a>

            <button type="submit" class="btn btn-primary">
                Đăng nhập
            </button>
        </div>

        <div class="position-relative my-4 text-center">
            <hr class="text-secondary opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">
                Hoặc tiếp tục với
            </span>
        </div>

        <a href="{{ route('google.login') }}" class="google-btn-blue">
            <div class="google-icon-wrapper">
                <img class="google-icon" src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo"/>
            </div>
            <span class="btn-text">Sign in with Google</span>
        </a>

    </form>
</x-guest-layout>