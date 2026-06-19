<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Rings',               'slug' => 'rings',              'sort_order' => 1],
            ['name' => 'Crosses',             'slug' => 'crosses',            'sort_order' => 2],
            ['name' => 'Pendants',            'slug' => 'pendants',           'sort_order' => 3],
            ['name' => 'Earrings',            'slug' => 'earrings',           'sort_order' => 4],
            ['name' => 'Bracelets & Bangles', 'slug' => 'bracelets-bangles',  'sort_order' => 5],
            ['name' => 'Cufflinks',           'slug' => 'cufflinks',          'sort_order' => 6],
            ['name' => 'Brooches',            'slug' => 'brooches',           'sort_order' => 7],
            ['name' => 'Tie-tacks',           'slug' => 'tie-tacks',          'sort_order' => 8],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
