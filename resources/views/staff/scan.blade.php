<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Basecamp Check-In & Check-Out"
        title="Scan QR Kartu Pengambilan"
        subtitle="Pindai QR Code dari Kartu Pengambilan Alat penyewa via kamera, unggah foto QR, atau masukkan Kode Booking secara cepat."
        actionLabel="Manajemen Transaksi"
        :actionHref="route('staff.bookings.index')"
    />

    <x-admin-flash />

    @if ($notFoundCode)
        <div data-aos="fade-up" class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-900">
            Kode Booking <strong class="font-mono">{{ $notFoundCode }}</strong> tidak ditemukan di database. Pastikan kode yang dipindai benar.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Camera QR Scanner + Upload QR Image -->
        <div data-aos="fade-up" class="lg:col-span-6 bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-earth-600">Pemindai Kamera Real-Time</p>
                    <h2 class="font-display text-xl font-bold text-ink mt-0.5">Scan QR Code Basecamp</h2>
                </div>
                <span id="scan-status-pill" class="px-3 py-1 rounded-full bg-canvas-warm border border-canvas-border text-ink-muted text-xs font-semibold">
                    Kamera Nonaktif
                </span>
            </div>

            <div class="rounded-2xl border-2 border-dashed border-canvas-border bg-canvas-warm overflow-hidden p-3">
                <div id="qr-reader" class="w-full min-h-[260px] rounded-xl overflow-hidden bg-ink flex items-center justify-center text-xs text-neutral-300">
                    <span>Tekan "Mulai Kamera" untuk mengaktifkan scanner QR</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    id="start-scan-btn"
                    class="px-5 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20"
                >
                    Mulai Kamera
                </button>
                <button
                    type="button"
                    id="stop-scan-btn"
                    class="hidden px-5 py-2.5 rounded-full border border-canvas-border text-ink-muted text-xs font-semibold hover:bg-canvas-warm transition"
                >
                    Hentikan Kamera
                </button>

                <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-meadow-500/60 text-xs font-semibold text-meadow-700 hover:bg-meadow-50 cursor-pointer transition">
                    <svg class="w-4 h-4 text-meadow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Scan Gambar QR</span>
                    <input type="file" id="qr-file-input" accept="image/*" class="hidden">
                </label>
            </div>

            <p id="scan-error-msg" class="hidden text-xs text-red-600 font-medium"></p>
        </div>

        <!-- Right: USB Scanner / Manual Input -->
        <div data-aos="fade-up" data-aos-delay="100" class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 space-y-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-meadow-700">USB Scanner / Input Cepat</p>
                    <h2 class="font-display text-xl font-bold text-ink mt-0.5">Cari atau Scan Kode Booking</h2>
                    <p class="text-xs text-ink-muted mt-1">
                        Arahkan USB barcode scanner ke kolom di bawah atau ketik kode booking, lalu tekan Enter untuk langsung membuka checklist serah terima.
                    </p>
                </div>

                <form id="scan-lookup-form" method="POST" action="{{ route('staff.scan') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="booking_code_input" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">
                            Kode Booking <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="booking_code_input"
                            name="booking_code"
                            value="{{ old('booking_code', $code ?? '') }}"
                            placeholder="RWL-20261122-XXXX"
                            autofocus
                            required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-base font-mono font-bold tracking-wider text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                        >
                    </div>

                    <button type="submit" class="w-full py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                        Verifikasi &amp; Buka Transaksi
                    </button>
                </form>
            </div>

            <!-- Ready for Pickup Shortcut -->
            <div class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-canvas-border/70 flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-lg text-ink">Siap Diambil Basecamp (Confirmed)</h3>
                        <p class="text-xs text-ink-muted">Klik langsung untuk verifikasi checklist &amp; serahkan alat</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-meadow-100 text-meadow-700 text-xs font-bold">{{ $readyBookings->count() }}</span>
                </div>
                <div class="divide-y divide-canvas-border/60">
                    @forelse ($readyBookings as $booking)
                        <a href="{{ route('staff.bookings.show', $booking) }}" class="px-6 py-3.5 flex items-center justify-between gap-4 hover:bg-canvas-warm/60 transition">
                            <div>
                                <p class="font-mono text-sm font-bold text-ink">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->contact_name ?? $booking->user?->name }} &middot; {{ $booking->bookingItems->count() }} item</p>
                            </div>
                            <span class="px-3 py-1.5 rounded-full bg-meadow-500 text-white text-xs font-semibold shrink-0">
                                Cek &amp; Serahkan &rarr;
                            </span>
                        </a>
                    @empty
                        <div class="px-6 py-6 text-center text-xs text-ink-muted">
                            Belum ada booking berstatus Siap Diambil (Confirmed).
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const startBtn = document.getElementById('start-scan-btn');
                const stopBtn = document.getElementById('stop-scan-btn');
                const fileInput = document.getElementById('qr-file-input');
                const statusPill = document.getElementById('scan-status-pill');
                const errorMsg = document.getElementById('scan-error-msg');
                const codeInput = document.getElementById('booking_code_input');
                const lookupForm = document.getElementById('scan-lookup-form');

                let html5QrCode = null;
                let isScanning = false;

                function showError(msg) {
                    errorMsg.textContent = msg;
                    errorMsg.classList.remove('hidden');
                }

                function clearError() {
                    errorMsg.classList.add('hidden');
                    errorMsg.textContent = '';
                }

                function handleDecodedCode(decodedText) {
                    const cleaned = String(decodedText || '').trim().replace(/^#/, '');
                    if (!cleaned) return;

                    codeInput.value = cleaned;
                    statusPill.textContent = 'QR Terdeteksi!';
                    statusPill.className = 'px-3 py-1 rounded-full bg-meadow-100 text-meadow-800 text-xs font-bold';

                    if (html5QrCode && isScanning) {
                        html5QrCode.stop().catch(() => {}).finally(() => {
                            lookupForm.submit();
                        });
                    } else {
                        lookupForm.submit();
                    }
                }

                startBtn.addEventListener('click', async function () {
                    clearError();

                    if (typeof Html5Qrcode === 'undefined') {
                        showError('Library pemindai kamera gagal dimuat. Gunakan input manual atau Scan Gambar QR.');
                        return;
                    }

                    try {
                        html5QrCode = html5QrCode || new Html5Qrcode('qr-reader');
                        await html5QrCode.start(
                            { facingMode: 'environment' },
                            { fps: 10, qrbox: { width: 220, height: 220 } },
                            (decodedText) => handleDecodedCode(decodedText),
                            () => {}
                        );
                        isScanning = true;
                        startBtn.classList.add('hidden');
                        stopBtn.classList.remove('hidden');
                        statusPill.textContent = 'Kamera Aktif...';
                        statusPill.className = 'px-3 py-1 rounded-full bg-earth-100 text-earth-800 text-xs font-bold';
                    } catch (err) {
                        showError('Kamera tidak tersedia atau izin ditolak browser. Gunakan fitur "Scan Gambar QR" atau input kode manual.');
                    }
                });

                stopBtn.addEventListener('click', async function () {
                    if (html5QrCode && isScanning) {
                        await html5QrCode.stop().catch(() => {});
                        isScanning = false;
                    }
                    stopBtn.classList.add('hidden');
                    startBtn.classList.remove('hidden');
                    statusPill.textContent = 'Kamera Nonaktif';
                    statusPill.className = 'px-3 py-1 rounded-full bg-canvas-warm border border-canvas-border text-ink-muted text-xs font-semibold';
                });

                fileInput.addEventListener('change', async function (e) {
                    clearError();
                    const file = e.target.files?.[0];
                    if (!file) return;

                    if (typeof Html5Qrcode === 'undefined') {
                        showError('Library pemindai QR gagal dimuat.');
                        return;
                    }

                    try {
                        if (html5QrCode && isScanning) {
                            await html5QrCode.stop().catch(() => {});
                            isScanning = false;
                            stopBtn.classList.add('hidden');
                            startBtn.classList.remove('hidden');
                        }
                        const fileScanner = html5QrCode || new Html5Qrcode('qr-reader');
                        const result = await fileScanner.scanFile(file, true);
                        handleDecodedCode(result);
                    } catch (err) {
                        showError('QR Code tidak terbaca pada gambar tersebut. Pastikan gambar jelas.');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
