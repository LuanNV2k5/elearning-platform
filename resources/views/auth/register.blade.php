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
        <div class="mt-4">
            <x-input-label for="role" value="Bạn là" />
            <select
                name="role"
                id="role"
                class="block mt-1 w-full rounded border-gray-300"
                required
            >
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>
                    Học sinh
                </option>
                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>
                    Giáo viên
                </option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
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
