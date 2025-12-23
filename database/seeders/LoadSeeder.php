<?php

namespace Database\Seeders;

use App\Enums\LoadStatus;
use App\Models\Load;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoadSeeder extends Seeder
{
    public function run(): void
    {
        $driver = User::role('driver')->first();

        if (!$driver) {
            $this->command->warn('No driver found. Please run UserSeeder first.');
            return;
        }

        // Create sample load
        Load::create([
            'reference_no' => 'LD-' . date('Ymd') . '-DEMO',
            'pickup_address' => '123 Warehouse St, Los Angeles, CA 90001',
            'delivery_address' => '456 Distribution Ave, Phoenix, AZ 85001',
            'pickup_at' => now()->addDay(),
            'delivery_at' => now()->addDays(2),
            'status' => LoadStatus::ASSIGNED,
            'assigned_driver_id' => $driver->id,
            'notes' => 'Sample load for testing. Handle with care.',
        ]);

        // Create unassigned load
        Load::create([
            'reference_no' => 'LD-' . date('Ymd') . '-PEND',
            'pickup_address' => '789 Factory Rd, Chicago, IL 60601',
            'delivery_address' => '321 Store Blvd, Dallas, TX 75201',
            'pickup_at' => now()->addDays(3),
            'delivery_at' => now()->addDays(5),
            'status' => LoadStatus::PENDING,
            'assigned_driver_id' => null,
            'notes' => 'Awaiting driver assignment.',
        ]);
    }
}
