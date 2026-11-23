<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Staff Pembatalan"
        title="Approval Pengajuan Pembatalan"
        subtitle="Tinjau pengajuan pembatalan penyewa beserta selisih hari sebelum tanggal sewa dan estimasi refund."
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100">
            <h2 class="font-serif text-xl text-neutral-950">Daftar Pengajuan Pembatalan</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Kebijakan estimasi: &gt; 3 hari sebelum mulai sewa = Refund 100% (jika lunas), &le; 3 hari = Refund 50%, belum bayar = Rp 0</p>
        </div>

        <div class="divide-y divide-neutral-100">
            @forelse ($cancellations as $cancellation)
                @php
                    $booking = $cancellation->booking;
                    $daysBeforeStart = $booking?->start_date
                        ? (int) today()->diffInDays($booking->start_date->copy()->startOfDay(), false)
                        : 0;
                    $isPaid = $booking && $booking->paid_at !== null;
                    $paidTotal = $booking ? $booking->payable_total : 0;

                    if (! $isPaid) {
                        $refundRate = 0;
                        $estimatedRefund = 0;
                        $refundNote = 'Belum dibayar (Refund Rp 0)';
                    } elseif ($daysBeforeStart > 3) {
                        $refundRate = 100;
                        $estimatedRefund = $paidTotal;
                        $refundNote = "H-{$daysBeforeStart} sebelum sewa (> 3 hari): Refund 100%";
                    } else {
                        $refundRate = 50;
                        $estimatedRefund = (int) round($paidTotal * 0.5);
                        $refundNote = "H-{$daysBeforeStart} sebelum sewa (<= 3 hari): Refund 50%";
                    }

                    $badgeClass = match ($cancellation->status) {
                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                        default => 'bg-amber-50 text-amber-800 border-amber-200',
                    };
                @endphp

                <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="font-mono text-base font-bold text-neutral-950">{{ $booking?->booking_code ?? '-' }}</span>
                            <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase {{ $badgeClass }}">
                                {{ $cancellation->status }}
                            </span>
                            @if ($booking)
                                <span class="text-xs text-neutral-500">
                                    Jadwal Sewa: <strong>{{ $booking->start_date?->format('d M Y') }}</strong> &middot;
                                    {{ $daysBeforeStart >= 0 ? "H-{$daysBeforeStart}" : 'Sudah lewat' }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-neutral-600">
                            Pemohon: <strong>{{ $cancellation->user?->name ?? $booking?->contact_name ?? '-' }}</strong>
                            @if ($booking)
                                &middot; Status Bayar:
                                <strong class="{{ $isPaid ? 'text-emerald-700' : 'text-neutral-500' }}">
                                    {{ $isPaid ? 'Sudah Lunas (Rp ' . number_format($paidTotal, 0, ',', '.') . ')' : 'Belum Lunas' }}
                                </strong>
                            @endif
                        </p>

                        <div class="rounded-2xl bg-neutral-50 border border-neutral-200/80 p-3.5 text-sm text-neutral-800">
                            <p class="text-[11px] uppercase tracking-wider font-semibold text-neutral-400 mb-1">Alasan User</p>
                            {{ $cancellation->reason }}
                        </div>

                        <p class="text-xs font-medium text-amber-700">
                            Estimasi Kebijakan Refund: <strong>Rp {{ number_format($estimatedRefund, 0, ',', '.') }}</strong> ({{ $refundNote }})
                        </p>

                        @if ($cancellation->status !== 'pending')
                            <p class="text-xs text-neutral-600">
                                <strong>Keputusan:</strong> Refund Rp {{ number_format($cancellation->refund_amount, 0, ',', '.') }}
                                &middot; Catatan: {{ $cancellation->admin_notes ?: '-' }}
                            </p>
                        @endif
                    </div>

                    @if ($cancellation->status === 'pending')
                        <div class="lg:w-96 space-y-3 bg-neutral-50 rounded-2xl border border-neutral-200 p-4">
                            <form method="POST" action="{{ route('staff.cancellations.approve', $cancellation) }}" class="space-y-2.5">
                                @csrf
                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Setujui Pembatalan</p>
                                <div>
                                    <label class="block text-[11px] font-semibold text-neutral-600 mb-1">Nominal Refund (Rp)</label>
                                    <input
                                        type="number"
                                        name="refund_amount"
                                        min="0"
                                        value="{{ $estimatedRefund }}"
                                        class="w-full rounded-xl border-neutral-300 text-xs focus:border-neutral-950 focus:ring-neutral-950"
                                    >
                                </div>
                                <div>
                                    <input
                                        type="text"
                                        name="admin_notes"
                                        placeholder="Catatan persetujuan (opsional)..."
                                        class="w-full rounded-xl border-neutral-300 text-xs focus:border-neutral-950 focus:ring-neutral-950"
                                    >
                                </div>
                                <button type="submit" class="w-full py-2 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
                                    Setujui Pembatalan &amp; Batalkan Booking
                                </button>
                            </form>

                            <form method="POST" action="{{ route('staff.cancellations.reject', $cancellation) }}" class="pt-3 border-t border-neutral-200 space-y-2">
                                @csrf
                                <p class="text-xs font-bold uppercase tracking-wider text-red-700">Tolak Pengajuan</p>
                                <input
                                    type="text"
                                    name="admin_notes"
                                    required
                                    placeholder="Wajib isi alasan penolakan..."
                                    class="w-full rounded-xl border-neutral-300 text-xs focus:border-red-600 focus:ring-red-600"
                                >
                                <button type="submit" class="w-full py-2 rounded-full border border-red-300 text-red-700 text-xs font-semibold hover:bg-red-50 transition">
                                    Tolak Pembatalan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-6 py-10 text-center text-sm text-neutral-500">
                    Belum ada pengajuan pembatalan dari penyewa.
                </div>
            @endforelse
        </div>

        @if ($cancellations->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $cancellations->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
