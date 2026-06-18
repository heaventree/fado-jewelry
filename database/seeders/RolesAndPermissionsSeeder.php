<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Products
            'products.view', 'products.create', 'products.edit', 'products.delete',
            // Categories
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            // Collections
            'collections.view', 'collections.create', 'collections.edit', 'collections.delete',
            // Orders
            'orders.view', 'orders.update_status',
            // Consultations
            'consultations.view',
            // Inventory
            'inventory.view', 'inventory.edit',
            // Customers
            'customers.view',
            // Invoices
            'invoices.view',
            // Coupons
            'coupons.view', 'coupons.create', 'coupons.edit', 'coupons.delete',
            // Currencies
            'currencies.view', 'currencies.manage',
            // Settings
            'settings.view', 'settings.manage',
            // Roles
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // super_admin: everything
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // store_admin: everything except roles management
        $storeAdmin = Role::firstOrCreate(['name' => 'store_admin', 'guard_name' => 'web']);
        $storeAdmin->syncPermissions(
            Permission::where('name', '!=', 'roles.manage')->get()
        );

        // staff: orders, consultations, inventory, invoices only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'orders.view', 'orders.update_status',
            'consultations.view',
            'inventory.view', 'inventory.edit',
            'invoices.view',
        ]);

        // customer: shop front end only — no admin permissions
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    }
}
