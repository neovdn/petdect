<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\WastePrice;
use App\Models\IotDevice;
use App\Models\CurrentReading;
use App\Models\CashFlow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. USERS (Admin & Kasir)
        $admin = User::create([
            'username' => 'admin',
            'full_name' => 'Administrator',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $kasir = User::create([
            'username' => 'kasir',
            'full_name' => 'Budi Santoso',
            'role' => 'kasir',
            'password' => Hash::make('kasir123'),
        ]);

        echo "✓ Users created\n";

        // 2. WASTE PRICES (Harga Sampah)
        WastePrice::create([
            'waste_type' => 'PET',
            'price_per_kg' => 3000, // Rp 3.000/kg
        ]);

        WastePrice::create([
            'waste_type' => 'NON-PET',
            'price_per_kg' => 1500, // Rp 1.500/kg
        ]);

        echo "✓ Waste prices created\n";

        // 3. CUSTOMERS
        Customer::create([
            'customer_code' => 'CUST-001',
            'full_name' => 'Ibu Siti Aminah',
            'phone_number' => '081234567890',
            'address' => 'Jl. Mawar No. 12, Surabaya',
            'total_transactions' => 0,
        ]);

        Customer::create([
            'customer_code' => 'CUST-002',
            'full_name' => 'Pak Ahmad Wijaya',
            'phone_number' => '081987654321',
            'address' => 'Jl. Melati No. 45, Surabaya',
            'total_transactions' => 0,
        ]);

        echo "✓ Customers created\n";

        // 4. IOT DEVICES
        $device = IotDevice::create([
            'device_id' => 'SCALE-001',
            'status' => 'offline',
            'detected_class' => null,
            'weight_value' => 0,
            'is_stable' => false,
        ]);

        echo "✓ IoT device created\n";

        // 5. CURRENT READING (Initial State)
        CurrentReading::create([
            'device_id' => 'SCALE-001',
            'detected_class' => null,
            'weight_value' => 0,
            'is_stable' => false,
        ]);

        echo "✓ Current reading initialized\n";

        // 6. CASH FLOW (Saldo Awal)
        CashFlow::create([
            'date' => now(),
            'type' => 'DEBIT',
            'amount' => 0,
            'description' => 'Saldo Awal Bank Sampah',
            'current_balance' => 0,
        ]);

        echo "✓ Cash flow initialized\n";

        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Login Admin: admin / admin123\n";
        echo "Login Kasir: kasir / kasir123\n";
    }
}