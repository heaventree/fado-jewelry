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
        'contact_email', 'contact_phone', 'contact_address',
        'orders_email', 'consultation_email',
        'order_email_from_name', 'order_email_from_address',
        // SEO
        'meta_title', 'meta_description', 'google_analytics',
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
        'wishlist_enabled', 'reviews_enabled',
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
            'contact_email'      => ['required', 'email', 'max:200'],
            'contact_phone'      => ['nullable', 'string', 'max:30'],
            'contact_address'    => ['nullable', 'string', 'max:500'],
            'orders_email'       => ['required', 'email', 'max:200'],
            'consultation_email' => ['required', 'email', 'max:200'],
            'order_email_from_name'    => ['nullable', 'string', 'max:100'],
            'order_email_from_address' => ['nullable', 'email', 'max:200'],
            // SEO
            'meta_title'         => ['nullable', 'string', 'max:120'],
            'meta_description'   => ['nullable', 'string', 'max:300'],
            'google_analytics'   => ['nullable', 'string', 'max:30', 'regex:/^(G-|UA-)[A-Z0-9\-]+$/i'],
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
            'wishlist_enabled'           => ['nullable', 'boolean'],
            'reviews_enabled'            => ['nullable', 'boolean'],
            // Orders
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
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
