<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code', // CUST-001
        'full_name',
        'phone_number',
        'address',
        'total_transactions',
    ];

    // Relasi: Satu customer punya banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}