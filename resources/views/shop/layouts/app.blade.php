<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'FADÓ Jewellery')) — Fine Irish Jewellery</title>
    <meta name="description" content="@yield('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.')">

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
                    <h4 class="fw-semibold" style="color:#044705">Search FADÓ Jewellery</h4>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/shop/search') }}" method="GET" class="d-flex gap-2">
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
