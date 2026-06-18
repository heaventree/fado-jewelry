@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
@endphp

@section('title', 'Order ' . $order->order_number . ' — ' . Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<div style="background:var(--fado-deep-green); padding:40px 0 36px">
    <div class="container">
        <nav style="margin-bottom:8px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.account.index') }}" style="color:rgba(255,255,255,.6); text-decoration:none">My Account</a></li>
                <li style="color:rgba(255,255,255,.4)">/</li>
                <li><a href="{{ route('shop.account.orders') }}" style="color:rgba(255,255,255,.6); text-decoration:none">Orders</a></li>
                <li style="color:rgba(255,255,255,.4)">/</li>
                <li style="color:rgba(255,255,255,.9); font-weight:600">{{ $order->order_number }}</li>
            </ol>
        </nav>
        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap">
            <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:#fff; margin:0">
                {{ $order->order_number }}
            </h1>
            @php
                $colours = ['pending'=>'#f59e0b','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#10b981','cancelled'=>'#ef4444','refunded'=>'#6b7280'];
                $colour  = $colours[$order->status] ?? '#6b7280';
            @endphp
            <span style="display:inline-block; padding:4px 14px; border-radius:20px; font-size:.75rem;
                         font-weight:700; background:{{ $colour }}30; color:{{ $colour }}; letter-spacing:.03em; border:1px solid {{ $colour }}40">
                {{ $order->status_label }}
            </span>
        </div>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:48px 0 80px">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('shop.account.partials.nav')
            </div>
            <div class="col-lg-9">

                {{-- Order meta strip --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:20px 24px;
                            margin-bottom:20px; display:flex; gap:32px; flex-wrap:wrap">
                    @foreach([
                        ['label' => 'Date',     'value' => $order->created_at->format('d M Y')],
                        ['label' => 'Currency', 'value' => $order->currency_code],
                        ['label' => 'Items',    'value' => $order->items->count()],
                        ['label' => 'Total',    'value' => $order->currency_symbol . number_format((float)$order->total, 2)],
                    ] as $meta)
                    <div>
                        <p style="font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                  color:var(--fado-warm-grey); margin-bottom:4px">{{ $meta['label'] }}</p>
                        <p style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green); margin:0">
                            {{ $meta['value'] }}
                        </p>
                    </div>
                    @endforeach
                </div>

                <div class="row g-4">
                    {{-- Items list --}}
                    <div class="col-lg-8">
                        <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; overflow:hidden; margin-bottom:20px">
                            <div style="padding:16px 24px; border-bottom:1px solid var(--fado-cream); background:var(--fado-cream)">
                                <h2 style="font-family:Georgia,serif; font-size:1rem; font-weight:400;
                                           color:var(--fado-deep-green); margin:0">Items Ordered</h2>
                            </div>
                            @foreach($order->items as $item)
                            @php $img = $item->product?->primaryImage; @endphp
                            <div style="display:flex; gap:16px; align-items:center; padding:18px 24px;
                                        {{ !$loop->last ? 'border-bottom:1px solid var(--fado-cream)' : '' }}">
                                <div style="width:64px; height:80px; background:var(--fado-cream); border-radius:2px; overflow:hidden; flex-shrink:0">
                                    @if($img)
                                        <img src="{{ Storage::url($img->path) }}" alt="{{ $item->product?->name }}"
                                             style="width:100%; height:100%; object-fit:cover; object-position:center top">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                            <i class="icon icon-gem" style="color:var(--fado-warm-grey)"></i>
                                        </div>
                                    @endif
                                </div>
                                <div style="flex:1; min-width:0">
                                    @if($item->product)
                                    <a href="{{ route('shop.product', $item->product) }}"
                                       style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green);
                                              text-decoration:none; line-height:1.3; display:block; margin-bottom:4px">
                                        {{ $item->product->name }}
                                    </a>
                                    @else
                                    <p style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:4px">Product removed</p>
                                    @endif
                                    <p style="font-size:.8125rem; color:#888; margin-bottom:2px">
                                        {{ $item->variant?->metal?->name }}
                                        @if($item->variant?->gemstone) / {{ $item->variant->gemstone->name }} @endif
                                    </p>
                                    @if($item->size)
                                    <p style="font-size:.8125rem; color:#888; margin-bottom:0">
                                        Ring size: US {{ number_format((float)$item->size->us_size, 1) }}
                                    </p>
                                    @endif
                                    <p style="font-size:.8125rem; color:var(--fado-warm-grey); margin:4px 0 0">
                                        Qty: {{ $item->quantity }}
                                    </p>
                                </div>
                                <div style="text-align:right; flex-shrink:0">
                                    <p style="font-size:.9375rem; font-weight:700; color:var(--fado-deep-green); margin-bottom:2px">
                                        {{ $order->currency_symbol }}{{ number_format($item->line_total, 2) }}
                                    </p>
                                    @if($item->quantity > 1)
                                    <p style="font-size:.75rem; color:var(--fado-warm-grey); margin:0">
                                        {{ $order->currency_symbol }}{{ number_format((float)$item->unit_price, 2) }} each
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            {{-- Totals footer --}}
                            <div style="background:var(--fado-cream); padding:16px 24px">
                                <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:.875rem">
                                    <span style="color:#555">Subtotal</span>
                                    <span style="font-weight:600; color:var(--fado-deep-green)">
                                        {{ $order->currency_symbol }}{{ number_format((float)$order->subtotal, 2) }}
                                    </span>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:.875rem">
                                    <span style="color:#555">Shipping</span>
                                    <span style="font-weight:600; color:var(--fado-green-mid)">
                                        @if($order->subtotal >= $freeShippingThreshold) Free @else To be confirmed @endif
                                    </span>
                                </div>
                                <div style="display:flex; justify-content:space-between; padding-top:10px;
                                            border-top:1px solid var(--fado-warm-grey); font-size:1rem">
                                    <span style="font-weight:700; color:var(--fado-deep-green)">Total</span>
                                    <span style="font-weight:700; color:var(--fado-deep-green)">
                                        {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping address --}}
                    <div class="col-lg-4">
                        @if($order->shipping_address)
                        <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:20px 24px; margin-bottom:16px">
                            <h3 style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                       color:var(--fado-warm-grey); margin-bottom:14px">Delivering To</h3>
                            @php $addr = $order->shipping_address; @endphp
                            <p style="font-size:.9rem; color:var(--fado-deep-green); line-height:1.85; margin:0">
                                <strong>{{ $addr['name'] ?? '' }}</strong><br>
                                {{ $addr['line1'] ?? '' }}<br>
                                @if(!empty($addr['line2'])) {{ $addr['line2'] }}<br> @endif
                                {{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }} @endif<br>
                                {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}<br>
                                @if(!empty($addr['phone'])) {{ $addr['phone'] }} @endif
                            </p>
                        </div>
                        @endif

                        {{-- Help card --}}
                        <div style="background:var(--fado-cream); border-radius:4px; padding:20px 24px">
                            <h3 style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:10px">
                                Need help with this order?
                            </h3>
                            <p style="font-size:.8125rem; color:#666; line-height:1.6; margin-bottom:14px">
                                Contact our team and quote your order number.
                            </p>
                            <a href="{{ route('shop.contact') }}"
                               style="display:inline-block; padding:9px 20px; background:var(--fado-deep-green);
                                      color:#fff; text-decoration:none; border-radius:2px; font-size:.8125rem; font-weight:600">
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>

                <div style="margin-top:4px">
                    <a href="{{ route('shop.account.orders') }}"
                       style="font-size:.875rem; color:var(--fado-green-mid); text-decoration:none">
                        ← Back to orders
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
