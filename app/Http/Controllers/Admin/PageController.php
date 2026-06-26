<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $keys = [
            'homepage_subtitle_1', 'homepage_subtitle_2', 'homepage_subtitle_3',
            'trust_label_1', 'trust_label_2', 'trust_label_3', 'trust_label_4',
            'trust_sub_1', 'trust_sub_2', 'trust_sub_3', 'trust_sub_4',
            'sale_banner_image',
            'home_meta_title', 'home_meta_description', 'home_og_image',
        ];

        return view('admin.pages.home', ['settings' => $this->load($keys)]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        Setting::setMany($request->only([
            'homepage_subtitle_1', 'homepage_subtitle_2', 'homepage_subtitle_3',
            'trust_label_1', 'trust_label_2', 'trust_label_3', 'trust_label_4',
            'trust_sub_1', 'trust_sub_2', 'trust_sub_3', 'trust_sub_4',
            'home_meta_title', 'home_meta_description',
        ]));

        $this->handleUpload($request, 'sale_banner_image_file', 'sale_banner_image');
        $this->handleUpload($request, 'home_og_image_file', 'home_og_image');

        return back()->with('success', 'Home page content saved.');
    }

    public function about(): View
    {
        $keys = [
            'about_hero_image', 'about_heading', 'about_story',
            'about_gallery_1', 'about_gallery_2', 'about_gallery_3',
            'craft_value_1_title', 'craft_value_1_text',
            'craft_value_2_title', 'craft_value_2_text',
            'craft_value_3_title', 'craft_value_3_text',
            'about_meta_title', 'about_meta_description', 'about_og_image',
        ];

        return view('admin.pages.about', ['settings' => $this->load($keys)]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        Setting::setMany($request->only([
            'about_heading', 'about_story',
            'craft_value_1_title', 'craft_value_1_text',
            'craft_value_2_title', 'craft_value_2_text',
            'craft_value_3_title', 'craft_value_3_text',
            'about_meta_title', 'about_meta_description',
        ]));

        $this->handleUpload($request, 'about_hero_image_file', 'about_hero_image');
        $this->handleUpload($request, 'about_gallery_1_file', 'about_gallery_1');
        $this->handleUpload($request, 'about_gallery_2_file', 'about_gallery_2');
        $this->handleUpload($request, 'about_gallery_3_file', 'about_gallery_3');
        $this->handleUpload($request, 'about_og_image_file', 'about_og_image');

        return back()->with('success', 'About page content saved.');
    }

    public function faq(): View
    {
        return view('admin.pages.faq', ['settings' => $this->load([
            'faq_banner_image', 'faq_meta_title', 'faq_meta_description',
        ])]);
    }

    public function updateFaq(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(['faq_meta_title', 'faq_meta_description']));
        $this->handleUpload($request, 'faq_banner_image_file', 'faq_banner_image');

        return back()->with('success', 'FAQ page content saved.');
    }

    public function contact(): View
    {
        return view('admin.pages.contact', ['settings' => $this->load([
            'store_lat', 'store_lng', 'contact_meta_title', 'contact_meta_description',
        ])]);
    }

    public function updateContact(Request $request): RedirectResponse
    {
        Setting::setMany($request->only([
            'store_lat', 'store_lng', 'contact_meta_title', 'contact_meta_description',
        ]));

        return back()->with('success', 'Contact page content saved.');
    }

    public function terms(): View
    {
        return view('admin.pages.terms', ['settings' => $this->load(['terms_conditions'])]);
    }

    public function updateTerms(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(['terms_conditions']));

        return back()->with('success', 'Terms & Conditions saved.');
    }

    public function privacy(): View
    {
        return view('admin.pages.privacy', ['settings' => $this->load(['privacy_policy'])]);
    }

    public function updatePrivacy(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(['privacy_policy']));

        return back()->with('success', 'Privacy Policy saved.');
    }

    private function load(array $keys): array
    {
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::get($key, '');
        }
        return $settings;
    }

    private function handleUpload(Request $request, string $fileField, string $settingKey): void
    {
        if ($request->hasFile($fileField)) {
            $path = $request->file($fileField)->store('pages', 'public');
            Setting::set($settingKey, $path);
        }
    }
}
