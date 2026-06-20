{{--
    Source: resources/theme-reference/home-jewelry.html, the "header-fixed" header block (lines 459-835).
    The reference file also contains a transparent overlay header ("header-abs-3", lines 52-458) intended
    to sit over a full-bleed hero on the homepage only. We use the single sticky "header-fixed" variant
    on every page (matching the direction already taken in the previous build) so the header behaves
    identically across home, listing, product, and account pages.

    Deviations from the reference (documented):
    - The "Home" mega-menu (ThemeForest demo-switcher links: "Home Fashion 1/2/3...") is ThemeForest
      sales-page filler with no equivalent in a real storefront — replaced with real "Jewellery" and
      "Collections" mega-menus built from the same `sub-menu mega-menu` / `mega-menu-item` / `sub-menu_list`
      markup (verified at home-jewelry.html lines 217-309, the "Shop" mega-menu).
    - The generic "Shop" / "Product" / "Blog" demo dropdowns are removed (their targets — demo shop layout
      variations, a blog — are out of scope per CLAUDE.md). "Page" (plain `sub-menu` / `sub-menu_list`,
      lines 782-797) is reused verbatim in style for "About Us".
    - The `mn-none` modifier on each `<li class="menu-item mn-none">` is dropped: that class only exists in
      this multi-home demo file to stop one of its two header navs being duplicated into the mobile menu
      (public/js/main.js:970 strips `.mn-none` when building `#wrapper-menu-navigation`). With a single
      header, keeping it would silently empty the mobile nav, so the modifier is omitted.
    - The col-6 promotional image column inside the mega-menu (`<ul class="list-hor"><li class="wg-cls hover-img">`)
      is omitted — no curated nav promo imagery has been agreed with the client yet.
    - The language selector (`tf-languages`) is omitted — no i18n is built; including it would be a fabricated
      feature.
--}}
@php
    use App\Models\Category;
    use App\Models\Collection;
    use App\Models\Setting;

    $navCategories  = Category::whereNull('parent_id')->orderBy('sort_order')->with('children')->get();
    $navCollections = Collection::orderBy('name')->get();

    $cartCount     = session('cart_count', 0);
    $wishlistCount = session('wishlist_count', 0);
@endphp
<header class="tf-header header-fixed">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 text-center text-xl-start">
                {{-- No FADO logo file has been supplied yet — rendered as a styled text wordmark
                     (see public/css/fado.css .fado-wordmark) rather than using Ochaka's own demo logo.svg. --}}
                <a href="{{ route('shop.home') }}" class="logo-site justify-content-center justify-content-xl-start">
                    <span class="fado-wordmark">{{ Setting::get('store_name', 'FADÓ') }}</span>
                </a>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <nav class="box-navigation">
                    <ul class="box-nav-menu">

                        {{-- Jewellery — real categories, mega-menu structure verified at home-jewelry.html:217-309 --}}
                        <li class="menu-item">
                            <a href="{{ route('shop.jewellery') }}" class="item-link">Jewellery<i class="icon icon-caret-down"></i></a>
                            @if($navCategories->isNotEmpty())
                            <div class="sub-menu mega-menu">
                                <div class="container">
                                    <div class="row">
                                        @foreach($navCategories->chunk((int) ceil($navCategories->count() / 3)) as $chunk)
                                        <div class="col-2">
                                            <div class="mega-menu-item">
                                                <ul class="sub-menu_list">
                                                    @foreach($chunk as $cat)
                                                    <li><a href="{{ route('shop.category', $cat->slug) }}" class="sub-menu_link">{{ $cat->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </li>

                        {{-- Collections — real collections, same mega-menu structure --}}
                        <li class="menu-item">
                            <a href="{{ route('shop.collections') }}" class="item-link">Collections<i class="icon icon-caret-down"></i></a>
                            @if($navCollections->isNotEmpty())
                            <div class="sub-menu mega-menu">
                                <div class="container">
                                    <div class="row">
                                        @foreach($navCollections->chunk((int) ceil($navCollections->count() / 3)) as $chunk)
                                        <div class="col-2">
                                            <div class="mega-menu-item">
                                                <ul class="sub-menu_list">
                                                    @foreach($chunk as $col)
                                                    <li><a href="{{ route('shop.collection', $col->slug) }}" class="sub-menu_link">{{ $col->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </li>

                        {{-- About Us — plain dropdown-free link (no sub-menu, single page) --}}
                        <li class="menu-item">
                            <a href="{{ route('shop.about') }}" class="item-link">About Us</a>
                        </li>

                    </ul>
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
