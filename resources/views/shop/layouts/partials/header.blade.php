@php
    $activeCurrencyCode   = session('fado_currency', 'EUR');
    $availableCurrencies  = \App\Models\Currency::orderBy('is_default','desc')->orderBy('code')->get();
    $cartCount            = session('cart_count', 0);
    $wishlistCount        = session('wishlist_count', 0);
    $shippingNotice       = \App\Models\Setting::get('shipping_notice', 'Free delivery on orders over €75');
@endphp

{{-- ── Top Bar — Ochaka tf-topbar bg-black (exact from index.html) ────────── --}}
<div class="tf-topbar bg-black">
    <div class="container-full">
        <div class="row">
            <div class="col-xl-7 col-lg-8">
                <div class="topbar-left">
                    <h6 class="text-up text-white fw-normal text-line-clamp-1">
                        {{ $shippingNotice }}
                    </h6>
                    <div class="group-btn">
                        <a href="{{ route('shop.jewellery') }}" class="tf-btn-line style-white letter-space-0">Jewellery</a>
                        <a href="{{ route('shop.collections') }}" class="tf-btn-line style-white letter-space-0">Collections</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-4 d-none d-lg-block">
                <ul class="topbar-right topbar-option-list">
                    <li class="h6">
                        <a href="{{ route('shop.contact') }}" class="text-white link">Contact</a>
                    </li>
                    <li class="br-line"></li>
                    <li class="h6">
                        <a href="{{ route('shop.contact') }}#consultation" class="text-white link">Book a Consultation</a>
                    </li>
                    <li class="br-line d-none d-xl-inline-flex"></li>
                    <li class="tf-currencies d-none d-xl-block">
                        <form action="{{ route('shop.currency.switch') }}" method="POST" class="d-inline mb-0">
                            @csrf
                            <select name="currency" onchange="this.form.submit()"
                                    class="tf-dropdown-select style-default color-white type-currencies">
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
{{-- /Top Bar --}}

{{-- ── Header — Ochaka header-fix header-abs-1 (exact from index.html) ─────── --}}
{{-- Single row: mobile hamburger | logo-left (col-xl-3) | nav-center (col-xl-6) | icons-right (col-xl-3) --}}
<header class="tf-header header-fix header-abs-1">
    <div class="container-full">
        <div class="row align-items-center">

            {{-- Mobile hamburger (hidden on xl) --}}
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>

            {{-- Logo — left on xl, centred on mobile --}}
            <div class="col-xl-3 col-md-4 col-6 d-flex justify-content-center justify-content-xl-start">
                <a href="{{ route('shop.home') }}" class="logo-site">
                    <div class="fado-wordmark">FADÓ</div>
                </a>
            </div>

            {{-- Desktop nav — centre (hidden below xl) --}}
            <div class="col-xl-6 d-none d-xl-block">
                <nav class="box-navigation">
                    @include('shop.layouts.partials.mega-menu')
                </nav>
            </div>

            {{-- Icons — right --}}
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
{{-- /Header --}}
