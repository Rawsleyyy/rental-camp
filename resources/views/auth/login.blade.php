<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-serif text-2xl font-bold text-neutral-950">Masuk ke Akun Rawlseyyy</h1>
        <p class="text-xs text-neutral-500 mt-1">Masuk untuk mulai menyewa alat camping pilihan Anda di Rawlseyyy.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-neutral-300 text-neutral-950 shadow-sm focus:ring-amber-400" name="remember">
                <span class="ms-2 text-sm text-neutral-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-neutral-100">
            @if (Route::has('password.request'))
                <a class="text-xs font-medium text-neutral-500 hover:text-amber-600 underline transition rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-xs text-neutral-500 pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-neutral-950 hover:text-amber-600 underline transition">Daftar sekarang</a>
            </p>
        @endif
    </form>

    <!-- Akun Demo Rawlseyyy -->
    <div class="mt-6 pt-5 border-t border-neutral-200/80">
        <div class="rounded-2xl bg-neutral-50 border border-neutral-200/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-neutral-500 mb-3">
                Akun Demo Rawlseyyy (Klik untuk isi otomatis)
            </p>
            <div class="space-y-2 text-xs">
                <button
                    type="button"
                    onclick="fillDemoAccount('admin@demo.test', 'Admin#2026')"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white border border-neutral-200/80 hover:border-amber-400 hover:bg-amber-50/40 transition text-left"
                >
                    <span class="font-semibold text-neutral-900">Admin</span>
                    <span class="font-mono text-[11px] text-neutral-600">admin@demo.test / Admin#2026</span>
                </button>
                <button
                    type="button"
                    onclick="fillDemoAccount('staff@demo.test', 'Staff#2026')"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white border border-neutral-200/80 hover:border-amber-400 hover:bg-amber-50/40 transition text-left"
                >
                    <span class="font-semibold text-neutral-900">Staff</span>
                    <span class="font-mono text-[11px] text-neutral-600">staff@demo.test / Staff#2026</span>
                </button>
                <button
                    type="button"
                    onclick="fillDemoAccount('user@demo.test', 'User#2026')"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white border border-neutral-200/80 hover:border-amber-400 hover:bg-amber-50/40 transition text-left"
                >
                    <span class="font-semibold text-neutral-900">User</span>
                    <span class="font-mono text-[11px] text-neutral-600">user@demo.test / User#2026</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function fillDemoAccount(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                emailInput.focus();
            }
        }
    </script>
</x-guest-layout>
