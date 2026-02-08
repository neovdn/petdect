@extends('layouts.app')

@section('header_title', 'Manajemen Kas & Harga')

@section('content')
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="fixed top-24 right-8 z-50 bg-emerald-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center transition-all duration-500 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- SECTION 1: KARTU INFORMASI KAS (COMPACT VERSION) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div class="flex items-center justify-between mb-2">
                    <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-wide">Live</span>
                </div>
                <div>
                    <h3 class="text-slate-400 text-xs font-medium uppercase tracking-wider">Saldo Kas</h3>
                    <p class="text-xl font-bold text-slate-800">Rp {{ number_format($currentBalance, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-xs font-medium uppercase tracking-wider">Total Masuk</h3>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($totalIn, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <div class="h-8 w-8 bg-red-50 rounded-lg flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-400 text-xs font-medium uppercase tracking-wider">Total Keluar</h3>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- SECTION 2: HARGA & ACTIONS (EQUAL HEIGHT & SOFT DESIGN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        
        {{-- Card Harga Aktif --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-4 h-full">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Harga Sampah</h3>
                    <p class="text-xs text-slate-400">Harga beli per kilogram.</p>
                </div>
                <button onclick="openModal('updatePriceModal')" class="flex items-center px-3 py-1.5 bg-white border border-emerald-200 text-emerald-600 text-xs font-bold rounded-lg hover:bg-emerald-50 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Harga
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($wastePrices as $price)
                <div class="flex items-center p-3 rounded-lg border {{ $price->waste_type == 'PET' ? 'bg-blue-50/30 border-blue-100' : 'bg-orange-50/30 border-orange-100' }}">
                    {{-- Icon Logic --}}
                    <div class="h-10 w-10 rounded-lg flex items-center justify-center {{ $price->waste_type == 'PET' ? 'bg-blue-100 text-blue-500' : 'bg-orange-100 text-orange-500' }} mr-3 flex-shrink-0">
                        @if($price->waste_type == 'PET')
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-milk"><path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7.5 15h9"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                        @endif
                    </div>
                    
                    <div class="w-full">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $price->waste_type == 'PET' ? 'text-blue-500' : 'text-orange-500' }}">
                                {{ $price->waste_type == 'PET' ? 'Plastik PET' : 'Non-PET / Lain' }}
                            </span>
                        </div>
                        <p class="text-lg font-bold text-slate-800 leading-tight">Rp {{ number_format($price->price_per_kg, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Card Quick Action (Tambah Modal) - SOFT EMERALD ACCENT & FIXED LAYOUT --}}
        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-4 h-full flex flex-col relative overflow-hidden group">
            {{-- Decorative Soft Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full opacity-60 transition-transform duration-500 group-hover:scale-110 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Tambah Modal</h3>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed pr-2">Input dana tambahan untuk operasional kas.</p>
                        </div>
                        <div class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-100 shadow-sm flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                {{-- Tombol ditaruh di bawah menggunakan flex-col & justify-between secara implisit --}}
                <div class="mt-4">
                    <button onclick="openModal('addModalModal')" class="w-full py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200 text-xs flex items-center justify-center gap-2">
                         <span>+ Input Kas Baru</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: TABEL MUTASI KAS (COMPACT ROWS) --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold text-slate-800">Riwayat Mutasi</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-2 pl-2">Waktu</th>
                        <th class="pb-2">Keterangan</th>
                        <th class="pb-2">Jenis</th>
                        <th class="pb-2 text-right">Nominal</th>
                        <th class="pb-2 text-right pr-2">Saldo</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-600 divide-y divide-slate-50">
                    @php $runningBalance = $currentBalance; @endphp
                    @forelse($cashFlows as $flow)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-2.5 pl-2 whitespace-nowrap">
                            <span class="font-bold text-slate-700">{{ $flow->created_at->format('d/m/y') }}</span>
                            <span class="text-slate-400 ml-1">{{ $flow->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-2.5 max-w-[150px] truncate" title="{{ $flow->description }}">
                            {{ $flow->description ?? '-' }}
                        </td>
                        <td class="py-2.5">
                            @if($flow->type == 'in')
                                <span class="text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">Masuk</span>
                            @else
                                <span class="text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-100">Keluar</span>
                            @endif
                        </td>
                        <td class="py-2.5 text-right font-bold {{ $flow->type == 'in' ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $flow->type == 'in' ? '+' : '-' }} {{ number_format($flow->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 text-right pr-2 font-medium text-slate-800">
                            {{ number_format($runningBalance, 0, ',', '.') }}
                        </td>
                    </tr>
                    @php
                        if($flow->type == 'in') $runningBalance -= $flow->amount;
                        else $runningBalance += $flow->amount;
                    @endphp
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400 text-xs">
                            Belum ada riwayat mutasi kas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination (Compact) --}}
        <div class="mt-3 text-xs">
            {{ $cashFlows->links() }}
        </div>
    </div>

    {{-- ================= MODALS ================= --}}

    {{-- Modal Tambah Kas --}}
    <div id="addModalModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('addModalModal')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full max-w-sm border border-slate-100">
                    <form action="{{ route('admin.cash-flow.store') }}" method="POST">
                        @csrf
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-bold text-slate-800">Tambah Modal Kas</h3>
                                <button type="button" onclick="closeModal('addModalModal')" class="text-slate-400 hover:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Nominal (Rp)</label>
                                    <input type="number" name="amount" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan</label>
                                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all" placeholder="Ketikan keterangan..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 flex flex-row-reverse border-t border-slate-100 gap-2">
                            <button type="submit" class="w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition-colors">Simpan</button>
                            <button type="button" onclick="closeModal('addModalModal')" class="w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update Harga --}}
    <div id="updatePriceModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('updatePriceModal')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full max-w-sm border border-slate-100">
                    <form action="{{ route('admin.waste-prices.update-batch') }}" method="POST">
                        @csrf
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-bold text-slate-800">Update Harga</h3>
                                <button type="button" onclick="closeModal('updatePriceModal')" class="text-slate-400 hover:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($wastePrices as $price)
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1 flex items-center gap-2">
                                        @if($price->waste_type == 'PET')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7.5 15h9"/></svg>
                                            Plastik PET (Kg)
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                                            Non-PET (Kg)
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">Rp</span>
                                        <input type="number" name="prices[{{ $price->waste_type }}]" value="{{ $price->price_per_kg }}" required 
                                               class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 flex flex-row-reverse border-t border-slate-100 gap-2">
                            <button type="submit" class="w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition-colors">Simpan</button>
                            <button type="button" onclick="closeModal('updatePriceModal')" class="w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection