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
        Schema::create('current_readings', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('detected_class')->nullable(); // PET / NON-PET
            $table->float('weight_value')->default(0);
            $table->boolean('is_stable')->default(false);
            $table->timestamps();

            // Foreign key ke iot_devices
            $table->foreign('device_id')->references('device_id')->on('iot_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_readings');
    }
};