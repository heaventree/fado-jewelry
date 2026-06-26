<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $content = Setting::get('robots_txt', "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /cart\nDisallow: /checkout\nDisallow: /account/\nSitemap: " . url('/sitemap.xml'));

        return response($content, 200, [
            'Content-Type'  => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
