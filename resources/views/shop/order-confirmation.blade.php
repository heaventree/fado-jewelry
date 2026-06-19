@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 75);
@endphp

@section('title', 'Order Confirmed — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Order Confirmed</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Order {{ $order->order_number }}</h6></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Confirmation notice --}}
                <div class="tf-notice-wrap text-center mb-32">
                    <i class="icon icon-check-circle" style="font-size:2.5rem; display:block; margin-bottom:12px"></i>
                    <h3 class="h3 fw-semibold mb-8">Thank you for your order!</h3>
                    <p class="h6 fw-normal text-main">
                        Your order <strong class="text-black">{{ $order->order_number }}</strong> has been received.
                        We'll be in touch within one business day to arrange payment and confirm dispatch.
                    </p>
                    <p class="h6 text-main mt-8">
                        A confirmation will be sent to <strong class="text-black">{{ $order->customer_email }}</strong>
                    </p>
                </div>

                {{-- Order summary card --}}
                <div class="account-order_detail mb-24">
                    <div class="order-detail_content tf-grid-layout">
                        <div class="detail-content_info">
                            <div class="detail-info_prd">
                                <p class="prd_name h4 text-black">Order {{ $order->order_number }}</p>
                            </div>
                            <div class="detail-info_item">
                                <p class="info-item_label">Date</p>
                                <p class="info-item_value">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="detail-info_item">
                                <p class="info-item_label">Status</p>
                                <p class="info-item_value">
                                    @php
                                        $sttMap = ['pending'=>'stt-pending','processing'=>'stt-pending','shipped'=>'stt-delivery','delivered'=>'stt-complete','cancelled'=>'stt-cancel'];
                                        $stt = $sttMap[$order->status] ?? 'stt-pending';
                                    @endphp
                                    <span class="tb-order_status {{ $stt }}">{{ $order->status_label }}</span>
                                </p>
                            </div>
                            <div class="detail-info_item">
                                <p class="info-item_label">Total</p>
                                <p class="info-item_value">€{{ number_format((float)$order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Items ordered --}}
                <div class="box-your-order mb-24">
                    <h4 class="fw-semibold mb-16">Items Ordered</h4>
                    <div class="list-order-product">
                        @foreach($order->items as $item)
                        @php $img = $item->product?->primaryImage; @endphp
                        <div class="order-item">
                            <div class="img-prd">
                                @if($img)
                                    <img class="lazyload" data-src="{{ Storage::url($img->path) }}"
                                         src="{{ Storage::url($img->path) }}" alt="{{ $item->product?->name }}">
                                @else
                                    <img src="/images/demo/product-placeholder.jpg" alt="{{ $item->product?->name }}">
                                @endif
                            </div>
                            <div class="infor-prd">
                                <p class="prd_name">{{ $item->product?->name ?? 'Product' }}</p>
                                <p class="prd_select text-small">
                                    {{ $item->variant?->metal?->name }}
                                    @if($item->variant?->gemstone) / {{ $item->variant->gemstone->name }} @endif
                                    @if($item->size) · US {{ number_format((float)$item->size->us_size, 1) }} @endif
                                </p>
                                <p class="text-small text-main">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="price-prd h6">€{{ number_format((float)$item->line_total, 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="list-total mt-16">
                        <div class="total-item h6">
                            <span>Subtotal</span>
                            <span class="fw-semibold text-black">€{{ number_format((float)$order->subtotal, 2) }}</span>
                        </div>
                        <div class="total-item h6">
                            <span>Shipping</span>
                            <span class="fw-semibold text-main">
                                @if($order->subtotal >= $freeShippingThreshold) Free @else To be confirmed @endif
                            </span>
                        </div>
                        <div class="last-total h5 fw-medium text-black">
                            <span>Total</span>
                            <span>€{{ number_format((float)$order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping address --}}
                @if($order->shipping_address)
                @php $addr = $order->shipping_address; @endphp
                <div class="order-receiver mb-24">
                    <h6 class="fw-semibold mb-12">Delivering To</h6>
                    @if(!empty($addr['name']))
                    <div class="recerver_text h6">
                        <span class="text">Name:</span>
                        <span class="text_info">{{ $addr['name'] }}</span>
                    </div>
                    @endif
                    <div class="recerver_text h6">
                        <span class="text">Address:</span>
                        <span class="text_info">
                            {{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif,
                            {{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }}@endif,
                            {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}
                        </span>
                    </div>
                </div>
                @endif

                {{-- What happens next --}}
                <div class="order-timeline mb-32">
                    <h6 class="fw-semibold mb-16">What Happens Next</h6>
                    @foreach([
                        ['icon' => 'icon-envelope',      'title' => 'Order confirmation',       'body' => 'You\'ll receive a confirmation email shortly with your order details.'],
                        ['icon' => 'icon-phone',          'title' => 'Payment',                   'body' => 'Our team will contact you within one business day to arrange secure card payment.'],
                        ['icon' => 'icon-package',        'title' => 'Handcrafted & dispatched', 'body' => 'Your jewellery is carefully packaged in our signature FADÓ gift box and dispatched within 2–3 working days.'],
                        ['icon' => 'icon-truck-simple',   'title' => 'Delivered to your door',   'body' => 'Standard delivery takes 3–7 working days depending on your location.'],
                    ] as $step)
                    <div class="timeline-step completed">
                        <div class="timeline_icon">
                            <span class="icon"><i class="{{ $step['icon'] }}"></i></span>
                        </div>
                        <div class="timeline_content">
                            <h5 class="step-title fw-semibold">{{ $step['title'] }}</h5>
                            <p class="step-detail h6 fw-normal">{{ $step['body'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- CTAs --}}
                <div class="d-flex gap-12 justify-content-center flex-wrap">
                    <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Continue Shopping</a>
                    <a href="{{ route('shop.contact') }}" class="tf-btn style-line">Contact Us</a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
