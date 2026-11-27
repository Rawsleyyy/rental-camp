<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Tambah Akun"
        title="Tambah Akun Staff atau User"
        subtitle="Buat akun Staff operasional atau Member penyewa baru. Demi keamanan, akun Admin hanya 1 dan hanya bisa diubah melalui source code."
        actionLabel="&larr; Kembali ke Daftar Akun"
        :actionHref="route('admin.users.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        Role Akun <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                        <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff Operasional</option>
                        <option value="user" @selected(old('role') === 'user')>User / Penyewa</option>
                    </select>
                    <p class="mt-1 text-[11px] text-ink-muted">Hanya Staff atau User yang dapat dibuat dari panel ini.</p>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        No. Telepon (Opsional)
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="0812..."
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                    Alamat (Opsional)
                </label>
                <textarea
                    id="address"
                    name="address"
                    rows="2"
                    class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                >{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>
            </div>

            <div class="pt-3 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Simpan Akun Baru
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-full border border-canvas-border text-sm font-semibold text-ink-muted hover:bg-canvas-warm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
