{{--
    No theme-reference file matches a guest-facing "thank you" page — checked
    account-orders-detail.html, but that's a logged-in account page (sidebar nav,
    avatar, courier/return timeline tabs) with no equivalent for a just-placed,
    possibly-guest order. Built from the site's own already-established classes
    instead of inventing new ones: `s-page-title` (checkout.blade.php/cart.blade.php),
    and the `box-your-order` / `list-order-product` / `list-total` / `last-total`
    order-summary pattern from checkout.blade.php's sidebar, reused here as the
    main content since it's the theme's real "items + totals" block.

    Real data only: order number, items (with metal/gemstone/size already
    eager-loaded per CheckoutController::confirmation), shipping address, payment
    method label, and status all come from the real $order. "What happens next"
    messaging is conditional on the real payment_method — order-on-request gets
    the real `payment_method_label` setting text; nothing is fabricated for Stripe
    since it can never be the stored payment_method (CheckoutController rejects it).
--}}
@extends('shop.layouts.app')

@section('title', 'Order Confirmed — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Order Confirmed</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li>
                    <h6 class="current-page fw-normal">Order Confirmed</h6>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- /Page Title --}}

<section class="flat-spacing">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-48">
                    <span class="icon" style="font-size: 48px; color: var(--fado-green-mid, #0AAC45);">
                        <i class="icon-check-circle"></i>
                    </span>
                    <h2 class="mt-12">Thank you{{ $order->user ? ', ' . explode(' ', $order->user->name)[0] : '' }}!</h2>
                    <p class="h6 text-main">
                        Your order <span class="fw-bold text-black">{{ $order->order_number }}</span> has been placed successfully.
                        A confirmation has been sent to <span class="fw-bold text-black">{{ $order->customer_email }}</span>.
                    </p>
                </div>

                <div class="box-your-order mb-32">
                    <h2 class="title type-semibold">Order Summary</h2>
                    <ul class="list-order-product">
                        @foreach($order->items as $item)
                        <li class="order-item">
                            <a href="{{ $item->product ? route('shop.product', $item->product) : '#' }}" class="img-prd">
                                <img class="lazyload" src="{{ asset($item->product?->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" data-src="{{ asset($item->product?->primaryImage?->path ?? 'images/ochaka/products/jewelry/product-5.jpg') }}" alt="{{ $item->product->name ?? 'Product' }}">
                            </a>
                            <div class="infor-prd">
                                <h6 class="prd_name">
                                    @if($item->product)
                                    <a href="{{ route('shop.product', $item->product) }}" class="link">{{ $item->product->name }}</a>
                                    @else
                                    {{ 'Product no longer available' }}
                                    @endif
                                </h6>
                                <div class="prd_select text-small">
                                    {{ $item->quantity }} × {{ $item->variant?->metal?->name ?? '' }}
                                    @if($item->variant?->gemstone) · {{ $item->variant->gemstone->name }} @endif
                                    @if($item->size) · Size US {{ number_format((float) $item->size->us_size, 1) }} @endif
                                </div>
                            </div>
                            <p class="price-prd h6">
                                {{ $order->currency_symbol }}{{ number_format($item->line_total, 2) }}
                            </p>
                        </li>
                        @endforeach
                    </ul>
                    <ul class="list-total">
                        <li class="total-item h6">
                            <span class="fw-bold text-black">Subtotal</span>
                            <span>{{ $order->currency_symbol }}{{ number_format((float) $order->subtotal, 2) }}</span>
                        </li>
                        <li class="total-item h6">
                            <span class="fw-bold text-black">Shipping</span>
                            <span>{{ (float) $order->shipping_total > 0 ? $order->currency_symbol . number_format((float) $order->shipping_total, 2) : 'Free' }}</span>
                        </li>
                    </ul>
                    <div class="last-total h5 fw-medium text-black">
                        <span>Total</span>
                        <span>{{ $order->currency_symbol }}{{ number_format((float) $order->total, 2) }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-32">
                        <h4 class="mb-12">Shipping Address</h4>
                        <p class="h6 text-main mb-0">
                            {{ $order->shipping_address['name'] ?? '' }}<br>
                            {{ $order->shipping_address['line1'] ?? '' }}<br>
                            @if(!empty($order->shipping_address['line2']))
                                {{ $order->shipping_address['line2'] }}<br>
                            @endif
                            {{ $order->shipping_address['city'] ?? '' }}
                            @if(!empty($order->shipping_address['county'])), {{ $order->shipping_address['county'] }}@endif
                            {{ $order->shipping_address['postcode'] ?? '' }}<br>
                            {{ $order->shipping_address['country'] ?? '' }}
                            @if(!empty($order->shipping_address['phone']))<br>{{ $order->shipping_address['phone'] }}@endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-32">
                        <h4 class="mb-12">Order Details</h4>
                        <p class="h6 text-main mb-0">
                            Order number: <span class="fw-bold text-black">{{ $order->order_number }}</span><br>
                            Date: <span class="fw-bold text-black">{{ $order->created_at->format('j M Y, H:i') }}</span><br>
                            Payment method: <span class="fw-bold text-black">{{ $order->payment_method === 'cod' ? 'Order on request' : ucfirst($order->payment_method) }}</span><br>
                            Status: <span class="fw-bold text-black">{{ $order->status_label }}</span>
                        </p>
                    </div>
                </div>

                @if($order->payment_method === 'cod')
                <div class="box-discount" style="background: var(--fado-pale-mint, #EBFCEF); border-radius: 8px; padding: 24px;">
                    <h4 class="mb-12">What happens next?</h4>
                    <p class="h6 text-main mb-0">
                        {{ \App\Models\Setting::get('payment_method_label') ?: 'Our team will contact you shortly to arrange payment for this order.' }}
                    </p>
                </div>
                @endif

                <div class="d-flex gap-12 justify-content-center mt-32">
                    <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">
                        Continue Shopping
                        <i class="icon icon-arrow-right"></i>
                    </a>
                    @auth
                    <a href="{{ route('shop.account.orders') }}" class="tf-btn btn-white animate-btn animate-dark">
                        View My Orders
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
{{-- /Order Confirmation --}}

@endsection
