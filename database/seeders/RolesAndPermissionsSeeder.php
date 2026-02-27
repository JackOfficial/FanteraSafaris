<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Define Permissions ---
        $permissions = [
            // Tour/Package Management
            'manage inventory',    // Create/Edit Safari packages
            'set pricing',         // Seasonality/Discounts
            
            // Booking Operations
            'view bookings',
            'edit bookings',
            'cancel bookings',
            
            // On-the-ground Logistics
            'assign guides',
            'manage fleet',        // Land Cruisers, Vans
            
            // Financials
            'view reports',
            'process refunds',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // --- 2. Create Roles & Assign Permissions ---

        // Super Admin: Can do everything
        $superAdmin = Role::create(['name' => 'super-admin']);
        // Gets all permissions via Gate::before in AuthServiceProvider (recommended)

        // Safari Manager: Inventory and Logistics
        $manager = Role::create(['name' => 'safari-manager']);
        $manager->givePermissionTo([
            'manage inventory',
            'set pricing',
            'view bookings',
            'assign guides',
            'manage fleet'
        ]);

        // Reservation Agent: Handles customers
        $agent = Role::create(['name' => 'reservation-agent']);
        $agent->givePermissionTo([
            'view bookings',
            'edit bookings'
        ]);

        // Tour Guide: Only sees their assigned tasks
        $guide = Role::create(['name' => 'tour-guide']);
        $guide->givePermissionTo(['view bookings']);
    }
}