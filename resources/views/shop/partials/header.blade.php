{{--
    Source: resources/theme-reference/home-jewelry.html — this page renders TWO header elements:
      1. `<header class="tf-header header-abs-3">` (lines 52-458) — a transparent overlay header that
         sits over the hero on initial page load (negative margin-bottom pulls it on top of the slider).
      2. `<header class="tf-header header-fixed">` (lines 459-835) — visually identical nav/content, but
         CSS (public/css/styles.css:2759 `.tf-header.header-fixed`) hides it via
         opacity:0/visibility:hidden/translateY(-120%)/pointer-events:none until JS adds `.is-fixed`
         (public/js/main.js:393 `headerFixed()`, toggled past a 200px scroll threshold).
    A previous pass replaced both with header-fixed alone, which is invisible until the user scrolls
    200px — that's why the header disappeared entirely on load. Both variants are restored here, exactly
    matching Ochaka's own dual-header pattern, so there's always a visible header.

    The shared `<ul class="box-nav-menu">` nav content (Jewellery / Collections / About Us, built from
    real DB categories/collections) lives in shop.partials.nav-menu and is @include'd into both headers,
    since the reference repeats the identical menu markup in each header block (lines 123-455 vs 474-809).

    Deviations from the reference (documented):
    - The "Home" mega-menu (ThemeForest demo-switcher links: "Home Fashion 1/2/3...") is ThemeForest
      sales-page filler with no equivalent in a real storefront — replaced with real "Jewellery" and
      "Collections" mega-menus built from the same `sub-menu mega-menu` / `mega-menu-item` / `sub-menu_list`
      markup (verified at home-jewelry.html lines 217-309, the "Shop" mega-menu).
    - The generic "Shop" / "Product" / "Blog" demo dropdowns are removed (their targets — demo shop layout
      variations, a blog — are out of scope per CLAUDE.md). "Page" (plain `sub-menu` / `sub-menu_list`,
      lines 782-797) is reused verbatim in style for "About Us".
    - The `mn-none` modifier present on header-fixed's `<li class="menu-item mn-none">` in the reference is
      dropped: that class only exists in this multi-home demo file to stop one of its two header navs being
      duplicated into the mobile menu (public/js/main.js:970 strips `.mn-none` when building
      `#wrapper-menu-navigation`). With one real nav shared by both headers, keeping it would silently empty
      the mobile nav, so the modifier is omitted.
    - The col-6 promotional image column inside the mega-menu (`<ul class="list-hor"><li class="wg-cls hover-img">`)
      is omitted — no curated nav promo imagery has been agreed with the client yet.
    - The language selector (`tf-languages`) is omitted everywhere — no i18n is built; including it would be
      a fabricated feature. The currency selector (`tf-currencies`) is real and kept, wired to the Currency model.
    - header-abs-3's logo-bottom tagline text "JEWELRY BOUTIQUE" is replaced with FADÓ's real tagline
      ("Fine Irish Jewellery", per CLAUDE.md) — content only, the decorative SVG diamonds either side are
      kept verbatim.
    - Known open issue (not fixed here, flagged for the next pass): header-abs-3 relies on
      `margin-bottom: -164px` to sit on top of a tall hero image — correct on the homepage, but on hero-less
      inner pages (product, listing, cart, etc.) this will pull the header up over the page content. Ochaka's
      own inner pages (e.g. product-detail.html:98) use a third, plain `header-fix` class with no special
      positioning for exactly this reason. This single shared partial does not yet branch on whether the
      current page has a hero — needs revisiting once inner pages are built.
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

{{-- ── Header 1: transparent overlay, visible on load, sits over the hero ── --}}
<header class="tf-header header-abs-3">
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 col-3 d-xl-none">
                    <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu style-white">
                        <span></span>
                    </a>
                </div>
                <div class="col-xl-4 d-none d-xl-block">
                    <div class="list-hor">
                        <div class="tf-currencies d-none d-xl-block">
                            <form action="{{ route('shop.currency.switch') }}" method="POST" class="mb-0">
                                @csrf
                                <select name="currency" onchange="this.form.submit()" class="tf-dropdown-select style-default color-white type-currencies">
                                    @foreach($availableCurrencies as $cur)
                                        <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-6">
                    {{-- No FADO logo file has been supplied yet — text wordmark, see public/css/fado.css .fado-wordmark --}}
                    <a href="{{ route('shop.home') }}" class="logo-site justify-content-center">
                        <span class="fado-wordmark">{{ Setting::get('store_name', 'FADÓ') }}</span>
                        <div class="logo-bottom">
                            <svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 1L4.5 3L2.5 5L0.5 3L2.5 1Z" fill="white" />
                                <path d="M10.5 0L13.5 3L10.5 6L7.5 3L10.5 0Z" fill="white" />
                            </svg>
                            <span class="text-small-3 text-white text-uppercase">Fine Irish Jewellery</span>
                            <svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.5 0L6.5 3L3.5 6L0.5 3L3.5 0Z" fill="white" />
                                <path d="M11.5 1L13.5 3L11.5 5L9.5 3L11.5 1Z" fill="white" />
                            </svg>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-3">
                    <ul class="nav-icon-list">
                        <li class="d-none d-lg-flex">
                            @auth
                                <a class="nav-icon-item text-white link" href="{{ route('shop.account.index') }}"><i class="icon icon-user"></i></a>
                            @else
                                <a class="nav-icon-item text-white link" href="{{ route('login') }}"><i class="icon icon-user"></i></a>
                            @endauth
                        </li>
                        <li class="d-none d-md-flex">
                            <a class="nav-icon-item text-white link" href="#search" data-bs-toggle="modal">
                                <i class="icon icon-magnifying-glass"></i>
                            </a>
                        </li>
                        <li class="d-none d-sm-flex">
                            <a class="nav-icon-item text-white link" href="{{ route('shop.wishlist') }}">
                                <i class="icon icon-heart"></i>
                                @if($wishlistCount > 0)<span class="count">{{ $wishlistCount }}</span>@endif
                            </a>
                        </li>
                        <li class="shop-cart">
                            <a class="nav-icon-item text-white link" href="{{ route('shop.cart') }}">
                                <i class="icon icon-shopping-cart-simple"></i>
                            </a>
                            @if($cartCount > 0)<span class="count">{{ $cartCount }}</span>@endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-inner d-none d-xl-block">
        <div class="container">
            <nav class="box-navigation style-white">
                @include('shop.partials.nav-menu')
            </nav>
        </div>
    </div>
</header>

{{-- ── Header 2: sticky variant, hidden until scroll (CSS + main.js headerFixed()) ── --}}
<header class="tf-header header-fixed">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 text-center text-xl-start">
                {{-- No FADO logo file has been supplied yet — text wordmark, see public/css/fado.css .fado-wordmark --}}
                <a href="{{ route('shop.home') }}" class="logo-site justify-content-center justify-content-xl-start">
                    <span class="fado-wordmark">{{ Setting::get('store_name', 'FADÓ') }}</span>
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
                    <li class="d-none d-sm-flex">
                        <a class="nav-icon-item link" href="{{ route('shop.wishlist') }}">
                            <i class="icon icon-heart"></i>
                            @if($wishlistCount > 0)<span class="count">{{ $wishlistCount }}</span>@endif
                        </a>
                    </li>
                    <li class="shop-cart">
                        <a class="nav-icon-item link" href="{{ route('shop.cart') }}">
                            <i class="icon icon-shopping-cart-simple"></i>
                        </a>
                        @if($cartCount > 0)<span class="count">{{ $cartCount }}</span>@endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
