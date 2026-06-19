@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
    $paymentMethodLabel = Setting::get('payment_method_label', 'Secure payment by card');
@endphp

@section('title', 'Checkout — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Checkout</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('shop.cart') }}" class="h6 link">Your Bag</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Checkout</h6></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">

        @if($errors->any())
        <div class="tf-notice-wrap mb-24" style="border-color:#dc3545">
            <p class="h6 fw-semibold text-danger mb-8">Please fix the following:</p>
            <ul class="mb-0 ps-16">
                @foreach($errors->all() as $error)
                <li class="h6 text-danger">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('shop.checkout.place') }}" id="checkoutForm">
        @csrf

        <div class="tf-page-checkout">
            <div class="row">

                {{-- Form side --}}
                <div class="col-lg-7">
                    <div class="tf-checkout-cart-main">

                        {{-- Contact information --}}
                        <div class="box-ip-checkout estimate-shipping">
                            <h2 class="title type-semibold">Contact Information</h2>
                            @guest
                            <p class="h6 text-main mb-16">
                                Already have an account?
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('shop.checkout')) }}" class="link fw-semibold">Sign in</a>
                                to checkout faster.
                            </p>
                            @endguest
                            <div class="row g-3">
                                <div class="col-12">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'name',
                                        'label'    => 'Full Name',
                                        'value'    => old('name', $user?->name),
                                        'type'     => 'text',
                                        'required' => true,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'email',
                                        'label'    => 'Email Address',
                                        'value'    => old('email', $user?->email),
                                        'type'     => 'email',
                                        'required' => true,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'phone',
                                        'label'    => 'Phone (optional)',
                                        'value'    => old('phone'),
                                        'type'     => 'tel',
                                        'required' => false,
                                    ])
                                </div>
                            </div>
                        </div>

                        {{-- Shipping address --}}
                        <div class="box-ip-checkout box-ip-shipping">
                            <h2 class="title type-semibold">Shipping Address</h2>
                            <div class="row g-3">
                                <div class="col-12">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'line1',
                                        'label'    => 'Address Line 1',
                                        'value'    => old('line1'),
                                        'type'     => 'text',
                                        'required' => true,
                                    ])
                                </div>
                                <div class="col-12">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'line2',
                                        'label'    => 'Address Line 2 (optional)',
                                        'value'    => old('line2'),
                                        'type'     => 'text',
                                        'required' => false,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'city',
                                        'label'    => 'City / Town',
                                        'value'    => old('city'),
                                        'type'     => 'text',
                                        'required' => true,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'county',
                                        'label'    => 'County / State (optional)',
                                        'value'    => old('county'),
                                        'type'     => 'text',
                                        'required' => false,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="{{ $errors->has('country') ? 'has-error' : '' }}">
                                        <select name="country">
                                            <option value="">Select country *</option>
                                            @foreach([
                                                'IE' => 'Ireland', 'GB' => 'United Kingdom', 'US' => 'United States',
                                                'CA' => 'Canada',  'AU' => 'Australia',       'DE' => 'Germany',
                                                'FR' => 'France',  'IT' => 'Italy',            'ES' => 'Spain',
                                                'NL' => 'Netherlands', 'BE' => 'Belgium',      'AT' => 'Austria',
                                                'CH' => 'Switzerland', 'SE' => 'Sweden',       'NO' => 'Norway',
                                                'DK' => 'Denmark', 'NZ' => 'New Zealand',
                                            ] as $code => $label)
                                            <option value="{{ $code }}" {{ old('country', 'IE') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')<p class="h6 text-danger mt-4">{{ $message }}</p>@enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    @include('shop.partials.checkout-field', [
                                        'name'     => 'postcode',
                                        'label'    => 'Eircode / Postcode',
                                        'value'    => old('postcode'),
                                        'type'     => 'text',
                                        'required' => true,
                                    ])
                                </div>
                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="box-ip-checkout box-ip-payment">
                            <h2 class="title type-semibold">Payment</h2>
                            <div class="box-ip-payment">
                                <div class="payment_accordion">
                                    <div class="payment_check">
                                        <i class="icon icon-shield-check"></i>
                                        <div>
                                            <h6 class="fw-semibold">Secure Payment</h6>
                                            <p class="h6 fw-normal text-main">{{ $paymentMethodLabel }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Place order (mobile) --}}
                        <div class="d-lg-none mt-16">
                            <button type="submit" class="tf-btn animate-btn w-100 fw-bold button_submit">
                                Place Order
                            </button>
                        </div>

                    </div>
                </div>

                {{-- Order summary --}}
                <div class="col-lg-5">
                    <div class="fl-sidebar-cart sticky-top">
                        <div class="box-your-order">
                            <h4 class="fw-semibold">Order Summary</h4>

                            <div class="list-order-product">
                                @foreach($items as $item)
                                @php $img = $item['product']->primaryImage; @endphp
                                <div class="order-item">
                                    <div class="img-prd">
                                        @if($img)
                                            <img class="lazyload" data-src="{{ Storage::url($img->path) }}"
                                                 src="{{ Storage::url($img->path) }}" alt="{{ $item['product']->name }}">
                                        @else
                                            <img src="/images/demo/product-placeholder.jpg" alt="{{ $item['product']->name }}">
                                        @endif
                                        <span class="quantity-prd">{{ $item['quantity'] }}</span>
                                    </div>
                                    <div class="infor-prd">
                                        <p class="prd_name">{{ $item['product']->name }}</p>
                                        <p class="prd_select text-small">
                                            {{ $item['variant']->metal?->name }}
                                            @if($item['variant']->gemstone) / {{ $item['variant']->gemstone->name }} @endif
                                            @if($item['size']) · US {{ number_format((float)$item['size']->us_size,1) }} @endif
                                        </p>
                                    </div>
                                    <div class="price-prd h6">{{ $currency->format($item['line_eur']) }}</div>
                                </div>
                                @endforeach
                            </div>

                            <div class="list-total">
                                <div class="total-item h6">
                                    <span>Subtotal</span>
                                    <span class="fw-semibold text-black">{{ $currency->format($subtotalEur) }}</span>
                                </div>
                                <div class="total-item h6">
                                    <span>Shipping</span>
                                    <span class="fw-semibold text-main">
                                        @if($subtotalEur >= $freeShippingThreshold) Free @else After order @endif
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

                            {{-- Place order (desktop) --}}
                            <button type="submit" class="tf-btn animate-btn w-100 fw-bold button_submit d-none d-lg-flex mt-24">
                                Place Order
                            </button>

                            <p class="text-small text-main text-center mt-12">
                                <i class="icon icon-lock-simple"></i>
                                Secure checkout — your details are protected
                            </p>

                            <div class="mt-16 text-center">
                                <a href="{{ route('shop.cart') }}" class="tf-btn-line h6">← Return to bag</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </form>

    </div>
</section>

@endsection
