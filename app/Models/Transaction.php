<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'cashier_id',
        'cashier_name',
        'customer_id',
        'customer_name_snapshot',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke User (Kasir)
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Relasi ke Transaction Items
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Auto-generate transaction code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $date = now()->format('Ymd');
                $lastTransaction = static::whereDate('created_at', now()->toDateString())
                    ->latest('id')
                    ->first();
                
                $number = $lastTransaction ? ((int) substr($lastTransaction->transaction_code, -3)) + 1 : 1;
                $transaction->transaction_code = 'TRX-' . $date . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}