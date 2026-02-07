@extends('layouts.app')

@section('title', 'Kasir - Smart Waste Bank')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#2D5016]">💰 Kasir - Transaksi Baru</h1>
        <p class="text-gray-600">Kelola transaksi penerimaan sampah dari customer</p>
    </div>
    
    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT PANEL: Live Scale (Timbangan Digital) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-[#7FB069]">
                <h2 class="text-xl font-bold text-[#2D5016] mb-4 flex items-center">
                    <svg class="h-6 w-6 mr-2 text-[#7FB069]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                    </svg>
                    Live Scale - Timbangan Digital
                </h2>
                
                <!-- Display Timbangan -->
                <div class="bg-gradient-to-br from-[#2D5016] to-[#4A7C2C] rounded-lg p-8 text-center mb-6">
                    <div class="text-[#7FB069] text-sm font-semibold mb-2">BERAT SAMPAH</div>
                    <div id="weight-display" class="text-6xl font-bold text-white mb-4">
                        0.00
                    </div>
                    <div class="text-[#7FB069] text-xl font-semibold">KG</div>
                    
                    <div class="mt-4 pt-4 border-t border-[#7FB069]">
                        <div class="text-[#7FB069] text-xs font-semibold mb-1">JENIS SAMPAH</div>
                        <div id="class-display" class="text-2xl font-bold text-white">
                            -
                        </div>
                    </div>
                    
                    <!-- Status Indicator -->
                    <div class="mt-4">
                        <span id="status-indicator" class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gray-600 text-white">
                            ⚪ Waiting...
                        </span>
                    </div>
                </div>
                
                <!-- Simulasi IoT (Developer Mode) -->
                <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-4">
                    <p class="text-xs font-semibold text-yellow-800 mb-2">🔧 DEVELOPER MODE - Simulasi Input IoT</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (kg)</label>
                            <input type="number" 
                                   id="sim-weight" 
                                   step="0.1" 
                                   min="0" 
                                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#2D5016]"
                                   placeholder="1.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Sampah</label>
                            <select id="sim-class" 
                                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#2D5016]">
                                <option value="PET">PET</option>
                                <option value="NON-PET">NON-PET</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" 
                                    id="btn-simulate" 
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                Simulate
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" 
                            id="btn-lock-add" 
                            class="flex-1 bg-[#2D5016] hover:bg-[#4A7C2C] text-white font-bold py-3 px-6 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        🔒 Lock & Add to Cart
                    </button>
                    <button type="button" 
                            id="btn-reset" 
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        ♻️ Reset
                    </button>
                </div>
            </div>
        </div>
        
        <!-- RIGHT PANEL: Keranjang Transaksi -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-[#7FB069] sticky top-4">
                <h2 class="text-xl font-bold text-[#2D5016] mb-4 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-2 text-[#7FB069]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Keranjang
                    </span>
                    <span id="cart-count" class="bg-[#2D5016] text-white text-sm px-3 py-1 rounded-full">0</span>
                </h2>
                
                <!-- Customer Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Customer</label>
                    <select id="customer-select" 
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#2D5016]">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->customer_code }})</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Cart Items -->
                <div id="cart-items" class="mb-4 max-h-64 overflow-y-auto">
                    <div class="text-center text-gray-400 py-8">
                        <svg class="h-16 w-16 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                        </svg>
                        <p class="text-sm">Keranjang kosong</p>
                    </div>
                </div>
                
                <!-- Total -->
                <div class="border-t-2 border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-lg font-bold text-[#2D5016]">
                        <span>TOTAL</span>
                        <span id="cart-total">Rp 0</span>
                    </div>
                </div>
                
                <!-- Checkout Button -->
                <button type="button" 
                        id="btn-checkout" 
                        class="w-full bg-[#7FB069] hover:bg-[#6A9957] text-white font-bold py-3 px-6 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    ✅ CHECKOUT
                </button>
            </div>
        </div>
        
    </div>
    
</div>

<!-- Success Modal (Hidden by default) -->
<div id="success-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md mx-4 text-center">
        <div class="mb-4">
            <svg class="h-20 w-20 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-[#2D5016] mb-2">Transaksi Berhasil!</h3>
        <p class="text-gray-600 mb-6" id="success-message">Transaksi telah disimpan</p>
        <div class="flex gap-3">
            <button type="button" 
                    id="btn-print" 
                    class="flex-1 bg-[#8B4513] hover:bg-[#A0522D] text-white font-bold py-2 px-4 rounded-lg transition">
                🖨️ Cetak Struk
            </button>
            <button type="button" 
                    id="btn-new-transaction" 
                    class="flex-1 bg-[#2D5016] hover:bg-[#4A7C2C] text-white font-bold py-2 px-4 rounded-lg transition">
                Transaksi Baru
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// State management
let currentReading = { weight: 0, class: null, is_stable: false };
let cart = [];
let lastTransactionId = null;

// Helper functions
function formatRupiah(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

// Polling untuk update reading setiap 1 detik
function startPolling() {
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("cashier.api.reading") }}');
            const data = await response.json();
            
            if (data.success) {
                currentReading = data.data;
                updateScaleDisplay();
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 1000);
}

// Update tampilan timbangan
function updateScaleDisplay() {
    document.getElementById('weight-display').textContent = currentReading.weight.toFixed(2);
    document.getElementById('class-display').textContent = currentReading.class || '-';
    
    const statusIndicator = document.getElementById('status-indicator');
    const btnLockAdd = document.getElementById('btn-lock-add');
    
    if (currentReading.is_stable && currentReading.weight > 0) {
        statusIndicator.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-500 text-white';
        statusIndicator.textContent = '🟢 Stable';
        btnLockAdd.disabled = false;
    } else if (currentReading.weight > 0) {
        statusIndicator.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold bg-yellow-500 text-white';
        statusIndicator.textContent = '🟡 Stabilizing...';
        btnLockAdd.disabled = true;
    } else {
        statusIndicator.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gray-600 text-white';
        statusIndicator.textContent = '⚪ Waiting...';
        btnLockAdd.disabled = true;
    }
}

// Simulasi IoT
document.getElementById('btn-simulate').addEventListener('click', async () => {
    const weight = parseFloat(document.getElementById('sim-weight').value) || 0;
    const wasteClass = document.getElementById('sim-class').value;
    
    try {
        const response = await fetch('{{ route("cashier.api.simulate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ weight, class: wasteClass })
        });
        
        const data = await response.json();
        if (data.success) {
            console.log('Simulated successfully');
        }
    } catch (error) {
        console.error('Simulation error:', error);
    }
});

// Lock & Add to Cart
document.getElementById('btn-lock-add').addEventListener('click', async () => {
    try {
        const response = await fetch('{{ route("cashier.api.lock-add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                weight: currentReading.weight,
                waste_type: currentReading.class
            })
        });
        
        const data = await response.json();
        if (data.success) {
            cart = data.cart;
            updateCartDisplay();
        }
    } catch (error) {
        console.error('Add to cart error:', error);
    }
});

// Reset Scale
document.getElementById('btn-reset').addEventListener('click', () => {
    document.getElementById('sim-weight').value = '';
    fetch('{{ route("cashier.api.simulate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ weight: 0, class: 'PET' })
    });
});

// Update cart display
async function updateCartDisplay() {
    const cartItemsDiv = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    const btnCheckout = document.getElementById('btn-checkout');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="text-center text-gray-400 py-8">
                <svg class="h-16 w-16 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                </svg>
                <p class="text-sm">Keranjang kosong</p>
            </div>
        `;
        cartCount.textContent = '0';
        cartTotal.textContent = 'Rp 0';
        btnCheckout.disabled = true;
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        total += parseFloat(item.subtotal);
        html += `
            <div class="bg-gray-50 rounded-lg p-3 mb-2 border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-semibold text-[#2D5016]">${item.waste_type}</span>
                        <p class="text-xs text-gray-600">${item.weight} kg × ${formatRupiah(item.price_per_kg)}</p>
                    </div>
                    <button type="button" 
                            onclick="removeCartItem(${index})" 
                            class="text-red-500 hover:text-red-700 font-bold">
                        ✕
                    </button>
                </div>
                <div class="text-right font-bold text-[#2D5016]">
                    ${formatRupiah(item.subtotal)}
                </div>
            </div>
        `;
    });
    
    cartItemsDiv.innerHTML = html;
    cartCount.textContent = cart.length;
    cartTotal.textContent = formatRupiah(total);
    btnCheckout.disabled = false;
}

// Remove item from cart
async function removeCartItem(index) {
    try {
        const response = await fetch(`/cashier/api/cart/${index}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            cart = data.cart;
            updateCartDisplay();
        }
    } catch (error) {
        console.error('Remove error:', error);
    }
}

// Checkout
document.getElementById('btn-checkout').addEventListener('click', async () => {
    const customerId = document.getElementById('customer-select').value;
    
    if (!customerId) {
        alert('Pilih customer terlebih dahulu!');
        return;
    }
    
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    
    try {
        const response = await fetch('{{ route("cashier.api.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ customer_id: customerId })
        });
        
        const data = await response.json();
        if (data.success) {
            lastTransactionId = data.transaction_id;
            document.getElementById('success-message').textContent = 
                `Transaksi ${data.transaction_code} telah disimpan`;
            document.getElementById('success-modal').classList.remove('hidden');
            
            // Reset
            cart = [];
            updateCartDisplay();
            document.getElementById('customer-select').value = '';
        } else {
            alert('Checkout gagal: ' + data.message);
        }
    } catch (error) {
        console.error('Checkout error:', error);
        alert('Terjadi kesalahan saat checkout');
    }
});

// Print receipt
document.getElementById('btn-print').addEventListener('click', () => {
    if (lastTransactionId) {
        window.open(`/cashier/print/${lastTransactionId}`, '_blank');
    }
});

// New transaction
document.getElementById('btn-new-transaction').addEventListener('click', () => {
    document.getElementById('success-modal').classList.add('hidden');
    lastTransactionId = null;
});

// Start polling on page load
startPolling();
updateCartDisplay();
</script>
@endpush