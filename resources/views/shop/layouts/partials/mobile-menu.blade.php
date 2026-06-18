{{-- Off-canvas mobile menu --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="fadoMobileMenu" aria-labelledby="fadoMobileMenuLabel">

    {{-- Header --}}
    <div class="fado-offcanvas-header">
        <span class="fado-mobile-logo">FADÓ</span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0" style="overflow-y:auto">

        {{-- ── Jewellery ────────────────────────────────────────────────────── --}}
        <a class="fado-mobile-nav-link" data-bs-toggle="collapse" href="#mbJewellery" role="button">
            Jewellery
            <i class="icon icon-caret-down" style="font-size:.75rem; opacity:.5"></i>
        </a>
        <div class="collapse" id="mbJewellery">
            <a href="{{ route('shop.category', 'rings') }}" class="fado-mobile-sub-link">Rings</a>
            <a href="{{ route('shop.category', 'crosses') }}" class="fado-mobile-sub-link">Crosses</a>
            <a href="{{ route('shop.category', 'pendants') }}" class="fado-mobile-sub-link">Pendants</a>
            <a href="{{ route('shop.category', 'earrings') }}" class="fado-mobile-sub-link">Earrings</a>
            <a href="{{ route('shop.category', 'bracelets-bangles') }}" class="fado-mobile-sub-link">Bracelets &amp; Bangles</a>
            <a href="{{ route('shop.category', 'cufflinks') }}" class="fado-mobile-sub-link">Cufflinks</a>
            <a href="{{ route('shop.category', 'brooches') }}" class="fado-mobile-sub-link">Brooches</a>
            <a href="{{ route('shop.category', 'tie-tacks') }}" class="fado-mobile-sub-link">Tie-tacks</a>
            <a href="{{ route('shop.jewellery') }}" class="fado-mobile-sub-link" style="color: var(--fado-green-mid); font-weight:600">View all jewellery →</a>
        </div>

        {{-- ── Collections ─────────────────────────────────────────────────── --}}
        <a class="fado-mobile-nav-link" data-bs-toggle="collapse" href="#mbCollections" role="button">
            Collections
            <i class="icon icon-caret-down" style="font-size:.75rem; opacity:.5"></i>
        </a>
        <div class="collapse" id="mbCollections">
            <p class="fado-mobile-sub-heading">Irish Heritage</p>
            <a href="{{ route('shop.collection', 'claddagh') }}" class="fado-mobile-sub-link">Claddagh</a>
            <a href="{{ route('shop.collection', 'corrib-claddagh') }}" class="fado-mobile-sub-link">Corrib Claddagh</a>
            <a href="{{ route('shop.collection', 'trinity') }}" class="fado-mobile-sub-link">Trinity</a>
            <a href="{{ route('shop.collection', 'an-ri') }}" class="fado-mobile-sub-link">An Rí</a>
            <a href="{{ route('shop.collection', 'high-crosses') }}" class="fado-mobile-sub-link">High Crosses</a>
            <a href="{{ route('shop.collection', 'newgrange') }}" class="fado-mobile-sub-link">Newgrange</a>
            <a href="{{ route('shop.collection', 'irish-folklore') }}" class="fado-mobile-sub-link">Irish Folklore</a>
            <a href="{{ route('shop.collection', 'shamrock') }}" class="fado-mobile-sub-link">Shamrock</a>
            <p class="fado-mobile-sub-heading">Fine Collections</p>
            <a href="{{ route('shop.collection', 'livia') }}" class="fado-mobile-sub-link">Livia</a>
            <a href="{{ route('shop.collection', 'sheelin') }}" class="fado-mobile-sub-link">Sheelin</a>
            <p class="fado-mobile-sub-heading">The Jewellery Garden</p>
            <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mobile-sub-link fw-semibold">The Garden Collection by FADÓ</a>
            <a href="{{ route('shop.collection', 'garden-daisy') }}" class="fado-mobile-sub-link" style="padding-left:48px">Daisy</a>
            <a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="fado-mobile-sub-link" style="padding-left:48px">Wild Daisy</a>
            <a href="{{ route('shop.collection', 'garden-bluebell') }}" class="fado-mobile-sub-link" style="padding-left:48px">Bluebell</a>
            <a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="fado-mobile-sub-link" style="padding-left:48px">Forget Me Not</a>
            <a href="{{ route('shop.collection', 'garden-butterfly') }}" class="fado-mobile-sub-link" style="padding-left:48px">Butterfly</a>
            <a href="{{ route('shop.collection', 'garden-bee') }}" class="fado-mobile-sub-link" style="padding-left:48px">Bee</a>
        </div>

        {{-- ── Wedding / Engagement ────────────────────────────────────────── --}}
        <a class="fado-mobile-nav-link" href="{{ route('shop.collection', 'the-garden-collection') }}">
            Wedding / Engagement
        </a>

        {{-- ── Flat links ───────────────────────────────────────────────────── --}}
        <a class="fado-mobile-nav-link" href="{{ route('shop.about') }}">About Us</a>
        <a class="fado-mobile-nav-link" href="{{ route('shop.contact') }}">Contact Us</a>
        <a class="fado-mobile-nav-link" href="{{ route('shop.contact') }}#consultation">Book an Appointment</a>

        <div style="border-top: 1px solid var(--fado-cream); margin: 12px 0;"></div>

        {{-- Account links --}}
        @auth
            <a class="fado-mobile-nav-link" href="{{ route('shop.account.index') }}">
                <i class="icon icon-user me-2" style="color: var(--fado-green-mid)"></i>My Account
            </a>
        @else
            <a class="fado-mobile-nav-link" href="{{ route('login') }}">
                <i class="icon icon-user me-2" style="color: var(--fado-green-mid)"></i>Sign In
            </a>
        @endauth
        <a class="fado-mobile-nav-link" href="{{ route('shop.wishlist') }}">
            <i class="icon icon-heart me-2" style="color: var(--fado-green-mid)"></i>Wishlist
        </a>
        <a class="fado-mobile-nav-link" href="{{ route('shop.cart') }}">
            <i class="icon icon-shopping-cart-simple me-2" style="color: var(--fado-green-mid)"></i>Shopping Bag
        </a>

    </div>
</div>
