<x-public-layout title="Checkout & Request Sewa — Rawlseyyy">
    <section class="bg-neutral-950 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-400 mb-2">Form Pengajuan Sewa</p>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white">Checkout &amp; Cek Ketersediaan Tanggal</h1>
            <p class="text-neutral-300 text-sm mt-2">
                Pilih tanggal sewa untuk memvalidasi ketersediaan stok alat secara real-time sebelum mengajukan persetujuan ke Staff.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-sm font-medium text-red-900">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm font-medium text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-red-900">
                    <p class="text-sm font-bold mb-1">Pengajuan sewa gagal diproses:</p>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                @csrf

                <!-- Left: Dates & Contact Form -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="font-serif text-xl font-bold text-neutral-950">1. Jadwal Sewa Alat</h2>
                        <p class="text-xs text-neutral-500 mt-1">Stok setiap item akan diperiksa secara otomatis terhadap booking lain di rentang tanggal ini.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date', now()->toDateString()) }}"
                                min="{{ now()->toDateString() }}"
                                required
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Tanggal Selesai / Kembali <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date', now()->addDay()->toDateString()) }}"
                                min="{{ now()->toDateString() }}"
                                required
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                        </div>
                    </div>

                    <div id="stock-status-banner" class="hidden rounded-2xl px-4 py-3 text-xs font-medium border"></div>

                    <div class="pt-4 border-t border-neutral-100">
                        <h2 class="font-serif text-xl font-bold text-neutral-950">2. Data Kontak Penyewa</h2>
                        <p class="text-xs text-neutral-500 mt-1">Digunakan untuk notifikasi persetujuan dan verifikasi di halaman Cek Booking.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="contact_name" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="contact_name"
                                name="contact_name"
                                value="{{ old('contact_name', $user->name) }}"
                                required
                                class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                            >
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_email" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="contact_email"
                                    value="{{ old('contact_email', $user->email) }}"
                                    required
                                    class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                                >
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="contact_phone"
                                    name="contact_phone"
                                    value="{{ old('contact_phone', $user->phone) }}"
                                    placeholder="0812xxxxxxxx"
                                    required
                                    class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Summary & Per-Item Real-time Stock -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 space-y-5">
                    <h2 class="font-serif text-xl font-bold text-neutral-950">Ringkasan Alat &amp; Biaya</h2>

                    <div class="divide-y divide-neutral-100">
                        @foreach ($carts as $cart)
                            <div class="py-3.5 flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-neutral-950">{{ $cart->item?->name }}</p>
                                    <p class="text-xs text-neutral-500">
                                        {{ $cart->quantity }} unit &times; Rp {{ number_format($cart->item?->daily_rate ?? 0, 0, ',', '.') }}/hari
                                    </p>
                                    <p
                                        id="item-stock-{{ $cart->item_id }}"
                                        class="mt-1 inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-neutral-100 text-neutral-700"
                                    >
                                        Memeriksa stok...
                                    </p>
                                </div>
                                <p class="text-sm font-semibold text-neutral-900 shrink-0">
                                    Rp {{ number_format(($cart->item?->daily_rate ?? 0) * $cart->quantity, 0, ',', '.') }}/hr
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-neutral-200 space-y-2 text-sm">
                        <div class="flex justify-between text-neutral-600">
                            <span>Durasi Sewa</span>
                            <span id="summary-days" class="font-semibold text-neutral-950">2 Hari</span>
                        </div>
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal Harian</span>
                            <span class="font-semibold text-neutral-950">Rp {{ number_format($dailySubtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-3 border-t border-neutral-100">
                            <span class="font-bold text-neutral-950">Total Estimasi Biaya</span>
                            <span id="summary-total" class="font-serif text-2xl font-bold text-neutral-950">
                                Rp {{ number_format($dailySubtotal * 2, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button
                        id="submit-checkout-btn"
                        type="submit"
                        class="w-full py-3.5 px-6 rounded-full bg-neutral-950 text-white text-sm font-semibold hover:bg-amber-400 hover:text-neutral-950 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-md"
                    >
                        Ajukan Sewa (Status Pending)
                    </button>

                    <p class="text-[11px] text-neutral-400 text-center leading-relaxed">
                        Pembayaran baru dilakukan setelah Staff menyetujui (ACC) pengajuan sewa Anda.
                    </p>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const startInput = document.getElementById('start_date');
                const endInput = document.getElementById('end_date');
                const daysEl = document.getElementById('summary-days');
                const totalEl = document.getElementById('summary-total');
                const bannerEl = document.getElementById('stock-status-banner');
                const submitBtn = document.getElementById('submit-checkout-btn');
                const checkUrl = @json(route('checkout.check-stock'));

                const formatRupiah = (amount) => 'Rp ' + Number(amount).toLocaleString('id-ID');

                async function checkAvailability() {
                    const startDate = startInput.value;
                    const endDate = endInput.value;

                    if (!startDate || !endDate) return;

                    if (endDate < startDate) {
                        endInput.value = startDate;
                    }

                    try {
                        const url = `${checkUrl}?start_date=${encodeURIComponent(startInput.value)}&end_date=${encodeURIComponent(endInput.value)}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });

                        if (!res.ok) return;
                        const data = await res.json();

                        daysEl.textContent = `${data.total_days} Hari`;
                        totalEl.textContent = formatRupiah(data.total_price);

                        data.items.forEach((item) => {
                            const badge = document.getElementById(`item-stock-${item.item_id}`);
                            if (!badge) return;

                            if (item.is_sufficient) {
                                badge.className = 'mt-1 inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700';
                                badge.textContent = `Tersedia ${item.available} unit pada tanggal ini`;
                            } else {
                                badge.className = 'mt-1 inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700';
                                badge.textContent = `Stok kurang! Tersedia ${item.available} unit (diminta ${item.requested})`;
                            }
                        });

                        bannerEl.classList.remove('hidden');
                        if (data.all_sufficient) {
                            bannerEl.className = 'rounded-2xl px-4 py-3 text-xs font-medium border border-emerald-200 bg-emerald-50 text-emerald-800';
                            bannerEl.textContent = `Seluruh alat tersedia untuk ${data.total_days} hari (${startInput.value} s/d ${endInput.value}).`;
                            submitBtn.disabled = false;
                        } else {
                            bannerEl.className = 'rounded-2xl px-4 py-3 text-xs font-medium border border-red-200 bg-red-50 text-red-800';
                            bannerEl.textContent = 'Sebagian alat tidak mencukupi stoknya pada rentang tanggal yang dipilih. Silakan ubah tanggal atau kurangi jumlah di keranjang.';
                            submitBtn.disabled = true;
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }

                startInput.addEventListener('change', checkAvailability);
                endInput.addEventListener('change', checkAvailability);
                checkAvailability();
            });
        </script>
    @endpush
</x-public-layout>
