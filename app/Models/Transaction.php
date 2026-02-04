<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Field yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'transaction_code',
        'cashier_name',
        'customer_id',
        'customer_name_snapshot', // Snapshot nama customer jaga-jaga kalau master berubah
        'pet_weight',
        'pet_price_at_transaction',
        'pet_subtotal',
        'non_pet_weight',
        'non_pet_price_at_transaction',
        'non_pet_subtotal',
        'total_amount',
    ];

    // Relasi ke Customer (Optional: customer bisa null jika dihapus)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}