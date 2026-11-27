@php
    $isStaffOrAdmin = in_array(Auth::user()->role ?? 'user', ['admin', 'staff'], true);
@endphp

@if ($isStaffOrAdmin)
    <x-app-layout>
        <x-admin-hero
            eyebrow="Pack & Pitch Account"
            title="Profile Settings"
            subtitle="Update your account information, avatar, email address, and password security."
        />

        <div class="space-y-6">
            <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-app-layout>
@else
    @php
        $paymentPayloads = $bookings->where('status', 'approved')->mapWithKeys(function ($booking) {
            $uniqueCode = (int) ($booking->unique_code ?? 0);
            $basePrice = (int) round((float) $booking->total_price);
            $payableTotal = $basePrice + $uniqueCode;

            return [
                $booking->id => [
                    'id' => $booking->id,
                    'code' => $booking->booking_code,
                    'base_price_formatted' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
                    'unique_code' => $uniqueCode > 0 ? (string) $uniqueCode : '-',
                    'payable_total_formatted' => 'Rp ' . number_format($payableTotal, 0, ',', '.'),
                    'rejection_reason' => $booking->payment_rejection_reason,
                ],
            ];
        })->all();
    @endphp

    <x-public-layout title="Profil & Aktivitas Sewa — Pack & Pitch">
        <section class="relative overflow-hidden bg-gradient-to-br from-skyblock-100/80 via-white to-earth-100/65 border-b border-canvas-border text-ink py-12">
            <div class="absolute top-0 inset-x-0 h-1 palette-strip"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-meadow-500 shadow-sm">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-meadow-500 text-white font-bold text-2xl flex items-center justify-center shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-earth-600">Pusat Aktivitas Member</p>
                        <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink mt-0.5">{{ $user->name }}</h1>
                        <p class="text-xs text-ink-muted mt-0.5">{{ $user->email }} &middot; {{ $user->phone ?: 'Belum ada no. telepon' }}</p>
                    </div>
                </div>

                <a href="{{ route('katalog') }}" class="inline-flex self-start sm:self-auto px-5 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    + Sewa Alat Camping
                </a>
            </div>
        </section>

        <section
            x-data="{
                tab: @js($activeTab),
                payModalOpen: false,
                payBookingId: null,
                payMethod: @js(old('payment_method', 'Transfer Bank')),
                paymentPayloads: @js($paymentPayloads),
                cancelBookingId: @js($cancellableBookings->first()?->id),
                openPayModal(id) {
                    if (this.paymentPayloads[id]) {
                        this.payBookingId = id;
                        this.payModalOpen = true;
                    }
                },
                get activePay() {
                    return this.paymentPayloads[this.payBookingId] || {
                        id: null,
                        code: '',
                        base_price_formatted: 'Rp 0',
                        unique_code: '-',
                        payable_total_formatted: 'Rp 0',
                        rejection_reason: null,
                    };
                }
            }"
            x-init="@if (session('pay_booking_id')) openPayModal({{ (int) session('pay_booking_id') }}); @endif"
            class="py-10"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Status Feedback Banners -->
                @if (session('status') === 'booking-created')
                    <div class="mb-6 rounded-2xl border border-meadow-200 bg-meadow-50 px-5 py-4 text-sm text-meadow-900">
                        Pengajuan sewa berhasil dibuat dengan kode <strong class="font-mono">{{ session('booking_code') }}</strong>. Saat ini berstatus <strong>Menunggu ACC Staff</strong>.
                    </div>
                @elseif (session('status') === 'payment-submitted')
                    <div class="mb-6 rounded-2xl border border-purple-200 bg-purple-50 px-5 py-4 text-sm text-purple-900">
                        Bukti pembayaran untuk kode booking <strong class="font-mono">{{ session('booking_code') }}</strong> berhasil dikirim! Saat ini berstatus <strong>Menunggu Verifikasi Pembayaran oleh Staff</strong>.
                    </div>
                @elseif (session('status') === 'payment-confirmed')
                    <div class="mb-6 rounded-2xl border border-meadow-200 bg-meadow-50 px-5 py-4 text-sm text-meadow-900">
                        Pembayaran untuk kode booking <strong class="font-mono">{{ session('booking_code') }}</strong> berhasil dikonfirmasi!
                    </div>
                @elseif (session('status') === 'cancellation-submitted')
                    <div class="mb-6 rounded-2xl border border-earth-200 bg-earth-50 px-5 py-4 text-sm text-earth-900">
                        Pengajuan pembatalan untuk kode <strong class="font-mono">{{ session('booking_code') }}</strong> telah dikirim dan sedang menunggu tinjauan Staff/Admin.
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-meadow-200 bg-meadow-50 px-5 py-4 text-sm text-meadow-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-900">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-900">
                        <p class="text-sm font-bold mb-1">Periksa kembali data Anda:</p>
                        <ul class="list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tab Buttons -->
                <div class="flex flex-wrap gap-2 mb-8 border-b border-canvas-border pb-4">
                    <button
                        type="button"
                        @click="tab = 'profile'"
                        :class="tab === 'profile' ? 'bg-meadow-500 text-white shadow-sm shadow-meadow-500/20' : 'bg-white text-ink-muted border border-canvas-border hover:bg-meadow-50 hover:text-meadow-700'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition"
                    >
                        Profil &amp; Data Pribadi
                    </button>
                    <button
                        type="button"
                        @click="tab = 'rentals'"
                        :class="tab === 'rentals' ? 'bg-meadow-500 text-white shadow-sm shadow-meadow-500/20' : 'bg-white text-ink-muted border border-canvas-border hover:bg-meadow-50 hover:text-meadow-700'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition"
                    >
                        Riwayat Sewa ({{ $bookings->count() }})
                    </button>
                    <button
                        type="button"
                        @click="tab = 'cancellations'"
                        :class="tab === 'cancellations' ? 'bg-meadow-500 text-white shadow-sm shadow-meadow-500/20' : 'bg-white text-ink-muted border border-canvas-border hover:bg-meadow-50 hover:text-meadow-700'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition"
                    >
                        Pengajuan &amp; Status Pembatalan ({{ $cancellations->count() }})
                    </button>
                </div>

                <!-- Tab 1: Profil & Data Pribadi -->
                <div x-show="tab === 'profile'" x-cloak class="space-y-6">
                    <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-3xl border border-canvas-border shadow-sm">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Riwayat Sewa -->
                <div x-show="tab === 'rentals'" x-cloak class="space-y-4">
                    @forelse ($bookings as $booking)
                        @php
                            $badgeClasses = match ($booking->status) {
                                'pending' => 'bg-earth-50 text-earth-800 border-earth-200',
                                'approved' => 'bg-skyblock-50 text-skyblock-700 border-skyblock-200',
                                'waiting_verification' => 'bg-purple-50 text-purple-800 border-purple-200',
                                'confirmed' => 'bg-meadow-50 text-meadow-800 border-meadow-200',
                                'ongoing' => 'bg-skyblock-100 text-skyblock-700 border-skyblock-200',
                                'returned' => 'bg-canvas-warm text-ink border-canvas-border',
                                default => 'bg-red-50 text-red-800 border-red-200',
                            };
                            $statusLabel = match ($booking->status) {
                                'pending' => 'Menunggu ACC Staff',
                                'approved' => 'Disetujui — Siap Bayar',
                                'waiting_verification' => 'Menunggu Verifikasi Bayar',
                                'confirmed' => 'Terkonfirmasi (Lunas)',
                                'ongoing' => 'Sedang Dipinjam',
                                'returned' => 'Dikembalikan / Selesai',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan',
                                default => ucfirst($booking->status),
                            };
                            $payableAmount = (int) round((float) $booking->total_price) + (int) ($booking->unique_code ?? 0);
                        @endphp

                        <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-canvas-border/70">
                                <div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="text-base font-mono font-bold text-ink">{{ $booking->booking_code }}</span>
                                        <span class="px-3 py-1 rounded-full border text-xs font-semibold {{ $badgeClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-ink-muted mt-1">
                                        Jadwal: {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }} ({{ $booking->total_days }} hari)
                                    </p>
                                </div>
                                <div class="sm:text-right">
                                    <p class="text-xs text-ink-muted uppercase tracking-wider font-semibold">
                                        {{ $booking->unique_code ? 'Total Transfer (+Kode Unik ' . $booking->unique_code . ')' : 'Total Biaya Sewa' }}
                                    </p>
                                    <p class="font-display text-xl font-bold text-ink">
                                        Rp {{ number_format($payableAmount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Rejection alert if Staff previously rejected payment proof -->
                            @if ($booking->status === 'approved' && $booking->payment_rejection_reason)
                                <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-900">
                                    <strong class="font-bold">Bukti Pembayaran Sebelumnya Ditolak Staff:</strong>
                                    <span class="block mt-0.5">{{ $booking->payment_rejection_reason }}</span>
                                </div>
                            @endif

                            <!-- Items Summary -->
                            <div class="py-4 flex flex-wrap gap-2">
                                @foreach ($booking->bookingItems as $bookingItem)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-canvas-warm border border-canvas-border/60 text-xs font-medium text-ink">
                                        <span>{{ $bookingItem->item->name ?? 'Alat Camping' }}</span>
                                        <span class="font-bold text-meadow-700">&times;{{ $bookingItem->quantity }}</span>
                                    </span>
                                @endforeach
                            </div>

                            <!-- Status Specific Details & Action Buttons -->
                            <div class="pt-3 border-t border-canvas-border/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="text-xs text-ink-muted">
                                    @if ($booking->status === 'pending')
                                        <span>Menunggu persetujuan (ACC) Staff sebelum dapat melakukan pembayaran.</span>
                                    @elseif ($booking->status === 'approved')
                                        <span class="text-skyblock-700 font-medium">
                                            Pengajuan disetujui Staff! Kode unik transfer Anda: <strong class="font-mono">{{ $booking->unique_code ?? '-' }}</strong>. Silakan transfer sesuai total nominal.
                                        </span>
                                    @elseif ($booking->status === 'waiting_verification')
                                        <span class="text-purple-700 font-medium">
                                            Bukti bayar via {{ $booking->payment_method }} (a.n. {{ $booking->payment_sender_name }}) sedang diverifikasi oleh Staff.
                                        </span>
                                    @elseif ($booking->status === 'confirmed')
                                        <span class="text-meadow-700 font-medium">
                                            Lunas via {{ $booking->payment_method ?? 'Transfer' }} ({{ $booking->paid_at?->format('d M Y H:i') }}). Alat siap diambil di basecamp.
                                        </span>
                                    @elseif ($booking->status === 'ongoing')
                                        <span>Jadwal kembali: <strong class="text-ink">{{ $booking->end_date?->format('d M Y') }}</strong>.</span>
                                        @if (now()->startOfDay()->gt($booking->end_date))
                                            <span class="text-red-600 font-semibold ml-1">(Melewati batas tanggal kembali!)</span>
                                        @endif
                                    @elseif ($booking->status === 'returned')
                                        <span>Dikembalikan {{ $booking->returned_at?->format('d M Y') ?? $booking->end_date?->format('d M Y') }} (Kondisi: <strong class="text-ink">{{ str_replace('_', ' ', ucfirst($booking->return_condition ?? 'baik')) }}</strong>).</span>
                                        @if ($booking->late_days > 0 || $booking->late_fee > 0)
                                            <span class="text-red-600 font-semibold ml-1">
                                                &middot; Terlambat {{ $booking->late_days }} hari (Denda Telat: Rp {{ number_format($booking->late_fee, 0, ',', '.') }})
                                            </span>
                                        @else
                                            <span class="text-meadow-700 font-semibold ml-1">&middot; Tepat waktu</span>
                                        @endif
                                        @if ($booking->damage_fee > 0)
                                            <span class="text-red-600 font-semibold ml-1">
                                                &middot; Denda Kerusakan: Rp {{ number_format($booking->damage_fee, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @elseif ($booking->status === 'rejected')
                                        <span class="text-red-600 font-medium">
                                            Transaksi ditolak oleh Staff{{ $booking->rejection_reason ? ': ' . $booking->rejection_reason : '.' }}
                                        </span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="text-red-600 font-medium">Transaksi dibatalkan.</span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($booking->status === 'approved')
                                        <button
                                            type="button"
                                            @click="openPayModal({{ $booking->id }})"
                                            class="px-5 py-2 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20"
                                        >
                                            {{ $booking->payment_rejection_reason ? 'Upload Ulang Bukti Bayar' : 'Bayar Sekarang' }}
                                        </button>
                                    @endif

                                    @if (in_array($booking->status, ['confirmed', 'ongoing'], true))
                                        <a
                                            href="{{ route('cek-booking', ['booking_code' => $booking->booking_code, 'contact' => $booking->contact_email ?: $user->email]) }}"
                                            class="px-4 py-2 rounded-full bg-meadow-600 text-white text-xs font-semibold hover:bg-meadow-700 transition"
                                        >
                                            Kartu Pengambilan / QR Code
                                        </a>
                                    @endif

                                    @if (in_array($booking->status, ['pending', 'approved', 'waiting_verification', 'confirmed'], true) && ! $booking->cancellations->whereIn('status', ['pending', 'approved'])->isNotEmpty())
                                        <button
                                            type="button"
                                            @click="tab = 'cancellations'; cancelBookingId = {{ $booking->id }}"
                                            class="px-4 py-2 rounded-full border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition"
                                        >
                                            Ajukan Pembatalan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl border border-canvas-border p-10 text-center">
                            <p class="text-sm text-ink-muted">Anda belum memiliki riwayat sewa alat camping.</p>
                            <a href="{{ route('katalog') }}" class="mt-4 inline-flex px-6 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition">
                                Mulai Sewa di Katalog &rarr;
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Tab 3: Pengajuan & Status Pembatalan -->
                <div x-show="tab === 'cancellations'" x-cloak class="space-y-8">
                    <!-- Cancellation Request Form -->
                    <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8">
                        <h2 class="font-display text-xl font-bold text-ink">Ajukan Pembatalan Booking</h2>
                        <p class="text-xs text-ink-muted mt-1 mb-5">
                            Pembatalan dapat diajukan sebelum alat diambil (status <em>pending</em>, <em>approved</em>, <em>waiting_verification</em>, atau <em>confirmed</em>).
                        </p>

                        @if ($cancellableBookings->isEmpty())
                            <div class="rounded-2xl bg-canvas-warm border border-canvas-border p-4 text-xs text-ink-muted">
                                Tidak ada booking yang memenuhi syarat untuk diajukan pembatalan saat ini.
                            </div>
                        @else
                            <form
                                method="POST"
                                :action="'{{ url('/bookings') }}/' + cancelBookingId + '/cancel'"
                                class="space-y-4 max-w-2xl"
                            >
                                @csrf
                                <div>
                                    <label for="cancel_booking_id" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                        Pilih Kode Booking
                                    </label>
                                    <select
                                        id="cancel_booking_id"
                                        x-model="cancelBookingId"
                                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                                    >
                                        @foreach ($cancellableBookings as $eligible)
                                            <option value="{{ $eligible->id }}">
                                                {{ $eligible->booking_code }} ({{ $eligible->start_date?->format('d M Y') }} · {{ strtoupper($eligible->status) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="reason" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                        Alasan Pembatalan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="reason"
                                        name="reason"
                                        rows="3"
                                        required
                                        placeholder="Jelaskan alasan pembatalan Anda..."
                                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                                    >{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="px-6 py-2.5 rounded-full bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition">
                                    Kirim Pengajuan Pembatalan
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Cancellation History Table -->
                    <div class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-canvas-border/70">
                            <h2 class="font-display text-xl font-bold text-ink">Riwayat Pengajuan Pembatalan</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-canvas-border/60">
                                <thead class="bg-canvas-warm/80">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode Booking</th>
                                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Alasan</th>
                                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Catatan / Refund</th>
                                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-canvas-border/60">
                                    @forelse ($cancellations as $cancellation)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-ink">
                                                {{ $cancellation->booking?->booking_code ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-ink-muted max-w-md">
                                                {{ $cancellation->reason }}
                                            </td>
                                            <td class="px-6 py-4 text-xs text-ink-muted">
                                                @if ($cancellation->refund_amount > 0)
                                                    <span class="font-semibold text-ink">Refund: Rp {{ number_format($cancellation->refund_amount, 0, ',', '.') }}</span><br>
                                                @endif
                                                <span>{{ $cancellation->admin_notes ?: 'Menunggu review' }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @php
                                                    $cancelClass = match ($cancellation->status) {
                                                        'approved' => 'bg-meadow-50 text-meadow-700',
                                                        'rejected' => 'bg-red-50 text-red-700',
                                                        default => 'bg-earth-50 text-earth-700',
                                                    };
                                                @endphp
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold uppercase {{ $cancelClass }}">
                                                    {{ $cancellation->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-sm text-ink-muted">
                                                Belum ada pengajuan pembatalan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Realistic Anti-Fraud Payment Modal -->
            <div
                x-show="payModalOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/50 backdrop-blur-sm overflow-y-auto"
            >
                <div @click.away="payModalOpen = false" class="bg-white rounded-3xl border border-canvas-border shadow-2xl max-w-lg w-full p-6 sm:p-8 my-8">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-earth-600">Pembayaran &amp; Verifikasi</p>
                            <h3 class="font-display text-xl font-bold text-ink mt-0.5" x-text="activePay.code"></h3>
                        </div>
                        <button type="button" @click="payModalOpen = false" class="text-ink-muted hover:text-ink text-xl leading-none">&times;</button>
                    </div>

                    <!-- Rejection Reason Banner inside Modal -->
                    <template x-if="activePay.rejection_reason">
                        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-900">
                            <p class="font-bold">Alasan Penolakan Sebelumnya:</p>
                            <p class="mt-0.5" x-text="activePay.rejection_reason"></p>
                        </div>
                    </template>

                    <!-- Nominal Breakdown Card -->
                    <div class="rounded-2xl bg-gradient-to-br from-meadow-700 to-meadow-600 text-white p-4 mb-5 space-y-2 text-xs">
                        <div class="flex justify-between text-meadow-50">
                            <span>Biaya Sewa Alat</span>
                            <span x-text="activePay.base_price_formatted"></span>
                        </div>
                        <div class="flex justify-between text-earth-100 font-medium">
                            <span>Kode Unik Verifikasi (3 Digit)</span>
                            <span class="font-mono" x-text="'+ Rp ' + activePay.unique_code"></span>
                        </div>
                        <div class="pt-2 border-t border-white/20 flex justify-between items-baseline">
                            <span class="font-semibold text-white">Total Nominal Transfer</span>
                            <span class="font-display text-2xl font-bold text-white" x-text="activePay.payable_total_formatted"></span>
                        </div>
                        <p class="text-[11px] text-meadow-100 leading-relaxed">
                            Transfer tepat hingga 3 digit terakhir agar Staff dapat memverifikasi pembayaran Anda secara cepat &amp; akurat.
                        </p>
                    </div>

                    <form
                        method="POST"
                        :action="'{{ url('/bookings') }}/' + payBookingId + '/pay'"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 p-3 rounded-xl border border-canvas-border cursor-pointer hover:border-meadow-500">
                                    <input type="radio" name="payment_method" value="Transfer Bank" x-model="payMethod" class="text-meadow-500 focus:ring-meadow-500">
                                    <span class="text-xs font-semibold text-ink">Transfer Bank</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 rounded-xl border border-canvas-border cursor-pointer hover:border-meadow-500">
                                    <input type="radio" name="payment_method" value="QRIS" x-model="payMethod" class="text-meadow-500 focus:ring-meadow-500">
                                    <span class="text-xs font-semibold text-ink">QRIS Toko</span>
                                </label>
                            </div>
                        </div>

                        <!-- Transfer Bank Info -->
                        <div x-show="payMethod === 'Transfer Bank'" class="rounded-2xl bg-canvas-warm border border-canvas-border p-4 text-xs text-ink-muted space-y-1.5">
                            <p class="font-bold text-ink">Rekening Resmi Pack &amp; Pitch Outdoor:</p>
                            <p><strong>BCA:</strong> <span class="font-mono">123-456-7890</span> a.n. Pack &amp; Pitch Outdoor</p>
                            <p><strong>Mandiri:</strong> <span class="font-mono">140-009-8877-661</span> a.n. Pack &amp; Pitch Outdoor</p>
                        </div>

                        <!-- QRIS Store Info (Clean SVG Illustration) -->
                        <div x-show="payMethod === 'QRIS'" x-cloak class="rounded-2xl bg-canvas-warm border border-canvas-border p-4 flex items-center gap-4">
                            <div class="bg-white p-2 rounded-xl border border-canvas-border shrink-0">
                                <svg width="88" height="88" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="QRIS Pack & Pitch">
                                    <rect width="88" height="88" rx="6" fill="white"/>
                                    <rect x="8" y="8" width="24" height="24" stroke="#2B2B2B" stroke-width="4"/>
                                    <rect x="16" y="16" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="56" y="8" width="24" height="24" stroke="#2B2B2B" stroke-width="4"/>
                                    <rect x="64" y="16" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="8" y="56" width="24" height="24" stroke="#2B2B2B" stroke-width="4"/>
                                    <rect x="16" y="64" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="40" y="10" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="40" y="26" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="10" y="40" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="26" y="40" width="22" height="8" fill="#2B2B2B"/>
                                    <rect x="56" y="40" width="12" height="8" fill="#2B2B2B"/>
                                    <rect x="72" y="40" width="8" height="8" fill="#2B2B2B"/>
                                    <rect x="40" y="56" width="8" height="14" fill="#2B2B2B"/>
                                    <rect x="56" y="56" width="24" height="8" fill="#2B2B2B"/>
                                    <rect x="56" y="72" width="12" height="8" fill="#2B2B2B"/>
                                    <rect x="72" y="72" width="8" height="8" fill="#2B2B2B"/>
                                </svg>
                            </div>
                            <div class="text-xs text-ink-muted space-y-1">
                                <p class="font-bold text-ink">QRIS Nasional — Pack &amp; Pitch Basecamp</p>
                                <p>NMID: <span class="font-mono">ID1026000498812</span></p>
                                <p class="text-[11px] text-ink-muted">Mendukung GoPay, OVO, DANA, ShopeePay &amp; seluruh Mobile Banking.</p>
                            </div>
                        </div>

                        <div>
                            <label for="payment_sender_name" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Nama Pengirim (Sesuai Rekening / E-Wallet) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="payment_sender_name"
                                name="payment_sender_name"
                                value="{{ old('payment_sender_name') }}"
                                required
                                placeholder="Contoh: Raka Pratama"
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                            >
                            @error('payment_sender_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_proof" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Upload Bukti Transfer (JPG/PNG, Maks 2MB) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="file"
                                id="payment_proof"
                                name="payment_proof"
                                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                required
                                class="block w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-meadow-500 file:text-white hover:file:bg-meadow-600 border border-canvas-border rounded-xl p-1.5 bg-canvas-warm/40"
                            >
                            @error('payment_proof')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2">
                            <button type="button" @click="payModalOpen = false" class="px-4 py-2 rounded-full border border-canvas-border text-xs font-semibold text-ink-muted hover:bg-canvas-warm transition">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                Kirim Bukti Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </x-public-layout>
@endif
