<?php

namespace App\Http\Controllers;

use App\Models\WastePrice;
use App\Models\CashFlow; // Model CashFlow ditambahkan
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MasterDataController extends Controller
{
    // ============ WASTE PRICES & CASH FLOW ============
    
    public function wastePrices()
    {
        // 1. Ambil Data Harga Sampah
        $wastePrices = WastePrice::all();
        
        // 2. Hitung Statistik Kas (Cash Flow)
        // Menghitung total masuk (in) dan keluar (out)
        $totalIn = CashFlow::where('type', 'in')->sum('amount');
        $totalOut = CashFlow::where('type', 'out')->sum('amount');
        
        // Saldo saat ini = Total Masuk - Total Keluar
        $currentBalance = $totalIn - $totalOut;

        // 3. Ambil Riwayat Transaksi Kas
        // Diurutkan dari yang terbaru, dipagination per 10 item
        $cashFlows = CashFlow::orderBy('created_at', 'desc')->paginate(10);

        // Return view dengan semua data yang dibutuhkan
        return view('admin.waste-prices', compact('wastePrices', 'totalIn', 'totalOut', 'currentBalance', 'cashFlows'));
    }

    // Method Baru: Update Harga Sampah Sekaligus (Batch)
    public function updateWastePriceBatch(Request $request)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->prices as $type => $price) {
            WastePrice::where('waste_type', $type)->update(['price_per_kg' => $price]);
        }

        return redirect()->back()->with('success', 'Harga sampah berhasil diperbarui.');
    }

    // Method Baru: Tambah Modal Kas Manual
    public function storeCashModal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        // Simpan transaksi modal ke CashFlow
        CashFlow::create([
            'type' => 'in', // Modal dianggap pemasukan (debit)
            'amount' => $request->amount,
            'description' => $request->description ?? 'Penambahan Modal Kas',
            // Jika ada kolom user_id di tabel cash_flows, uncomment baris bawah:
            // 'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Modal kas berhasil ditambahkan.');
    }

    // Method Lama (Opsional, tetap disimpan untuk backward compatibility jika perlu)
    public function updateWastePrice(Request $request, $waste_type)
    {
        $validated = $request->validate([
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        WastePrice::where('waste_type', $waste_type)->update([
            'price_per_kg' => $validated['price_per_kg'],
        ]);

        return redirect()->back()->with('success', 'Harga sampah berhasil diupdate');
    }

    // ============ CUSTOMERS ============
    
    public function customers()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('admin.customers', compact('customers'));
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Generate customer code
        $lastCustomer = Customer::latest('id')->first();
        $number = $lastCustomer ? ((int) substr($lastCustomer->customer_code, -3)) + 1 : 1;
        $validated['customer_code'] = 'CUST-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        Customer::create($validated);

        return redirect()->back()->with('success', 'Customer berhasil ditambahkan');
    }

    public function updateCustomer(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Customer::findOrFail($id)->update($validated);

        return redirect()->back()->with('success', 'Customer berhasil diupdate');
    }

    public function deleteCustomer($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Customer berhasil dihapus');
    }

    // ============ USERS ============
    
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:admin,kasir',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:admin,kasir',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($id);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}