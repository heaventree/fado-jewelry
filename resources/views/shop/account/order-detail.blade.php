@extends('shop.layouts.app')

@section('title', 'Order #' . $order->id . ' — FADÓ Jewellery')
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
                        <li><a href="{{ route('shop.account.orders') }}" class="h6 link">Orders</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">#{{ $order->id }}</h6>
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
                        <div class="my-account-content flat-animate-tab">
                            <div class="account-order_detail">
                                @php $firstItem = $order->items->first(); @endphp
                                @if($firstItem && $firstItem->product && $firstItem->product->primaryImage)
                                <div class="order-detail_image">
                                    <img class="lazyload" src="{{ Storage::url($firstItem->product->primaryImage->path) }}" data-src="{{ Storage::url($firstItem->product->primaryImage->path) }}" alt="">
                                </div>
                                @endif
                                <div class="order-detail_content tf-grid-layout">
                                    <div class="detail-content_info">
                                        <div class="detail-info_status bg-primary h6">
                                            {{ ucfirst($order->status) }}
                                        </div>
                                        <div class="detail-info_prd">
                                            <p class="prd_name h4 text-black">Order #{{ $order->id }}</p>
                                            <div class="price-wrap">
                                                <span class="price-new h6 text-main fw-semibold">€{{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="detail-info_item">
                                            <p class="info-item_label">Items</p>
                                            <p class="info-item_value">{{ $order->items->count() }} item(s)</p>
                                        </div>
                                        <div class="detail-info_item">
                                            <p class="info-item_label">Order date</p>
                                            <p class="info-item_value">{{ $order->created_at->format('F j, Y - H:i') }}</p>
                                        </div>
                                        @if($order->shipping_address)
                                        @php $addr = $order->shipping_address; @endphp
                                        <div class="detail-info_item">
                                            <p class="info-item_label">Ship to</p>
                                            <p class="info-item_value">{{ $addr['name'] ?? '' }}</p>
                                        </div>
                                        <div class="detail-info_item">
                                            <p class="info-item_label">Address</p>
                                            <p class="info-item_value">{{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif, {{ $addr['city'] ?? '' }} {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="br-line d-flex"></span>
                                    <div>
                                        <a href="{{ route('shop.account.orders') }}" class="tf-btn style-line">
                                            Back to orders
                                            <i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="account-order_tab">
                                <ul class="tab-order_detail" role="tablist">
                                    <li class="nav-tab-item" role="presentation">
                                        <a href="#item-detail" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab active">
                                            <span class="h4">
                                                Item details
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-tab-item" role="presentation">
                                        <a href="#receiver" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab">
                                            <span class="h4">
                                                Order info
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content overflow-hidden">
                                    <div class="tab-pane active show" id="item-detail" role="tabpanel">
                                        @foreach($order->items as $item)
                                        <div class="order-item_detail">
                                            <div class="prd-info">
                                                @if($item->product && $item->product->primaryImage)
                                                <div class="info_image">
                                                    <img class="lazyload" src="{{ Storage::url($item->product->primaryImage->path) }}"
                                                        data-src="{{ Storage::url($item->product->primaryImage->path) }}" alt="Product">
                                                </div>
                                                @endif
                                                <div class="info_detail">
                                                    <a href="#" class="link info-name h4">{{ $item->product_name }}</a>
                                                    <p class="info-price">Price: <span class="fw-semibold h6 text-black">€{{ number_format($item->price, 2) }}</span></p>
                                                    <p class="info-variant">Qty: <span class="fw-semibold h6 text-black">{{ $item->quantity }}</span></p>
                                                    @if($item->variant && $item->variant->metal)
                                                    <p class="info-variant">Metal: <span class="fw-semibold h6 text-black">{{ $item->variant->metal->name }}</span></p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="prd-price">
                                                <div class="price_total">
                                                    <span>Subtotal:</span>
                                                    <span class="fw-semibold h6 text-black">€{{ number_format($item->price * $item->quantity, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="prd-order_total">
                                            <span>Order total</span>
                                            <span class="fw-semibold h6 text-black">€{{ number_format($order->total, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="receiver" role="tabpanel">
                                        <div class="order-receiver">
                                            <div class="recerver_text h6">
                                                <span class="text">Order Number:</span>
                                                <span class="text_info">#{{ $order->id }}</span>
                                            </div>
                                            <div class="recerver_text h6">
                                                <span class="text">Date:</span>
                                                <span class="text_info">{{ $order->created_at->format('F j, Y - H:i') }}</span>
                                            </div>
                                            <div class="recerver_text h6">
                                                <span class="text">Total:</span>
                                                <span class="text_info">€{{ number_format($order->total, 2) }}</span>
                                            </div>
                                            <div class="recerver_text h6">
                                                <span class="text">Status:</span>
                                                <span class="text_info">{{ ucfirst($order->status) }}</span>
                                            </div>
                                            @if($order->payment_method)
                                            <div class="recerver_text h6">
                                                <span class="text">Payment Method:</span>
                                                <span class="text_info">{{ $order->payment_method }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection
