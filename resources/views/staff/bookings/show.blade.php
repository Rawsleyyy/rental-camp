<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Detail Transaksi"
        :title="'Booking ' . $booking->booking_code"
        subtitle="Tinjau rincian alat, verifikasi bukti pembayaran, serah terima alat, dan proses pengembalian beserta kalkulasi denda otomatis."
        actionLabel="← Kembali ke Daftar"
        :actionHref="route('staff.bookings.index')"
    />

    <x-admin-flash />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Column: Info & Items -->
        <div class="lg:col-span-7 space-y-6">
            <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-canvas-border/70">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-earth-600 font-semibold">Kode Transaksi</p>
                        <p class="font-mono text-2xl font-bold text-ink mt-0.5">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-meadow-100 text-meadow-700 border border-meadow-200">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div class="rounded-2xl bg-canvas-warm p-4 border border-canvas-border">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-earth-600">Identitas Penyewa</p>
                        <p class="font-bold text-ink mt-1">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                        <p class="text-xs text-ink-muted">{{ $booking->contact_email ?? $booking->user?->email }}</p>
                        <p class="text-xs text-ink-muted">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                        @if ($booking->user?->address)
                            <p class="text-xs text-ink-muted mt-1">{{ $booking->user->address }}</p>
                        @endif
                    </div>

                    <div class="rounded-2xl bg-canvas-warm p-4 border border-canvas-border">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-earth-600">Jadwal &amp; Biaya Sewa</p>
                        <p class="font-bold text-ink mt-1">
                            {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }}
                        </p>
                        <p class="text-xs text-ink-muted">Durasi: {{ $booking->total_days }} hari</p>
                        <p class="text-xs text-ink-muted mt-1">Harga Sewa: <strong class="text-ink">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
                        @if ($booking->unique_code)
                            <p class="text-xs text-earth-700 font-semibold">
                                Kode Unik: {{ $booking->unique_code }} &middot; Total Transfer: Rp {{ number_format($booking->payable_total, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Rented Items Table -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-ink-muted mb-3">Daftar Alat Camping</h3>
                    <div class="border border-canvas-border rounded-2xl overflow-hidden">
                        <table class="min-w-full divide-y divide-canvas-border/60 text-sm">
                            <thead class="bg-canvas-warm/80">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-[11px] font-semibold uppercase text-ink-muted">Item</th>
                                    <th class="px-4 py-2.5 text-center text-[11px] font-semibold uppercase text-ink-muted">Qty</th>
                                    <th class="px-4 py-2.5 text-right text-[11px] font-semibold uppercase text-ink-muted">Tarif / Hari</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-canvas-border/60">
                                @foreach ($booking->bookingItems as $bi)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-ink">{{ $bi->item->name ?? 'Alat Camping' }}</td>
                                        <td class="px-4 py-3 text-center font-bold text-ink">{{ $bi->quantity }}</td>
                                        <td class="px-4 py-3 text-right text-ink-muted">Rp {{ number_format($bi->price_at_booking, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($booking->rejection_reason)
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-900">
                        <strong class="font-bold">Alasan Penolakan Sewa:</strong> {{ $booking->rejection_reason }}
                    </div>
                @endif

                @if ($booking->picked_up_at)
                    <div class="rounded-2xl border border-meadow-200 bg-meadow-50/70 p-5 space-y-3 text-xs">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-bold text-sm text-meadow-900">Checklist Serah Terima Basecamp (Tersimpan)</p>
                            <span class="text-[11px] font-semibold text-meadow-800">
                                Diserahkan: {{ $booking->picked_up_at->format('d M Y H:i') }}
                                @if ($booking->handoverStaff)
                                    &middot; Oleh {{ $booking->handoverStaff->name }}
                                @endif
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach ($booking->bookingItems as $bi)
                                <div class="flex items-start justify-between gap-3 bg-white rounded-xl border border-meadow-200/70 px-3.5 py-2.5">
                                    <div class="flex items-start gap-2.5">
                                        <span class="mt-0.5 w-4 h-4 rounded-full bg-meadow-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0">&#10003;</span>
                                        <div>
                                            <p class="font-semibold text-ink">{{ $bi->item->name ?? 'Alat Camping' }} (&times;{{ $bi->quantity }})</p>
                                            <p class="text-[11px] text-ink-muted">{{ $bi->handover_notes ?: 'Kondisi fisik, kelengkapan & kebersihan dicek' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase text-meadow-700 shrink-0">Lolos Cek</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($booking->status === 'returned')
                    <div class="rounded-2xl border border-canvas-border bg-canvas-warm p-5 space-y-2 text-xs">
                        <p class="font-bold text-sm text-ink">Ringkasan Pengembalian</p>
                        <p>Tanggal Kembali Aktual: <strong>{{ $booking->returned_at?->format('d M Y H:i') }}</strong></p>
                        <p>Kondisi Alat: <strong class="uppercase">{{ str_replace('_', ' ', $booking->return_condition ?? 'baik') }}</strong></p>
                        <p>Status Waktu:
                            @if ($booking->is_late)
                                <span class="text-red-600 font-bold">Terlambat {{ $booking->late_days }} Hari (Denda Telat: Rp {{ number_format($booking->late_fee, 0, ',', '.') }})</span>
                            @else
                                <span class="text-meadow-700 font-bold">Dikembalikan Tepat Waktu</span>
                            @endif
                        </p>
                        @if ($booking->damage_fee > 0)
                            <p class="text-red-600 font-bold">Denda Kerusakan / Kehilangan: Rp {{ number_format($booking->damage_fee, 0, ',', '.') }}</p>
                        @endif
                        <p class="pt-1 text-sm font-bold text-ink">
                            Total Denda Keseluruhan: Rp {{ number_format($booking->total_penalty, 0, ',', '.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Contextual Action Panel -->
        <div class="lg:col-span-5 space-y-6">
            @if ($booking->status === 'pending')
                <!-- Pending: Approve or Reject -->
                <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 space-y-5">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-earth-600">Tindakan Diperlukan</p>
                        <h3 class="font-display text-xl font-bold text-ink mt-1">Persetujuan Sewa (ACC)</h3>
                        <p class="text-xs text-ink-muted mt-1">Menyetujui booking akan otomatis menghasilkan <strong>Kode Unik 3 Digit</strong> untuk proses transfer penyewa.</p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.approve', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Setujui Sewa (ACC) &amp; Generate Kode Unik
                        </button>
                    </form>

                    <div class="pt-4 border-t border-canvas-border/70">
                        <form method="POST" action="{{ route('staff.bookings.reject', $booking) }}" class="space-y-3">
                            @csrf
                            <label for="rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted">
                                Atau Tolak Pengajuan (Wajib Isi Alasan)
                            </label>
                            <textarea
                                id="rejection_reason"
                                name="rejection_reason"
                                rows="3"
                                required
                                placeholder="Misal: Unit sedang dalam perawatan berkala..."
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-red-600 focus:ring-red-600"
                            ></textarea>
                            <button type="submit" class="w-full py-2.5 rounded-full border border-red-300 text-red-700 text-xs font-semibold hover:bg-red-50 transition">
                                Tolak Sewa
                            </button>
                        </form>
                    </div>
                </div>

            @elseif ($booking->status === 'approved')
                <div data-aos="fade-up" class="bg-white rounded-3xl border border-skyblock-200 shadow-sm p-6 sm:p-8 space-y-3">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-skyblock-700">Menunggu Pembayaran</p>
                    <h3 class="font-display text-xl font-bold text-ink">Kode Unik: <span class="font-mono text-earth-700">{{ $booking->unique_code }}</span></h3>
                    <p class="text-xs text-ink-muted">
                        Total yang harus ditransfer penyewa adalah <strong class="text-ink">Rp {{ number_format($booking->payable_total, 0, ',', '.') }}</strong>.
                        Staff dapat memverifikasi setelah penyewa mengunggah bukti pembayaran.
                    </p>
                    @if ($booking->payment_rejection_reason)
                        <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-xs text-red-800">
                            <strong>Penolakan Bukti Sebelumnya:</strong> {{ $booking->payment_rejection_reason }}
                        </div>
                    @endif
                </div>

            @elseif ($booking->status === 'waiting_verification')
                <!-- Waiting Verification: Show Proof + Verify or Reject Payment -->
                <div data-aos="fade-up" class="bg-white rounded-3xl border border-purple-200 shadow-sm p-6 sm:p-8 space-y-5">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-purple-600">Verifikasi Anti-Penipuan</p>
                        <h3 class="font-display text-xl font-bold text-ink mt-1">Periksa Bukti Pembayaran</h3>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-meadow-700 to-meadow-600 text-white p-4 text-xs space-y-1.5">
                        <p class="text-meadow-100">Nominal Harus Masuk (Sewa + Kode Unik {{ $booking->unique_code }}):</p>
                        <p class="font-display text-2xl font-bold text-white">Rp {{ number_format($booking->payable_total, 0, ',', '.') }}</p>
                        <p class="pt-1 text-meadow-50">Metode: <strong>{{ $booking->payment_method }}</strong> &middot; Pengirim: <strong>{{ $booking->payment_sender_name }}</strong></p>
                    </div>

                    @if ($booking->payment_proof)
                        <div class="border border-canvas-border rounded-2xl overflow-hidden bg-canvas-warm p-2">
                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer {{ $booking->booking_code }}" class="w-full max-h-72 object-contain rounded-xl">
                            </a>
                            <p class="text-[11px] text-center text-ink-muted mt-2">Klik gambar untuk melihat ukuran penuh</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.bookings.verify-payment', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Konfirmasi Pembayaran (Valid)
                        </button>
                    </form>

                    <div class="pt-4 border-t border-canvas-border/70">
                        <form method="POST" action="{{ route('staff.bookings.reject-payment', $booking) }}" class="space-y-3">
                            @csrf
                            <label for="payment_rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted">
                                Tolak Bukti Pembayaran (Kembalikan ke Penyewa)
                            </label>
                            <textarea
                                id="payment_rejection_reason"
                                name="payment_rejection_reason"
                                rows="2"
                                required
                                placeholder="Misal: Nominal transfer kurang 3 digit kode unik / bukti buram..."
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-red-600 focus:ring-red-600"
                            ></textarea>
                            <button type="submit" class="w-full py-2.5 rounded-full border border-red-300 text-red-700 text-xs font-semibold hover:bg-red-50 transition">
                                Tolak Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>

            @elseif ($booking->status === 'confirmed')
                <!-- Confirmed: Handover Gear Checklist at Basecamp -->
                <div
                    data-aos="fade-up"
                    x-data="{
                        allChecked: false,
                        toggleAll() {
                            this.allChecked = !this.allChecked;
                            this.$el.querySelectorAll('input[name=\'checked_items[]\']').forEach(cb => cb.checked = this.allChecked);
                        }
                    }"
                    class="bg-white rounded-3xl border border-meadow-200 shadow-sm p-6 sm:p-8 space-y-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-meadow-700">Siap Diambil di Basecamp</p>
                        <h3 class="font-display text-xl font-bold text-ink mt-1">Checklist Serah Terima Alat</h3>
                        <p class="text-xs text-ink-muted mt-1">
                            Pembayaran lunas ({{ $booking->paid_at?->format('d M Y H:i') }}). Periksa fisik, kelengkapan aksesoris &amp; kebersihan setiap item lalu centang sebelum diserahkan ke penyewa.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.checkout', $booking) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="from_checklist" value="1">

                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-ink-muted">Daftar Inspeksi Alat</span>
                            <button type="button" @click="toggleAll()" class="text-xs font-semibold text-meadow-700 hover:text-meadow-600 underline">
                                Centang Semua Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach ($booking->bookingItems as $bi)
                                <div class="rounded-2xl border border-canvas-border bg-canvas-warm/70 p-3.5 space-y-2">
                                    <label class="flex items-start justify-between gap-3 cursor-pointer">
                                        <span class="flex items-start gap-3">
                                            <input
                                                type="checkbox"
                                                name="checked_items[]"
                                                value="{{ $bi->id }}"
                                                @checked(in_array($bi->id, (array) old('checked_items', [])))
                                                required
                                                class="mt-0.5 rounded border-canvas-border text-meadow-500 focus:ring-meadow-500"
                                            >
                                            <span>
                                                <span class="block text-sm font-semibold text-ink">{{ $bi->item->name ?? 'Alat Camping' }}</span>
                                                <span class="block text-[11px] text-ink-muted">Cek fisik, pasak/aksesoris &amp; kebersihan</span>
                                            </span>
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full bg-meadow-100 text-meadow-700 text-[11px] font-bold shrink-0">
                                            {{ $bi->quantity }} Unit
                                        </span>
                                    </label>
                                    <input
                                        type="text"
                                        name="item_notes[{{ $bi->id }}]"
                                        value="{{ old('item_notes.' . $bi->id) }}"
                                        placeholder="Catatan cek (opsional, misal: Frame utuh, 12 pasak lengkap)"
                                        class="w-full rounded-xl border-canvas-border bg-white text-xs text-ink focus:border-meadow-500 focus:ring-meadow-500"
                                    >
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Verifikasi Checklist &amp; Serahkan Alat
                        </button>
                    </form>
                </div>

            @elseif ($booking->status === 'ongoing')
                <!-- Ongoing: Return Processing & Auto Penalty Calculator -->
                @php
                    $dailyAverage = (float) $booking->total_price / max(1, (int) $booking->total_days);
                @endphp
                <div
                    data-aos="fade-up"
                    x-data="{
                        endDate: '{{ $booking->end_date->toDateString() }}',
                        returnedAt: '{{ old('returned_at', now()->toDateString()) }}',
                        dailyAverage: {{ round($dailyAverage, 4) }},
                        condition: '{{ old('return_condition', 'baik') }}',
                        damageFee: {{ (int) old('damage_fee', 0) }},
                        get lateDays() {
                            if (!this.returnedAt || !this.endDate) return 0;
                            const end = new Date(this.endDate + 'T00:00:00');
                            const ret = new Date(this.returnedAt + 'T00:00:00');
                            const diffMs = ret - end;
                            return diffMs > 0 ? Math.round(diffMs / 86400000) : 0;
                        },
                        get lateFee() {
                            return Math.round(this.dailyAverage * this.lateDays);
                        },
                        get totalPenalty() {
                            return this.lateFee + (parseInt(this.damageFee, 10) || 0);
                        },
                        formatRp(val) {
                            return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                        }
                    }"
                    class="bg-white rounded-3xl border border-skyblock-200 shadow-sm p-6 sm:p-8 space-y-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-skyblock-700">Check-in Pengembalian</p>
                        <h3 class="font-display text-xl font-bold text-ink mt-1">Proses Pengembalian Alat</h3>
                        <p class="text-xs text-ink-muted mt-1">
                            Batas pengembalian: <strong class="text-ink">{{ $booking->end_date->format('d M Y') }}</strong> &middot; Tarif rata-rata harian: <strong class="text-ink">Rp {{ number_format($dailyAverage, 0, ',', '.') }}/hari</strong>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.return', $booking) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="returned_at" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Tanggal Kembali Aktual <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="returned_at"
                                name="returned_at"
                                x-model="returnedAt"
                                required
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                            >
                        </div>

                        <!-- Live Late Calculation Banner -->
                        <div
                            :class="lateDays > 0 ? 'border-red-200 bg-red-50 text-red-900' : 'border-meadow-200 bg-meadow-50 text-meadow-900'"
                            class="rounded-2xl border p-4 text-xs space-y-1"
                        >
                            <template x-if="lateDays === 0">
                                <p class="font-bold">Status: Dikembalikan Tepat Waktu (Denda Telat: Rp 0)</p>
                            </template>
                            <template x-if="lateDays > 0">
                                <div>
                                    <p class="font-bold" x-text="'Status: Terlambat ' + lateDays + ' Hari'"></p>
                                    <p class="mt-0.5" x-text="'Estimasi Denda Keterlambatan: ' + formatRp(lateFee)"></p>
                                </div>
                            </template>
                        </div>

                        <div>
                            <label for="return_condition" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Kondisi Barang Saat Kembali <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="return_condition"
                                name="return_condition"
                                x-model="condition"
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                            >
                                <option value="baik">Baik (Lengkap &amp; Utuh)</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>

                        <div>
                            <label for="damaged_item_id" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Item Terkait (Jika Ada Kerusakan/Kehilangan)
                            </label>
                            <select id="damaged_item_id" name="damaged_item_id" class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                                @foreach ($booking->bookingItems as $bi)
                                    <option value="{{ $bi->item_id }}">{{ $bi->item->name ?? 'Alat Camping' }} ({{ $bi->quantity }} unit)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="damage_fee" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Denda Kerusakan / Kehilangan (Rp)
                            </label>
                            <input
                                type="number"
                                id="damage_fee"
                                name="damage_fee"
                                min="0"
                                step="1000"
                                x-model.number="damageFee"
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                            >
                        </div>

                        <div>
                            <label for="damage_notes" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                                Catatan Kondisi / Kerusakan
                            </label>
                            <textarea
                                id="damage_notes"
                                name="damage_notes"
                                rows="2"
                                placeholder="Opsional: catat kondisi frame, resleting, kelengkapan pasak, dll..."
                                class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                            >{{ old('damage_notes') }}</textarea>
                        </div>

                        <div class="rounded-2xl bg-gradient-to-br from-earth-600 to-earth-500 text-white p-4 flex items-center justify-between text-xs">
                            <span class="text-earth-50 font-medium">Total Denda (Telat + Kerusakan)</span>
                            <span class="font-display text-xl font-bold text-white" x-text="formatRp(totalPenalty)"></span>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Selesaikan Pengembalian &amp; Simpan Denda
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
