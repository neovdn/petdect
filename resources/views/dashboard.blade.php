@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#2D5016]">📊 Dashboard Admin</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->full_name }}!</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-gradient-to-br from-[#2D5016] to-[#4A7C2C] rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Transaksi Hari Ini</h3>
                <svg class="h-8 w-8 opacity-75" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="text-4xl font-bold">{{ $todayTransactions }}</div>
            <p class="text-xs opacity-75 mt-1">transaksi</p>
        </div>
        
        <div class="bg-gradient-to-br from-[#7FB069] to-[#6A9957] rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Sampah Hari Ini</h3>
                <svg class="h-8 w-8 opacity-75" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="text-4xl font-bold">{{ number_format($todayWaste, 2) }}</div>
            <p class="text-xs opacity-75 mt-1">kg terkumpul</p>
        </div>
        
        <div class="bg-gradient-to-br from-[#8B4513] to-[#A0522D] rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Saldo Kas</h3>
                <svg class="h-8 w-8 opacity-75" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="text-4xl font-bold">
                Rp {{ number_format(abs($currentBalance), 0, ',', '.') }}
            </div>
            <p class="text-xs opacity-75 mt-1">
                @if($currentBalance >= 0)
                    <span class="text-green-200">▲ Surplus</span>
                @else
                    <span class="text-red-200">▼ Terpakai</span>
                @endif
            </p>
        </div>
        
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-[#7FB069]">
        <h2 class="text-xl font-bold text-[#2D5016] mb-4">📈 Cash Flow (30 Hari Terakhir)</h2>
        <canvas id="cashFlowChart" style="max-height: 300px;"></canvas>
    </div>

    <div id="chart-data" data-payload="{{ json_encode($cashFlowData) }}" class="hidden"></div>
    
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('cashFlowChart').getContext('2d');

// Ambil data dari atribut HTML untuk menghindari error sintaks di editor
const rawData = document.getElementById('chart-data').getAttribute('data-payload');
const chartData = JSON.parse(rawData);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(item => item.date),
        datasets: [{
            label: 'Saldo Kas (Rp)',
            data: chartData.map(item => item.balance),
            borderColor: '#2D5016',
            backgroundColor: 'rgba(45, 80, 22, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush