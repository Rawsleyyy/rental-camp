<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Audit Trail"
        title="Log Aktivitas Admin &amp; Staff"
        subtitle="Pantau setiap aksi pengelolaan inventaris, persetujuan sewa, dan pembaruan artikel secara kronologis."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('admin.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Riwayat Aktivitas Sistem</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $activityLogs->total() }} entri log tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Pelaksana</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Deskripsi</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($activityLogs as $log)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-xs uppercase tracking-wider text-neutral-400">{{ $log->user->role ?? '-' }}</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-neutral-100 text-neutral-800">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-600 max-w-lg">
                                {{ $log->description }}
                            </td>
                            <td class="px-7 py-4 text-right text-xs text-neutral-500 whitespace-nowrap">
                                {{ $log->created_at?->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-sm text-neutral-500">
                                Belum ada log aktivitas sistem yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($activityLogs->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $activityLogs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
