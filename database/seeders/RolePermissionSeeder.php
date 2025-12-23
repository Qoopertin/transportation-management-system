<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view loads',
            'create loads',
            'update loads',
            'delete loads',
            'view users',
            'create users',
            'update users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin - all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Dispatcher - load management permissions
        $dispatcherRole = Role::create(['name' => 'dispatcher']);
        $dispatcherRole->givePermissionTo([
            'view loads',
            'create loads',
            'update loads',
        ]);

        // Driver - view loads only
        $driverRole = Role::create(['name' => 'driver']);
        $driverRole->givePermissionTo('view loads');
    }
}
