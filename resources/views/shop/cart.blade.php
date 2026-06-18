@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
    $shippingNotice = Setting::get('shipping_notice', 'Free delivery on orders over €75');
    $paymentMethodLabel = Setting::get('payment_method_label', 'Secure payment by card');
@endphp

@section('title', 'Your Bag — FADÓ Jewellery')

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey); padding:24px 0">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:6px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.home') }}" style="color:var(--fado-warm-grey); text-decoration:none">Home</a></li>
                <li style="color:var(--fado-warm-grey)">/</li>
                <li style="color:var(--fado-deep-green); font-weight:600">Your Bag</li>
            </ol>
        </nav>
        <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:var(--fado-deep-green); margin:0">
            Your Bag
            @if($items->isNotEmpty())
                <span style="font-family:inherit; font-size:1rem; color:var(--fado-warm-grey); font-weight:400; margin-left:8px">
                    ({{ $items->sum('quantity') }} {{ $items->sum('quantity') === 1 ? 'item' : 'items' }})
                </span>
            @endif
        </h1>
    </div>
</div>

{{-- Flash --}}
@if(session('cart_updated'))
<div style="background:var(--fado-green-mid); color:#fff; text-align:center; padding:10px; font-size:.875rem">
    {{ session('cart_updated') }}
</div>
@endif

<div style="background:var(--fado-near-white); padding:48px 0 80px; min-height:60vh">
    <div class="container">

        @if($items->isEmpty())

        {{-- ── Empty cart ──────────────────────────────────────────────────── --}}
        <div style="text-align:center; padding:80px 24px">
            <i class="icon icon-shopping-cart-simple"
               style="font-size:4rem; color:var(--fado-warm-grey); display:block; margin-bottom:24px"></i>
            <h2 style="font-family:Georgia,serif; font-size:1.5rem; font-weight:400;
                       color:var(--fado-deep-green); margin-bottom:12px">
                Your bag is empty
            </h2>
            <p style="color:#888; margin-bottom:32px">
                Explore our collections of fine Irish jewellery and add pieces you love.
            </p>
            <a href="{{ route('shop.jewellery') }}"
               style="display:inline-block; padding:14px 36px; background:var(--fado-deep-green);
                      color:#fff; text-decoration:none; border-radius:2px; font-size:.9375rem;
                      font-weight:600; transition:background .2s"
               onmouseover="this.style.background='var(--fado-green-mid)'"
               onmouseout="this.style.background='var(--fado-deep-green)'">
                Browse Jewellery
            </a>
        </div>

        @else

        <div class="row g-5">

            {{-- ── Cart items ─────────────────────────────────────────────── --}}
            <div class="col-lg-8">
                <div style="background:#fff; border-radius:4px; overflow:hidden; border:1px solid var(--fado-cream)">

                    @foreach($items as $item)
                    @php
                        $img = $item['product']->primaryImage;
                    @endphp
                    <div class="fado-cart-row"
                         style="display:flex; gap:20px; padding:24px; {{ !$loop->last ? 'border-bottom:1px solid var(--fado-cream)' : '' }}">

                        {{-- Product image --}}
                        <a href="{{ route('shop.product', $item['product']) }}"
                           style="flex-shrink:0; display:block; width:90px; height:112px;
                                  background:var(--fado-cream); border-radius:2px; overflow:hidden">
                            @if($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $item['product']->name }}"
                                     style="width:100%; height:100%; object-fit:cover; object-position:center top">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                    <i class="icon icon-gem" style="font-size:1.5rem; color:var(--fado-warm-grey)"></i>
                                </div>
                            @endif
                        </a>

                        {{-- Item details --}}
                        <div style="flex:1; min-width:0">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px">
                                <div>
                                    <a href="{{ route('shop.product', $item['product']) }}"
                                       style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green);
                                              text-decoration:none; line-height:1.3; display:block; margin-bottom:4px">
                                        {{ $item['product']->name }}
                                    </a>
                                    <p style="font-size:.8125rem; color:#888; margin-bottom:2px">
                                        {{ $item['variant']->metal?->name }}
                                        @if($item['variant']->gemstone)
                                            / {{ $item['variant']->gemstone->name }}
                                        @endif
                                    </p>
                                    @if($item['size'])
                                    <p style="font-size:.8125rem; color:#888; margin-bottom:0">
                                        Ring size: US {{ number_format((float)$item['size']->us_size, 1) }}
                                    </p>
                                    @endif
                                </div>

                                {{-- Line price --}}
                                <div style="text-align:right; flex-shrink:0">
                                    <p style="font-size:1rem; font-weight:700; color:var(--fado-deep-green); margin-bottom:2px">
                                        {{ $currency->format($item['line_eur']) }}
                                    </p>
                                    @if($item['quantity'] > 1)
                                    <p style="font-size:.75rem; color:var(--fado-warm-grey); margin:0">
                                        {{ $currency->format($item['price_eur']) }} each
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Qty stepper + remove --}}
                            <div style="display:flex; align-items:center; gap:16px; margin-top:14px">
                                {{-- Qty update form --}}
                                <form method="POST" action="{{ route('shop.cart.update') }}"
                                      class="fado-qty-form" style="display:flex; align-items:center">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <div style="display:flex; border:1px solid var(--fado-warm-grey); border-radius:2px; overflow:hidden">
                                        <button type="button"
                                                onclick="stepCartQty(this, -1)"
                                                style="width:34px; height:34px; background:#fff; border:none; cursor:pointer;
                                                       font-size:1.1rem; color:var(--fado-deep-green); line-height:1">−</button>
                                        <input type="number" name="quantity"
                                               value="{{ $item['quantity'] }}" min="0" max="10"
                                               onchange="this.form.submit()"
                                               style="width:40px; height:34px; border:none;
                                                      border-left:1px solid var(--fado-cream);
                                                      border-right:1px solid var(--fado-cream);
                                                      text-align:center; font-size:.875rem;
                                                      color:var(--fado-deep-green); outline:none">
                                        <button type="button"
                                                onclick="stepCartQty(this, 1)"
                                                style="width:34px; height:34px; background:#fff; border:none; cursor:pointer;
                                                       font-size:1.1rem; color:var(--fado-deep-green); line-height:1">+</button>
                                    </div>
                                </form>

                                {{-- Remove --}}
                                <form method="POST" action="{{ route('shop.cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit"
                                            style="background:none; border:none; cursor:pointer;
                                                   font-size:.8125rem; color:var(--fado-warm-grey);
                                                   text-decoration:underline; padding:0;
                                                   transition:color .2s"
                                            onmouseover="this.style.color='#dc3545'"
                                            onmouseout="this.style.color='var(--fado-warm-grey)'">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div style="margin-top:20px">
                    <a href="{{ route('shop.jewellery') }}"
                       style="font-size:.875rem; color:var(--fado-green-mid); text-decoration:none">
                        ← Continue shopping
                    </a>
                </div>
            </div>

            {{-- ── Order summary sidebar ───────────────────────────────────── --}}
            <div class="col-lg-4">
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                            padding:28px; position:sticky; top:24px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:20px; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Order Summary
                    </h2>

                    {{-- Line items summary --}}
                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:20px">
                        @foreach($items as $item)
                        <div style="display:flex; justify-content:space-between; font-size:.8125rem; color:#555">
                            <span style="flex:1; margin-right:12px; line-height:1.4">
                                {{ $item['product']->name }}
                                <span style="color:var(--fado-warm-grey)">×{{ $item['quantity'] }}</span>
                            </span>
                            <span style="flex-shrink:0; font-weight:600; color:var(--fado-deep-green)">
                                {{ $currency->format($item['line_eur']) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <hr style="border-color:var(--fado-cream); margin:16px 0">

                    {{-- Subtotal --}}
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px">
                        <span style="font-size:.875rem; color:#555">Subtotal</span>
                        <span style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green)">
                            {{ $currency->format($subtotalEur) }}
                        </span>
                    </div>

                    {{-- Shipping note --}}
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px">
                        <span style="font-size:.875rem; color:#555">Shipping</span>
                        <span style="font-size:.875rem; color:var(--fado-green-mid); font-weight:600">
                            @if($subtotalEur >= $freeShippingThreshold) Free @else Calculated at checkout @endif
                        </span>
                    </div>

                    <hr style="border-color:var(--fado-cream); margin:16px 0">

                    {{-- Total --}}
                    <div style="display:flex; justify-content:space-between; margin-bottom:24px">
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

                    {{-- Checkout CTA --}}
                    <a href="{{ route('shop.checkout') }}"
                       style="display:block; width:100%; padding:14px; background:var(--fado-deep-green);
                              color:#fff; text-align:center; text-decoration:none; border-radius:2px;
                              font-size:.9375rem; font-weight:600; letter-spacing:.03em; transition:background .2s"
                       onmouseover="this.style.background='var(--fado-green-mid)'"
                       onmouseout="this.style.background='var(--fado-deep-green)'">
                        Proceed to Checkout
                    </a>

                    {{-- Trust badges --}}
                    <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--fado-cream);
                                display:flex; flex-direction:column; gap:8px">
                        <div style="display:flex; align-items:center; gap:8px; font-size:.75rem; color:#888">
                            <i class="icon icon-shield-check" style="color:var(--fado-green-mid)"></i>
                            Secure, encrypted checkout
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; font-size:.75rem; color:#888">
                            <i class="icon icon-arrow-counter-clockwise" style="color:var(--fado-green-mid)"></i>
                            30-day returns on unworn pieces
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; font-size:.75rem; color:#888">
                            <i class="icon icon-truck-simple" style="color:var(--fado-green-mid)"></i>
                            {{ $shippingNotice }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</div>

@endsection

@push('css')
<style>
/* Hide number spinners */
.fado-cart-row input[type=number]::-webkit-inner-spin-button,
.fado-cart-row input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
.fado-cart-row input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

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
