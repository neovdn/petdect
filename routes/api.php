<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IoTController;

// IoT Endpoints (No authentication untuk development)
// Nanti bisa ditambahkan API Token authentication
Route::prefix('iot')->group(function () {
    Route::post('/update-reading', [IoTController::class, 'updateReading']);
    Route::post('/reset-scale', [IoTController::class, 'resetScale']);
    Route::get('/device/{deviceId}/status', [IoTController::class, 'getDeviceStatus']);
});