<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage system settings']);
        Permission::create(['name' => 'view financial reports']);
        Permission::create(['name' => 'manage invoices']);

        // 2. Create Roles and Assign Permissions
        
        // System Admin gets everything
        $adminRole = Role::create(['name' => 'system admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Accountant gets only financial permissions
        $accountantRole = Role::create(['name' => 'accountant']);
        $accountantRole->givePermissionTo(['view financial reports', 'manage invoices']);
    }
}
