<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Manajemen Akun"
        title="Kelola Akun Pengguna &amp; Staff"
        subtitle="Tambahkan atau hapus akun Staff operasional dan Member penyewa. Akun Admin tunggal dilindungi dan hanya dapat diubah melalui source code."
        actionLabel="+ Tambah Akun"
        :actionHref="route('admin.users.create')"
    />

    <x-admin-flash />

    <!-- Filter & Search Bar -->
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-5 mb-6 space-y-4">
        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    'all' => ['label' => 'Semua', 'count' => $counts['all']],
                    'admin' => ['label' => 'Admin', 'count' => $counts['admin']],
                    'staff' => ['label' => 'Staff', 'count' => $counts['staff']],
                    'user' => ['label' => 'User / Penyewa', 'count' => $counts['user']],
                ];
            @endphp
            @foreach ($tabs as $key => $info)
                <a
                    href="{{ route('admin.users.index', ['role' => $key, 'q' => $search]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition {{ $roleFilter === $key ? 'bg-meadow-500 text-white shadow-sm shadow-meadow-500/20' : 'bg-canvas-warm text-ink-muted border border-canvas-border hover:bg-meadow-50 hover:text-meadow-700' }}"
                >
                    <span>{{ $info['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $roleFilter === $key ? 'bg-white/25 text-white' : 'bg-white text-ink' }}">
                        {{ $info['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="role" value="{{ $roleFilter }}">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama atau email akun..."
                class="flex-1 rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
            >
            <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                Cari Akun
            </button>
        </form>
    </div>

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Semua Akun Terdaftar</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $users->total() }} pengguna tercatat dalam sistem Rawlseyyy</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">#</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Nama &amp; Email</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Role</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kontak &amp; Alamat</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Terdaftar</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($users as $account)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4 text-sm text-ink-muted font-medium">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-meadow-500 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($account->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $account->name }}</p>
                                        <p class="text-xs font-mono text-ink-muted">{{ $account->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4">
                                @php
                                    $roleBadge = match ($account->role) {
                                        'admin' => 'bg-meadow-100 text-meadow-700 border border-meadow-200',
                                        'staff' => 'bg-earth-100 text-earth-700 border border-earth-200',
                                        default => 'bg-skyblock-100 text-skyblock-700 border border-skyblock-200',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $roleBadge }}">
                                    {{ $account->role }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <p class="text-sm text-ink">{{ $account->phone ?? '-' }}</p>
                                <p class="text-xs text-ink-muted truncate max-w-xs">{{ $account->address ?? 'Belum ada alamat' }}</p>
                            </td>
                            <td class="px-7 py-4 text-xs text-ink-muted whitespace-nowrap">
                                {{ $account->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-right whitespace-nowrap">
                                @if ($account->role === 'admin')
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold bg-canvas-warm border border-canvas-border text-ink-muted">
                                        Admin Utama (Dilindungi)
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.destroy', $account) }}" onsubmit="return confirm('Yakin ingin menghapus akun {{ $account->role }} ({{ $account->email }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3.5 py-1.5 rounded-full border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-7 py-12 text-center text-sm text-ink-muted">
                                Tidak ada akun yang cocok dengan filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
