<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Audit Trail"
        title="Log Aktivitas Admin &amp; Staff"
        subtitle="Pantau setiap aksi pengelolaan inventaris, persetujuan sewa, dan pembaruan artikel secara kronologis."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('admin.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Riwayat Aktivitas Sistem</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $activityLogs->total() }} entri log tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Pelaksana</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Deskripsi</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($activityLogs as $log)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-xs uppercase tracking-wider text-earth-600">{{ $log->user->role ?? '-' }}</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-canvas-warm border border-canvas-border text-ink">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted max-w-lg">
                                {{ $log->description }}
                            </td>
                            <td class="px-7 py-4 text-right text-xs text-ink-muted whitespace-nowrap">
                                {{ $log->created_at?->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-sm text-ink-muted">
                                Belum ada log aktivitas sistem yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($activityLogs->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $activityLogs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
