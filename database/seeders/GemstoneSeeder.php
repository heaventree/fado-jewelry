<?php

namespace Database\Seeders;

use App\Models\Gemstone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GemstoneSeeder extends Seeder
{
    public function run(): void
    {
        $gemstones = [
            'Diamond',
            'Emerald',
            'Ruby',
            'Sapphire',
            'Cubic Zirconia',
            'Amethyst',
            'Aquamarine',
            'Garnet',
            'Opal',
            'Pearl',
            'Topaz',
        ];

        foreach ($gemstones as $name) {
            Gemstone::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
