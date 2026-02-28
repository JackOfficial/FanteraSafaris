<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddClientRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. New Permissions
        $perms = ['make bookings', 'view own bookings', 'manage profile'];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // 2. Create Role
        $client = Role::firstOrCreate(['name' => 'client']);
        
        // 3. Sync Permissions
        $client->syncPermissions($perms);
    }
}