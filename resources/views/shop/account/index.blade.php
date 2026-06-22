@extends('shop.layouts.app')

@section('title', 'My Account — FADÓ Jewellery')
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">My Account</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">My account</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Account -->
        <section class="flat-spacing">
            <input class="fileInputDash" type="file" accept="image/*" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        @include('shop.account._sidebar', ['activeNav' => 'dashboard'])
                    </div>
                    <div class="col-xl-9">
                        <div class="my-account-content">
                            <div class="acount-order_stats">
                                <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="3" data-mobile-sm="2" data-mobile="1"
                                    data-space-lg="48" data-space-md="16" data-space="12" data-pagination="1" data-pagination-sm="2"
                                    data-pagination-md="3" data-pagination-lg="3">
                                    <div class="swiper-wrapper">
                                        <!-- item 1 -->
                                        <div class="swiper-slide">
                                            <div class="order-box">
                                                <div class="order_icon">
                                                    <i class="icon icon-package-thin"></i>
                                                </div>
                                                <div class="order_info">
                                                    <p class="info_label h6">Pending</p>
                                                    <h2 class="info_count type-semibold">{{ $recentOrders->where('status', 'pending')->count() }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- item 2 -->
                                        <div class="swiper-slide">
                                            <div class="order-box">
                                                <div class="order_icon">
                                                    <i class="icon icon-check-fat"></i>
                                                </div>
                                                <div class="order_info">
                                                    <p class="info_label h6">Completed</p>
                                                    <h2 class="info_count type-semibold">{{ $recentOrders->where('status', 'completed')->count() }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- item 3 -->
                                        <div class="swiper-slide">
                                            <div class="order-box">
                                                <div class="order_icon">
                                                    <i class="icon icon-box-arrow-up"></i>
                                                </div>
                                                <div class="order_info">
                                                    <p class="info_label h6">Total orders</p>
                                                    <h2 class="info_count type-semibold">{{ $orderCount }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sw-dot-default tf-sw-pagination"></div>
                                </div>
                            </div>
                            <div class="account-my_order">
                                <h2 class="account-title type-semibold">Recent Orders</h2>
                                <div class="overflow-auto">
                                    <table class="table-my_order order_recent">
                                        <thead>
                                            <tr>
                                                <th>Order</th>
                                                <th>Products</th>
                                                <th>Pricing</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                            <tr class="tb-order-item">
                                                <td class="tb-order_code">#{{ $order->id }}</td>
                                                <td>
                                                    <div class="tb-order_product">
                                                        <div class="infor-prd">
                                                            <h6>
                                                                <a href="{{ route('account.order', $order) }}" class="prd_name link">
                                                                    {{ $order->items->count() }} item(s)
                                                                </a>
                                                            </h6>
                                                            <p class="prd_select text-small">
                                                                {{ $order->created_at->format('M d, Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="tb-order_price">€{{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    <div class="tb-order_status stt-{{ $order->status === 'completed' ? 'complete' : ($order->status === 'cancelled' ? 'cancel' : ($order->status === 'processing' ? 'delivery' : 'pending')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center h6 py-4">No orders yet</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wd-full wg-pagination">
                                    <a href="{{ route('account.orders') }}" class="pagination-item h6 direct"><i class="icon icon-caret-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection
