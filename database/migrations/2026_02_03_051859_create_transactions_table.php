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
            $table->string('transaction_code')->unique(); // TRX-20260205001
            
            // Relasi ke users (kasir)
            $table->foreignId('cashier_id')->constrained('users')->onDelete('restrict');
            $table->string('cashier_name'); // Snapshot nama kasir
            
            // Relasi ke customers
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name_snapshot'); // Snapshot nama customer
            
            // Total transaksi (sum dari transaction_items)
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