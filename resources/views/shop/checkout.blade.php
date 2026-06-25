{{--
    Source: resources/theme-reference/checkout.html lines 475-771
    (`<section class="s-page-title">` through the end of `<!-- /Check Out -->`).

    Deviations from the reference (documented, anti-fabrication):
    - "Have a coupon?" box (lines 496-505) omitted — same gap as cart.blade.php/cart-drawer:
      real Coupon admin CRUD exists, no customer-facing "apply to order" endpoint yet.
    - First/Last name split into one "name" field (lines 513-517) — CheckoutController
      validates/stores a single `name` string; splitting would just be re-joined.
    - The giant country `<select>` with embedded province JSON (lines 529-558) and the
      "Choose State"/province `<select>` (lines 571-575) are replaced with a real ISO-3166
      alpha-2 country list (CheckoutController validates `country` as a 2-letter code) and
      a plain "County/State" text input — there's no real per-country province dataset or
      backend field for one; the reference's is also non-functional demo JS.
    - Order note `<textarea>` (line 581) omitted — OrderItem/Order have no notes field.
    - Payment method box (586-651): built dynamically from
      CheckoutController::paymentMethods(), not hardcoded "Direct bank transfer / Credit
      card / Cash on Delivery / Paypal". Only "Order on request" (`cod_enabled` setting) is
      selectable. Stripe is shown, disabled, only if `stripe_publishable_key` is configured —
      it has no working charge integration (no SDK, no processing code anywhere in this app).
    - Shipping Method box (663-679): the reference's "Free $00.00 / Express $5.00" with fake
      delivery-date ranges replaced with the real Ireland/International rates
      (`shipping_rate_ireland`/`shipping_rate_international`/`free_shipping_threshold`
      settings) — recalculated live by JS as the country select changes, since the rate
      depends on the address being entered in this same form.
    - Order summary sidebar (688-766): real cart items/subtotal; "Discounts" row omitted
      (no coupon flow); "Shipping" now reflects the live calculation above instead of a
      hardcoded "Free".
--}}
@extends('shop.layouts.app')

@section('title', 'Checkout — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

@php
    $ireRate    = $shippingRates['IE'];
    $intlRate   = $shippingRates['INTL'];
    $threshold  = $shippingRates['threshold'];
    $freeAlready = $subtotalEur >= $threshold;
@endphp

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Checkout</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li>
                    <h6 class="current-page fw-normal">Checkout</h6>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- /Page Title --}}

<section class="flat-spacing">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="tf-page-checkout mb-lg-0">
                    <form class="tf-checkout-cart-main" method="POST" action="{{ route('shop.checkout.place') }}">
                        @csrf
                        <div class="box-ip-checkout estimate-shipping">
                            <h2 class="title type-semibold">Information</h2>
                            @if($savedAddresses->isNotEmpty())
                            <div class="form_content mb-3">
                                <fieldset>
                                    <div class="tf-select">
                                        <select class="w-100" id="address-picker" onchange="fillAddress(this)">
                                            <option value="">— Select a saved address —</option>
                                            @foreach($savedAddresses as $sa)
                                            <option value="{{ $sa->id }}"
                                                data-name="{{ $sa->name }}"
                                                data-line1="{{ $sa->line1 }}"
                                                data-line2="{{ $sa->line2 }}"
                                                data-city="{{ $sa->city }}"
                                                data-county="{{ $sa->county }}"
                                                data-postcode="{{ $sa->postcode }}"
                                                data-country="{{ $sa->country }}"
                                                data-phone="{{ $sa->phone }}">
                                                {{ $sa->label }}: {{ $sa->line1 }}, {{ $sa->city }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                            @endif
                            <div class="form_content">
                                <fieldset>
                                    <input type="text" name="name" id="chk-name" value="{{ old('name', $user?->name) }}" placeholder="Full name" required>
                                </fieldset>
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="email" name="email" value="{{ old('email', $user?->email) }}" placeholder="Email address" required>
                                    </fieldset>
                                    <fieldset>
                                        <input type="text" name="phone" id="chk-phone" value="{{ old('phone') }}" placeholder="Phone number">
                                    </fieldset>
                                </div>
                                <fieldset>
                                    <div class="tf-select">
                                        <select class="w-100" id="checkout-country" name="country" required data-ire-rate="{{ $ireRate }}" data-intl-rate="{{ $intlRate }}" data-threshold="{{ $threshold }}">
                                            <option value="IE" selected>Ireland</option>
                                            <option value="AT">Austria</option>
                                            <option value="AU">Australia</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="CA">Canada</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="EE">Estonia</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="DE">Germany</option>
                                            <option value="GR">Greece</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IT">Italy</option>
                                            <option value="JP">Japan</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MT">Malta</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NO">Norway</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="RO">Romania</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="ES">Spain</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="text" name="city" id="chk-city" value="{{ old('city') }}" placeholder="Town/City" required>
                                    </fieldset>
                                    <fieldset>
                                        <input type="text" name="line1" id="chk-line1" value="{{ old('line1') }}" placeholder="Street address" required>
                                    </fieldset>
                                </div>
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="text" name="line2" id="chk-line2" value="{{ old('line2') }}" placeholder="Apartment, suite, etc. (optional)">
                                    </fieldset>
                                    <fieldset>
                                        <input type="text" name="county" id="chk-county" value="{{ old('county') }}" placeholder="County/State">
                                    </fieldset>
                                </div>
                                <fieldset>
                                    <input type="text" name="postcode" id="chk-postcode" value="{{ old('postcode') }}" placeholder="Postal code" required>
                                </fieldset>
                                @auth
                                <div class="checkbox-wrap mt-3">
                                    <input type="checkbox" name="save_address" value="1" class="tf-check" id="save-address" checked>
                                    <label for="save-address" class="h6">Save this address to my account</label>
                                </div>
                                @endauth
                            </div>
                        </div>
                        <div class="box-ip-payment">
                            <h2 class="title type-semibold">Choose Payment Option</h2>
                            <div class="payment-method-box" id="payment-method-box">
                                @forelse($paymentMethods as $key => $method)
                                <div class="payment_accordion">
                                    <label for="pay-{{ $key }}" class="payment_check checkbox-wrap @if(!$method['functional']) disabled @endif"
                                        data-bs-toggle="collapse" data-bs-target="#pay-{{ $key }}-body" aria-controls="pay-{{ $key }}-body">
                                        <input type="radio" name="payment_method" class="tf-check-rounded style-2" id="pay-{{ $key }}"
                                            value="{{ $key }}" @if($loop->first) checked @endif @disabled(!$method['functional'])>
                                        <span class="pay-title">{{ $method['label'] }}</span>
                                        @unless($method['functional'])<span class="text-small text-main-2">(coming soon)</span>@endunless
                                    </label>
                                    <div id="pay-{{ $key }}-body" class="collapse @if($loop->first) show @endif" data-bs-parent="#payment-method-box">
                                        <p class="payment_body h6">{{ $method['description'] }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="h6 text-main">No payment methods are currently configured — please contact the store.</p>
                                @endforelse
                            </div>
                            <p class="h6 mb-20">
                                Your personal data will be used to process your order and support your experience throughout this website.
                            </p>
                            <div class="checkbox-wrap">
                                <input id="agree" type="checkbox" class="tf-check style-2" required>
                                <label for="agree" class="h6">I have read and agree to the website <a href="{{ route('shop.terms') }}" target="_blank" class="text-primary">terms and conditions</a> *</label>
                            </div>
                        </div>
                        <div class="box-ip-shipping">
                            <h2 class="title type-semibold">Shipping</h2>
                            @if($freeAlready)
                            <p class="h6 text-main">Free shipping — your order qualifies (over €{{ number_format($threshold, 2) }}).</p>
                            @else
                            <p class="h6 text-main" id="checkout-shipping-line">
                                Shipping: <span id="checkout-shipping-amount">€{{ number_format($ireRate, 2) }}</span>
                                (<span id="checkout-shipping-region">Ireland</span> rate — recalculated for your country above)
                            </p>
                            @endif
                            @if($shippingNotice)
                            <p class="h6 text-main">{{ $shippingNotice }}</p>
                            @endif
                        </div>
                        <div class="button_submit">
                            <button type="submit" class="tf-btn animate-btn w-100">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="fl-sidebar-cart sticky-top">
                    <div class="box-your-order">
                        <h2 class="title type-semibold">Your Order</h2>
                        <ul class="list-order-product">
                            @foreach($items as $item)
                            <li class="order-item">
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
                                        {{ $item['quantity'] }} × {{ $item['variant']->metal->name ?? '' }}
                                        @if($item['size']) · Size US {{ number_format((float) $item['size']->us_size, 1) }} @endif
                                    </div>
                                </div>
                                <p class="price-prd h6">
                                    €{{ number_format($item['line_eur'], 2) }}
                                </p>
                            </li>
                            @endforeach
                        </ul>
                        <ul class="list-total">
                            <li class="total-item h6">
                                <span class="fw-bold text-black">Subtotal</span>
                                <span>€{{ number_format($subtotalEur, 2) }}</span>
                            </li>
                            <li class="total-item h6">
                                <span class="fw-bold text-black">Shipping</span>
                                <span id="checkout-summary-shipping">{{ $freeAlready ? 'Free' : '€' . number_format($ireRate, 2) }}</span>
                            </li>
                        </ul>
                        <div class="last-total h5 fw-medium text-black">
                            <span>Total</span>
                            <span id="checkout-summary-total">€{{ number_format($subtotalEur + ($freeAlready ? 0 : $ireRate), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- /Check Out --}}

@push('scripts')
<script>
function fillAddress(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    var fields = {name:'chk-name',line1:'chk-line1',line2:'chk-line2',city:'chk-city',county:'chk-county',postcode:'chk-postcode',phone:'chk-phone'};
    for (var k in fields) {
        var el = document.getElementById(fields[k]);
        if (el) el.value = opt.dataset[k] || '';
    }
    var country = opt.dataset.country;
    if (country) {
        var cs = document.getElementById('checkout-country');
        if (cs) { cs.value = country; cs.dispatchEvent(new Event('change')); }
    }
}
(function () {
    var countrySelect = document.getElementById('checkout-country');
    if (!countrySelect) return;

    var ireRate     = parseFloat(countrySelect.dataset.ireRate);
    var intlRate    = parseFloat(countrySelect.dataset.intlRate);
    var threshold   = parseFloat(countrySelect.dataset.threshold);
    var subtotalEur = {{ (float) $subtotalEur }};
    var freeAlready = subtotalEur >= threshold;

    if (freeAlready) return; // shipping line already shows the static "free" message server-side

    function recalc() {
        var isIreland = countrySelect.value === 'IE';
        var rate = isIreland ? ireRate : intlRate;
        var amountEl = document.getElementById('checkout-shipping-amount');
        var regionEl = document.getElementById('checkout-shipping-region');
        var summaryShipping = document.getElementById('checkout-summary-shipping');
        var summaryTotal = document.getElementById('checkout-summary-total');

        if (amountEl) amountEl.textContent = '€' + rate.toFixed(2);
        if (regionEl) regionEl.textContent = isIreland ? 'Ireland' : 'International';
        if (summaryShipping) summaryShipping.textContent = '€' + rate.toFixed(2);
        if (summaryTotal) summaryTotal.textContent = '€' + (subtotalEur + rate).toFixed(2);
    }

    countrySelect.addEventListener('change', recalc);
})();
</script>
@endpush

@endsection
