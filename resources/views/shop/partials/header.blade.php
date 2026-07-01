{{--
    Source: _ochaka-reference/home-decor.html — top bar (lines 50-93) + header (lines 96-470).

    Layout: logo left (col-xl-3), nav center (col-xl-6), icons right (col-xl-3) — single row.
    Homepage adds header-abs-2 to overlap the hero slider via negative margin.
    Inner pages use bg-off-white for a solid light background.

    Deviations from the reference (documented):
    - Top bar demo text/links replaced with real store info + FADO links (FAQ, Contact).
    - TikTok icon and language select omitted (no tiktok_url setting, no i18n).
    - HOME/SHOP demo mega-menus replaced with real Jewellery/Collections from DB.
--}}
@php
    use App\Models\Category;
    use App\Models\Collection;
    use App\Models\Currency;
    use App\Models\Setting;

    $navCategories  = Category::whereNull('parent_id')->orderBy('sort_order')->with('children')->get();
    $navCollections = Collection::orderBy('name')->get();

    $activeCurrencyCode  = session('fado_currency', 'EUR');
    $availableCurrencies = Currency::orderByDesc('is_default')->orderBy('code')->get();

    $cartCount     = session('cart_count', 0);
    $wishlistCount = session('wishlist_count', 0);
@endphp

{{-- ── Top Bar ── --}}
<div class="tf-topbar bg-black">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8">
                <div class="topbar-left">
                    <h6 class="text-up text-white fw-normal text-line-clamp-1">{{ Setting::get('store_tagline', 'Fine Irish Jewellery — Handcrafted with passion') }}</h6>
                </div>
            </div>
            <div class="col-xl-5 col-lg-4 d-none d-lg-block">
                <ul class="topbar-right topbar-option-list">
                    <li class="h6">
                        <a href="{{ route('shop.faq') }}" class="text-white link">Help & FAQs</a>
                    </li>
                    <li class="br-line"></li>
                    <li class="h6">
                        <a href="{{ route('shop.contact') }}" class="text-white link">Contact</a>
                    </li>
                    <li class="br-line d-none d-xl-inline-flex"></li>
                    <li class="tf-currencies d-none d-xl-block">
                        <form action="{{ route('shop.currency.switch') }}" method="POST" class="mb-0">
                            @csrf
                            <select name="currency" onchange="this.form.submit()" class="tf-dropdown-select style-default color-white type-currencies">
                                @foreach($availableCurrencies as $cur)
                                    <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ── Header ── --}}
<header class="tf-header header-fix{{ request()->routeIs('shop.home') ? ' header-abs-2' : ' bg-off-white' }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 text-center text-xl-start">
                <a href="{{ route('shop.home') }}" class="logo-site justify-content-center justify-content-xl-start">
                    <img class="logo-default" src="{{ asset('images/white-fado-logo.png') }}" alt="{{ Setting::get('store_name', 'FADÓ') }}">
                    <img class="logo-sticky" src="{{ asset('images/white-fado-logo.png') }}" alt="{{ Setting::get('store_name', 'FADÓ') }}">
                </a>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <nav class="box-navigation">
                    @include('shop.partials.nav-menu')
                </nav>
            </div>
            <div class="col-xl-3 col-md-4 col-3">
                <ul class="nav-icon-list">
                    <li class="d-none d-lg-flex">
                        @auth
                            <a class="nav-icon-item link" href="{{ route('shop.account.index') }}"><i class="icon icon-user"></i></a>
                        @else
                            <a class="nav-icon-item link" href="{{ route('login') }}"><i class="icon icon-user"></i></a>
                        @endauth
                    </li>
                    <li class="d-none d-md-flex">
                        <a class="nav-icon-item link" href="#search" data-bs-toggle="modal">
                            <i class="icon icon-magnifying-glass"></i>
                        </a>
                    </li>
                    <li class="d-none d-sm-flex shop-wishlist">
                        <a class="nav-icon-item link" href="{{ route('shop.wishlist') }}">
                            <i class="icon icon-heart"></i>
                        </a>
                        @if($wishlistCount > 0)<span class="count wishlist-count">{{ $wishlistCount }}</span>@endif
                    </li>
                    <li class="shop-cart" data-bs-toggle="offcanvas" data-bs-target="#shoppingCart">
                        <a class="nav-icon-item link" href="#shoppingCart" data-bs-toggle="offcanvas">
                            <i class="icon icon-shopping-cart-simple"></i>
                        </a>
                        @if($cartCount > 0)<span class="count">{{ $cartCount }}</span>@endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
