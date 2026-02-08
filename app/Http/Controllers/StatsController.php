<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        // =================================================================
        // 1. DATA KARTU INFORMASI (TOP CARDS)
        // =================================================================
        
        // Kas Bank Sampah (Mengambil saldo terakhir dari tabel cash_flows)
        $currentCash = CashFlow::orderBy('id', 'desc')->value('current_balance') ?? 0;

        // Total Dana Keluar (Total pembelian sampah / transaksi)
        $totalOutcome = Transaction::sum('total_amount');

        // Total Sampah (Kg)
        $totalWeight = TransactionItem::sum('weight');

        // Rata-rata per Transaksi
        $countTrx = Transaction::count();
        $avgTransaction = $countTrx > 0 ? $totalOutcome / $countTrx : 0;

        // Transaksi Hari Ini
        $todayTrxCount = Transaction::whereDate('created_at', Carbon::today())->count();


        // =================================================================
        // 2. DATA PIE CHART (PET vs NON-PET)
        // =================================================================
        // Mengelompokkan berdasarkan waste_type (PET / NON_PET)
        $wasteDistribution = TransactionItem::select('waste_type', DB::raw('sum(weight) as total_weight'))
            ->groupBy('waste_type')
            ->pluck('total_weight', 'waste_type')
            ->toArray();

        // Pastikan key ada meski datanya 0 agar chart tidak error
        $pieData = [
            'PET' => $wasteDistribution['PET'] ?? 0,
            'NON_PET' => $wasteDistribution['NON_PET'] ?? 0, // Sesuaikan string dgn database kamu (misal: 'NON-PET')
        ];


        // =================================================================
        // 3. DATA BAR CHART (CASHFLOW PER MINGGU - 4 MINGGU TERAKHIR)
        // =================================================================
        // Kita akan ambil data 4 minggu ke belakang
        $weeks = [];
        $weeklyIncome = [];
        $weeklyExpense = [];

        // Loop 4 minggu terakhir (termasuk minggu ini)
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek   = Carbon::now()->subWeeks($i)->endOfWeek();

            $weeks[] = $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M');

            // Expense: Diambil dari Transaction (Uang keluar ke nasabah)
            $expense = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('total_amount');
            $weeklyExpense[] = $expense;

            // Income: Diambil dari CashFlow tipe 'income' (Uang masuk dari pengepul)
            $income = CashFlow::where('type', 'income')
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount');
            $weeklyIncome[] = $income;
        }


        // =================================================================
        // 4. STATISTIK DETAIL (BAWAH)
        // =================================================================
        
        // Helper function untuk query range waktu
        $getStats = function($model, $column, $type = 'sum') {
            return [
                'today' => $type == 'count' 
                    ? $model::whereDate('created_at', Carbon::today())->count()
                    : $model::whereDate('created_at', Carbon::today())->sum($column),
                'week'  => $type == 'count'
                    ? $model::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count()
                    : $model::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum($column),
                'month' => $type == 'count'
                    ? $model::whereMonth('created_at', Carbon::now()->month)->count()
                    : $model::whereMonth('created_at', Carbon::now()->month)->sum($column),
            ];
        };

        $statsTrx = $getStats(Transaction::class, 'id', 'count');
        $statsWeight = $getStats(TransactionItem::class, 'weight', 'sum');

        // Breakdown Jenis Sampah Bulan Ini
        $statsPetMonth = TransactionItem::where('waste_type', 'PET')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('weight');
            
        $statsNonPetMonth = TransactionItem::where('waste_type', 'NON_PET') // Sesuaikan string database
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('weight');

        return view('admin.stats', compact(
            'currentCash', 'totalOutcome', 'totalWeight', 'avgTransaction', 'todayTrxCount',
            'pieData',
            'weeks', 'weeklyIncome', 'weeklyExpense',
            'statsTrx', 'statsWeight', 'statsPetMonth', 'statsNonPetMonth'
        ));
    }
}