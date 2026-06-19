<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        use App\Models\Setting;
        $defaultTitle       = Setting::get('meta_title',       config('app.name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery');
        $defaultDescription = Setting::get('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.');
        $gaId               = Setting::get('google_analytics');
        $canonicalUrl       = request()->url();
    @endphp

    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', $canonicalUrl)">

    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="@yield('title', $defaultTitle)">
    <meta property="og:description" content="@yield('meta_description', $defaultDescription)">
    <meta property="og:url"         content="@yield('canonical', $canonicalUrl)">
    <meta property="og:image"       content="@yield('og_image', asset('images/fado-og.jpg'))">
    <meta property="og:site_name"   content="{{ Setting::get('store_name', 'FADÓ Jewellery') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('meta_description', $defaultDescription)">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/fado-og.jpg'))">

    {{-- Google Analytics (only when ID is set and not in local env) --}}
    @if($gaId && app()->environment('production'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $gaId }}');</script>
    @endif

    {{-- Ochaka theme assets --}}
    <link rel="stylesheet" href="/fonts/fonts.css">
    <link rel="stylesheet" href="/icon/icomoon/style.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/styles.css">

    {{-- FADO colour overrides --}}
    <link rel="stylesheet" href="/css/fado.css">

    <link rel="shortcut icon" href="/favicon.ico">

    @stack('css')
</head>
<body>

{{-- Scroll-to-top --}}
<button id="goTop">
    <span class="border-progress"></span>
    <span class="icon icon-caret-up"></span>
</button>

{{-- Page-load preloader --}}
<div class="preload preload-container" id="preload">
    <div class="preload-logo">
        <div class="spinner"></div>
    </div>
</div>

<div id="wrapper">

    @include('shop.layouts.partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('shop.layouts.partials.footer')

</div>{{-- /#wrapper --}}

@include('shop.layouts.partials.mobile-menu')

{{-- Search modal --}}
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-0 border-0">
            <div class="modal-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h4 class="fw-semibold">Search FADÓ Jewellery</h4>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('shop.search') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control form-control-lg"
                           placeholder="Search for rings, pendants, earrings…" autofocus>
                    <button type="submit" class="btn btn-fado-primary px-4">
                        <i class="icon icon-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Ochaka JS --}}
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.min.js"></script>
<script src="/js/swiper-bundle.min.js"></script>
<script src="/js/carousel.js"></script>
<script src="/js/bootstrap-select.min.js"></script>
<script src="/js/lazysize.min.js"></script>
<script src="/js/wow.min.js"></script>
<script src="/js/main.js"></script>

@stack('scripts')

</body>
</html>
