@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 75);
@endphp

@section('title', 'Order Confirmed — FADÓ Jewellery')

@section('content')

{{-- ── Confirmation hero ─────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-deep-green); padding:56px 0; text-align:center">
    <div class="container">
        <div style="width:64px; height:64px; background:rgba(255,255,255,.12); border-radius:50%;
                    display:flex; align-items:center; justify-content:center; margin:0 auto 20px">
            <i class="icon icon-check-circle" style="font-size:2rem; color:var(--fado-green-light)"></i>
        </div>
        <h1 style="font-family:Georgia,serif; font-size:clamp(1.5rem,3vw,2.25rem); color:#fff;
                   font-weight:400; margin-bottom:12px">
            Thank you for your order!
        </h1>
        <p style="color:rgba(255,255,255,.75); font-size:1rem; max-width:500px; margin:0 auto 20px; line-height:1.7">
            Your order <strong style="color:#fff">{{ $order->order_number }}</strong> has been received.
            We'll be in touch within one business day to arrange payment and confirm dispatch.
        </p>
        <p style="color:rgba(255,255,255,.6); font-size:.875rem; margin:0">
            A confirmation will be sent to
            <strong style="color:rgba(255,255,255,.85)">{{ $order->customer_email }}</strong>
        </p>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:56px 0 80px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- ── Order detail card ──────────────────────────────────── --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                            overflow:hidden; margin-bottom:24px">

                    {{-- Card header --}}
                    <div style="background:var(--fado-cream); padding:16px 24px;
                                display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px">
                        <div>
                            <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                      color:var(--fado-warm-grey); margin-bottom:4px">Order Number</p>
                            <p style="font-size:1.125rem; font-weight:700; color:var(--fado-deep-green); margin:0; font-family:Georgia,serif">
                                {{ $order->order_number }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                      color:var(--fado-warm-grey); margin-bottom:4px">Date</p>
                            <p style="font-size:.9375rem; color:var(--fado-deep-green); margin:0">
                                {{ $order->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                      color:var(--fado-warm-grey); margin-bottom:4px">Status</p>
                            <span style="display:inline-block; padding:3px 12px; border-radius:20px; font-size:.75rem;
                                         font-weight:600; background:var(--fado-green-light); color:var(--fado-deep-green)">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div>
                            <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                      color:var(--fado-warm-grey); margin-bottom:4px">Total</p>
                            <p style="font-size:1.125rem; font-weight:700; color:var(--fado-deep-green); margin:0">
                                €{{ number_format((float)$order->total, 2) }}
                            </p>
                        </div>
                    </div>

                    {{-- Order items --}}
                    <div style="padding:24px">
                        <h3 style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                   color:var(--fado-warm-grey); margin-bottom:16px">Items Ordered</h3>

                        @foreach($order->items as $item)
                        @php $img = $item->product?->primaryImage; @endphp
                        <div style="display:flex; gap:16px; align-items:center;
                                    padding:16px 0; {{ !$loop->last ? 'border-bottom:1px solid var(--fado-cream)' : '' }}">
                            {{-- Image --}}
                            <div style="width:60px; height:75px; background:var(--fado-cream); border-radius:2px; overflow:hidden; flex-shrink:0">
                                @if($img)
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $item->product?->name }}"
                                         style="width:100%; height:100%; object-fit:cover; object-position:center top">
                                @else
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                        <i class="icon icon-gem" style="color:var(--fado-warm-grey)"></i>
                                    </div>
                                @endif
                            </div>
                            {{-- Details --}}
                            <div style="flex:1; min-width:0">
                                <p style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green);
                                          margin-bottom:4px; line-height:1.3">
                                    {{ $item->product?->name ?? 'Product' }}
                                </p>
                                <p style="font-size:.8125rem; color:#888; margin-bottom:2px">
                                    {{ $item->variant?->metal?->name }}
                                    @if($item->variant?->gemstone) / {{ $item->variant->gemstone->name }} @endif
                                </p>
                                @if($item->size)
                                <p style="font-size:.8125rem; color:#888; margin-bottom:0">
                                    Ring size: US {{ number_format((float)$item->size->us_size, 1) }}
                                </p>
                                @endif
                            </div>
                            {{-- Price --}}
                            <div style="text-align:right; flex-shrink:0">
                                <p style="font-size:.9375rem; font-weight:700; color:var(--fado-deep-green); margin-bottom:2px">
                                    €{{ number_format((float)$item->line_total, 2) }}
                                </p>
                                <p style="font-size:.75rem; color:var(--fado-warm-grey); margin:0">
                                    Qty: {{ $item->quantity }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div style="background:var(--fado-cream); padding:16px 24px">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px">
                            <span style="font-size:.875rem; color:#555">Subtotal</span>
                            <span style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green)">
                                €{{ number_format((float)$order->subtotal, 2) }}
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:12px">
                            <span style="font-size:.875rem; color:#555">Shipping</span>
                            <span style="font-size:.875rem; color:var(--fado-green-mid); font-weight:600">
                                @if($order->subtotal >= $freeShippingThreshold) Free @else To be confirmed @endif
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between;
                                    padding-top:12px; border-top:1px solid var(--fado-warm-grey)">
                            <span style="font-size:1rem; font-weight:700; color:var(--fado-deep-green)">Total</span>
                            <span style="font-size:1.125rem; font-weight:700; color:var(--fado-deep-green)">
                                €{{ number_format((float)$order->total, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ── Shipping address ────────────────────────────────────── --}}
                @if($order->shipping_address)
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:24px; margin-bottom:24px">
                    <h3 style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                               color:var(--fado-warm-grey); margin-bottom:16px">Delivering To</h3>
                    @php $addr = $order->shipping_address; @endphp
                    <p style="font-size:.9375rem; color:var(--fado-deep-green); line-height:1.8; margin:0">
                        <strong>{{ $addr['name'] ?? '' }}</strong><br>
                        {{ $addr['line1'] ?? '' }}<br>
                        @if(!empty($addr['line2'])) {{ $addr['line2'] }}<br> @endif
                        {{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }} @endif<br>
                        {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}<br>
                        @if(!empty($addr['phone'])) {{ $addr['phone'] }} @endif
                    </p>
                </div>
                @endif

                {{-- ── What happens next ──────────────────────────────────── --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:24px; margin-bottom:32px">
                    <h3 style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                               color:var(--fado-warm-grey); margin-bottom:16px">What Happens Next</h3>
                    <div style="display:flex; flex-direction:column; gap:14px">
                        @foreach([
                            ['icon' => 'icon-envelope', 'title' => 'Order confirmation',     'body' => 'You\'ll receive a confirmation email shortly with your order details.'],
                            ['icon' => 'icon-phone',    'title' => 'Payment',                 'body' => 'Our team will contact you within one business day to arrange secure card payment.'],
                            ['icon' => 'icon-package',  'title' => 'Handcrafted & dispatched','body' => 'Your jewellery is carefully packaged in our signature FADÓ gift box and dispatched within 2–3 working days.'],
                            ['icon' => 'icon-truck-simple', 'title' => 'Delivered to your door', 'body' => 'Standard delivery takes 3–7 working days depending on your location.'],
                        ] as $step)
                        <div style="display:flex; gap:14px; align-items:flex-start">
                            <div style="width:36px; height:36px; background:var(--fado-cream); border-radius:50%;
                                        display:flex; align-items:center; justify-content:center; flex-shrink:0">
                                <i class="icon {{ $step['icon'] }}" style="color:var(--fado-deep-green); font-size:.875rem"></i>
                            </div>
                            <div>
                                <p style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:2px">
                                    {{ $step['title'] }}
                                </p>
                                <p style="font-size:.8125rem; color:#888; margin:0; line-height:1.6">
                                    {{ $step['body'] }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTAs --}}
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('shop.jewellery') }}"
                       style="padding:14px 32px; background:var(--fado-deep-green); color:#fff;
                              text-decoration:none; border-radius:2px; font-size:.9375rem; font-weight:600;
                              transition:background .2s"
                       onmouseover="this.style.background='var(--fado-green-mid)'"
                       onmouseout="this.style.background='var(--fado-deep-green)'">
                        Continue Shopping
                    </a>
                    <a href="{{ route('shop.contact') }}"
                       style="padding:14px 32px; border:1px solid var(--fado-warm-grey); color:var(--fado-deep-green);
                              text-decoration:none; border-radius:2px; font-size:.9375rem;
                              transition:border-color .2s"
                       onmouseover="this.style.borderColor='var(--fado-green-mid)'"
                       onmouseout="this.style.borderColor='var(--fado-warm-grey)'">
                        Contact Us
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
