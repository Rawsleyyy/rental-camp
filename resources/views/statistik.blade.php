<x-public-layout title="Statistik Penyewaan — Rawlseyyy">
    <section class="bg-neutral-950 text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-400 mb-2">Transparansi &amp; Analitik</p>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white">Statistik Penyewaan Rawlseyyy</h1>
            <p class="text-neutral-300 text-sm max-w-2xl mx-auto mt-3">
                Data tren penyewaan bulanan, perbandingan status &amp; pembatalan, serta tingkat pemakaian stok alat camping berdasarkan transaksi riil database.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Summary KPIs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white rounded-3xl border border-neutral-200/80 p-5 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">Total Booking</p>
                    <p class="mt-2 font-serif text-3xl font-bold text-neutral-950">{{ $summary['totalBookings'] }}</p>
                </div>
                <div class="bg-white rounded-3xl border border-neutral-200/80 p-5 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">Pengajuan Batal</p>
                    <p class="mt-2 font-serif text-3xl font-bold text-neutral-950">{{ $summary['totalCancellations'] }}</p>
                </div>
                <div class="bg-white rounded-3xl border border-neutral-200/80 p-5 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">Total Unit Stok</p>
                    <p class="mt-2 font-serif text-3xl font-bold text-neutral-950">{{ $summary['totalItemsStock'] }}</p>
                </div>
                <div class="bg-neutral-950 text-white rounded-3xl border border-neutral-800 p-5 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">Total Unit Disewa</p>
                    <p class="mt-2 font-serif text-3xl font-bold text-amber-400">{{ $summary['totalRentedUnits'] }}</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm">
                    <h2 class="font-serif text-xl font-bold text-neutral-950">1. Tren Jumlah Penyewaan per Bulan</h2>
                    <p class="text-xs text-neutral-500 mt-1 mb-5">Jumlah transaksi sewa alat dalam 6 bulan terakhir.</p>
                    <div class="relative h-72">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm">
                    <h2 class="font-serif text-xl font-bold text-neutral-950">2. Perbandingan Status &amp; Pembatalan</h2>
                    <p class="text-xs text-neutral-500 mt-1 mb-5">Distribusi status seluruh booking termasuk transaksi dibatalkan.</p>
                    <div class="relative h-72">
                        <canvas id="statusBreakdownChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart Row 2 -->
            <div class="bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm">
                <h2 class="font-serif text-xl font-bold text-neutral-950">3. Tingkat Pemakaian Stok Alat (Utilisasi &amp; Popularitas)</h2>
                <p class="text-xs text-neutral-500 mt-1 mb-5">Perbandingan total unit yang pernah disewa terhadap stok yang tersedia per alat.</p>
                <div class="relative h-80">
                    <canvas id="stockUsageChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const monthlyLabels = @json($monthlyLabels);
                const monthlyCounts = @json($monthlyCounts);
                const statusLabels = @json($statusLabels);
                const statusCounts = @json($statusCounts);
                const itemLabels = @json($itemLabels);
                const itemRentedCounts = @json($itemRentedCounts);
                const itemUtilizationRates = @json($itemUtilizationRates);

                // 1. Bar Chart: Monthly Rental Trend
                const monthlyCtx = document.getElementById('monthlyTrendChart');
                if (monthlyCtx) {
                    new Chart(monthlyCtx, {
                        type: 'bar',
                        data: {
                            labels: monthlyLabels,
                            datasets: [{
                                label: 'Jumlah Penyewaan',
                                data: monthlyCounts,
                                backgroundColor: '#171717',
                                hoverBackgroundColor: '#fbbf24',
                                borderRadius: 10,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                }

                // 2. Doughnut Chart: Status & Cancellation Comparison
                const statusCtx = document.getElementById('statusBreakdownChart');
                if (statusCtx) {
                    new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusCounts,
                                backgroundColor: [
                                    '#f59e0b', // pending
                                    '#3b82f6', // approved
                                    '#10b981', // confirmed
                                    '#6366f1', // ongoing
                                    '#171717', // returned
                                    '#ef4444', // cancelled
                                    '#9ca3af', // rejected
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: { boxWidth: 12, font: { size: 11 } }
                                }
                            }
                        }
                    });
                }

                // 3. Mixed Bar + Line Chart: Stock Usage & Utilization
                const stockCtx = document.getElementById('stockUsageChart');
                if (stockCtx) {
                    new Chart(stockCtx, {
                        data: {
                            labels: itemLabels,
                            datasets: [
                                {
                                    type: 'bar',
                                    label: 'Total Unit Disewa',
                                    data: itemRentedCounts,
                                    backgroundColor: '#fbbf24',
                                    borderRadius: 8,
                                    yAxisID: 'y',
                                },
                                {
                                    type: 'line',
                                    label: 'Tingkat Utilisasi (%)',
                                    data: itemUtilizationRates,
                                    borderColor: '#171717',
                                    backgroundColor: '#171717',
                                    tension: 0.3,
                                    yAxisID: 'y1',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    position: 'left',
                                    ticks: { precision: 0 },
                                    title: { display: true, text: 'Unit Disewa' }
                                },
                                y1: {
                                    beginAtZero: true,
                                    max: 100,
                                    position: 'right',
                                    grid: { drawOnChartArea: false },
                                    title: { display: true, text: 'Utilisasi (%)' }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-public-layout>
