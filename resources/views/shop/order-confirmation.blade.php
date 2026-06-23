@extends('shop.layouts.app')

@section('title', 'Order Confirmed — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Order Confirmed</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Order Confirmed</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Confirmation -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="tf-notice-wrap text-center mb-4">
                            <div class="icon mb-3">
                                <i class="icon icon-check-circle fs-48"></i>
                            </div>
                            <h2 class="type-semibold mb-2">Thank you for your order!</h2>
                            <p class="h6 text-main">Your order {{ $order->order_number }} has been placed successfully.</p>
                        </div>
                        <div class="account-order_detail mb-4">
                            <div class="order-detail_content tf-grid-layout">
                                <div class="detail-content_info">
                                    <div class="detail-info_prd">
                                        <p class="prd_name h4 text-black">Order {{ $order->order_number }}</p>
                                    </div>
                                    <div class="detail-info_item">
                                        <p class="info-item_label">Date</p>
                                        <p class="info-item_value">{{ $order->created_at->format('F j, Y - H:i') }}</p>
                                    </div>
                                    <div class="detail-info_item">
                                        <p class="info-item_label">Status</p>
                                        <p class="info-item_value">{{ ucfirst($order->status) }}</p>
                                    </div>
                                    @if($order->payment_method)
                                    @php
                                        $paymentLabels = ['cod' => 'Cash on Delivery', 'stripe' => 'Credit Card', 'bank_transfer' => 'Bank Transfer', 'paypal' => 'PayPal'];
                                    @endphp
                                    <div class="detail-info_item">
                                        <p class="info-item_label">Payment</p>
                                        <p class="info-item_value">{{ $paymentLabels[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                    </div>
                                    @endif
                                    @if($order->shipping_address)
                                    @php $addr = $order->shipping_address; @endphp
                                    <div class="detail-info_item">
                                        <p class="info-item_label">Ship to</p>
                                        <p class="info-item_value">{{ $addr['name'] ?? '' }}, {{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif, {{ $addr['city'] ?? '' }} {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="box-your-order mb-4">
                            <h2 class="title type-semibold">Items Ordered</h2>
                            <ul class="list-order-product">
                                @foreach($order->items as $item)
                                <li class="order-item">
                                    @if($item->product && $item->product->primaryImage)
                                    <a href="{{ route('shop.product', $item->product) }}" class="img-prd">
                                        <img class="lazyload" src="{{ asset($item->product->primaryImage->path) }}" data-src="{{ asset($item->product->primaryImage->path) }}" alt="{{ $item->product->name ?? 'Product' }}">
                                    </a>
                                    @endif
                                    <div class="infor-prd">
                                        <h6 class="prd_name">
                                            @if($item->product)
                                            <a href="{{ route('shop.product', $item->product) }}" class="link">{{ $item->product->name }}</a>
                                            @else
                                            Product
                                            @endif
                                        </h6>
                                        <div class="prd_select text-small">
                                            {{ $item->quantity }} x @if($item->variant && $item->variant->metal) {{ $item->variant->metal->name }} @endif
                                            @if($item->size) | Size US {{ number_format((float) $item->size->us_size, 1) }} @endif
                                        </div>
                                    </div>
                                    <p class="price-prd h6">&euro;{{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                </li>
                                @endforeach
                            </ul>
                            <ul class="list-total">
                                <li class="total-item h6">
                                    <span class="fw-bold text-black">Subtotal</span>
                                    <span>&euro;{{ number_format($order->subtotal, 2) }}</span>
                                </li>
                                <li class="total-item h6">
                                    <span class="fw-bold text-black">Shipping</span>
                                    <span>{{ $order->shipping_total > 0 ? '&euro;' . number_format($order->shipping_total, 2) : 'Free' }}</span>
                                </li>
                            </ul>
                            <div class="last-total h5 fw-medium text-black">
                                <span>Total</span>
                                <span>&euro;{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                        <div class="order-timeline mb-5">
                            <div class="timeline-step completed">
                                <div class="timeline_icon"><span class="icon"><i class="icon-check-1"></i></span></div>
                                <div class="timeline_content">
                                    <h5 class="step-title fw-semibold">Order placed</h5>
                                    <h6 class="step-date fw-normal mb-0">{{ $order->created_at->format('F j, Y - H:i') }}</h6>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline_icon"><span class="icon"><i class="icon-truck"></i></span></div>
                                <div class="timeline_content">
                                    <h5 class="step-title fw-semibold">Processing</h5>
                                    <h6 class="step-date fw-normal mb-0">We will prepare your order shortly</h6>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline_icon"><span class="icon"><i class="icon-check-1"></i></span></div>
                                <div class="timeline_content">
                                    <h5 class="step-title fw-semibold">Shipped</h5>
                                    <h6 class="step-date fw-normal mb-0">You will receive a tracking notification</h6>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            @auth
                            <a href="{{ route('shop.account.order', $order) }}" class="tf-btn animate-btn">
                                View order details <i class="icon icon-arrow-right"></i>
                            </a>
                            @endauth
                            <a href="{{ route('shop.jewellery') }}" class="tf-btn style-line">
                                Continue shopping <i class="icon icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Confirmation -->
@endsection
