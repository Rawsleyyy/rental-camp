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
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-5 mb-6 space-y-4">
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
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition {{ $roleFilter === $key ? 'bg-neutral-950 text-white' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }}"
                >
                    <span>{{ $info['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $roleFilter === $key ? 'bg-amber-400 text-neutral-950' : 'bg-white text-neutral-800' }}">
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
                class="flex-1 rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
            >
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                Cari Akun
            </button>
        </form>
    </div>

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
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Terdaftar</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($users as $account)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4 text-sm text-neutral-400 font-medium">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-neutral-950 text-amber-400 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($account->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-950">{{ $account->name }}</p>
                                        <p class="text-xs font-mono text-neutral-500">{{ $account->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4">
                                @php
                                    $roleBadge = match ($account->role) {
                                        'admin' => 'bg-neutral-950 text-amber-400',
                                        'staff' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        default => 'bg-neutral-100 text-neutral-700',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $roleBadge }}">
                                    {{ $account->role }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <p class="text-sm text-neutral-800">{{ $account->phone ?? '-' }}</p>
                                <p class="text-xs text-neutral-500 truncate max-w-xs">{{ $account->address ?? 'Belum ada alamat' }}</p>
                            </td>
                            <td class="px-7 py-4 text-xs text-neutral-500 whitespace-nowrap">
                                {{ $account->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-right whitespace-nowrap">
                                @if ($account->role === 'admin')
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold bg-neutral-100 text-neutral-500">
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
                            <td colspan="6" class="px-7 py-12 text-center text-sm text-neutral-500">
                                Tidak ada akun yang cocok dengan filter saat ini.
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
