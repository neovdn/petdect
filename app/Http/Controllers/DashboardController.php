<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer; // Pastikan model Customer/User sesuai relasi Anda
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =================================================================
        // 1. DATA KARTU STATISTIK (STATS CARDS)
        // =================================================================

        // Card 1: Total Kas Keluar (Uang yang dibayarkan ke nasabah)
        // Asumsi: Transaksi adalah pengeluaran bank sampah ke nasabah
        $totalExpenses = Transaction::sum('total_amount'); 

        // Card 2: Total Sampah Terkumpul (Kg)
        $totalWeight = TransactionItem::sum('weight');

        // Card 3: Total Transaksi Berhasil
        $totalTransactions = Transaction::count();

        // Card 4: Rata-rata per Transaksi
        $averageTransaction = $totalTransactions > 0 
            ? $totalExpenses / $totalTransactions 
            : 0;

        // Persentase Kenaikan/Penurunan (Opsional: Dibandingkan bulan lalu)
        // Disini saya set statis dulu atau 0, nanti bisa dikembangkan logic compare-nya
        $expenseGrowth = 0; 
        $weightGrowth = 0;


        // =================================================================
        // 2. DATA TABEL TRANSAKSI TERBARU (RECENT TRANSACTIONS)
        // =================================================================
        // Mengambil 5 transaksi terakhir beserta data nasabahnya
        // Pastikan di Model Transaction ada fungsi: public function customer() { return $this->belongsTo(Customer::class); }
        $recentTransactions = Transaction::with('customer') 
            ->latest()
            ->take(5)
            ->get();


        // =================================================================
        // 3. DATA GRAFIK (CHART.JS) - 7 HARI TERAKHIR
        // =================================================================
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);
        
        // Siapkan array kosong untuk menampung data grafik
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        // Loop 7 hari terakhir
        for ($i = 0; $i <= 6; $i++) {
            $date = $startDate->copy()->addDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('D, d M'); // Label: Mon, 12 Feb

            // Simpan Label
            $chartLabels[] = $displayDate;

            // Query Data Pengeluaran (Transaksi Harian)
            $dailyExpense = Transaction::whereDate('created_at', $formattedDate)->sum('total_amount');
            $chartExpense[] = $dailyExpense;

            // Query Data Pemasukan (Misal: Penjualan Sampah ke Pengepul Besar)
            // Jika belum ada fitur penjualan, kita ambil dari CashFlow tipe 'income'
            $dailyIncome = CashFlow::where('type', 'income')
                ->whereDate('date', $formattedDate)
                ->sum('amount');
            $chartIncome[] = $dailyIncome;
        }

        // =================================================================
        // 4. RETURN VIEW
        // =================================================================
        return view('admin.dashboard', compact(
            'totalExpenses',
            'totalWeight',
            'totalTransactions',
            'averageTransaction',
            'recentTransactions',
            'chartLabels',
            'chartIncome',
            'chartExpense'
        ));
    }
}