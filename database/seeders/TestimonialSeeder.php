<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name'         => 'Aoife Murphy',
                'location'     => 'Dublin, Ireland',
                'body'         => 'Absolutely beautiful Claddagh ring — the craftsmanship is outstanding. It arrived in gorgeous packaging and my fiancée was thrilled. Highly recommend FADÓ.',
                'rating'       => 5,
                'product_name' => 'Claddagh Ring',
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Seán O\'Brien',
                'location'     => 'Galway, Ireland',
                'body'         => 'Ordered a Trinity knot pendant for my mother\'s birthday. The quality of the silver is exceptional and the delivery was faster than expected. Will be ordering again.',
                'rating'       => 5,
                'product_name' => 'Trinity Knot Pendant',
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Claire Doyle',
                'location'     => 'Cork, Ireland',
                'body'         => 'The Garden Collection earrings are even more beautiful in person than in the photos. Delicate, detailed, and truly unique Irish jewellery.',
                'rating'       => 5,
                'product_name' => 'Garden Collection Earrings',
                'sort_order'   => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(['name' => $t['name']], $t);
        }
    }
}
