<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',        // DEBIT / KREDIT
        'amount',
        'description',
        'current_balance',
    ];

    // Casting agar 'date' otomatis jadi objek Carbon (memudahkan format tanggal)
    protected $casts = [
        'date' => 'date',
    ];
}