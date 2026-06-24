{{--
    Source: resources/theme-reference/home-jewelry.html, the `<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">`
    block (lines 2671-2753).

    The `<ul class="nav-ul-mb" id="wrapper-menu-navigation">` is intentionally left empty — public/js/main.js
    (handleMobileMenu, ~line 966) clones the desktop `.box-nav-menu` from header.blade.php into this element
    at runtime, so the mobile nav always mirrors the real header nav without needing to be duplicated here.

    Deviations from the reference (documented):
    - "Ochaka." text-logo and the demo "Login" button placement are replaced with the real store name and a
      real auth-aware login link (auth/login routes already used in header.blade.php).
    - The TikTok social icon and the language `<select>` are omitted for the same reasons given in
      footer.blade.php (no tiktok_url setting; no i18n built).
    - The payment-method icon list is omitted for the same reason given in footer.blade.php (payment method
      decision pending with the client per CLAUDE.md).
--}}
<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close-popup" data-bs-dismiss="offcanvas">
        <i class="icon-close"></i>
    </span>
    <div class="canvas-header">
        <img src="{{ asset('images/white-fado-logo.png') }}" alt="{{ \App\Models\Setting::get('store_name', 'FADÓ') }}">
        @auth
            <a href="{{ route('shop.account.index') }}" class="tf-btn type-small style-2">
                Account
                <i class="icon icon-user"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="tf-btn type-small style-2">
                Login
                <i class="icon icon-user"></i>
            </a>
        @endauth
        <span class="br-line"></span>
    </div>
    <div class="canvas-body">
        <div class="mb-content-top">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation"></ul>
        </div>
        <div class="group-btn">
            <a href="{{ route('shop.wishlist') }}" class="tf-btn type-small style-2">
                Wishlist
                <i class="icon icon-heart"></i>
            </a>
            <div data-bs-dismiss="offcanvas">
                <a href="#search" data-bs-toggle="modal" class="tf-btn type-small style-2">
                    Search
                    <i class="icon icon-magnifying-glass"></i>
                </a>
            </div>
        </div>
        <div class="flow-us-wrap">
            @php
                $fb = \App\Models\Setting::get('facebook_url');
                $ig = \App\Models\Setting::get('instagram_url');
                $tw = \App\Models\Setting::get('twitter_url');
            @endphp
            @if($fb || $ig || $tw)
            <h5 class="title">Follow us on</h5>
            <ul class="tf-social-icon">
                @if($fb)
                <li><a href="{{ $fb }}" target="_blank" class="social-facebook"><span class="icon"><i class="icon-fb"></i></span></a></li>
                @endif
                @if($ig)
                <li><a href="{{ $ig }}" target="_blank" class="social-instagram"><span class="icon"><i class="icon-instagram-logo"></i></span></a></li>
                @endif
                @if($tw)
                <li><a href="{{ $tw }}" target="_blank" class="social-x"><span class="icon"><i class="icon-x"></i></span></a></li>
                @endif
            </ul>
            @endif
        </div>
    </div>
    <div class="canvas-footer">
        @php
            $activeCurrencyCode  = session('fado_currency', 'EUR');
            $availableCurrencies = \App\Models\Currency::orderByDesc('is_default')->orderBy('code')->get();
        @endphp
        <div class="tf-currencies">
            <form action="{{ route('shop.currency.switch') }}" method="POST" class="mb-0">
                @csrf
                <select name="currency" onchange="this.form.submit()" class="tf-dropdown-select style-default type-currencies">
                    @foreach($availableCurrencies as $cur)
                        <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>
