@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 75);
@endphp

@section('title', 'Order ' . $order->order_number . ' — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">{{ $order->order_number }}</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('shop.account.index') }}" class="h6 link">My Account</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('shop.account.orders') }}" class="h6 link">Orders</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">{{ $order->order_number }}</h6></li>
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
                <div class="my-account-content flat-animate-tab">

                    {{-- Order summary header --}}
                    @php
                        $sttMap = ['pending'=>'stt-pending','processing'=>'stt-pending','shipped'=>'stt-delivery','delivered'=>'stt-complete','cancelled'=>'stt-cancel','refunded'=>'stt-cancel'];
                        $stt = $sttMap[$order->status] ?? 'stt-pending';
                        $firstItem = $order->items->first();
                        $firstImg  = $firstItem?->product?->primaryImage;
                    @endphp
                    <div class="account-order_detail">
                        <div class="order-detail_image">
                            @if($firstImg)
                                <img class="lazyload" src="{{ Storage::url($firstImg->path) }}"
                                     data-src="{{ Storage::url($firstImg->path) }}" alt="{{ $firstItem?->product?->name }}">
                            @else
                                <div class="img-placeholder d-flex align-items-center justify-content-center h-100">
                                    <i class="icon icon-gem" style="font-size:2rem; opacity:.4"></i>
                                </div>
                            @endif
                        </div>
                        <div class="order-detail_content tf-grid-layout">
                            <div class="detail-content_info">
                                <div class="detail-info_status tb-order_status {{ $stt }}">
                                    {{ $order->status_label }}
                                </div>
                                <div class="detail-info_prd">
                                    <p class="prd_name h4 text-black">{{ $firstItem?->product?->name ?? 'Order ' . $order->order_number }}</p>
                                    <div class="price-wrap">
                                        <span class="price-new h6 text-main fw-semibold">
                                            {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-info_item">
                                    <p class="info-item_label">Order date</p>
                                    <p class="info-item_value">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="detail-info_item">
                                    <p class="info-item_label">Items</p>
                                    <p class="info-item_value">{{ $order->items->count() }} item(s)</p>
                                </div>
                                @if($order->shipping_address)
                                @php $addr = $order->shipping_address; @endphp
                                <div class="detail-info_item">
                                    <p class="info-item_label">Address</p>
                                    <p class="info-item_value">{{ $addr['line1'] ?? '' }}, {{ $addr['city'] ?? '' }}, {{ $addr['country'] ?? '' }}</p>
                                </div>
                                @endif
                            </div>
                            <span class="br-line d-flex"></span>
                            <div>
                                <a href="{{ route('shop.jewellery') }}" class="tf-btn style-line">
                                    Continue Shopping
                                    <i class="icon icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Order tabs --}}
                    <div class="account-order_tab">
                        <ul class="tab-order_detail" role="tablist">
                            <li class="nav-tab-item" role="presentation">
                                <a href="#order-items" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab active">
                                    <span class="h4">Items Ordered</span>
                                </a>
                            </li>
                            <li class="nav-tab-item" role="presentation">
                                <a href="#order-receiver" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab">
                                    <span class="h4">Order Info</span>
                                </a>
                            </li>
                            @if($order->shipping_address)
                            <li class="nav-tab-item" role="presentation">
                                <a href="#order-shipping" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab">
                                    <span class="h4">Shipping</span>
                                </a>
                            </li>
                            @endif
                        </ul>

                        <div class="tab-content overflow-hidden">
                            {{-- Items tab --}}
                            <div class="tab-pane active show" id="order-items" role="tabpanel">
                                <div class="order-item_detail">
                                    @foreach($order->items as $item)
                                    @php $img = $item->product?->primaryImage; @endphp
                                    <div class="prd-info{{ !$loop->last ? ' mb-16' : '' }}">
                                        <div class="info_image">
                                            @if($img)
                                                <img class="lazyload" src="{{ Storage::url($img->path) }}"
                                                     data-src="{{ Storage::url($img->path) }}" alt="{{ $item->product?->name }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                    <i class="icon icon-gem" style="opacity:.4"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="info_detail">
                                            @if($item->product)
                                            <a href="{{ route('shop.product', $item->product) }}" class="link info-name h4">{{ $item->product->name }}</a>
                                            @else
                                            <p class="info-name h4">Product removed</p>
                                            @endif
                                            @if($item->variant?->metal)
                                            <p class="h6 text-main">Metal: <span class="fw-semibold text-black">{{ $item->variant->metal->name }}</span></p>
                                            @endif
                                            @if($item->variant?->gemstone)
                                            <p class="h6 text-main">Stone: <span class="fw-semibold text-black">{{ $item->variant->gemstone->name }}</span></p>
                                            @endif
                                            @if($item->size)
                                            <p class="h6 text-main">Ring Size: <span class="fw-semibold text-black">US {{ number_format((float)$item->size->us_size, 1) }}</span></p>
                                            @endif
                                            <p class="info-variant">Qty: <span class="fw-semibold h6 text-black">{{ $item->quantity }}</span></p>
                                        </div>
                                    </div>
                                    @if(!$loop->last)<span class="br-line d-flex my-16"></span>@endif
                                    @endforeach

                                    <div class="prd-price mt-20">
                                        <div class="price_total">
                                            <span>Subtotal:</span>
                                            <span class="fw-semibold h6 text-black">{{ $order->currency_symbol }}{{ number_format((float)$order->subtotal, 2) }}</span>
                                        </div>
                                        <p class="price_dis">
                                            <span>Shipping:</span>
                                            <span class="fw-semibold h6 text-black">
                                                @if($order->subtotal >= $freeShippingThreshold) Free @else To be confirmed @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="prd-order_total">
                                        <span>Order Total</span>
                                        <span class="fw-semibold h6 text-black">{{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Order info tab --}}
                            <div class="tab-pane" id="order-receiver" role="tabpanel">
                                <div class="order-receiver">
                                    <div class="recerver_text h6">
                                        <span class="text">Order Number:</span>
                                        <span class="text_info">{{ $order->order_number }}</span>
                                    </div>
                                    <div class="recerver_text h6">
                                        <span class="text">Date:</span>
                                        <span class="text_info">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="recerver_text h6">
                                        <span class="text">Currency:</span>
                                        <span class="text_info">{{ $order->currency_code }}</span>
                                    </div>
                                    <div class="recerver_text h6">
                                        <span class="text">Total:</span>
                                        <span class="text_info">{{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}</span>
                                    </div>
                                    <div class="recerver_text h6">
                                        <span class="text">Payment Method:</span>
                                        <span class="text_info">Cash on Delivery</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Shipping tab --}}
                            @if($order->shipping_address)
                            @php $addr = $order->shipping_address; @endphp
                            <div class="tab-pane" id="order-shipping" role="tabpanel">
                                <div class="order-receiver">
                                    @if(!empty($addr['name']))
                                    <div class="recerver_text h6">
                                        <span class="text">Name:</span>
                                        <span class="text_info">{{ $addr['name'] }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($addr['line1']))
                                    <div class="recerver_text h6">
                                        <span class="text">Address:</span>
                                        <span class="text_info">{{ $addr['line1'] }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif</span>
                                    </div>
                                    @endif
                                    @if(!empty($addr['city']))
                                    <div class="recerver_text h6">
                                        <span class="text">City:</span>
                                        <span class="text_info">{{ $addr['city'] }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($addr['country']))
                                    <div class="recerver_text h6">
                                        <span class="text">Country:</span>
                                        <span class="text_info">{{ $addr['country'] }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($addr['postcode']))
                                    <div class="recerver_text h6">
                                        <span class="text">Postcode:</span>
                                        <span class="text_info">{{ $addr['postcode'] }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-20">
                                    <p class="h6 text-main">Need help?
                                        <a href="{{ route('shop.contact') }}" class="link fw-semibold">Contact us</a>
                                        and quote your order number.
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-24">
                        <a href="{{ route('shop.account.orders') }}" class="tf-btn-line">← Back to orders</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
