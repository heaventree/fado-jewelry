@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Orders — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Order History</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('shop.account.index') }}" class="h6 link">My Account</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Orders</h6></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 d-none d-xl-block">
                @include('shop.account.partials.nav')
            </div>
            <div class="col-xl-9">
                <div class="my-account-content">
                    <h2 class="account-title type-semibold">My Orders</h2>

                    @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="icon icon-package" style="font-size:3rem; opacity:.4; display:block; margin-bottom:16px"></i>
                        <p class="h5 fw-normal mb-12">No orders yet</p>
                        <p class="h6 text-main mb-20">When you place an order, it will appear here.</p>
                        <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Browse Jewellery</a>
                    </div>
                    @else
                    <div class="overflow-auto">
                        <table class="table-my_order">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                @php
                                    $sttMap = ['pending'=>'stt-pending','processing'=>'stt-pending','shipped'=>'stt-delivery','delivered'=>'stt-complete','cancelled'=>'stt-cancel','refunded'=>'stt-cancel'];
                                    $stt = $sttMap[$order->status] ?? 'stt-pending';
                                @endphp
                                <tr class="tb-order-item">
                                    <td class="tb-order_code">{{ $order->order_number }}</td>
                                    <td class="h6">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="h6">{{ $order->items_count ?? $order->items->count() }}</td>
                                    <td>
                                        <div class="tb-order_status {{ $stt }}">{{ $order->status_label }}</div>
                                    </td>
                                    <td class="tb-order_price">{{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}</td>
                                    <td class="tb-order_action">
                                        <a href="{{ route('shop.account.order', $order) }}" class="link fw-semibold">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                    <div class="wd-full wg-pagination mt-24">
                        {{ $orders->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
