@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Orders — ' . Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<div style="background:var(--fado-deep-green); padding:40px 0 36px">
    <div class="container">
        <nav style="margin-bottom:8px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.account.index') }}" style="color:rgba(255,255,255,.6); text-decoration:none">My Account</a></li>
                <li style="color:rgba(255,255,255,.4)">/</li>
                <li style="color:rgba(255,255,255,.9); font-weight:600">Orders</li>
            </ol>
        </nav>
        <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:#fff; margin:0">Order History</h1>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:48px 0 80px; min-height:60vh">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('shop.account.partials.nav')
            </div>
            <div class="col-lg-9">

                @if($orders->isEmpty())
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:64px 24px; text-align:center">
                    <i class="icon icon-package" style="font-size:3rem; color:var(--fado-warm-grey); display:block; margin-bottom:16px; opacity:.5"></i>
                    <h2 style="font-family:Georgia,serif; font-size:1.25rem; font-weight:400; color:var(--fado-deep-green); margin-bottom:10px">
                        No orders yet
                    </h2>
                    <p style="color:#888; font-size:.875rem; margin-bottom:24px">When you place an order, it will appear here.</p>
                    <a href="{{ route('shop.jewellery') }}"
                       style="display:inline-block; padding:12px 28px; background:var(--fado-deep-green);
                              color:#fff; text-decoration:none; border-radius:2px; font-size:.875rem; font-weight:600">
                        Browse Jewellery
                    </a>
                </div>
                @else
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; overflow:hidden; margin-bottom:20px">
                    <table style="width:100%; border-collapse:collapse; font-size:.875rem">
                        <thead>
                            <tr style="background:var(--fado-cream)">
                                <th style="padding:12px 24px; text-align:left; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Order</th>
                                <th style="padding:12px 16px; text-align:left; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey); white-space:nowrap">Date</th>
                                <th style="padding:12px 16px; text-align:left; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Items</th>
                                <th style="padding:12px 16px; text-align:left; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Status</th>
                                <th style="padding:12px 24px; text-align:right; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php
                                $colours = ['pending'=>'#f59e0b','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#10b981','cancelled'=>'#ef4444','refunded'=>'#6b7280'];
                                $colour  = $colours[$order->status] ?? '#6b7280';
                            @endphp
                            <tr style="border-top:1px solid var(--fado-cream); transition:background .15s"
                                onmouseover="this.style.background='var(--fado-near-white)'"
                                onmouseout="this.style.background='transparent'">
                                <td style="padding:14px 24px">
                                    <a href="{{ route('shop.account.order', $order) }}"
                                       style="font-weight:600; color:var(--fado-deep-green); text-decoration:none; font-family:Georgia,serif">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="padding:14px 16px; color:#666; white-space:nowrap">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td style="padding:14px 16px; color:#666">
                                    {{ $order->items_count ?? $order->items->count() }}
                                </td>
                                <td style="padding:14px 16px">
                                    <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:.7rem;
                                                 font-weight:700; background:{{ $colour }}18; color:{{ $colour }}; letter-spacing:.03em">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td style="padding:14px 24px; text-align:right; font-weight:600; color:var(--fado-deep-green); white-space:nowrap">
                                    {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($orders->hasPages())
                <div class="d-flex justify-content-center" style="margin-top:8px">
                    {{ $orders->links() }}
                </div>
                @endif
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
