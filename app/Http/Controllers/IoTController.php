<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrentReading;
use App\Models\IotDevice;
use Illuminate\Http\Request;

class IoTController extends Controller
{
    /**
     * Endpoint untuk update reading dari IoT device
     * Method: POST
     * URL: /api/iot/update-reading
     * 
     * Body JSON:
     * {
     *   "device_id": "SCALE-001",
     *   "detected_class": "PET",
     *   "weight_value": 1.5,
     *   "is_stable": true
     * }
     */
    public function updateReading(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:iot_devices,device_id',
            'detected_class' => 'nullable|string|in:PET,NON-PET',
            'weight_value' => 'required|numeric|min:0',
            'is_stable' => 'required|boolean',
        ]);

        // Update status device jadi online
        IotDevice::where('device_id', $validated['device_id'])->update([
            'status' => 'online',
            'detected_class' => $validated['detected_class'],
            'weight_value' => $validated['weight_value'],
            'is_stable' => $validated['is_stable'],
        ]);

        // Update current reading
        CurrentReading::updateOrCreate(
            ['device_id' => $validated['device_id']],
            [
                'detected_class' => $validated['detected_class'],
                'weight_value' => $validated['weight_value'],
                'is_stable' => $validated['is_stable'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading updated successfully',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Endpoint untuk reset/tare timbangan
     */
    public function resetScale(Request $request)
    {
        $deviceId = $request->input('device_id', 'SCALE-001');

        CurrentReading::where('device_id', $deviceId)->update([
            'weight_value' => 0,
            'detected_class' => null,
            'is_stable' => false,
        ]);

        IotDevice::where('device_id', $deviceId)->update([
            'weight_value' => 0,
            'detected_class' => null,
            'is_stable' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scale reset successfully',
        ]);
    }

    /**
     * Endpoint untuk cek status device
     */
    public function getDeviceStatus($deviceId)
    {
        $device = IotDevice::where('device_id', $deviceId)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'device_id' => $device->device_id,
                'status' => $device->status,
                'detected_class' => $device->detected_class,
                'weight_value' => $device->weight_value,
                'is_stable' => $device->is_stable,
                'last_active' => $device->updated_at,
            ]
        ]);
    }
}