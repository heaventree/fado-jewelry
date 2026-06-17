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
        'store_name',
        'store_tagline',
        'contact_email',
        'contact_phone',
        'contact_address',
        'orders_email',
        'consultation_email',
        'meta_title',
        'meta_description',
        'google_analytics',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'maintenance_mode',
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
            'store_name'         => ['required', 'string', 'max:120'],
            'store_tagline'      => ['nullable', 'string', 'max:200'],
            'contact_email'      => ['required', 'email', 'max:200'],
            'contact_phone'      => ['nullable', 'string', 'max:30'],
            'contact_address'    => ['nullable', 'string', 'max:500'],
            'orders_email'       => ['required', 'email', 'max:200'],
            'consultation_email' => ['required', 'email', 'max:200'],
            'meta_title'         => ['nullable', 'string', 'max:120'],
            'meta_description'   => ['nullable', 'string', 'max:300'],
            'google_analytics'   => ['nullable', 'string', 'max:30', 'regex:/^(G-|UA-)[A-Z0-9\-]+$/i'],
            'facebook_url'       => ['nullable', 'url', 'max:300'],
            'instagram_url'      => ['nullable', 'url', 'max:300'],
            'twitter_url'        => ['nullable', 'url', 'max:300'],
            'maintenance_mode'   => ['nullable', 'boolean'],
        ]);

        $data['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';

        // Only save keys we explicitly allow
        $allowed = array_intersect_key($data, array_flip(self::KEYS));
        Setting::setMany($allowed);

        return back()->with('success', 'Settings saved.');
    }
}
