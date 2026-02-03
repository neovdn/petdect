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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // TRX-2026...
            $table->string('cashier_name'); // Snapshot nama kasir
            
            // Relasi ke tabel customers
            // Kalau customer dihapus, data transaksi jangan hilang (set null)
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name_snapshot'); // Jaga-jaga kalau user ganti nama
            
            // Detail Sampah (Dibuat flat aja biar gampang)
            $table->float('pet_weight');
            $table->decimal('pet_price_at_transaction', 10, 2);
            $table->decimal('pet_subtotal', 10, 2);
            
            $table->float('non_pet_weight');
            $table->decimal('non_pet_price_at_transaction', 10, 2);
            $table->decimal('non_pet_subtotal', 10, 2);
            
            $table->decimal('total_amount', 12, 2);
            $table->timestamps(); // timestamp transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
