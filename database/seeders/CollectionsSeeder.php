<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionsSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'name'               => 'Claddagh',
                'slug'               => 'claddagh',
                'banner_title'       => 'The Claddagh Collection',
                'banner_description' => 'Love, loyalty and friendship — Ireland\'s most enduring symbol, crafted in sterling silver, gold and platinum.',
            ],
            [
                'name'               => 'Corrib Claddagh',
                'slug'               => 'corrib-claddagh',
                'banner_image'       => 'banners/Banner_large_Corrib.jpg',
                'banner_title'       => 'The Corrib Claddagh Collection',
                'banner_description' => 'A contemporary take on the classic Claddagh, inspired by the waters of the River Corrib.',
            ],
            [
                'name'               => 'Trinity',
                'slug'               => 'trinity',
                'banner_title'       => 'The Trinity Collection',
                'banner_description' => 'The ancient Celtic trinity knot — no beginning, no end — reimagined in fine Irish jewellery.',
            ],
            [
                'name'               => 'An Rí',
                'slug'               => 'an-ri',
                'banner_image'       => 'banners/banner_Small_An_Ri.jpg',
                'banner_title'       => 'An Rí — The King Collection',
                'banner_description' => 'Regal Celtic knotwork inspired by the high kings of Ireland, crafted in gold and silver.',
            ],
            [
                'name'               => 'Livia',
                'slug'               => 'livia',
                'banner_image'       => 'banners/Banner_Meduim_Livia.jpg',
                'banner_title'       => 'The Livia Collection',
                'banner_description' => 'Elegant, flowing designs with a modern sensibility — jewellery for every day and every occasion.',
            ],
            [
                'name'               => 'Sheelin',
                'slug'               => 'sheelin',
                'banner_image'       => 'banners/banner_Small_Sheelin.jpg',
                'banner_title'       => 'The Sheelin Collection',
                'banner_description' => 'Inspired by the still waters of Lough Sheelin, this collection captures the quiet beauty of the Irish lakeland.',
            ],
            [
                'name'               => 'High Crosses',
                'slug'               => 'high-crosses',
                'banner_title'       => 'The High Crosses Collection',
                'banner_description' => 'Ireland\'s iconic high crosses — ancient Christian monuments of extraordinary beauty — brought to life in fine jewellery.',
            ],
            [
                'name'               => 'Newgrange',
                'slug'               => 'newgrange',
                'banner_title'       => 'The Newgrange Collection',
                'banner_description' => 'The spiral of life — inspired by the 5,000-year-old passage tomb at Newgrange, one of the world\'s great prehistoric monuments.',
            ],
            [
                'name'               => 'Irish Folklore',
                'slug'               => 'irish-folklore',
                'banner_title'       => 'The Irish Folklore Collection',
                'banner_description' => 'Celtic mythology and ancient Irish legend, captured in finely crafted silver and gold.',
            ],
            [
                'name'               => 'Shamrock',
                'slug'               => 'shamrock',
                'banner_title'       => 'The Shamrock Collection',
                'banner_description' => 'Ireland\'s national emblem — the three-leafed shamrock — celebrated in sterling silver and gold.',
            ],
            [
                'name'               => 'The Garden Collection by FADÓ',
                'slug'               => 'the-garden-collection',
                'banner_title'       => 'The Jewellery Garden by FADÓ',
                'banner_description' => 'Bluebells, wild daisies, butterflies and bees — Ireland\'s wildflower meadows captured in sterling silver and gold. Perfect for weddings and engagements.',
            ],
        ];

        foreach ($collections as $data) {
            Collection::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
