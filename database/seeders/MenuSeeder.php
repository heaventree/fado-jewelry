<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $header = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Header Menu']
        );

        if ($header->items()->count() === 0) {
            $this->seedItems($header, [
                ['label' => 'Jewellery',   'url' => '/jewellery'],
                ['label' => 'Collections', 'url' => '/collections'],
                ['label' => 'About Us',    'url' => '/about'],
                ['label' => 'Contact',     'url' => '/contact'],
            ]);
        }

        $footerShopping = Menu::firstOrCreate(
            ['location' => 'footer_col_1'],
            ['name' => 'Footer Shopping']
        );

        if ($footerShopping->items()->count() === 0) {
            $this->seedItems($footerShopping, [
                ['label' => 'Jewellery',   'url' => '/jewellery'],
                ['label' => 'Collections', 'url' => '/collections'],
                ['label' => 'My Wishlist', 'url' => '/wishlist'],
                ['label' => 'My Bag',      'url' => '/cart'],
            ]);
        }

        $footerInfo = Menu::firstOrCreate(
            ['location' => 'footer_col_2'],
            ['name' => 'Footer Info']
        );

        if ($footerInfo->items()->count() === 0) {
            $this->seedItems($footerInfo, [
                ['label' => 'About Us',             'url' => '/about'],
                ['label' => 'Contact',              'url' => '/contact'],
                ['label' => 'Book a Consultation',  'url' => '/contact#consultation'],
                ['label' => 'Help & FAQs',          'url' => '/faq'],
                ['label' => 'Privacy Policy',       'url' => '/privacy'],
            ]);
        }
    }

    private function seedItems(Menu $menu, array $items): void
    {
        foreach ($items as $i => $item) {
            MenuItem::create([
                'menu_id'    => $menu->id,
                'label'      => $item['label'],
                'url'        => $item['url'],
                'type'       => 'custom',
                'target'     => '_self',
                'sort_order' => $i,
            ]);
        }
    }
}
