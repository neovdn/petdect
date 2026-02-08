@extends('layouts.app')

@section('header_title', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-sm font-medium">Total Kas Keluar</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-sm font-medium">Total Sampah Terkumpul</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalWeight, 1, ',', '.') }} <span class="text-sm font-normal text-slate-400">kg</span></p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-sm font-medium">Total Transaksi</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalTransactions, 0, ',', '.') }} <span class="text-sm font-normal text-slate-400">trx</span></p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-sm font-medium">Rata-rata / Transaksi</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
                <a href="{{ route('cashier.index') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <th class="pb-3 pl-2">ID</th>
                            <th class="pb-3">Nasabah</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right pr-2">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-50">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 pl-2 font-medium text-slate-800">#{{ $trx->id }}</td>
                            <td class="py-4 font-medium">{{ $trx->customer->name ?? 'Guest/Umum' }}</td>
                            <td class="py-4 font-semibold text-emerald-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Selesai</span>
                            </td>
                            <td class="py-4 text-right pr-2 text-slate-400">{{ $trx->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400">Belum ada transaksi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 p-6 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Income vs Expense (7 Hari)</h3>
            
            <div class="relative flex-1 w-full min-h-[300px]">
                <canvas id="cashFlowChart"
                    data-labels="{{ json_encode($chartLabels) }}"
                    data-income="{{ json_encode($chartIncome) }}"
                    data-expense="{{ json_encode($chartExpense) }}">
                </canvas>
            </div>

            <div class="mt-6 flex justify-center space-x-6">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-blue-400 mr-2"></span>
                    <span class="text-xs text-slate-500">Pengeluaran (Beli)</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 mr-2"></span>
                    <span class="text-xs text-slate-500">Pemasukan (Jual)</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen canvas
            const canvas = document.getElementById('cashFlowChart');
            const ctx = canvas.getContext('2d');
            
            // Ambil data dari atribut HTML & parse JSON-nya
            // Editor tidak akan error lagi karena ini murni Javascript
            const labels = JSON.parse(canvas.dataset.labels);
            const dataIncome = JSON.parse(canvas.dataset.income);
            const dataExpense = JSON.parse(canvas.dataset.expense);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pengeluaran (Beli Sampah)',
                            data: dataExpense,
                            backgroundColor: '#60A5FA', // Blue-400
                            borderRadius: 4,
                            barThickness: 10,
                        },
                        {
                            label: 'Pemasukan (Jual Sampah)',
                            data: dataIncome,
                            backgroundColor: '#34D399', // Emerald-400
                            borderRadius: 4,
                            barThickness: 10,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
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
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                },
                                font: { size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        });
    </script>
@endsection