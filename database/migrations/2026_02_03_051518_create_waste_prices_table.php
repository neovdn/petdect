<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('waste_prices', function (Blueprint $table) {
            $table->string('waste_type')->primary(); 
            $table->decimal('price_per_kg', 10, 2); // Pakai decimal buat uang biar presisi
            $table->timestamps(); // last_updated otomatis tercover disini (updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_prices');
    }
};
