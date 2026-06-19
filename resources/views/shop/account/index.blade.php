@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Account — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">My Account</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">My account</h6></li>
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

                    {{-- Stats --}}
                    <div class="acount-order_stats">
                        <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="3" data-mobile-sm="2" data-mobile="1"
                            data-space-lg="48" data-space-md="16" data-space="12" data-pagination="1" data-pagination-sm="2"
                            data-pagination-md="3" data-pagination-lg="3">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="order-box">
                                        <div class="order_icon">
                                            <i class="icon icon-package-thin"></i>
                                        </div>
                                        <div class="order_info">
                                            <p class="info_label h6">Total Orders</p>
                                            <h2 class="info_count type-semibold">{{ $orderCount }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="order-box">
                                        <div class="order_icon">
                                            <i class="icon icon-heart"></i>
                                        </div>
                                        <div class="order_info">
                                            <p class="info_label h6">Wishlist Items</p>
                                            <h2 class="info_count type-semibold">{{ session('wishlist_count', 0) }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="order-box">
                                        <div class="order_icon">
                                            <i class="icon icon-user-circle"></i>
                                        </div>
                                        <div class="order_info">
                                            <p class="info_label h6">Member Since</p>
                                            <h2 class="info_count type-semibold" style="font-size:1.25rem">{{ $user->created_at->format('M Y') }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sw-dot-default tf-sw-pagination"></div>
                        </div>
                    </div>

                    {{-- Recent orders --}}
                    <div class="account-my_order">
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <h2 class="account-title type-semibold">Recent Orders</h2>
                            <a href="{{ route('shop.account.orders') }}" class="h6 link fw-semibold">View all →</a>
                        </div>

                        @if($recentOrders->isEmpty())
                        <div class="text-center py-5">
                            <i class="icon icon-package" style="font-size:2.5rem; opacity:.4; display:block; margin-bottom:16px"></i>
                            <p class="h6 text-main mb-20">You haven't placed any orders yet.</p>
                            <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Browse Jewellery</a>
                        </div>
                        @else
                        <div class="overflow-auto">
                            <table class="table-my_order order_recent">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    @php
                                        $sttMap = ['pending'=>'stt-pending','processing'=>'stt-pending','shipped'=>'stt-delivery','delivered'=>'stt-complete','cancelled'=>'stt-cancel','refunded'=>'stt-cancel'];
                                        $stt = $sttMap[$order->status] ?? 'stt-pending';
                                    @endphp
                                    <tr class="tb-order-item">
                                        <td class="tb-order_code">
                                            <a href="{{ route('shop.account.order', $order) }}" class="link fw-semibold">{{ $order->order_number }}</a>
                                        </td>
                                        <td class="h6">{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="tb-order_status {{ $stt }}">{{ $order->status_label }}</div>
                                        </td>
                                        <td class="tb-order_price">{{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    {{-- Quick links --}}
                    <div class="row g-3 mt-24">
                        <div class="col-sm-6">
                            <a href="{{ route('shop.wishlist') }}" class="tf-btn style-line w-100 justify-content-start gap-12">
                                <i class="icon icon-heart"></i>
                                <span class="h6">My Wishlist</span>
                                <i class="icon icon-arrow-right ms-auto"></i>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('shop.account.profile') }}" class="tf-btn style-line w-100 justify-content-start gap-12">
                                <i class="icon icon-setting"></i>
                                <span class="h6">Edit Profile</span>
                                <i class="icon icon-arrow-right ms-auto"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
