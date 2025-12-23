<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label class="form-label fw-bold">Bạn là</label>
            
            <div class="d-flex gap-4 mt-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_student" value="student" 
                        {{ old('role') == 'student' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="role_student">
                        Học sinh
                    </label>
                </div>
        
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_teacher" value="teacher" 
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

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Mật khẩu" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Nhập lại mật khẩu" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
            />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                Đã có tài khoản?
            </a>

            <x-primary-button>
                Đăng ký
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
