<header id="fado-header">

    {{-- ── Utility bar (deep green strip) ──────────────────────────────────── --}}
    <div class="fado-header-utility">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.contact') }}">
                        <i class="icon icon-phone" style="font-size:.8rem; margin-right:4px"></i>Contact Us
                    </a>
                    <span style="opacity:.35">|</span>
                    <a href="{{ route('shop.contact') }}#consultation">
                        <i class="icon icon-calendar-blank" style="font-size:.8rem; margin-right:4px"></i>Book an appointment
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span style="opacity:.6; font-size:.75rem">Free delivery on orders over €100</span>
                    {{-- Currency switcher --}}
                    @php
                        $activeCurrencyCode = session('fado_currency', 'EUR');
                        $availableCurrencies = \App\Models\Currency::orderBy('is_default','desc')->orderBy('code')->get();
                    @endphp
                    <form action="{{ route('shop.currency.switch') }}" method="POST" class="d-inline">
                        @csrf
                        <select name="currency"
                                onchange="this.form.submit()"
                                style="background:transparent; border:none; color:rgba(255,255,255,0.85); font-size:.8125rem; cursor:pointer; outline:none">
                            @foreach($availableCurrencies as $cur)
                            <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>
                                {{ $cur->code === 'EUR' ? '€' : ($cur->code === 'USD' ? '$' : '') }} {{ $cur->code }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main header row (logo + icons) ──────────────────────────────────── --}}
    <div class="fado-header-main">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">

                {{-- Mobile hamburger --}}
                <button class="d-xl-none btn p-0 border-0 me-3"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#fadoMobileMenu"
                        aria-controls="fadoMobileMenu" style="color: var(--fado-deep-green); font-size: 1.4rem;">
                    <i class="icon icon-list"></i>
                </button>

                {{-- Logo --}}
                <a href="{{ route('shop.home') }}" class="fado-logo-wordmark text-decoration-none">
                    <div>FADÓ</div>
                    <div class="fado-logo-tagline">Fine Irish Jewellery</div>
                </a>

                {{-- Spacer --}}
                <div class="flex-grow-1"></div>

                {{-- Header icons (search, wishlist, account, cart) --}}
                <div class="fado-header-icons d-flex align-items-center gap-1">

                    {{-- Search --}}
                    <a href="#search" data-bs-toggle="modal" title="Search">
                        <i class="icon icon-magnifying-glass"></i>
                    </a>

                    {{-- Account --}}
                    @auth
                        <a href="{{ route('shop.account.index') }}" title="My Account">
                            <i class="icon icon-user"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" title="Sign in">
                            <i class="icon icon-user"></i>
                        </a>
                    @endauth

                    {{-- Wishlist --}}
                    <a href="{{ route('shop.wishlist') }}" title="Wishlist" style="position:relative">
                        <i class="icon icon-heart"></i>
                        @php $wishlistCount = session('wishlist_count', 0); @endphp
                        @if($wishlistCount > 0)
                            <span class="fado-cart-count" style="background:var(--fado-gold)">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('shop.cart') }}" title="Shopping bag" style="position:relative">
                        <i class="icon icon-shopping-cart-simple"></i>
                        @php $cartCount = session('cart_count', 0); @endphp
                        @if($cartCount > 0)
                            <span class="fado-cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>

                </div>
            </div>
        </div>
    </div>

    {{-- ── Mega-menu navigation bar ─────────────────────────────────────────── --}}
    @include('shop.layouts.partials.mega-menu')

</header>
