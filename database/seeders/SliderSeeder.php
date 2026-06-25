<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'heading'     => 'Irish Claddagh Rings',
                'subheading'  => 'Handcrafted in Ireland',
                'button_text' => 'Shop Now',
                'button_url'  => '/shop',
                'image'       => 'sliders/slider-1.jpg',
                'sort_order'  => 1,
            ],
            [
                'heading'     => 'Gold & Silver Jewellery',
                'subheading'  => 'For every occasion',
                'button_text' => 'Explore',
                'button_url'  => '/collections',
                'image'       => 'sliders/slider-2.jpg',
                'sort_order'  => 2,
            ],
            [
                'heading'     => 'Free Delivery on Orders Over €50',
                'subheading'  => '',
                'button_text' => 'Shop Now',
                'button_url'  => '/shop',
                'image'       => 'sliders/slider-3.jpg',
                'sort_order'  => 3,
            ],
        ];

        foreach ($slides as $slide) {
            Slider::firstOrCreate(['heading' => $slide['heading']], $slide);
        }
    }
}
