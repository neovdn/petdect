@extends('layouts.app')

@section('header_title', 'Laporan Statistik')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Kas Bank Sampah</div>
            <div class="text-2xl font-bold text-emerald-600">
                Rp {{ number_format($currentCash, 0, ',', '.') }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Saldo saat ini</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Total Dana Keluar</div>
            <div class="text-2xl font-bold text-slate-800">
                Rp {{ number_format($totalOutcome, 0, ',', '.') }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Sejak awal beroperasi</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Total Sampah</div>
            <div class="text-2xl font-bold text-slate-800">
                {{ number_format($totalWeight, 1, ',', '.') }} <span class="text-sm font-normal">kg</span>
            </div>
            <div class="text-xs text-slate-400 mt-1">Semua jenis sampah</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Rata-rata Trx</div>
            <div class="text-2xl font-bold text-slate-800">
                Rp {{ number_format($avgTransaction, 0, ',', '.') }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Per transaksi</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all">
            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Transaksi Hari Ini</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ $todayTrxCount }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Transaksi berhasil</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Komposisi Sampah</h3>
            <div class="relative flex-1 min-h-[250px] flex justify-center items-center">
                <canvas id="pieChart"
                    data-pet="{{ $pieData['PET'] }}"
                    data-nonpet="{{ $pieData['NON_PET'] }}">
                </canvas>
            </div>
            <div class="mt-4 flex justify-center space-x-6 text-sm">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> PET
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-slate-300 mr-2"></span> Non-PET
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Cashflow Mingguan</h3>
                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1 rounded-full">4 Minggu Terakhir</span>
            </div>
            <div class="relative flex-1 w-full min-h-[300px]">
                <canvas id="barChart"
                    data-weeks="{{ json_encode($weeks) }}"
                    data-income="{{ json_encode($weeklyIncome) }}"
                    data-expense="{{ json_encode($weeklyExpense) }}">
                </canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Statistik Detail</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            
            <div class="p-6">
                <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Volume Transaksi
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Hari Ini</span>
                        <span class="font-bold text-slate-800">{{ $statsTrx['today'] }} Trx</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Minggu Ini</span>
                        <span class="font-bold text-slate-800">{{ $statsTrx['week'] }} Trx</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Bulan Ini</span>
                        <span class="font-bold text-slate-800">{{ $statsTrx['month'] }} Trx</span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    Berat Terkumpul
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Hari Ini</span>
                        <span class="font-bold text-emerald-600">{{ number_format($statsWeight['today'], 1, ',', '.') }} kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Minggu Ini</span>
                        <span class="font-bold text-emerald-600">{{ number_format($statsWeight['week'], 1, ',', '.') }} kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Bulan Ini</span>
                        <span class="font-bold text-emerald-600">{{ number_format($statsWeight['month'], 1, ',', '.') }} kg</span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Jenis Sampah (Bulan Ini)
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Botol Plastik (PET)</span>
                        <div class="text-right">
                            <span class="font-bold text-blue-600 block">{{ number_format($statsPetMonth, 1, ',', '.') }} kg</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Non-PET / Lainnya</span>
                        <div class="text-right">
                            <span class="font-bold text-slate-600 block">{{ number_format($statsNonPetMonth, 1, ',', '.') }} kg</span>
                        </div>
                    </div>
                    <div class="h-px bg-slate-100 my-2"></div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400">Dominasi</span>
                        <span class="font-medium text-slate-800">
                            {{ $statsPetMonth >= $statsNonPetMonth ? 'PET' : 'Non-PET' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // 1. AMBIL DATA DARI ATRIBUT HTML (Bersih dari PHP)
            // ==========================================
            
            // Data Pie Chart
            const pieCanvas = document.getElementById('pieChart');
            const pieDataPET = Number(pieCanvas.dataset.pet);
            const pieDataNonPET = Number(pieCanvas.dataset.nonpet);

            // Data Bar Chart
            const barCanvas = document.getElementById('barChart');
            // JSON.parse digunakan untuk mengubah string JSON kembali menjadi Array JS
            const barWeeks = JSON.parse(barCanvas.dataset.weeks);
            const barIncome = JSON.parse(barCanvas.dataset.income);
            const barExpense = JSON.parse(barCanvas.dataset.expense);

            // ==========================================
            // 2. RENDER PIE CHART
            // ==========================================
            new Chart(pieCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['PET', 'Non-PET'],
                    datasets: [{
                        data: [pieDataPET, pieDataNonPET],
                        backgroundColor: ['#3B82F6', '#CBD5E1'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // ==========================================
            // 3. RENDER BAR CHART
            // ==========================================
            new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: barWeeks,
                    datasets: [
                        {
                            label: 'Pemasukan (Jual)',
                            data: barIncome,
                            backgroundColor: '#10B981', 
                            borderRadius: 4,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Pengeluaran (Beli)',
                            data: barExpense,
                            backgroundColor: '#EF4444', 
                            borderRadius: 4,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f1f5f9' },
                            ticks: { 
                                callback: function(value) { return 'Rp ' + (value/1000) + 'k'; },
                                font: { size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
@endsection