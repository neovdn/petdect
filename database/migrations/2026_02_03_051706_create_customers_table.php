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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // customer_id internal (1, 2, 3)
            $table->string('customer_code')->unique(); // CUST-001 (Manual input/generate)
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->integer('total_transactions')->default(0);
            $table->timestamps(); // registered_at tercover di created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
