@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
    $shippingNotice = Setting::get('shipping_notice', 'Free delivery on orders over €75');
    $paymentMethodLabel = Setting::get('payment_method_label', 'Secure payment by card');
@endphp

@section('title', 'Your Bag — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Your Bag</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Your Bag</h6></li>
            </ul>
        </div>
    </div>
</section>

@if(session('cart_updated'))
<div class="tf-notice-wrap">
    <div class="container">
        <p class="h6 text-center">{{ session('cart_updated') }}</p>
    </div>
</div>
@endif

<section class="flat-spacing">
    <div class="container">

        @if($items->isEmpty())
        <div class="text-center py-5">
            <i class="icon icon-shopping-cart-simple" style="font-size:4rem; opacity:.3; display:block; margin-bottom:24px"></i>
            <h3 class="h3 fw-normal mb-12">Your bag is empty</h3>
            <p class="h6 text-main mb-32">Explore our collections of fine Irish jewellery and add pieces you love.</p>
            <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Browse Jewellery</a>
        </div>
        @else

        <div class="row g-5">

            {{-- Cart items --}}
            <div class="col-lg-8">
                <div class="list-order-product cart-items">
                    @foreach($items as $item)
                    @php $img = $item['product']->primaryImage; @endphp
                    <div class="order-item{{ !$loop->last ? '' : '' }}">
                        <div class="img-prd">
                            <a href="{{ route('shop.product', $item['product']) }}">
                                @if($img)
                                    <img class="lazyload" data-src="{{ Storage::url($img->path) }}"
                                         src="{{ Storage::url($img->path) }}" alt="{{ $item['product']->name }}">
                                @else
                                    <img src="/images/demo/product-placeholder.jpg" alt="{{ $item['product']->name }}">
                                @endif
                            </a>
                        </div>
                        <div class="infor-prd">
                            <a href="{{ route('shop.product', $item['product']) }}" class="prd_name link fw-medium">
                                {{ $item['product']->name }}
                            </a>
                            <p class="prd_select text-small">
                                {{ $item['variant']->metal?->name }}
                                @if($item['variant']->gemstone) / {{ $item['variant']->gemstone->name }} @endif
                                @if($item['size']) · US {{ number_format((float)$item['size']->us_size, 1) }} @endif
                            </p>

                            <div class="d-flex align-items-center gap-16 mt-8">
                                {{-- Qty stepper --}}
                                <form method="POST" action="{{ route('shop.cart.update') }}" class="wg-quantity-v2">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <div class="wg-quantity">
                                        <button type="button" class="btn-quantity btn-decrease" onclick="stepCartQty(this, -1)">−</button>
                                        <input type="number" name="quantity" class="quantity-field"
                                               value="{{ $item['quantity'] }}" min="0" max="10"
                                               onchange="this.form.submit()">
                                        <button type="button" class="btn-quantity btn-increase" onclick="stepCartQty(this, 1)">+</button>
                                    </div>
                                </form>

                                {{-- Remove --}}
                                <form method="POST" action="{{ route('shop.cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit" class="tf-btn-line remove-item h6">Remove</button>
                                </form>
                            </div>
                        </div>
                        <div class="price-prd">
                            <span class="h6 fw-semibold text-black">{{ $currency->format($item['line_eur']) }}</span>
                            @if($item['quantity'] > 1)
                            <span class="text-small text-main">{{ $currency->format($item['price_eur']) }} each</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-20">
                    <a href="{{ route('shop.jewellery') }}" class="tf-btn-line h6">← Continue shopping</a>
                </div>
            </div>

            {{-- Order summary sidebar --}}
            <div class="col-lg-4">
                <div class="fl-sidebar-cart sticky-top">
                    <div class="box-your-order">
                        <h4 class="fw-semibold">Order Summary</h4>

                        <div class="list-total mt-24">
                            <div class="total-item h6">
                                <span>Subtotal</span>
                                <span class="fw-semibold text-black">{{ $currency->format($subtotalEur) }}</span>
                            </div>
                            <div class="total-item h6">
                                <span>Shipping</span>
                                <span class="fw-semibold text-main">
                                    @if($subtotalEur >= $freeShippingThreshold) Free @else Calculated at checkout @endif
                                </span>
                            </div>
                            <div class="last-total h5 fw-medium text-black">
                                <span>Total</span>
                                <div class="text-end">
                                    <span>{{ $currency->format($subtotalEur) }}</span>
                                    @if($currency->code !== 'EUR')
                                    <p class="text-small text-main mb-0">(€{{ number_format($subtotalEur, 2) }} EUR)</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('shop.checkout') }}" class="tf-btn animate-btn w-100 fw-bold mt-24">
                            Proceed to Checkout
                        </a>

                        <div class="mt-16 d-flex flex-column gap-8">
                            <p class="text-small text-main d-flex align-items-center gap-8">
                                <i class="icon icon-shield-check"></i> Secure, encrypted checkout
                            </p>
                            <p class="text-small text-main d-flex align-items-center gap-8">
                                <i class="icon icon-arrow-counter-clockwise"></i> 30-day returns on unworn pieces
                            </p>
                            <p class="text-small text-main d-flex align-items-center gap-8">
                                <i class="icon icon-truck-simple"></i> {{ $shippingNotice }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
function stepCartQty(btn, delta) {
    const form  = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const val   = Math.max(0, Math.min(10, parseInt(input.value || 1) + delta));
    input.value = val;
    form.submit();
}
</script>
@endpush
