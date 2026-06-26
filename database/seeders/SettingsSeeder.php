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
            'gtm_code'            => '',
            'fb_pixel_id'         => '',
            'custom_head_scripts' => '',
            'custom_body_scripts' => '',

            // ── Per-page SEO ────────────────────────────────────────────
            'home_meta_title'       => '',
            'home_meta_description' => '',
            'home_og_image'         => '',
            'about_meta_title'      => '',
            'about_meta_description' => '',
            'about_og_image'        => '',
            'faq_meta_title'        => '',
            'faq_meta_description'  => '',
            'contact_meta_title'    => '',
            'contact_meta_description' => '',

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

            // ── Contact (location) ──────────────────────────────────────
            'store_lat' => '53.3498',
            'store_lng' => '-6.2603',

            // ── Homepage ────────────────────────────────────────────────
            'homepage_subtitle_1' => 'From classic rings to Celtic crosses — discover Irish jewellery for every occasion',
            'homepage_subtitle_2' => '',
            'homepage_subtitle_3' => '',
            'trust_label_1' => '30 Day Returns',
            'trust_label_2' => 'Secure Payment',
            'trust_label_3' => 'Free Delivery',
            'trust_label_4' => 'Irish Crafted',
            'trust_sub_1' => '30-day returns on unworn pieces',
            'trust_sub_2' => 'Encrypted, secure payment',
            'trust_sub_3' => 'Free delivery on orders over €75',
            'trust_sub_4' => 'Handcrafted in Ireland',
            'sale_banner_image' => '',
            'newsletter_intro' => 'Become the first to know about new collections and offers.',

            // ── Product ─────────────────────────────────────────────────
            'delivery_info'  => "Orders dispatched within 2–3 working days. Free standard delivery on orders over €50.\nExpress delivery (1–2 working days) is available at checkout. International shipping to the US and worldwide is also available.",
            'returns_policy' => "30-day return policy on all unworn items in original packaging. For size exchanges, contact us within 30 days.\nCustom or engraved pieces cannot be returned unless faulty.",

            // ── About ───────────────────────────────────────────────────
            'about_hero_image' => '',
            'about_heading' => 'Our Story',
            'about_story' => '',
            'about_gallery_1' => '',
            'about_gallery_2' => '',
            'about_gallery_3' => '',
            'craft_value_1_title' => 'Handcrafted with care',
            'craft_value_1_text' => 'Every piece made by skilled Irish artisans',
            'craft_value_2_title' => 'Dublin hallmarked',
            'craft_value_2_text' => 'Guaranteed quality since 1637',
            'craft_value_3_title' => 'Gift-ready packaging',
            'craft_value_3_text' => 'Presented in our signature green box',

            // ── Legal ───────────────────────────────────────────────────
            'privacy_policy' => '',
            'terms_conditions' => '',
            'faq_banner_image' => '',
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
