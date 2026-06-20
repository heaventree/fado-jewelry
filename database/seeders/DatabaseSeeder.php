<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            MetalSeeder::class,
            GemstoneSeeder::class,
            RingSizeSeeder::class,
            CurrencySeeder::class,
            SettingsSeeder::class,
            CategoriesSeeder::class,
            CollectionsSeeder::class,
            DummyProductsSeeder::class,
            DummyCustomerDataSeeder::class,
        ]);
    }
}
