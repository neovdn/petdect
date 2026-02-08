@extends('layouts.app')

@section('header_title', 'Sistem Deteksi Sampah')

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-6">
    
    <div class="w-full lg:w-1/2 flex flex-col gap-6">
        
        <div class="flex-1 bg-black rounded-2xl overflow-hidden relative group shadow-lg shadow-slate-200/50">
            <div class="absolute inset-0 flex items-center justify-center bg-slate-900">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-slate-600 font-medium text-sm">Menunggu Koneksi Kamera...</p>
                </div>
            </div>
            
            <video id="cameraFeed" class="absolute inset-0 w-full h-full object-cover hidden" autoplay muted></video>

            <div class="absolute top-4 left-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-black/50 text-white backdrop-blur-sm border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse" id="statusDot"></span>
                    <span id="statusText">Offline</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100">
            <h3 class="text-slate-800 font-bold mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Kontrol Deteksi
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <button onclick="startDetection()" id="btnStart" class="flex items-center justify-center w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mulai Deteksi
                </button>
                
                <button onclick="stopDetection()" id="btnStop" disabled class="flex items-center justify-center w-full py-3 px-4 bg-slate-100 text-slate-400 font-semibold rounded-xl transition-all cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                    </svg>
                    Stop
                </button>
            </div>
            
            <p class="mt-3 text-xs text-slate-400 text-center">Pastikan area timbangan bersih sebelum memulai deteksi.</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex flex-col h-full">
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 flex flex-col h-full">
            
            <div class="p-6 border-b border-slate-100">
                <div class="bg-slate-100 p-1 rounded-xl flex font-medium text-sm">
                    <button onclick="switchTab('existing')" id="tabExisting" class="flex-1 py-2.5 rounded-lg bg-white text-slate-800 shadow-sm transition-all text-center">
                        Pelanggan Lama
                    </button>
                    <button onclick="switchTab('new')" id="tabNew" class="flex-1 py-2.5 rounded-lg text-slate-500 hover:text-slate-700 transition-all text-center">
                        Pelanggan Baru
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6 no-scrollbar">
                
                <form id="transactionForm" action="{{ route('cashier.api.checkout') }}" method="POST">
                    @csrf
                    
                    <div id="sectionExisting">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Pelanggan</label>
                        <select name="customer_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-700">
                            <option value="" disabled selected>Pilih nama atau nomor telepon...</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->full_name }} - {{ $c->phone_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="sectionNew" class="hidden space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="new_name" placeholder="Nama pelanggan..." class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-700">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon</label>
                                <input type="text" name="new_phone" placeholder="08..." class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat</label>
                            <textarea name="new_address" rows="2" placeholder="Alamat domisili..." class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-700"></textarea>
                        </div>
                    </div>

                    <hr class="border-dashed border-slate-200 my-6">

                    <div>
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4 flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Hasil Deteksi
                        </h4>

                        <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-bold text-slate-700">Botol Plastik (PET)</span>
                                <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                    Rp {{ number_format($wastePrices->where('waste_type', 'PET')->first()->price_per_kg ?? 0, 0, ',', '.') }}/kg
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 relative">
                                    <input type="number" id="weightPet" name="weight_pet" step="0.1" value="0" readonly class="w-full rounded-lg border-slate-200 bg-white text-right pr-8 font-mono text-slate-700 focus:ring-0">
                                    <span class="absolute right-3 top-2.5 text-slate-400 text-sm">kg</span>
                                </div>
                                <div class="text-slate-300">=</div>
                                <div class="flex-1 relative">
                                    <input type="text" id="totalPet" value="0" readonly class="w-full rounded-lg border-slate-200 bg-slate-100 text-right font-bold text-slate-800 focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-bold text-slate-700">Botol Berwarna (Non-PET)</span>
                                <span class="text-xs font-medium bg-orange-100 text-orange-700 px-2 py-1 rounded">
                                    Rp {{ number_format($wastePrices->where('waste_type', 'NON-PET')->first()->price_per_kg ?? 0, 0, ',', '.') }}/kg
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 relative">
                                    <input type="number" id="weightNonPet" name="weight_non_pet" step="0.1" value="0" readonly class="w-full rounded-lg border-slate-200 bg-white text-right pr-8 font-mono text-slate-700 focus:ring-0">
                                    <span class="absolute right-3 top-2.5 text-slate-400 text-sm">kg</span>
                                </div>
                                <div class="text-slate-300">=</div>
                                <div class="flex-1 relative">
                                    <input type="text" id="totalNonPet" value="0" readonly class="w-full rounded-lg border-slate-200 bg-slate-100 text-right font-bold text-slate-800 focus:ring-0">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 bg-white border-t border-slate-100">
                <div class="flex justify-between items-end mb-4">
                    <span class="text-sm text-slate-500 font-medium mb-1 block">Total Estimasi</span>
                    <span class="text-3xl font-bold text-emerald-600 tracking-tight" id="grandTotal">Rp 0</span>
                </div>
                
                <button type="button" onclick="submitTransaction()" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Proses Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $petPriceData = $wastePrices->where('waste_type', 'PET')->first();
    $nonPetPriceData = $wastePrices->where('waste_type', 'NON-PET')->first();

    $petPrice = $petPriceData ? $petPriceData->price_per_kg : 0;
    $nonPetPrice = $nonPetPriceData ? $nonPetPriceData->price_per_kg : 0;
@endphp

<script>
    const pricePet = Number("{{ $petPrice }}");
    const priceNonPet = Number("{{ $nonPetPrice }}");
    
    // TAB SWITCHING LOGIC
    function switchTab(type) {
        const btnExisting = document.getElementById('tabExisting');
        const btnNew = document.getElementById('tabNew');
        const secExisting = document.getElementById('sectionExisting');
        const secNew = document.getElementById('sectionNew');
        
        if (type === 'existing') {
            btnExisting.className = 'flex-1 py-2.5 rounded-lg bg-white text-slate-800 shadow-sm transition-all text-center';
            btnNew.className = 'flex-1 py-2.5 rounded-lg text-slate-500 hover:text-slate-700 transition-all text-center';
            secExisting.classList.remove('hidden');
            secNew.classList.add('hidden');
        } else {
            btnNew.className = 'flex-1 py-2.5 rounded-lg bg-white text-slate-800 shadow-sm transition-all text-center';
            btnExisting.className = 'flex-1 py-2.5 rounded-lg text-slate-500 hover:text-slate-700 transition-all text-center';
            secNew.classList.remove('hidden');
            secExisting.classList.add('hidden');
        }
    }

    // DETECTION CONTROL MOCKUP
    let isDetecting = false;
    let detectionInterval;

    function startDetection() {
        document.getElementById('btnStart').className = "hidden"; // Hide start
        const btnStop = document.getElementById('btnStop');
        btnStop.disabled = false;
        btnStop.className = "flex items-center justify-center w-full py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-red-200 active:scale-95";
        
        document.getElementById('statusDot').className = "w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse";
        document.getElementById('statusText').innerText = "Mendeteksi...";
        
        isDetecting = true;

        // SIMULASI DATA MASUK DARI IOT (Hanya Mockup untuk Demo)
        // Nanti diganti dengan fetch API ke route('cashier.api.reading')
        detectionInterval = setInterval(() => {
            // Random weight mockup
            const wPet = (Math.random() * 2).toFixed(1);
            const wNonPet = (Math.random() * 1.5).toFixed(1);
            
            updateWeights(wPet, wNonPet);
        }, 2000);
    }

    function stopDetection() {
        const btnStart = document.getElementById('btnStart');
        const btnStop = document.getElementById('btnStop');

        btnStart.className = "flex items-center justify-center w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-200 active:scale-95"; // Restore start style
        btnStop.className = "hidden"; // Hide stop button completely or disable it
        // Or revert style:
        btnStop.className = "flex items-center justify-center w-full py-3 px-4 bg-slate-100 text-slate-400 font-semibold rounded-xl transition-all cursor-not-allowed";
        btnStop.disabled = true;

        document.getElementById('statusDot').className = "w-2 h-2 rounded-full bg-red-500 mr-2";
        document.getElementById('statusText').innerText = "Offline";

        clearInterval(detectionInterval);
        isDetecting = false;
    }

    function updateWeights(pet, nonPet) {
        document.getElementById('weightPet').value = pet;
        document.getElementById('weightNonPet').value = nonPet;
        calculateTotal();
    }

    function calculateTotal() {
        const wPet = parseFloat(document.getElementById('weightPet').value) || 0;
        const wNonPet = parseFloat(document.getElementById('weightNonPet').value) || 0;

        const totalP = wPet * pricePet;
        const totalNP = wNonPet * priceNonPet;
        const grand = totalP + totalNP;

        // Format Currency
        const fmt = (num) => 'Rp ' + num.toLocaleString('id-ID');

        document.getElementById('totalPet').value = fmt(totalP);
        document.getElementById('totalNonPet').value = fmt(totalNP);
        document.getElementById('grandTotal').innerText = fmt(grand);
    }

    function submitTransaction() {
        // Logika submit form
        // document.getElementById('transactionForm').submit();
        alert('Data transaksi akan diproses...');
    }
</script>
@endsection