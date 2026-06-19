@php
    $activeCurrencyCode   = session('fado_currency', 'EUR');
    $availableCurrencies  = \App\Models\Currency::orderBy('is_default','desc')->orderBy('code')->get();
    $cartCount            = session('cart_count', 0);
    $wishlistCount        = session('wishlist_count', 0);
@endphp

{{-- ── Transparent header — sits over hero (Ochaka header-abs-3) ──────────── --}}
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
                            <form action="{{ route('shop.currency.switch') }}" method="POST" class="d-inline mb-0">
                                @csrf
                                <select name="currency" onchange="this.form.submit()"
                                        class="tf-dropdown-select style-default color-white type-currencies">
                                    @foreach($availableCurrencies as $cur)
                                        <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-6">
                    <a href="{{ route('shop.home') }}" class="logo-site justify-content-center" style="text-decoration:none">
                        <div class="fado-wordmark">FADÓ</div>
                        <div class="logo-bottom">
                            <span class="text-small-3 text-white text-uppercase">Fine Irish Jewellery</span>
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
                @include('shop.layouts.partials.mega-menu')
            </nav>
        </div>
    </div>
</header>

{{-- ── Fixed sticky header — appears on scroll (Ochaka header-fixed) ──────── --}}
<header class="tf-header header-fixed">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 text-center text-xl-start">
                <a href="{{ route('shop.home') }}" class="logo-site justify-content-center justify-content-xl-start" style="text-decoration:none">
                    <div class="fado-wordmark">FADÓ</div>
                </a>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <nav class="box-navigation">
                    @include('shop.layouts.partials.mega-menu')
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
