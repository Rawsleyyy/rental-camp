<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-ink">Masuk ke Akun Rawlseyyy</h1>
        <p class="text-xs text-ink-muted mt-1">Masuk untuk mulai menyewa alat camping pilihan Anda di Rawlseyyy.</p>
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
                <input id="remember_me" type="checkbox" class="rounded border-canvas-border text-meadow-500 shadow-sm focus:ring-meadow-500" name="remember">
                <span class="ms-2 text-sm text-ink-muted">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-canvas-border/70">
            @if (Route::has('password.request'))
                <a class="text-xs font-medium text-ink-muted hover:text-meadow-700 underline transition rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-meadow-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-xs text-ink-muted pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-meadow-700 hover:text-meadow-600 underline transition">Daftar sekarang</a>
            </p>
        @endif
    </form>

    <!-- Akun Demo Rawlseyyy -->
    <div class="mt-6 pt-5 border-t border-canvas-border">
        <div class="rounded-2xl bg-canvas-warm border border-earth-200/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-earth-600 mb-3">
                Akun Demo Rawlseyyy (Klik untuk isi otomatis)
            </p>
            <div class="space-y-2 text-xs">
                <button
                    type="button"
                    onclick="fillDemoAccount('admin@demo.test', 'Admin#2026')"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-white border border-canvas-border hover:border-meadow-500 hover:bg-meadow-50/50 transition text-left"
                >
                    <span class="px-2 py-0.5 rounded-full bg-meadow-100 text-meadow-700 font-semibold text-[11px]">Admin</span>
                    <span class="font-mono text-[11px] text-ink-muted truncate">admin@demo.test / Admin#2026</span>
                </button>
                <button
                    type="button"
                    onclick="fillDemoAccount('staff@demo.test', 'Staff#2026')"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-white border border-canvas-border hover:border-meadow-500 hover:bg-meadow-50/50 transition text-left"
                >
                    <span class="px-2 py-0.5 rounded-full bg-earth-100 text-earth-700 font-semibold text-[11px]">Staff</span>
                    <span class="font-mono text-[11px] text-ink-muted truncate">staff@demo.test / Staff#2026</span>
                </button>
                <button
                    type="button"
                    onclick="fillDemoAccount('user@demo.test', 'User#2026')"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-white border border-canvas-border hover:border-meadow-500 hover:bg-meadow-50/50 transition text-left"
                >
                    <span class="px-2 py-0.5 rounded-full bg-skyblock-100 text-skyblock-700 font-semibold text-[11px]">User</span>
                    <span class="font-mono text-[11px] text-ink-muted truncate">user@demo.test / User#2026</span>
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
