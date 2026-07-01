<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Traits\OptimizesImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    use OptimizesImages;
    private const SEO_SUFFIXES = [
        '_meta_title', '_meta_description', '_focus_keyword', '_keywords',
        '_robots', '_canonical',
    ];

    private function seoKeys(string $prefix, bool $hasOgImage = false): array
    {
        $keys = array_map(fn ($s) => $prefix . $s, self::SEO_SUFFIXES);
        if ($hasOgImage) {
            $keys[] = $prefix . '_og_image';
        }
        return $keys;
    }

    private function seoSaveKeys(string $prefix): array
    {
        return array_map(fn ($s) => $prefix . $s, self::SEO_SUFFIXES);
    }

    public function home(): View
    {
        $keys = array_merge([
            'homepage_subtitle_1', 'homepage_subtitle_2', 'homepage_subtitle_3',
            'trust_label_1', 'trust_label_2', 'trust_label_3', 'trust_label_4',
            'trust_sub_1', 'trust_sub_2', 'trust_sub_3', 'trust_sub_4',
            'sale_banner_image',
        ], $this->seoKeys('home', true));

        return view('admin.pages.home', ['settings' => $this->load($keys)]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(array_merge([
            'homepage_subtitle_1', 'homepage_subtitle_2', 'homepage_subtitle_3',
            'trust_label_1', 'trust_label_2', 'trust_label_3', 'trust_label_4',
            'trust_sub_1', 'trust_sub_2', 'trust_sub_3', 'trust_sub_4',
        ], $this->seoSaveKeys('home'))));

        $this->handleUpload($request, 'sale_banner_image_file', 'sale_banner_image');
        $this->handleUpload($request, 'home_og_image_file', 'home_og_image');

        return back()->with('success', 'Home page content saved.');
    }

    public function about(): View
    {
        $keys = array_merge([
            'about_hero_image', 'about_heading', 'about_story',
            'about_gallery_1', 'about_gallery_2', 'about_gallery_3',
            'craft_value_1_title', 'craft_value_1_text',
            'craft_value_2_title', 'craft_value_2_text',
            'craft_value_3_title', 'craft_value_3_text',
        ], $this->seoKeys('about', true));

        return view('admin.pages.about', ['settings' => $this->load($keys)]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(array_merge([
            'about_heading', 'about_story',
            'craft_value_1_title', 'craft_value_1_text',
            'craft_value_2_title', 'craft_value_2_text',
            'craft_value_3_title', 'craft_value_3_text',
        ], $this->seoSaveKeys('about'))));

        $this->handleUpload($request, 'about_hero_image_file', 'about_hero_image');
        $this->handleUpload($request, 'about_gallery_1_file', 'about_gallery_1');
        $this->handleUpload($request, 'about_gallery_2_file', 'about_gallery_2');
        $this->handleUpload($request, 'about_gallery_3_file', 'about_gallery_3');
        $this->handleUpload($request, 'about_og_image_file', 'about_og_image');

        return back()->with('success', 'About page content saved.');
    }

    public function faq(): View
    {
        $keys = array_merge([
            'faq_banner_image',
        ], $this->seoKeys('faq'));

        return view('admin.pages.faq', ['settings' => $this->load($keys)]);
    }

    public function updateFaq(Request $request): RedirectResponse
    {
        Setting::setMany($request->only($this->seoSaveKeys('faq')));
        $this->handleUpload($request, 'faq_banner_image_file', 'faq_banner_image');

        return back()->with('success', 'FAQ page content saved.');
    }

    public function contact(): View
    {
        $keys = array_merge([
            'store_lat', 'store_lng',
        ], $this->seoKeys('contact'));

        return view('admin.pages.contact', ['settings' => $this->load($keys)]);
    }

    public function updateContact(Request $request): RedirectResponse
    {
        Setting::setMany($request->only(array_merge([
            'store_lat', 'store_lng',
        ], $this->seoSaveKeys('contact'))));

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

    public function sitemap(): View
    {
        $counts = [
            'products'    => Product::where('is_active', true)->count(),
            'categories'  => Category::count(),
            'collections' => Collection::count(),
            'posts'       => Post::published()->count(),
        ];

        $totalUrls = $counts['products'] + $counts['categories'] + $counts['collections'] + $counts['posts'] + 7;

        return view('admin.pages.sitemap', compact('counts', 'totalUrls'));
    }

    public function robots(): View
    {
        $robotsTxt = Setting::get('robots_txt', "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /cart\nDisallow: /checkout\nDisallow: /account/\nSitemap: " . url('/sitemap.xml'));

        return view('admin.pages.robots', compact('robotsTxt'));
    }

    public function updateRobots(Request $request): RedirectResponse
    {
        $request->validate(['robots_txt' => ['required', 'string', 'max:5000']]);
        Setting::set('robots_txt', $request->input('robots_txt'));

        return back()->with('success', 'robots.txt saved.');
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
            $path = $this->storeImageWithQuality($request->file($fileField), 'pages', $request->input('image_quality'), 1920, 82);
            Setting::set($settingKey, $path);
        }
    }
}
