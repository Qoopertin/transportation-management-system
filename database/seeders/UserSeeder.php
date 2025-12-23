<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Dispatcher user
        $dispatcher = User::create([
            'name' => 'Dispatcher User',
            'email' => 'dispatcher@example.com',
            'password' => Hash::make('password'),
        ]);
        $dispatcher->assignRole('dispatcher');

        // Create Driver user
        $driver = User::create([
            'name' => 'Driver User',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
        ]);
        $driver->assignRole('driver');
        
        // Create additional driver for testing
        $driver2 = User::create([
            'name' => 'John Driver',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);
        $driver2->assignRole('driver');
    }
}
