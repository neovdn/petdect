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
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->string('device_id')->primary(); // Hardware ID
            $table->string('status')->default('offline');
            $table->string('detected_class')->nullable(); // PET / NON-PET
            $table->float('weight_value')->default(0);
            $table->boolean('is_stable')->default(false);
            $table->timestamps(); // Buat ngecek kapan terakhir alat kirim data (heartbeat)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_devices');
    }
};
