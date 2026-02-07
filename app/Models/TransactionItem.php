<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'waste_type',
        'weight',
        'price_per_kg',
        'subtotal',
    ];

    protected $casts = [
        'weight' => 'float',
        'price_per_kg' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relasi ke Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi ke WastePrice
    public function wastePrice()
    {
        return $this->belongsTo(WastePrice::class, 'waste_type', 'waste_type');
    }
}