<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WastePrice extends Model
{
    use HasFactory;

    // Memberitahu Laravel bahwa primary key-nya BUKAN 'id' tapi 'waste_type'
    protected $primaryKey = 'waste_type';
    
    // Tipe datanya string, bukan integer
    protected $keyType = 'string';
    
    // Matikan auto-increment karena ini string manual
    public $incrementing = false;

    protected $fillable = [
        'waste_type',   // Contoh: "PET", "NON_PET"
        'price_per_kg',
    ];
}