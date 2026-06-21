{{--
    Source: resources/theme-reference/wishlist.html lines 475-2350
    (`<section class="s-page-title">` through `<!-- /Wishlist -->`).

    Deviations from the reference (documented, anti-fabrication — consistent with the same
    decisions already made for the shop grid in listing-results.blade.php):
    - "Compare" action icon and the size-selector `.variant-box` overlay (lines 513-518,
      530-536) omitted — no compare feature exists, and there's no real "pick a size from
      the grid" capability (sizes are chosen on the PDP).
    - Colour swatch list (`.product-color_list`, lines 544-561) omitted — no real per-product
      colour/hex data exists; this was demo swatch imagery, not real product variants.
    - "New arrival" badge replaced with the real is_bestseller/on-sale badge logic already
      used on the shop grid, instead of a hardcoded label.
    - The reference has no empty-wishlist state (its demo assumes 12 populated items) — added
      one reusing the same `box-text_empty` classes already established in cart.blade.php.
    - The `.remove` trash icon (lines 503-505) is real here, wired to the existing
      shop.wishlist.remove route, not decorative.
--}}
@extends('shop.layouts.app')

@section('title', 'Your Wishlist — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Your Wishlist</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li>
                    <h6 class="current-page fw-normal">Wishlist</h6>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- /Page Title --}}

<div class="flat-spacing">
    <div class="container">
        @if($items->isEmpty())
        <div class="box-text_empty type-shop_cart text-center">
            <div class="shop-empty_top">
                <span class="icon">
                    <i class="icon-heart"></i>
                </span>
                <h3 class="text-emp fw-normal">Your wishlist is empty</h3>
                <p class="h6 text-main">Save pieces you love here so you can find them again later.</p>
            </div>
            <div class="shop-empty_bot">
                <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Shopping</a>
                <a href="{{ route('shop.home') }}" class="tf-btn style-line">Back to home</a>
            </div>
        </div>
        @else
        <div class="tf-grid-layout tf-col-2 md-col-3 xl-col-4 wrapper-wishlist">
            @foreach($items as $item)
                @php
                    $product = $item['product'];
                    $variant = $item['variant'];
                    $img  = $product->primaryImage?->path;
                    $img2 = $product->images->skip(1)->first()?->path;
                    $onSale = $variant?->isOnSale() ?? $product->variants->first(fn ($v) => $v->isOnSale());
                    $from = $variant?->price_eur ?? $product->variants->min('price_eur');
                @endphp
                <div class="card-product grid">
                    <div class="card-product_wrapper">
                        <a href="{{ route('shop.product', $product) }}" class="product-img">
                            @if($img)
                                <img class="lazyload img-product" src="{{ asset($img) }}" data-src="{{ asset($img) }}" alt="{{ $product->name }}">
                                @if($img2)
                                <img class="lazyload img-hover" src="{{ asset($img2) }}" data-src="{{ asset($img2) }}" alt="{{ $product->name }}">
                                @endif
                            @else
                                <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                            @endif
                        </a>
                        <span class="remove box-icon"
                              onclick="fetch('{{ route('shop.wishlist.remove') }}', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify({product_id: {{ $product->id }}})}).then(() => location.reload())">
                            <i class="icon icon-trash"></i>
                        </span>
                        <ul class="product-action_list">
                            <li>
                                <a href="{{ route('shop.product', $product) }}" class="hover-tooltip box-icon">
                                    <span class="icon icon-shopping-cart-simple"></span>
                                    <span class="tooltip">Add to cart</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('shop.product', $product) }}" class="hover-tooltip box-icon">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick view</span>
                                </a>
                            </li>
                        </ul>
                        @if($product->is_bestseller)
                        <ul class="product-badge_list">
                            <li class="product-badge_item h6 hot">Bestseller</li>
                        </ul>
                        @elseif($onSale)
                        <ul class="product-badge_list">
                            <li class="product-badge_item flash-sale">Sale</li>
                        </ul>
                        @endif
                    </div>
                    <div class="card-product_info">
                        <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
                        <div class="price-wrap">
                            @if($onSale)
                                <span class="price-old h6 fw-normal">€{{ number_format((float) ($variant?->price_eur ?? $onSale->price_eur), 2) }}</span>
                                <span class="price-new h6">€{{ number_format((float) ($variant?->sale_price_eur ?? $onSale->sale_price_eur), 2) }}</span>
                            @elseif($from)
                                <span class="price-new h6">From €{{ number_format((float) $from, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
{{-- /Wishlist --}}

@endsection
