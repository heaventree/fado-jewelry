<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(): View
    {
        return view('shop.home');
    }

    public function jewellery(Request $request): View
    {
        // Phase 3 Step 3 — full filter/listing built there
        return view('shop.coming-soon', ['page' => 'Jewellery']);
    }

    public function category(string $category): View
    {
        return view('shop.coming-soon', ['page' => ucwords(str_replace('-', ' ', $category))]);
    }

    public function collections(): View
    {
        $collections = Collection::all();
        return view('shop.coming-soon', ['page' => 'Collections']);
    }

    public function collection(string $slug): View
    {
        return view('shop.coming-soon', ['page' => ucwords(str_replace('-', ' ', $slug))]);
    }

    public function product(Product $product): View
    {
        // Phase 3 Step 4 — product detail built there
        return view('shop.coming-soon', ['page' => $product->name]);
    }

    public function cart(): View
    {
        return view('shop.coming-soon', ['page' => 'Shopping Bag']);
    }

    public function checkout(): View
    {
        return view('shop.coming-soon', ['page' => 'Checkout']);
    }

    public function wishlist(): View
    {
        return view('shop.coming-soon', ['page' => 'Wishlist']);
    }

    public function account(): View
    {
        return view('shop.coming-soon', ['page' => 'My Account']);
    }

    public function about(): View
    {
        return view('shop.coming-soon', ['page' => 'About Us']);
    }

    public function contact(): View
    {
        return view('shop.coming-soon', ['page' => 'Contact Us']);
    }

    public function privacy(): View
    {
        return view('shop.coming-soon', ['page' => 'Privacy Policy']);
    }

    public function search(Request $request): View
    {
        return view('shop.coming-soon', ['page' => 'Search']);
    }

    public function newsletterSubscribe(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        // Mailchimp / email marketing integration goes here in a later phase
        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }

    public function switchCurrency(Request $request): RedirectResponse
    {
        $request->validate(['currency' => ['required', 'in:EUR,USD']]);
        session(['currency' => $request->input('currency')]);
        return back();
    }
}
