<x-public-layout title="Cek Booking & Kartu Pengambilan — Rawlseyyy">
    <section class="relative overflow-hidden bg-gradient-to-br from-skyblock-100/80 via-white to-earth-100/65 border-b border-canvas-border text-ink py-14">
        <div class="absolute top-0 inset-x-0 h-1 palette-strip"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="inline-flex px-3.5 py-1 rounded-full bg-meadow-100 text-meadow-700 text-xs font-semibold uppercase tracking-[0.22em] mb-3">Verifikasi Reservasi</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink">Cek Booking &amp; Kartu Pengambilan Alat</h1>
            <p class="text-ink-muted text-sm max-w-2xl mx-auto mt-3">
                Masukkan Kode Booking beserta Email atau Nomor Telepon yang digunakan saat checkout untuk melihat status terkini atau mencetak Kartu Pengambilan QR Code.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Lookup Form -->
            <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('cek-booking') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    @csrf

                    <div class="md:col-span-5">
                        <label for="booking_code" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                            Kode Booking <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="booking_code"
                            name="booking_code"
                            value="{{ old('booking_code', $bookingCode) }}"
                            placeholder="Contoh: RWL-20261122-1001"
                            required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm font-mono text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                        >
                        @error('booking_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-5">
                        <label for="contact" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                            Email atau Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="contact"
                            name="contact"
                            value="{{ old('contact', $contact) }}"
                            placeholder="user@demo.test atau 0812..."
                            required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                        >
                        @error('contact')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Cek
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lookup Results -->
            @if ($notFound)
                <div class="rounded-3xl border border-red-200 bg-red-50 p-6 text-center">
                    <h2 class="text-base font-bold text-red-900">Data Booking Tidak Ditemukan</h2>
                    <p class="text-xs text-red-700 mt-1">
                        Pastikan Kode Booking dan Email / Nomor Telepon yang Anda masukkan sudah sesuai dengan data saat melakukan pemesanan.
                    </p>
                </div>
            @elseif ($booking)
                @php
                    $statusLabels = [
                        'pending' => ['label' => 'Menunggu ACC Staff', 'class' => 'bg-earth-50 text-earth-800 border-earth-200'],
                        'approved' => ['label' => 'Disetujui — Menunggu Pembayaran', 'class' => 'bg-skyblock-50 text-skyblock-700 border-skyblock-200'],
                        'waiting_verification' => ['label' => 'Menunggu Verifikasi Pembayaran', 'class' => 'bg-purple-50 text-purple-800 border-purple-200'],
                        'confirmed' => ['label' => 'Terkonfirmasi — Siap Diambil', 'class' => 'bg-meadow-50 text-meadow-800 border-meadow-200'],
                        'ongoing' => ['label' => 'Sedang Berjalan', 'class' => 'bg-skyblock-100 text-skyblock-700 border-skyblock-200'],
                        'returned' => ['label' => 'Sudah Dikembalikan', 'class' => 'bg-canvas-warm text-ink border-canvas-border'],
                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-50 text-red-800 border-red-200'],
                        'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-800 border-red-200'],
                    ];
                    $statusInfo = $statusLabels[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-canvas-warm text-ink border-canvas-border'];
                @endphp

                @if (! in_array($booking->status, ['confirmed', 'ongoing'], true))
                    <!-- Non-Confirmed Status Card -->
                    <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-canvas-border/70">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-earth-600 font-semibold">Kode Booking</p>
                                <p class="text-xl font-mono font-bold text-ink mt-0.5">{{ $booking->booking_code }}</p>
                            </div>
                            <span class="inline-flex px-4 py-1.5 rounded-full border text-xs font-bold {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </div>

                        <div class="mt-5 text-sm text-ink-muted space-y-2">
                            <p><strong class="text-ink">Penyewa:</strong> {{ $booking->contact_name ?? $booking->user?->name }}</p>
                            <p><strong class="text-ink">Tanggal Sewa:</strong> {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }} ({{ $booking->total_days }} hari)</p>
                            @if ($booking->status === 'waiting_verification')
                                <div class="mt-3 rounded-2xl border border-purple-200 bg-purple-50 px-4 py-3 text-xs font-medium text-purple-900">
                                    Bukti pembayaran sedang diverifikasi oleh Staff. Kartu Pengambilan Alat &amp; QR Code akan muncul otomatis setelah pembayaran dinyatakan valid.
                                </div>
                            @elseif ($booking->status === 'rejected' && $booking->rejection_reason)
                                <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-900">
                                    Alasan Penolakan: {{ $booking->rejection_reason }}
                                </div>
                            @else
                                <p class="pt-2 text-xs text-ink-muted">
                                    Kartu Pengambilan Alat &amp; QR Code hanya tersedia jika status booking sudah <strong class="text-ink">Confirmed</strong> (disetujui Staff dan pembayaran telah terverifikasi).
                                </p>
                            @endif
                        </div>

                        @if ($booking->status === 'approved')
                            <div class="mt-5">
                                <a href="{{ route('profile.edit', ['tab' => 'rentals']) }}" class="inline-flex px-5 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    Lanjutkan Pembayaran di Profil &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Pickup Card with SVG QR Code & Handover Checklist -->
                    <div class="bg-white rounded-3xl border-2 border-meadow-500 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-meadow-600 to-meadow-500 text-white px-6 sm:px-8 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.25em] text-earth-100 font-semibold">Rawlseyyy Pickup Pass</p>
                                <h2 class="font-display text-2xl font-bold mt-1">Kartu Pengambilan Alat Camping</h2>
                                <p class="text-xs font-mono text-meadow-50 mt-1">{{ $booking->booking_code }}</p>
                            </div>
                            <span class="inline-flex self-start sm:self-auto px-4 py-1.5 rounded-full bg-white text-meadow-700 text-xs font-bold uppercase tracking-wider shadow-sm">
                                {{ $statusInfo['label'] }}
                            </span>
                        </div>

                        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                            <!-- Left: Renter Info & Checklist -->
                            <div class="md:col-span-8 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-canvas-warm rounded-2xl p-4 border border-canvas-border text-sm">
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-earth-600 font-semibold">Nama Penyewa</p>
                                        <p class="font-bold text-ink mt-0.5">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                                        <p class="text-xs text-ink-muted">{{ $booking->contact_email ?? $booking->user?->email }}</p>
                                        <p class="text-xs text-ink-muted">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-earth-600 font-semibold">Jadwal Sewa</p>
                                        <p class="font-bold text-ink mt-0.5">
                                            {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-ink-muted">Durasi: {{ $booking->total_days }} Hari</p>
                                        <p class="text-xs font-semibold text-meadow-700 mt-1">
                                            Lunas ({{ $booking->payment_method ?? 'Verified' }}) &middot; Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Checklist Serah Terima Alat (Verifikasi Petugas Basecamp) -->
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                        <h3 class="text-xs font-bold uppercase tracking-widest text-ink-muted">
                                            Checklist Serah Terima Alat (Verifikasi Basecamp)
                                        </h3>
                                        @if ($booking->status === 'ongoing' && $booking->picked_up_at)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-meadow-50 border border-meadow-200 text-[11px] font-semibold text-meadow-800">
                                                &#10003; Dicek {{ $booking->handoverStaff?->name ?? 'Petugas' }} ({{ $booking->picked_up_at->format('d M Y H:i') }})
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full bg-earth-50 border border-earth-200 text-[11px] font-semibold text-earth-800">
                                                Menunggu Cek Petugas saat Pengambilan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="divide-y divide-canvas-border/60 border border-canvas-border rounded-2xl overflow-hidden">
                                        @foreach ($booking->bookingItems as $bookingItem)
                                            @php
                                                $isVerified = $bookingItem->is_handover_checked || $booking->status === 'ongoing';
                                            @endphp
                                            <div class="flex items-center justify-between gap-4 px-4 py-3 bg-white">
                                                <div class="flex items-center gap-3">
                                                    @if ($isVerified)
                                                        <span class="w-6 h-6 rounded-lg bg-meadow-500 text-white flex items-center justify-center text-xs font-bold shrink-0" title="Terverifikasi Petugas">
                                                            &#10003;
                                                        </span>
                                                    @else
                                                        <span class="w-6 h-6 rounded-lg border-2 border-dashed border-canvas-border bg-canvas-warm flex items-center justify-center text-[10px] text-ink-muted shrink-0" title="Menunggu Inspeksi Basecamp">
                                                            &bull;
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <span class="block text-sm font-semibold text-ink">{{ $bookingItem->item->name ?? 'Alat Camping' }}</span>
                                                        <span class="block text-xs {{ $isVerified ? 'text-meadow-700 font-medium' : 'text-ink-muted' }}">
                                                            @if ($isVerified)
                                                                Lolos inspeksi basecamp{{ $bookingItem->handover_notes ? ': ' . $bookingItem->handover_notes : '' }}
                                                            @else
                                                                Akan diperiksa kondisi fisik, kelengkapan aksesoris &amp; kebersihan oleh petugas
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 rounded-full bg-meadow-100 text-meadow-700 text-xs font-bold shrink-0">
                                                    {{ $bookingItem->quantity }} Unit
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Right: SVG QR Code -->
                            <div class="md:col-span-4 flex flex-col items-center justify-center bg-canvas-warm rounded-2xl border border-canvas-border p-6 text-center">
                                <div class="bg-white p-3 rounded-2xl border border-canvas-border shadow-sm inline-block">
                                    {!! $qrSvg !!}
                                </div>
                                <p class="mt-3 text-xs font-mono font-bold text-ink">{{ $booking->booking_code }}</p>
                                <p class="mt-1 text-[11px] text-ink-muted leading-relaxed">
                                    Tunjukkan QR Code ini dan KTP asli kepada petugas Rawlseyyy saat pengambilan alat.
                                </p>
                                <button
                                    type="button"
                                    onclick="window.print()"
                                    class="mt-4 w-full py-2 px-4 rounded-full border border-meadow-500/60 text-xs font-semibold text-meadow-700 hover:bg-meadow-500 hover:text-white transition print:hidden"
                                >
                                    Cetak Kartu Pengambilan
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
</x-public-layout>
