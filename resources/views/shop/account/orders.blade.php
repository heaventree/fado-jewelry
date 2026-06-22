@extends('shop.layouts.app')

@section('title', 'My Orders — FADÓ Jewellery')
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
                            <h6 class="current-page fw-normal">My orders</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Account -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        @include('shop.account._sidebar', ['activeNav' => 'orders'])
                    </div>
                    <div class="col-xl-9">
                        <div class="my-account-content">
                            <h2 class="account-title type-semibold">My Orders</h2>
                            <div class="overflow-auto">
                                <table class="table-my_order">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Products</th>
                                            <th>Pricing</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
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
                                            <td class="tb-order_action">
                                                <a href="{{ route('account.order', $order) }}" class="link fw-semibold">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center h6 py-4">No orders yet</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($orders->hasPages())
                            <div class="wd-full wg-pagination">
                                @if($orders->onFirstPage())
                                    <span class="pagination-item h6 direct"><i class="icon icon-caret-left"></i></span>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}" class="pagination-item h6 direct"><i class="icon icon-caret-left"></i></a>
                                @endif
                                @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
                                    @if($page === $orders->currentPage())
                                        <span class="pagination-item h6 active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="pagination-item h6">{{ $page }}</a>
                                    @endif
                                @endforeach
                                @if($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}" class="pagination-item h6 direct"><i class="icon icon-caret-right"></i></a>
                                @else
                                    <span class="pagination-item h6 direct"><i class="icon icon-caret-right"></i></span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection
