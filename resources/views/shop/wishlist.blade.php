@extends('shop.layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Wishlist — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

{{-- Page Title — Ochaka s-page-title --}}
<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Your Wishlist
                @if($items->isNotEmpty())
                    <span class="h6 fw-normal text-secondary ms-2">({{ $items->count() }})</span>
                @endif
            </h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Wishlist</h6></li>
            </ul>
        </div>
    </div>
</section>

{{-- Flash messages --}}
@if(session('wishlist_removed') || session('wishlist_added'))
<div class="container">
    @if(session('wishlist_removed'))
    <div class="alert-message h6 type-success mt_16">{{ session('wishlist_removed') }}</div>
    @endif
    @if(session('wishlist_added'))
    <div class="alert-message h6 type-success mt_16">{{ session('wishlist_added') }}</div>
    @endif
</div>
@endif

{{-- Wishlist grid — Ochaka tf-grid-layout wrapper-wishlist --}}
<div class="flat-spacing">
    <div class="container">

        @if($items->isEmpty())

        <div class="text-center py-5">
            <i class="icon icon-heart" style="font-size:3.5rem; opacity:.3; display:block; margin-bottom:20px"></i>
            <h2 class="h4 fw-normal mb-12">Your wishlist is empty</h2>
            <p class="h6 fw-normal text-secondary mb_32">Save pieces you love and come back to them later.</p>
            @guest
            <p class="h6 fw-normal text-secondary mb_20">
                <a href="{{ route('login') }}" class="link">Sign in</a> to save your wishlist permanently.
            </p>
            @endguest
            <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">
                Browse Jewellery <i class="icon icon-arrow-right"></i>
            </a>
        </div>

        @else

        <div class="tf-grid-layout tf-col-2 md-col-3 xl-col-4 wrapper-wishlist">
            @foreach($items as $item)
            @php
                $product = $item['product'];
                $variant = $item['variant'] ?? $product->variants->first();
                $img     = $product->primaryImage;
                $img2    = $product->images->skip(1)->first();
                $from    = $product->variants->min('price_eur');
            @endphp

            <div class="card-product grid style-2">
                <div class="card-product_wrapper">
                    <a href="{{ route('shop.product', $product) }}" class="product-img">
                        @if($img)
                            <img class="lazyload img-product" src="{{ Storage::url($img->path) }}" data-src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}">
                            @if($img2)
                            <img class="lazyload img-hover" src="{{ Storage::url($img2->path) }}" data-src="{{ Storage::url($img2->path) }}" alt="{{ $product->name }}">
                            @endif
                        @else
                            <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                        @endif
                    </a>

                    {{-- Remove from wishlist --}}
                    <form method="POST" action="{{ route('shop.wishlist.remove') }}" style="position:absolute;top:10px;right:10px;z-index:1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="remove box-icon" title="Remove from wishlist">
                            <i class="icon icon-trash"></i>
                        </button>
                    </form>

                    <ul class="product-action_list">
                        <li>
                            <a href="{{ route('shop.product', $product) }}" class="hover-tooltip box-icon">
                                <span class="icon icon-view"></span>
                                <span class="tooltip">View product</span>
                            </a>
                        </li>
                        @if($variant && $variant->stock > 0)
                        <li>
                            <form method="POST" action="{{ route('shop.cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="hover-tooltip box-icon">
                                    <span class="icon icon-shopping-cart-simple"></span>
                                    <span class="tooltip">Add to cart</span>
                                </button>
                            </form>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="card-product_info">
                    <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
                    @if($variant?->metal)
                    <p class="h6 fw-normal text-secondary">{{ $variant->metal->name }}{{ $variant->gemstone ? ' / ' . $variant->gemstone->name : '' }}</p>
                    @endif
                    <div class="price-wrap mb-0">
                        @if($from)
                            <span class="price-new h6">From {{ app(\App\Services\CurrencyService::class)->format((float)$from) }}</span>
                        @else
                            <span class="price-new h6 fw-normal text-secondary" style="font-style:italic">Price on enquiry</span>
                        @endif
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        <div class="mt_40">
            @guest
            <p class="h6 fw-normal text-secondary">
                <a href="{{ route('login') }}" class="link">Sign in</a> to save your wishlist across devices.
            </p>
            @endguest
            <a href="{{ route('shop.jewellery') }}" class="tf-btn-line sm-letter-1">← Continue browsing</a>
        </div>

        @endif

    </div>
</div>

@endsection
