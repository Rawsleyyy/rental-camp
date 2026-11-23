<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Manajemen Akun"
        title="Daftar Pengguna &amp; Hak Akses"
        subtitle="Pantau seluruh akun terdaftar mulai dari Admin, Staff operasional, hingga Member penyewa alat camping."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('admin.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Semua Akun Terdaftar</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $users->total() }} pengguna tercatat dalam sistem Rawlseyyy</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">#</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Nama &amp; Email</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Role</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kontak &amp; Alamat</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4 text-sm text-neutral-400 font-medium">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-neutral-950 text-amber-400 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-950">{{ $user->name }}</p>
                                        <p class="text-xs font-mono text-neutral-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4">
                                @php
                                    $roleBadge = match ($user->role) {
                                        'admin' => 'bg-neutral-950 text-amber-400',
                                        'staff' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        default => 'bg-neutral-100 text-neutral-700',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $roleBadge }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <p class="text-sm text-neutral-800">{{ $user->phone ?? '-' }}</p>
                                <p class="text-xs text-neutral-500 truncate max-w-xs">{{ $user->address ?? 'Belum ada alamat' }}</p>
                            </td>
                            <td class="px-7 py-4 text-right text-xs text-neutral-500">
                                {{ $user->created_at?->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-12 text-center text-sm text-neutral-500">
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
