<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600">
            {{ __("Update your account's profile photo, contact details, and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <!-- Avatar Preview & Upload -->
        <div class="flex items-center gap-4">
            @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border border-neutral-200">
            @else
                <div class="w-16 h-16 rounded-2xl bg-neutral-950 text-amber-400 font-bold text-xl flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1">
                <x-input-label for="avatar" value="Foto Profil (Opsional)" />
                <input
                    id="avatar"
                    name="avatar"
                    type="file"
                    accept="image/*"
                    class="mt-1.5 block w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-neutral-950 file:text-white hover:file:bg-amber-400 hover:file:text-neutral-950 cursor-pointer"
                />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-neutral-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-neutral-600 hover:text-neutral-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            {{ __('A new verification link has been sent to the email address you provided.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" value="Nomor Telepon / WhatsApp" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="0812xxxxxxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="address" value="Alamat Lengkap" />
            <textarea
                id="address"
                name="address"
                rows="3"
                placeholder="Masukkan alamat tempat tinggal Anda..."
                class="mt-1 block w-full border-neutral-300 bg-neutral-50/70 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 rounded-xl shadow-sm text-sm py-2.5 px-3.5 transition"
            >{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-medium"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
