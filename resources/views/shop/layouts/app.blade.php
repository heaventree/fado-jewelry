{{-- Source: resources/theme-reference/home-jewelry.html lines 1-51 (doctype/head/body open/preload/wrapper) and 3811-3845 (script tags). --}}
<!DOCTYPE html>

<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    @php
        use App\Models\Setting;
        $defaultTitle       = Setting::get('meta_title', Setting::get('store_name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery');
        $defaultDescription = Setting::get('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.');
        $gaId               = Setting::get('google_analytics_id');
        $canonicalUrl       = request()->url();
    @endphp
    <title>@yield('title', $defaultTitle)</title>
    <meta name="author" content="{{ Setting::get('store_name', 'FADÓ Jewellery') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="@yield('canonical', $canonicalUrl)">

    {{-- Open Graph / Twitter Card --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="@yield('title', $defaultTitle)">
    <meta property="og:description" content="@yield('meta_description', $defaultDescription)">
    <meta property="og:url"         content="@yield('canonical', $canonicalUrl)">
    {{-- No dedicated FADO Open Graph share image has been supplied yet, so the favicon is used as a
         non-broken placeholder fallback until one is provided. --}}
    <meta property="og:image"       content="@yield('og_image', asset('favicon.ico'))">
    <meta property="og:site_name"   content="{{ Setting::get('store_name', 'FADÓ Jewellery') }}">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('meta_description', $defaultDescription)">
    <meta name="twitter:image"       content="@yield('og_image', asset('favicon.ico'))">

    @if($gaId && app()->environment('production'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $gaId }}');</script>
    @endif

    <!-- font -->
    <link rel="stylesheet" href="{{ asset('fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/icomoon/style.css') }}">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">

    {{-- FADO colour overrides (palette swap only — see CLAUDE.md) --}}
    <link rel="stylesheet" href="{{ asset('css/fado.css') }}">

    {{-- Favicon: no FADO favicon asset has been supplied yet, so the existing public/favicon.ico is used
         rather than referencing Ochaka's own demo favicon.svg. --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @stack('css')
</head>

<body>

    <!-- Scroll Top -->
    <button id="goTop">
        <span class="border-progress"></span>
        <span class="icon icon-caret-up"></span>
    </button>

    <!-- preload -->
    <div class="preload preload-container" id="preload">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->

    <div id="wrapper">

        @include('shop.partials.header')

        @yield('content')

        @include('shop.partials.footer')

    </div>
    <!-- /#wrapper -->

    @include('shop.partials.mobile-menu')

    @include('shop.partials.cart-drawer')

    {{--
        Search modal — source: theme-reference/home-jewelry.html lines 3214-3347 (`#search` modal).
        - .quick-link-list (3226-3234): real top-level categories, not Ochaka's demo
          "Graphic tees"/"Plain t-shirts" etc.
        - .view-history-wrap (3236-3264) omitted — no real session-based recent-search
          tracking exists anywhere in this app; the reference's "History" is fake
          per-user search history with no backing data model.
        - .trend-product-wrap (3265-3343): real bestseller products, the same
          `is_bestseller` flag/query used for the homepage's best-sellers section, not
          Ochaka's demo "Queen fashion long sleeve shirt" filler.
    --}}
    @php
        $searchModalCategories = \App\Models\Category::whereNull('parent_id')->orderBy('sort_order')->limit(7)->get();
        $searchModalTrending = \App\Models\Product::with('primaryImage')
            ->where('is_active', true)
            ->where('is_bestseller', true)
            ->latest()
            ->limit(4)
            ->get();
    @endphp
    <div class="modal modalCentered fade modal-search" id="search">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
                <div>
                    <form class="form-search style-2" action="{{ route('shop.search') }}" method="GET">
                        <fieldset>
                            <input type="text" placeholder="Search item" class="style-stroke" name="q" tabindex="0" value="{{ request('q') }}" aria-required="true" required>
                        </fieldset>
                        <button type="submit" class="link"><i class="icon icon-magnifying-glass"></i></button>
                    </form>
                    @if($searchModalCategories->isNotEmpty())
                    <ul class="quick-link-list">
                        @foreach($searchModalCategories as $cat)
                        <li><a href="{{ route('shop.category', $cat->slug) }}" class="link-item text-main h6 link">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @if($searchModalTrending->isNotEmpty())
                <div class="trend-product-wrap">
                    <div class="heading">
                        <h4 class="title flex-grow-1">Bestsellers</h4>
                        <a href="{{ route('shop.jewellery') }}" class="tf-btn-line has-icon none-line fw-medium fs-18 text-normal">
                            View All Product
                            <i class="icon icon-caret-circle-right"></i>
                        </a>
                    </div>
                    <div class="trend-product-inner">
                        @foreach($searchModalTrending->chunk(2) as $chunk)
                        <div class="trend-product-list">
                            @foreach($chunk as $product)
                            @php $variantForSale = $product->variants->first(fn ($v) => $v->isOnSale()); @endphp
                            <div class="trend-product-item">
                                <div class="image">
                                    <img class="lazyload" src="{{ asset($product->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" data-src="{{ asset($product->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" alt="{{ $product->name }}">
                                </div>
                                <div class="content">
                                    <h6 class="title">
                                        <a href="{{ route('shop.product', $product) }}" class="link">{{ $product->name }}</a>
                                    </h6>
                                    <div class="price-wrap">
                                        @if($variantForSale)
                                            <span class="price-old h6 fw-normal">€{{ number_format((float) $variantForSale->price_eur, 2) }}</span>
                                            <span class="price-new h6">€{{ number_format((float) $variantForSale->sale_price_eur, 2) }}</span>
                                        @else
                                            <span class="price-new h6">From €{{ number_format((float) $product->variants->min('price_eur'), 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /Search -->

    <!-- Ochaka JS -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/lazysize.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/zoom.js') }}"></script>

    @stack('scripts')

</body>
</html>
