<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::firstOrCreate(
            ['code' => 'EUR'],
            [
                'name'         => 'Euro',
                'rate'         => 1.000000,
                'is_default'   => true,
                'region_codes' => [
                    // EU member states + EEA
                    'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE',
                    'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT',
                    'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO',
                    'SE', 'SI', 'SK', 'NO', 'IS', 'LI',
                ],
            ]
        );

        Currency::firstOrCreate(
            ['code' => 'USD'],
            [
                'name'         => 'US Dollar',
                'rate'         => 1.100000, // placeholder — updated manually in admin
                'is_default'   => false,
                'region_codes' => null, // catch-all for all non-EUR regions
            ]
        );
    }
}
