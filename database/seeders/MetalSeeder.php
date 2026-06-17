<?php

namespace Database\Seeders;

use App\Models\Metal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MetalSeeder extends Seeder
{
    public function run(): void
    {
        $metals = [
            'Sterling Silver',
            '9ct Yellow Gold',
            '9ct White Gold',
            '9ct Rose Gold',
            '10ct Yellow Gold',
            '14ct Yellow Gold',
            '14ct White Gold',
            '18ct Yellow Gold',
            '18ct White Gold',
            'Platinum',
            'Two-tone (Yellow/White Gold)',
        ];

        foreach ($metals as $name) {
            Metal::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
