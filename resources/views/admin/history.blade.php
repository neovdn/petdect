@extends('layouts.app')

@section('header_title', 'Riwayat Transaksi')

@section('content')
    <div class="bg-white p-5 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 mb-6">
        <form action="{{ route('cashier.history') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4 justify-between">
            
            <div class="flex flex-1 gap-4 overflow-x-auto pb-2 md:pb-0">
                <div class="relative min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" 
                           placeholder="Cari ID atau Nama...">
                </div>

                <div class="relative min-w-[180px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-600">
                </div>

                <select name="type" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-600 min-w-[150px]">
                    <option value="">Semua Tipe</option>
                    <option value="PET" {{ request('type') == 'PET' ? 'selected' : '' }}>Hanya PET</option>
                    <option value="NON-PET" {{ request('type') == 'NON-PET' ? 'selected' : '' }}>Hanya Non-PET</option>
                </select>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-blue-200">
                    Terapkan
                </button>
                <a href="{{ route('cashier.history') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 px-1">
        <p class="text-sm text-slate-500 mb-2 sm:mb-0">
            Menampilkan <span class="font-bold text-slate-800">{{ $transactions->firstItem() ?? 0 }}</span> 
            sampai <span class="font-bold text-slate-800">{{ $transactions->lastItem() ?? 0 }}</span> 
            dari <span class="font-bold text-slate-800">{{ $transactions->total() }}</span> transaksi
        </p>
        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-500">Total Nilai:</span>
            <span class="text-lg font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100">
                Rp {{ number_format($totalValue ?? 0, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4 text-center">Non-PET (kg)</th>
                        <th class="px-6 py-4 text-center">PET (kg)</th>
                        <th class="px-6 py-4 text-right">Nilai Transaksi</th>
                        <th class="px-6 py-4">Kasir</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                        @php
                            // Helper logic untuk menghitung berat per kategori langsung di view
                            $petWeight = $trx->items->where('waste_type', 'PET')->sum('weight');
                            $nonPetWeight = $trx->items->where('waste_type', 'NON-PET')->sum('weight');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-700 group-hover:text-blue-600 transition-colors">
                                    #{{ $trx->transaction_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm text-slate-700 font-medium">{{ $trx->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-slate-400">{{ $trx->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 mr-3">
                                        {{ substr($trx->customer_name_snapshot ?? 'G', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $trx->customer_name_snapshot ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nonPetWeight > 0)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-600 border border-orange-100">
                                        {{ $nonPetWeight }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($petWeight > 0)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ $petWeight }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-emerald-600">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $trx->cashier_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('transaction.receipt', $trx->id) }}" target="_blank" 
                                   class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                   title="Cetak Nota">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada riwayat transaksi ditemukan.</p>
                                    <p class="text-xs mt-1 text-slate-300">Coba ubah filter atau tanggal pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection