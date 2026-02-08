<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        
        $todayWaste = TransactionItem::whereHas('transaction', function($q) {
            $q->whereDate('created_at', today());
        })->sum('weight');

        $lastCashFlow = CashFlow::latest()->first();
        $currentBalance = $lastCashFlow ? $lastCashFlow->current_balance : 0;

        // Data untuk chart (30 hari terakhir)
        $cashFlowData = CashFlow::where('date', '>=', now()->subDays(30))
            ->orderBy('date')
            ->get()
            ->groupBy(function($item) {
                return $item->date->format('Y-m-d');
            })
            ->map(function($group) {
                return [
                    'date' => $group->first()->date->format('d M'),
                    'balance' => $group->last()->current_balance,
                ];
            })
            ->values();

        return view('dashboard', compact(
            'todayTransactions',
            'todayWaste',
            'currentBalance',
            'cashFlowData'
        ));
    }
}