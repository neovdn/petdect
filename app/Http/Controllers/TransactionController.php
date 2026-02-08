<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\WastePrice;
use App\Models\CurrentReading;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Halaman utama kasir
     */
    public function index()
    {
        $customers = Customer::orderBy('full_name')->get();
        $wastePrices = WastePrice::all();
        $currentReading = CurrentReading::where('device_id', 'SCALE-001')->first();
        
        return view('index', compact('customers', 'wastePrices', 'currentReading'));
    }

    /**
     * API: Get current scale reading (untuk AJAX polling)
     */
    public function getCurrentReading()
    {
        $reading = CurrentReading::where('device_id', 'SCALE-001')->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'weight' => $reading->weight_value ?? 0,
                'class' => $reading->detected_class ?? 'UNKNOWN',
                'is_stable' => $reading->is_stable ?? false,
            ]
        ]);
    }

    /**
     * API: Simulate IoT input (Developer Mode)
     */
    public function simulateReading(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0',
            'class' => 'required|in:PET,NON-PET',
        ]);

        CurrentReading::updateOrCreate(
            ['device_id' => 'SCALE-001'],
            [
                'weight_value' => $validated['weight'],
                'detected_class' => $validated['class'],
                'is_stable' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading simulated successfully',
        ]);
    }

    /**
     * API: Lock weight dan tambah ke keranjang
     */
    public function lockAndAddToCart(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'waste_type' => 'required|in:PET,NON-PET',
        ]);

        // Ambil harga dari database
        $wastePrice = WastePrice::where('waste_type', $validated['waste_type'])->first();
        
        if (!$wastePrice) {
            return response()->json([
                'success' => false,
                'message' => 'Harga sampah tidak ditemukan'
            ], 404);
        }

        // Hitung subtotal
        $subtotal = $validated['weight'] * $wastePrice->price_per_kg;

        // Simpan ke session cart
        $cart = session()->get('transaction_cart', []);
        $cart[] = [
            'waste_type' => $validated['waste_type'],
            'weight' => $validated['weight'],
            'price_per_kg' => $wastePrice->price_per_kg,
            'subtotal' => $subtotal,
        ];
        session()->put('transaction_cart', $cart);

        // Reset reading
        CurrentReading::where('device_id', 'SCALE-001')->update([
            'weight_value' => 0,
            'detected_class' => null,
            'is_stable' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'cart' => $cart,
            'cart_count' => count($cart),
        ]);
    }

    /**
     * API: Get cart data
     */
    public function getCart()
    {
        $cart = session()->get('transaction_cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * API: Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $index = $request->input('index');
        $cart = session()->get('transaction_cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart); // Re-index array
            session()->put('transaction_cart', $cart);

            return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan',
        ], 404);
    }

    /**
     * Checkout & Simpan Transaksi
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $cart = session()->get('transaction_cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Ambil data customer
            $customer = Customer::findOrFail($validated['customer_id']);
            $cashier = Auth::user();

            // Hitung total
            $totalAmount = array_sum(array_column($cart, 'subtotal'));

            // Buat transaksi
            $transaction = Transaction::create([
                'cashier_id' => $cashier->id,
                'cashier_name' => $cashier->full_name,
                'customer_id' => $customer->id,
                'customer_name_snapshot' => $customer->full_name,
                'total_amount' => $totalAmount,
            ]);

            // Simpan transaction items
            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'waste_type' => $item['waste_type'],
                    'weight' => $item['weight'],
                    'price_per_kg' => $item['price_per_kg'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Update total transaksi customer
            $customer->increment('total_transactions');

            // Catat cash flow (Pengeluaran bank sampah ke customer)
            $lastCashFlow = CashFlow::latest()->first();
            $currentBalance = $lastCashFlow ? $lastCashFlow->current_balance : 0;
            $newBalance = $currentBalance - $totalAmount;

            CashFlow::create([
                'date' => now(),
                'type' => 'KREDIT',
                'amount' => $totalAmount,
                'description' => 'Pembayaran transaksi ' . $transaction->transaction_code . ' - ' . $customer->full_name,
                'current_balance' => $newBalance,
            ]);

            // Clear cart
            session()->forget('transaction_cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaction_id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cetak struk PDF
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['items', 'customer', 'cashier'])->findOrFail($id);
        
        $pdf = Pdf::loadView('receipt', compact('transaction'));
        
        return $pdf->stream('struk-' . $transaction->transaction_code . '.pdf');
    }

    /**
     * Riwayat transaksi
     */
    public function history(Request $request)
    {
        // Tambahkan 'items' ke dalam with()
        $query = Transaction::with(['customer', 'cashier', 'items']);

        // Logika filter sederhana (jika diperlukan)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%$search%")
                ->orWhere('customer_name_snapshot', 'like', "%$search%");
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()->paginate(10);
        
        // Hitung total nilai transaksi di halaman ini (atau total keseluruhan query jika mau)
        $totalValue = $transactions->sum('total_amount'); 

        return view('cashier.history', compact('transactions', 'totalValue'));
    }
}