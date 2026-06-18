@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
    $paymentMethodLabel = Setting::get('payment_method_label', 'Secure payment by card');
@endphp

@section('title', 'Checkout — FADÓ Jewellery')

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey); padding:24px 0">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:6px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.home') }}" style="color:var(--fado-warm-grey); text-decoration:none">Home</a></li>
                <li style="color:var(--fado-warm-grey)">/</li>
                <li><a href="{{ route('shop.cart') }}" style="color:var(--fado-warm-grey); text-decoration:none">Your Bag</a></li>
                <li style="color:var(--fado-warm-grey)">/</li>
                <li style="color:var(--fado-deep-green); font-weight:600">Checkout</li>
            </ol>
        </nav>
        <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:var(--fado-deep-green); margin:0">
            Checkout
        </h1>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:48px 0 80px">
    <div class="container">

        {{-- Validation errors --}}
        @if($errors->any())
        <div style="background:#fff3f3; border:1px solid #f5c6c6; border-radius:3px; padding:16px 20px; margin-bottom:28px">
            <p style="font-weight:600; color:#dc3545; margin-bottom:8px">Please fix the following:</p>
            <ul style="margin:0; padding-left:20px; color:#dc3545; font-size:.875rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('shop.checkout.place') }}" id="checkoutForm">
        @csrf
        <div class="row g-5">

            {{-- ── LEFT — checkout form ───────────────────────────────────── --}}
            <div class="col-lg-7">

                {{-- Contact info --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px; margin-bottom:20px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:20px; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Contact Information
                    </h2>

                    @guest
                    <p style="font-size:.875rem; color:#888; margin-bottom:20px">
                        Already have an account?
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('shop.checkout')) }}"
                           style="color:var(--fado-green-mid)">Sign in</a>
                        to checkout faster.
                    </p>
                    @endguest

                    <div class="row g-3">
                        <div class="col-12">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'name',
                                'label' => 'Full Name',
                                'value' => old('name', $user?->name),
                                'type'  => 'text',
                                'required' => true,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'email',
                                'label' => 'Email Address',
                                'value' => old('email', $user?->email),
                                'type'  => 'email',
                                'required' => true,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'phone',
                                'label' => 'Phone (optional)',
                                'value' => old('phone'),
                                'type'  => 'tel',
                                'required' => false,
                            ])
                        </div>
                    </div>
                </div>

                {{-- Shipping address --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px; margin-bottom:20px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:20px; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Shipping Address
                    </h2>

                    <div class="row g-3">
                        <div class="col-12">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'line1',
                                'label' => 'Address Line 1',
                                'value' => old('line1'),
                                'type'  => 'text',
                                'required' => true,
                            ])
                        </div>
                        <div class="col-12">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'line2',
                                'label' => 'Address Line 2 (optional)',
                                'value' => old('line2'),
                                'type'  => 'text',
                                'required' => false,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'city',
                                'label' => 'City / Town',
                                'value' => old('city'),
                                'type'  => 'text',
                                'required' => true,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'county',
                                'label' => 'County / State (optional)',
                                'value' => old('county'),
                                'type'  => 'text',
                                'required' => false,
                            ])
                        </div>
                        <div class="col-md-6">
                            <label style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:6px">
                                Country <span style="color:#dc3545">*</span>
                            </label>
                            <div style="position:relative">
                                <select name="country"
                                        style="width:100%; padding:11px 40px 11px 14px;
                                               border:1px solid {{ $errors->has('country') ? '#dc3545' : 'var(--fado-warm-grey)' }};
                                               border-radius:3px; font-size:.9375rem; color:var(--fado-deep-green);
                                               background:#fff; appearance:none; cursor:pointer; outline:none;
                                               background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E\");
                                               background-repeat:no-repeat; background-position:right 14px center"
                                        onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                        onblur="this.style.borderColor='{{ $errors->has('country') ? '#dc3545' : 'var(--fado-warm-grey)' }}'">
                                    <option value="">Select country…</option>
                                    <option value="IE" {{ old('country', 'IE') === 'IE' ? 'selected' : '' }}>Ireland</option>
                                    <option value="GB" {{ old('country') === 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="US" {{ old('country') === 'US' ? 'selected' : '' }}>United States</option>
                                    <option value="CA" {{ old('country') === 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="AU" {{ old('country') === 'AU' ? 'selected' : '' }}>Australia</option>
                                    <option value="DE" {{ old('country') === 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="FR" {{ old('country') === 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="IT" {{ old('country') === 'IT' ? 'selected' : '' }}>Italy</option>
                                    <option value="ES" {{ old('country') === 'ES' ? 'selected' : '' }}>Spain</option>
                                    <option value="NL" {{ old('country') === 'NL' ? 'selected' : '' }}>Netherlands</option>
                                    <option value="BE" {{ old('country') === 'BE' ? 'selected' : '' }}>Belgium</option>
                                    <option value="AT" {{ old('country') === 'AT' ? 'selected' : '' }}>Austria</option>
                                    <option value="CH" {{ old('country') === 'CH' ? 'selected' : '' }}>Switzerland</option>
                                    <option value="SE" {{ old('country') === 'SE' ? 'selected' : '' }}>Sweden</option>
                                    <option value="NO" {{ old('country') === 'NO' ? 'selected' : '' }}>Norway</option>
                                    <option value="DK" {{ old('country') === 'DK' ? 'selected' : '' }}>Denmark</option>
                                    <option value="NZ" {{ old('country') === 'NZ' ? 'selected' : '' }}>New Zealand</option>
                                </select>
                            </div>
                            @error('country')
                            <p style="font-size:.75rem; color:#dc3545; margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            @include('shop.partials.checkout-field', [
                                'name'  => 'postcode',
                                'label' => 'Eircode / Postcode',
                                'value' => old('postcode'),
                                'type'  => 'text',
                                'required' => true,
                            ])
                        </div>
                    </div>
                </div>

                {{-- Payment notice --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px; margin-bottom:28px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:16px; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Payment
                    </h2>
                    <div style="display:flex; align-items:flex-start; gap:12px; background:var(--fado-cream);
                                padding:16px; border-radius:3px">
                        <i class="icon icon-shield-check" style="color:var(--fado-green-mid); font-size:1.25rem; flex-shrink:0; margin-top:2px"></i>
                        <div>
                            <p style="font-size:.875rem; color:var(--fado-deep-green); font-weight:600; margin-bottom:4px">
                                Payment
                            </p>
                            <p style="font-size:.8125rem; color:#666; margin:0; line-height:1.6">
                                {{ $paymentMethodLabel }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Place order button (mobile — visible below lg) --}}
                <div class="d-lg-none">
                    <button type="submit"
                            style="width:100%; padding:16px; background:var(--fado-deep-green); color:#fff;
                                   border:none; border-radius:2px; font-size:1rem; font-weight:600;
                                   cursor:pointer; letter-spacing:.03em; transition:background .2s"
                            onmouseover="this.style.background='var(--fado-green-mid)'"
                            onmouseout="this.style.background='var(--fado-deep-green)'">
                        Place Order
                    </button>
                </div>

            </div>

            {{-- ── RIGHT — order summary ───────────────────────────────────── --}}
            <div class="col-lg-5">
                <div style="position:sticky; top:24px">
                    <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px">
                        <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                                   color:var(--fado-deep-green); margin-bottom:20px; padding-bottom:16px;
                                   border-bottom:1px solid var(--fado-cream)">
                            Order Summary
                        </h2>

                        {{-- Items --}}
                        <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:20px">
                            @foreach($items as $item)
                            @php $img = $item['product']->primaryImage; @endphp
                            <div style="display:flex; gap:12px; align-items:center">
                                {{-- Mini image --}}
                                <div style="position:relative; flex-shrink:0">
                                    <div style="width:52px; height:64px; background:var(--fado-cream); border-radius:2px; overflow:hidden">
                                        @if($img)
                                            <img src="{{ Storage::url($img->path) }}" alt="{{ $item['product']->name }}"
                                                 style="width:100%; height:100%; object-fit:cover; object-position:center top">
                                        @else
                                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                                <i class="icon icon-gem" style="color:var(--fado-warm-grey)"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span style="position:absolute; top:-6px; right:-6px; background:var(--fado-deep-green);
                                                 color:#fff; font-size:.6rem; width:18px; height:18px;
                                                 border-radius:50%; display:flex; align-items:center; justify-content:center;
                                                 font-weight:700">
                                        {{ $item['quantity'] }}
                                    </span>
                                </div>
                                <div style="flex:1; min-width:0">
                                    <p style="font-size:.8125rem; font-weight:600; color:var(--fado-deep-green);
                                              margin-bottom:2px; line-height:1.3">{{ $item['product']->name }}</p>
                                    <p style="font-size:.75rem; color:#888; margin:0">
                                        {{ $item['variant']->metal?->name }}
                                        @if($item['variant']->gemstone) / {{ $item['variant']->gemstone->name }} @endif
                                        @if($item['size']) · US {{ number_format((float)$item['size']->us_size,1) }} @endif
                                    </p>
                                </div>
                                <span style="flex-shrink:0; font-size:.875rem; font-weight:600; color:var(--fado-deep-green)">
                                    {{ $currency->format($item['line_eur']) }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <hr style="border-color:var(--fado-cream); margin:16px 0">

                        <div style="display:flex; justify-content:space-between; margin-bottom:8px">
                            <span style="font-size:.875rem; color:#555">Subtotal</span>
                            <span style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green)">
                                {{ $currency->format($subtotalEur) }}
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:16px">
                            <span style="font-size:.875rem; color:#555">Shipping</span>
                            <span style="font-size:.875rem; color:var(--fado-green-mid); font-weight:600">
                                @if($subtotalEur >= $freeShippingThreshold) Free @else Calculated after order @endif
                            </span>
                        </div>

                        <hr style="border-color:var(--fado-cream); margin:16px 0">

                        <div style="display:flex; justify-content:space-between; margin-bottom:28px">
                            <span style="font-size:1rem; font-weight:700; color:var(--fado-deep-green)">Total</span>
                            <div style="text-align:right">
                                <span style="font-size:1.25rem; font-weight:700; color:var(--fado-deep-green)">
                                    {{ $currency->format($subtotalEur) }}
                                </span>
                                @if($currency->code !== 'EUR')
                                <p style="font-size:.7rem; color:var(--fado-warm-grey); margin:2px 0 0">
                                    (€{{ number_format($subtotalEur, 2) }} EUR)
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Place order (desktop) --}}
                        <button type="submit"
                                class="d-none d-lg-block"
                                style="width:100%; padding:16px; background:var(--fado-deep-green); color:#fff;
                                       border:none; border-radius:2px; font-size:1rem; font-weight:600;
                                       cursor:pointer; letter-spacing:.03em; transition:background .2s"
                                onmouseover="this.style.background='var(--fado-green-mid)'"
                                onmouseout="this.style.background='var(--fado-deep-green)'">
                            Place Order
                        </button>

                        <p style="font-size:.75rem; color:#aaa; text-align:center; margin-top:14px; margin-bottom:0">
                            <i class="icon icon-lock-simple" style="font-size:.7rem"></i>
                            Secure checkout — your details are protected
                        </p>
                    </div>

                    <div style="margin-top:16px; text-align:center">
                        <a href="{{ route('shop.cart') }}"
                           style="font-size:.875rem; color:var(--fado-green-mid); text-decoration:none">
                            ← Return to bag
                        </a>
                    </div>
                </div>
            </div>

        </div>
        </form>

    </div>
</div>

@endsection
