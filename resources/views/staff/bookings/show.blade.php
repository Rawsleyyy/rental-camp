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
            <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-neutral-100">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Kode Transaksi</p>
                        <p class="font-mono text-2xl font-bold text-neutral-950 mt-0.5">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-neutral-950 text-amber-400">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div class="rounded-2xl bg-neutral-50 p-4 border border-neutral-200/70">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">Identitas Penyewa</p>
                        <p class="font-bold text-neutral-950 mt-1">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                        <p class="text-xs text-neutral-600">{{ $booking->contact_email ?? $booking->user?->email }}</p>
                        <p class="text-xs text-neutral-600">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                        @if ($booking->user?->address)
                            <p class="text-xs text-neutral-500 mt-1">{{ $booking->user->address }}</p>
                        @endif
                    </div>

                    <div class="rounded-2xl bg-neutral-50 p-4 border border-neutral-200/70">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">Jadwal &amp; Biaya Sewa</p>
                        <p class="font-bold text-neutral-950 mt-1">
                            {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }}
                        </p>
                        <p class="text-xs text-neutral-600">Durasi: {{ $booking->total_days }} hari</p>
                        <p class="text-xs text-neutral-600 mt-1">Harga Sewa: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
                        @if ($booking->unique_code)
                            <p class="text-xs text-amber-700 font-semibold">
                                Kode Unik: {{ $booking->unique_code }} &middot; Total Transfer: Rp {{ number_format($booking->payable_total, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Rented Items Table -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-500 mb-3">Daftar Alat Camping</h3>
                    <div class="border border-neutral-200 rounded-2xl overflow-hidden">
                        <table class="min-w-full divide-y divide-neutral-100 text-sm">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-[11px] font-semibold uppercase text-neutral-500">Item</th>
                                    <th class="px-4 py-2.5 text-center text-[11px] font-semibold uppercase text-neutral-500">Qty</th>
                                    <th class="px-4 py-2.5 text-right text-[11px] font-semibold uppercase text-neutral-500">Tarif / Hari</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                @foreach ($booking->bookingItems as $bi)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-neutral-900">{{ $bi->item->name ?? 'Alat Camping' }}</td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $bi->quantity }}</td>
                                        <td class="px-4 py-3 text-right text-neutral-600">Rp {{ number_format($bi->price_at_booking, 0, ',', '.') }}</td>
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
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-5 space-y-3 text-xs">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-bold text-sm text-emerald-950">Checklist Serah Terima Basecamp (Tersimpan)</p>
                            <span class="text-[11px] font-semibold text-emerald-800">
                                Diserahkan: {{ $booking->picked_up_at->format('d M Y H:i') }}
                                @if ($booking->handoverStaff)
                                    &middot; Oleh {{ $booking->handoverStaff->name }}
                                @endif
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach ($booking->bookingItems as $bi)
                                <div class="flex items-start justify-between gap-3 bg-white rounded-xl border border-emerald-200/70 px-3.5 py-2.5">
                                    <div class="flex items-start gap-2.5">
                                        <span class="mt-0.5 w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">&#10003;</span>
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $bi->item->name ?? 'Alat Camping' }} (&times;{{ $bi->quantity }})</p>
                                            <p class="text-[11px] text-neutral-500">{{ $bi->handover_notes ?: 'Kondisi fisik, kelengkapan & kebersihan dicek' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase text-emerald-700 shrink-0">Lolos Cek</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($booking->status === 'returned')
                    <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-5 space-y-2 text-xs">
                        <p class="font-bold text-sm text-neutral-950">Ringkasan Pengembalian</p>
                        <p>Tanggal Kembali Aktual: <strong>{{ $booking->returned_at?->format('d M Y H:i') }}</strong></p>
                        <p>Kondisi Alat: <strong class="uppercase">{{ str_replace('_', ' ', $booking->return_condition ?? 'baik') }}</strong></p>
                        <p>Status Waktu:
                            @if ($booking->is_late)
                                <span class="text-red-600 font-bold">Terlambat {{ $booking->late_days }} Hari (Denda Telat: Rp {{ number_format($booking->late_fee, 0, ',', '.') }})</span>
                            @else
                                <span class="text-emerald-700 font-bold">Dikembalikan Tepat Waktu</span>
                            @endif
                        </p>
                        @if ($booking->damage_fee > 0)
                            <p class="text-red-600 font-bold">Denda Kerusakan / Kehilangan: Rp {{ number_format($booking->damage_fee, 0, ',', '.') }}</p>
                        @endif
                        <p class="pt-1 text-sm font-bold text-neutral-950">
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
                <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 space-y-5">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-amber-600">Tindakan Diperlukan</p>
                        <h3 class="font-serif text-xl font-bold text-neutral-950 mt-1">Persetujuan Sewa (ACC)</h3>
                        <p class="text-xs text-neutral-500 mt-1">Menyetujui booking akan otomatis menghasilkan <strong>Kode Unik 3 Digit</strong> untuk proses transfer penyewa.</p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.approve', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                            Setujui Sewa (ACC) &amp; Generate Kode Unik
                        </button>
                    </form>

                    <div class="pt-4 border-t border-neutral-100">
                        <form method="POST" action="{{ route('staff.bookings.reject', $booking) }}" class="space-y-3">
                            @csrf
                            <label for="rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600">
                                Atau Tolak Pengajuan (Wajib Isi Alasan)
                            </label>
                            <textarea
                                id="rejection_reason"
                                name="rejection_reason"
                                rows="3"
                                required
                                placeholder="Misal: Unit sedang dalam perawatan berkala..."
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-red-600 focus:ring-red-600"
                            ></textarea>
                            <button type="submit" class="w-full py-2.5 rounded-full border border-red-300 text-red-700 text-xs font-semibold hover:bg-red-50 transition">
                                Tolak Sewa
                            </button>
                        </form>
                    </div>
                </div>

            @elseif ($booking->status === 'approved')
                <div data-aos="fade-up" class="bg-white rounded-3xl border border-blue-200 shadow-sm p-6 sm:p-8 space-y-3">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-blue-600">Menunggu Pembayaran</p>
                    <h3 class="font-serif text-xl font-bold text-neutral-950">Kode Unik: <span class="font-mono text-amber-600">{{ $booking->unique_code }}</span></h3>
                    <p class="text-xs text-neutral-600">
                        Total yang harus ditransfer penyewa adalah <strong>Rp {{ number_format($booking->payable_total, 0, ',', '.') }}</strong>.
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
                        <h3 class="font-serif text-xl font-bold text-neutral-950 mt-1">Periksa Bukti Pembayaran</h3>
                    </div>

                    <div class="rounded-2xl bg-neutral-950 text-white p-4 text-xs space-y-1.5">
                        <p class="text-neutral-400">Nominal Harus Masuk (Sewa + Kode Unik {{ $booking->unique_code }}):</p>
                        <p class="font-serif text-2xl font-bold text-amber-400">Rp {{ number_format($booking->payable_total, 0, ',', '.') }}</p>
                        <p class="pt-1 text-neutral-300">Metode: <strong>{{ $booking->payment_method }}</strong> &middot; Pengirim: <strong>{{ $booking->payment_sender_name }}</strong></p>
                    </div>

                    @if ($booking->payment_proof)
                        <div class="border border-neutral-200 rounded-2xl overflow-hidden bg-neutral-50 p-2">
                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer {{ $booking->booking_code }}" class="w-full max-h-72 object-contain rounded-xl">
                            </a>
                            <p class="text-[11px] text-center text-neutral-500 mt-2">Klik gambar untuk melihat ukuran penuh</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.bookings.verify-payment', $booking) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                            Konfirmasi Pembayaran (Valid)
                        </button>
                    </form>

                    <div class="pt-4 border-t border-neutral-100">
                        <form method="POST" action="{{ route('staff.bookings.reject-payment', $booking) }}" class="space-y-3">
                            @csrf
                            <label for="payment_rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600">
                                Tolak Bukti Pembayaran (Kembalikan ke Penyewa)
                            </label>
                            <textarea
                                id="payment_rejection_reason"
                                name="payment_rejection_reason"
                                rows="2"
                                required
                                placeholder="Misal: Nominal transfer kurang 3 digit kode unik / bukti buram..."
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-red-600 focus:ring-red-600"
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
                    class="bg-white rounded-3xl border border-emerald-200 shadow-sm p-6 sm:p-8 space-y-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-600">Siap Diambil di Basecamp</p>
                        <h3 class="font-serif text-xl font-bold text-neutral-950 mt-1">Checklist Serah Terima Alat</h3>
                        <p class="text-xs text-neutral-600 mt-1">
                            Pembayaran lunas ({{ $booking->paid_at?->format('d M Y H:i') }}). Periksa fisik, kelengkapan aksesoris &amp; kebersihan setiap item lalu centang sebelum diserahkan ke penyewa.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.checkout', $booking) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="from_checklist" value="1">

                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-500">Daftar Inspeksi Alat</span>
                            <button type="button" @click="toggleAll()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 underline">
                                Centang Semua Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach ($booking->bookingItems as $bi)
                                <div class="rounded-2xl border border-neutral-200 bg-neutral-50/70 p-3.5 space-y-2">
                                    <label class="flex items-start justify-between gap-3 cursor-pointer">
                                        <span class="flex items-start gap-3">
                                            <input
                                                type="checkbox"
                                                name="checked_items[]"
                                                value="{{ $bi->id }}"
                                                @checked(in_array($bi->id, (array) old('checked_items', [])))
                                                required
                                                class="mt-0.5 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500"
                                            >
                                            <span>
                                                <span class="block text-sm font-semibold text-neutral-950">{{ $bi->item->name ?? 'Alat Camping' }}</span>
                                                <span class="block text-[11px] text-neutral-500">Cek fisik, pasak/aksesoris &amp; kebersihan</span>
                                            </span>
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full bg-neutral-950 text-white text-[11px] font-bold shrink-0">
                                            {{ $bi->quantity }} Unit
                                        </span>
                                    </label>
                                    <input
                                        type="text"
                                        name="item_notes[{{ $bi->id }}]"
                                        value="{{ old('item_notes.' . $bi->id) }}"
                                        placeholder="Catatan cek (opsional, misal: Frame utuh, 12 pasak lengkap)"
                                        class="w-full rounded-xl border-neutral-200 bg-white text-xs focus:border-neutral-950 focus:ring-neutral-950"
                                    >
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
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
                    class="bg-white rounded-3xl border border-indigo-200 shadow-sm p-6 sm:p-8 space-y-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-indigo-600">Check-in Pengembalian</p>
                        <h3 class="font-serif text-xl font-bold text-neutral-950 mt-1">Proses Pengembalian Alat</h3>
                        <p class="text-xs text-neutral-500 mt-1">
                            Batas pengembalian: <strong>{{ $booking->end_date->format('d M Y') }}</strong> &middot; Tarif rata-rata harian: <strong>Rp {{ number_format($dailyAverage, 0, ',', '.') }}/hari</strong>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('staff.bookings.return', $booking) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="returned_at" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Tanggal Kembali Aktual <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="returned_at"
                                name="returned_at"
                                x-model="returnedAt"
                                required
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                        </div>

                        <!-- Live Late Calculation Banner -->
                        <div
                            :class="lateDays > 0 ? 'border-red-200 bg-red-50 text-red-900' : 'border-emerald-200 bg-emerald-50 text-emerald-900'"
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
                            <label for="return_condition" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Kondisi Barang Saat Kembali <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="return_condition"
                                name="return_condition"
                                x-model="condition"
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                                <option value="baik">Baik (Lengkap &amp; Utuh)</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>

                        <div>
                            <label for="damaged_item_id" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Item Terkait (Jika Ada Kerusakan/Kehilangan)
                            </label>
                            <select id="damaged_item_id" name="damaged_item_id" class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
                                @foreach ($booking->bookingItems as $bi)
                                    <option value="{{ $bi->item_id }}">{{ $bi->item->name ?? 'Alat Camping' }} ({{ $bi->quantity }} unit)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="damage_fee" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Denda Kerusakan / Kehilangan (Rp)
                            </label>
                            <input
                                type="number"
                                id="damage_fee"
                                name="damage_fee"
                                min="0"
                                step="1000"
                                x-model.number="damageFee"
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                        </div>

                        <div>
                            <label for="damage_notes" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Catatan Kondisi / Kerusakan
                            </label>
                            <textarea
                                id="damage_notes"
                                name="damage_notes"
                                rows="2"
                                placeholder="Opsional: catat kondisi frame, resleting, kelengkapan pasak, dll..."
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >{{ old('damage_notes') }}</textarea>
                        </div>

                        <div class="rounded-2xl bg-neutral-950 text-white p-4 flex items-center justify-between text-xs">
                            <span class="text-neutral-300 font-medium">Total Denda (Telat + Kerusakan)</span>
                            <span class="font-serif text-xl font-bold text-amber-400" x-text="formatRp(totalPenalty)"></span>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-full bg-neutral-950 text-white text-sm font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                            Selesaikan Pengembalian &amp; Simpan Denda
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
