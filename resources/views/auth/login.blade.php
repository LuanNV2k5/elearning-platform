<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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

        <!-- Remember -->
        <div class="block mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                <span class="ms-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('register') }}">
                Chưa có tài khoản?
            </a>

            <x-primary-button>
                Đăng nhập
            </x-primary-button>
        </div>
        <a href="{{ route('google.login') }}"
           style="display:block;text-align:center;margin-top:10px">
            🔐 Đăng nhập bằng Google
        </a>   
    </form>
</x-guest-layout>
