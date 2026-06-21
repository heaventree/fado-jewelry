{{--
    Source: resources/theme-reference/view-cart.html lines 475-793
    (`<section class="s-page-title">` through the end of `<!-- /View Cart -->`).

    Deviations from the reference (documented, anti-fabrication):
    - The cart-expiry countdown notification ("Your cart will expire in 65 minutes!",
      lines 495-503) omitted — no real cart-expiry feature exists; fabricated urgency tactic.
    - The free-shipping progress bar (lines 504-516) is REAL here, not the reference's
      hardcoded "$150"/50% — computed from the live `free_shipping_threshold` setting and
      actual subtotal.
    - Per-row size `<select>` (lines 543-555) replaced with plain text (metal / gemstone /
      size) — there is no "change size from the cart" feature in CartService; an editable
      select implies a capability that doesn't exist. Quantity +/- IS real (wired to
      shop.cart.update).
    - "Add voucher discount" input + the 3 hardcoded promo-code boxes (lines 668-732,
      "Themesflat"/"SliVox"/"MasShin") omitted — there is a real Coupon admin CRUD
      (per CLAUDE.md) but no customer-facing "apply coupon to cart" endpoint exists yet.
      Flagging this as a gap rather than building a new feature or faking the codes.
    - Sidebar "Discounts" row omitted (no coupon flow to produce one). The shipping
      `fieldset` radios (hardcoded "$35 Local"/"$35 Flat Rate") replaced with a plain
      "Calculated at checkout" line — real Ireland/International shipping rate settings
      exist, but they're applied at checkout once an address is known, not in the cart.
    - Added an empty-cart state (not present in the reference, which assumes a populated
      demo cart) — reuses the same classes as the cart drawer's empty state for consistency.
--}}
@extends('shop.layouts.app')

@section('title', 'Shopping Cart — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

@php
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 0);
    $remainingForFreeShipping = max(0, $freeShippingThreshold - $subtotalEur);
    $shippingProgress = $freeShippingThreshold > 0 ? min(100, ($subtotalEur / $freeShippingThreshold) * 100) : 100;
@endphp

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Shopping Cart</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li>
                    <h6 class="current-page fw-normal">Shopping Cart</h6>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- /Page Title --}}

<div class="flat-spacing each-list-prd">
    <div class="container">
        @if($items->isEmpty())
        <div class="box-text_empty type-shop_cart text-center">
            <div class="shop-empty_top">
                <span class="icon">
                    <i class="icon-shopping-cart-simple"></i>
                </span>
                <h3 class="text-emp fw-normal">Your cart is empty</h3>
                <p class="h6 text-main">Your cart is currently empty. Let us assist you in finding the right piece.</p>
            </div>
            <div class="shop-empty_bot">
                <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Shopping</a>
                <a href="{{ route('shop.home') }}" class="tf-btn style-line">Back to home</a>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-xxl-9 col-xl-8">
                @if($freeShippingThreshold > 0)
                <div class="tf-cart-sold">
                    <div class="notification-progress">
                        <div class="text">
                            <i class="icon icon-truck"></i>
                            <p class="h6">
                                Free Shipping for orders over <span class="text-primary fw-bold">€{{ number_format($freeShippingThreshold, 2) }}</span>
                            </p>
                        </div>
                        <div class="progress-cart">
                            <div class="value" style="width: {{ $shippingProgress }}%;" data-progress="{{ $shippingProgress }}">
                                <span class="round"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <table class="tf-table-page-cart">
                    <thead>
                        <tr>
                            <th class="h6">Product</th>
                            <th class="h6">Price</th>
                            <th class="h6">Quantity</th>
                            <th class="h6">Total price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="tf-cart_item each-prd file-delete" data-cart-key="{{ $item['key'] }}">
                            <td>
                                <div class="cart_product">
                                    <a href="{{ route('shop.product', $item['product']) }}" class="img-prd">
                                        <img class="lazyload" src="{{ asset($item['product']->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" data-src="{{ asset($item['product']->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" alt="{{ $item['product']->name }}">
                                    </a>
                                    <div class="infor-prd">
                                        <h6 class="prd_name">
                                            <a href="{{ route('shop.product', $item['product']) }}" class="link">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h6>
                                        <div class="prd_select text-small">
                                            {{ $item['variant']->metal->name ?? '' }}
                                            @if($item['variant']->gemstone) · {{ $item['variant']->gemstone->name }} @endif
                                            @if($item['size']) · Size US {{ number_format((float) $item['size']->us_size, 1) }} @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="cart_price h6 each-price" data-cart-title="Price">€{{ number_format($item['price_eur'], 2) }}</td>
                            <td class="cart_quantity" data-cart-title="Quantity">
                                <div class="wg-quantity">
                                    <button class="btn-quantity minus-quantity" type="button" data-cart-key="{{ $item['key'] }}" data-step="-1">
                                        <i class="icon-minus fs-14"></i>
                                    </button>
                                    <input class="quantity-product cart-qty-input" type="text" name="number" value="{{ $item['quantity'] }}" data-cart-key="{{ $item['key'] }}" readonly>
                                    <button class="btn-quantity plus-quantity" type="button" data-cart-key="{{ $item['key'] }}" data-step="1">
                                        <i class="icon-plus fs-14"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="cart_total h6 each-subtotal-price" data-cart-title="Total">€{{ number_format($item['line_eur'], 2) }}</td>
                            <td class="cart_remove remove link" data-cart-title="Remove" data-cart-key="{{ $item['key'] }}">
                                <i class="icon icon-close"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="fl-sidebar-cart bg-white-smoke sticky-top">
                    <div class="box-order-summary">
                        <h4 class="title fw-semibold">Order Summary</h4>
                        <div class="subtotal h6 text-button d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold">Subtotal</h6>
                            <span class="total">€{{ number_format($subtotalEur, 2) }}</span>
                        </div>
                        <div class="ship">
                            <h6 class="fw-bold">Shipping</h6>
                            <p class="h6 text-main mb-0">Calculated at checkout</p>
                        </div>
                        <h5 class="total-order d-flex justify-content-between align-items-center">
                            <span>Total</span>
                            <span class="total each-total-price">€{{ number_format($subtotalEur, 2) }}</span>
                        </h5>
                        <div class="list-ver">
                            <a href="{{ route('shop.checkout') }}" class="tf-btn w-100 animate-btn">
                                Proceed to checkout
                                <i class="icon icon-arrow-right"></i>
                            </a>
                            <a href="{{ route('shop.jewellery') }}" class="tf-btn btn-white animate-btn animate-dark w-100">
                                Continue shopping
                                <i class="icon icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
{{-- /View Cart --}}

@push('scripts')
<script>
(function () {
    function postCart(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
    }

    document.querySelectorAll('.btn-quantity[data-cart-key]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-cart-key');
            var input = document.querySelector('.cart-qty-input[data-cart-key="' + key + '"]');
            var qty = Math.max(0, parseInt(input.value, 10) + parseInt(btn.getAttribute('data-step'), 10));
            postCart('{{ route('shop.cart.update') }}', { key: key, quantity: qty }).then(function () { location.reload(); });
        });
    });

    document.querySelectorAll('.cart_remove[data-cart-key]').forEach(function (el) {
        el.addEventListener('click', function () {
            postCart('{{ route('shop.cart.remove') }}', { key: el.getAttribute('data-cart-key') }).then(function () { location.reload(); });
        });
    });
})();
</script>
@endpush

@endsection
