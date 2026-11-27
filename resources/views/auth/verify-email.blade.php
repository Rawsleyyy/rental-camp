<x-guest-layout>
    <div class="mb-4">
        <h1 class="font-display text-2xl font-bold text-ink">Verifikasi Email Anda</h1>
        <p class="text-xs text-ink-muted mt-1 leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-xl border border-meadow-200 bg-meadow-50 px-4 py-3 font-medium text-xs text-meadow-800">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 pt-3 border-t border-canvas-border/70 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-xs font-medium text-ink-muted hover:text-meadow-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-meadow-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
