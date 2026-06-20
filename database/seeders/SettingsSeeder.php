<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [

            // ── Store identity ────────────────────────────────────────────
            'store_name'         => 'FADÓ Jewellery',
            'store_tagline'      => 'Fine Irish Jewellery',
            'store_email'        => 'info@fadojewellery.ie',
            'store_phone'        => '',
            'store_address'      => '',

            // ── Emails ───────────────────────────────────────────────────
            'orders_email'           => 'orders@fadojewellery.ie',
            'consultation_email'     => 'info@fadojewellery.ie',
            'order_email_from_name'  => 'FADÓ Jewellery',
            'order_email_from_address' => 'noreply@fadojewellery.ie',

            // ── SEO ──────────────────────────────────────────────────────
            'meta_title'         => 'FADÓ Jewellery — Fine Irish Jewellery',
            'meta_description'   => 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.',
            'google_analytics_id' => '',

            // ── Social ───────────────────────────────────────────────────
            'facebook_url'       => '',
            'instagram_url'      => '',
            'twitter_url'        => '',

            // ── Maintenance ──────────────────────────────────────────────
            'maintenance_mode'   => '0',

            // ── Payments ─────────────────────────────────────────────────
            'cod_enabled'              => '1',
            'payment_method_label'     => 'Pay by card — our team will contact you within one business day to arrange secure payment over phone or via a payment link.',
            'stripe_publishable_key'   => '',
            'stripe_secret_key'        => '',

            // ── Shipping ─────────────────────────────────────────────────
            'free_shipping_threshold'     => '75',
            'shipping_rate_ireland'       => '5.95',
            'shipping_rate_international' => '12.95',
            'shipping_notice'             => 'Free delivery on orders over €75',

            // ── Display ──────────────────────────────────────────────────
            'products_per_page'    => '16',
            'new_arrivals_count'   => '8',
            'featured_collections_count' => '6',
            'related_products_count' => '4',
            'wishlist_enabled'     => '1',
            'reviews_enabled'      => '0',

            // ── Orders ───────────────────────────────────────────────────
            'low_stock_threshold' => '3',

            // ── Consultation ─────────────────────────────────────────────
            'consultation_enabled'    => '1',
            'consultation_intro_text' => 'Book a private consultation with our jewellery specialists — in-store, by phone, or video call.',
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
