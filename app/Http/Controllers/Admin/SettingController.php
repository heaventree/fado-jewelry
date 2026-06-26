<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const KEYS = [
        // Store identity
        'store_name', 'store_tagline',
        // Contact
        'store_email', 'store_phone', 'store_address',
        'orders_email', 'consultation_email',
        'order_email_from_name', 'order_email_from_address',
        'store_lat', 'store_lng',
        // SEO & Tracking
        'meta_title', 'meta_description', 'google_analytics_id',
        'gtm_code', 'fb_pixel_id', 'custom_head_scripts', 'custom_body_scripts',
        // Per-page SEO
        'home_meta_title', 'home_meta_description', 'home_og_image',
        'about_meta_title', 'about_meta_description', 'about_og_image',
        'faq_meta_title', 'faq_meta_description',
        'contact_meta_title', 'contact_meta_description',
        // Social
        'facebook_url', 'instagram_url', 'twitter_url',
        // Maintenance
        'maintenance_mode',
        // Payment
        'cod_enabled', 'payment_method_label',
        'stripe_publishable_key', 'stripe_secret_key',
        // Shipping
        'free_shipping_threshold', 'shipping_rate_ireland',
        'shipping_rate_international', 'shipping_notice',
        // Display
        'products_per_page', 'new_arrivals_count',
        'featured_collections_count', 'related_products_count',
        'featured_product_id',
        'wishlist_enabled', 'reviews_enabled',
        // Homepage
        'homepage_subtitle_1', 'homepage_subtitle_2', 'homepage_subtitle_3',
        'trust_label_1', 'trust_label_2', 'trust_label_3', 'trust_label_4',
        'trust_sub_1', 'trust_sub_2', 'trust_sub_3', 'trust_sub_4',
        'sale_banner_image', 'newsletter_intro',
        // Product
        'delivery_info', 'returns_policy',
        // About
        'about_hero_image', 'about_heading', 'about_story',
        'about_gallery_1', 'about_gallery_2', 'about_gallery_3',
        'craft_value_1_title', 'craft_value_1_text',
        'craft_value_2_title', 'craft_value_2_text',
        'craft_value_3_title', 'craft_value_3_text',
        // Legal
        'privacy_policy', 'terms_conditions', 'faq_banner_image',
        // Orders
        'low_stock_threshold',
        // Consultation
        'consultation_enabled', 'consultation_intro_text',
    ];

    public function index(): View
    {
        $settings = Setting::whereIn('key', self::KEYS)
            ->pluck('value', 'key')
            ->toArray();

        return view('general.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Store identity
            'store_name'         => ['required', 'string', 'max:120'],
            'store_tagline'      => ['nullable', 'string', 'max:200'],
            // Contact
            'store_email'        => ['required', 'email', 'max:200'],
            'store_phone'        => ['nullable', 'string', 'max:30'],
            'store_address'      => ['nullable', 'string', 'max:500'],
            'orders_email'       => ['required', 'email', 'max:200'],
            'consultation_email' => ['required', 'email', 'max:200'],
            'order_email_from_name'    => ['nullable', 'string', 'max:100'],
            'order_email_from_address' => ['nullable', 'email', 'max:200'],
            // SEO
            'meta_title'         => ['nullable', 'string', 'max:120'],
            'meta_description'   => ['nullable', 'string', 'max:300'],
            'google_analytics_id' => ['nullable', 'string', 'max:30', 'regex:/^(G-|UA-)[A-Z0-9\-]+$/i'],
            // Social
            'facebook_url'       => ['nullable', 'url', 'max:300'],
            'instagram_url'      => ['nullable', 'url', 'max:300'],
            'twitter_url'        => ['nullable', 'url', 'max:300'],
            // Maintenance
            'maintenance_mode'   => ['nullable', 'boolean'],
            // Payment
            'cod_enabled'             => ['nullable', 'boolean'],
            'payment_method_label'    => ['nullable', 'string', 'max:500'],
            'stripe_publishable_key'  => ['nullable', 'string', 'max:200'],
            'stripe_secret_key'       => ['nullable', 'string', 'max:200'],
            // Shipping
            'free_shipping_threshold'     => ['nullable', 'numeric', 'min:0'],
            'shipping_rate_ireland'       => ['nullable', 'numeric', 'min:0'],
            'shipping_rate_international' => ['nullable', 'numeric', 'min:0'],
            'shipping_notice'             => ['nullable', 'string', 'max:200'],
            // Display
            'products_per_page'          => ['nullable', 'integer', 'min:4', 'max:96'],
            'new_arrivals_count'         => ['nullable', 'integer', 'min:1', 'max:24'],
            'featured_collections_count' => ['nullable', 'integer', 'min:1', 'max:12'],
            'related_products_count'     => ['nullable', 'integer', 'min:1', 'max:12'],
            'featured_product_id'        => ['nullable', 'integer', 'exists:products,id'],
            'wishlist_enabled'           => ['nullable', 'boolean'],
            'reviews_enabled'            => ['nullable', 'boolean'],
            // Orders
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            // Contact (location)
            'store_lat' => ['nullable', 'string', 'max:20'],
            'store_lng' => ['nullable', 'string', 'max:20'],
            // Homepage
            'homepage_subtitle_1' => ['nullable', 'string', 'max:300'],
            'homepage_subtitle_2' => ['nullable', 'string', 'max:300'],
            'homepage_subtitle_3' => ['nullable', 'string', 'max:300'],
            'trust_label_1' => ['nullable', 'string', 'max:100'],
            'trust_label_2' => ['nullable', 'string', 'max:100'],
            'trust_label_3' => ['nullable', 'string', 'max:100'],
            'trust_label_4' => ['nullable', 'string', 'max:100'],
            'trust_sub_1' => ['nullable', 'string', 'max:200'],
            'trust_sub_2' => ['nullable', 'string', 'max:200'],
            'trust_sub_3' => ['nullable', 'string', 'max:200'],
            'trust_sub_4' => ['nullable', 'string', 'max:200'],
            'sale_banner_image' => ['nullable', 'string', 'max:500'],
            'newsletter_intro' => ['nullable', 'string', 'max:300'],
            // Product
            'delivery_info' => ['nullable', 'string', 'max:2000'],
            'returns_policy' => ['nullable', 'string', 'max:2000'],
            // About
            'about_hero_image' => ['nullable', 'string', 'max:500'],
            'about_heading' => ['nullable', 'string', 'max:200'],
            'about_story' => ['nullable', 'string', 'max:5000'],
            'about_gallery_1' => ['nullable', 'string', 'max:500'],
            'about_gallery_2' => ['nullable', 'string', 'max:500'],
            'about_gallery_3' => ['nullable', 'string', 'max:500'],
            'craft_value_1_title' => ['nullable', 'string', 'max:100'],
            'craft_value_1_text' => ['nullable', 'string', 'max:200'],
            'craft_value_2_title' => ['nullable', 'string', 'max:100'],
            'craft_value_2_text' => ['nullable', 'string', 'max:200'],
            'craft_value_3_title' => ['nullable', 'string', 'max:100'],
            'craft_value_3_text' => ['nullable', 'string', 'max:200'],
            // Legal
            'privacy_policy' => ['nullable', 'string', 'max:20000'],
            'terms_conditions' => ['nullable', 'string', 'max:20000'],
            'faq_banner_image' => ['nullable', 'string', 'max:500'],
            // Consultation
            'consultation_enabled'    => ['nullable', 'boolean'],
            'consultation_intro_text' => ['nullable', 'string', 'max:500'],
        ]);

        // Normalise boolean checkboxes
        foreach (['maintenance_mode', 'cod_enabled', 'wishlist_enabled', 'reviews_enabled', 'consultation_enabled'] as $bool) {
            $data[$bool] = $request->boolean($bool) ? '1' : '0';
        }

        // Only save keys we explicitly allow
        $allowed = array_intersect_key($data, array_flip(self::KEYS));
        Setting::setMany($allowed);

        return back()->with('success', 'Settings saved.');
    }
}
