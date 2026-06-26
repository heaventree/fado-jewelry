<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('is_active', true)
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = Category::select('slug', 'updated_at')
            ->orderBy('sort_order')
            ->get();

        $collections = Collection::select('slug', 'updated_at')
            ->orderBy('name')
            ->get();

        $posts = Post::published()
            ->select('slug', 'updated_at')
            ->orderBy('published_at', 'desc')
            ->get();

        $content = view('sitemap', compact('products', 'categories', 'collections', 'posts'))->render();

        return response($content, 200, [
            'Content-Type'  => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
