<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'store_name'         => 'FADÓ Jewellery',
            'store_tagline'      => 'Fine Irish Jewellery',
            'contact_email'      => 'info@fadojewellery.ie',
            'contact_phone'      => '',
            'contact_address'    => '',
            'meta_title'         => 'FADÓ Jewellery — Fine Irish Jewellery',
            'meta_description'   => 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.',
            'google_analytics'   => '',
            'facebook_url'       => '',
            'instagram_url'      => '',
            'twitter_url'        => '',
            'maintenance_mode'   => '0',
            'orders_email'       => 'orders@fadojewellery.ie',
            'consultation_email' => 'info@fadojewellery.ie',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->upsert(
                [['key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()]],
                ['key'],
                ['value', 'updated_at']
            );
        }
    }
}
