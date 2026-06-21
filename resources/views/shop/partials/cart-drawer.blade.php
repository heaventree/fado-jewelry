{{--
    Source: resources/theme-reference/shop-filter-drawer.html lines 4308-4642
    (`<div class="offcanvas offcanvas-end popup-shopping-cart" id="shoppingCart">`).
    Included once globally (shop.layouts.app), opened from the header's cart icon via
    Bootstrap's native offcanvas (data-bs-toggle="offcanvas" data-bs-target="#shoppingCart") —
    no custom JS needed for open/close.

    Deviations from the reference (documented, anti-fabrication):
    - "tf-minicart-recommendations" ("You may also like", lines 4309-4379) omitted — no real
      recommendation logic exists; the reference's products are themesflat demo filler.
    - The cart-expiry countdown ("Your cart will expire in 65 minutes!") omitted — no real
      cart-expiry feature exists; this is a fabricated urgency tactic in the source theme.
    - The three "tf-mini-cart-tool-btn" buttons (Note / Shipping / Gift) and their
      "tool-openable" forms (lines 4509-4523, 4546-4642 ish) omitted — none of those have any
      backend support (no order-note field, no shipping estimator, no gift-wrap option).
    - "Add X to get free shipping" progress bar is REAL, not the reference's hardcoded
      "$15.40" — computed from the actual `free_shipping_threshold` setting and live subtotal.
    - Size/Colour swatch dots (reference: "Size: XS" + a hardcoded `.dot-color` swap) replaced
      with the item's real metal/gemstone/size text — there's no per-metal/gemstone hex colour
      defined anywhere in this build, so a colour swatch would be fabricated.
--}}
@php
    $cartItems = app(\App\Services\CartService::class)->items();
    $cartSubtotalEur = app(\App\Services\CartService::class)->subtotalEur();
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 0);
    $cartRemainingForFreeShipping = max(0, $freeShippingThreshold - $cartSubtotalEur);
    $cartShippingProgress = $freeShippingThreshold > 0 ? min(100, ($cartSubtotalEur / $freeShippingThreshold) * 100) : 100;
@endphp
<div class="offcanvas offcanvas-end popup-shopping-cart" id="shoppingCart">
    <div class="canvas-wrapper">
        <div class="popup-header">
            <span class="title fw-semibold h4">Shopping cart</span>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas"></span>
        </div>
        <div class="wrap">
            <div class="tf-mini-cart-wrap list-file-delete wrap-empty_text">
                <div class="tf-mini-cart-main">
                    <div class="tf-mini-cart-sroll">
                        <div class="tf-mini-cart-items @if($cartItems->isEmpty()) list-empty @endif">
                            @if($cartItems->isEmpty())
                            <div class="box-text_empty type-shop_cart">
                                <div class="shop-empty_top">
                                    <span class="icon">
                                        <i class="icon-shopping-cart-simple"></i>
                                    </span>
                                    <h3 class="text-emp fw-normal">Your cart is empty</h3>
                                    <p class="h6 text-main">
                                        Your cart is currently empty. Let us assist you in finding the right piece
                                    </p>
                                </div>
                                <div class="shop-empty_bot">
                                    <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">
                                        Shopping
                                    </a>
                                    <a href="{{ route('shop.home') }}" class="tf-btn style-line">
                                        Back to home
                                    </a>
                                </div>
                            </div>
                            @else
                                @foreach($cartItems as $item)
                                <div class="tf-mini-cart-item file-delete">
                                    <div class="tf-mini-cart-image">
                                        <img class="lazyload" data-src="{{ asset($item['product']->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" src="{{ asset($item['product']->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" alt="{{ $item['product']->name }}">
                                    </div>
                                    <div class="tf-mini-cart-info">
                                        <div class="text-small text-main-2 sub">{{ $item['variant']->metal->name ?? '' }}</div>
                                        <h6 class="title">
                                            <a href="{{ route('shop.product', $item['product']) }}" class="link text-line-clamp-1">{{ $item['product']->name }}</a>
                                        </h6>
                                        <div class="size">
                                            @if($item['size'])
                                            <div class="text-small text-main-2 sub">Size: US {{ number_format((float) $item['size']->us_size, 1) }}</div>
                                            @endif
                                            @if($item['variant']->gemstone)
                                            <div class="text-small text-main-2 sub">Gemstone: {{ $item['variant']->gemstone->name }}</div>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="h6 fw-semibold">
                                                <span class="number">{{ $item['quantity'] }}x</span>
                                                <span class="price text-primary tf-mini-card-price">€{{ number_format($item['price_eur'], 2) }}</span>
                                            </div>
                                            <i class="icon link icon-close remove"
                                               onclick="fetch('{{ route('shop.cart.remove') }}', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify({key:'{{ $item['key'] }}'})}).then(() => location.reload())"></i>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @if($cartItems->isNotEmpty())
                <div class="tf-mini-cart-bottom box-empty_clear">
                    <div class="tf-mini-cart-threshold">
                        <div class="text">
                            <h6 class="subtotal">Subtotal (<span class="prd-count">{{ $cartItems->sum('quantity') }}</span> item{{ $cartItems->sum('quantity') === 1 ? '' : 's' }})</h6>
                            <h4 class="text-primary total-price tf-totals-total-value">€{{ number_format($cartSubtotalEur, 2) }}</h4>
                        </div>
                        @if($freeShippingThreshold > 0)
                        <div class="tf-progress-bar tf-progress-ship">
                            <div class="value" style="width: {{ $cartShippingProgress }}%;" data-progress="{{ $cartShippingProgress }}"></div>
                        </div>
                        @if($cartRemainingForFreeShipping > 0)
                        <div class="desc text-main">Add <span class="text-primary fw-bold">€{{ number_format($cartRemainingForFreeShipping, 2) }}</span> to cart and get free shipping!</div>
                        @else
                        <div class="desc text-main">You've qualified for free shipping!</div>
                        @endif
                        @endif
                    </div>
                    <div class="tf-mini-cart-bottom-wrap">
                        <div class="tf-mini-cart-view-checkout">
                            <a href="{{ route('shop.cart') }}" class="tf-btn btn-white animate-btn animate-dark line">View cart</a>
                            <a href="{{ route('shop.checkout') }}" class="tf-btn animate-btn d-inline-flex bg-dark-2 w-100 justify-content-center"><span>Check out</span></a>
                        </div>
                        @if($freeShippingThreshold > 0)
                        <div class="free-shipping">
                            <i class="icon icon-truck"></i>
                            Free shipping on all orders over €{{ number_format($freeShippingThreshold, 2) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
