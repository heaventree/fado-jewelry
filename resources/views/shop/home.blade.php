@extends('shop.layouts.app')

@section('title', \App\Models\Setting::get('home_meta_title') ?: \App\Models\Setting::get('store_name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery')
@section('meta_description', \App\Models\Setting::get('home_meta_description') ?: \App\Models\Setting::get('meta_description', ''))
@section('meta_keywords', \App\Models\Setting::get('home_keywords', ''))
@section('meta_robots', \App\Models\Setting::get('home_robots', 'index, follow'))
@section('canonical', \App\Models\Setting::get('home_canonical') ?: url('/'))
@if(\App\Models\Setting::get('home_og_image'))
@section('og_image', asset('storage/' . \App\Models\Setting::get('home_og_image')))
@endif

@section('content')

{{-- Banner Slider — wired to DB via Slider model --}}
@if($sliders->isNotEmpty())
<div class="tf-slideshow type-abs tf-btn-swiper-main">
    <div dir="ltr" class="swiper tf-swiper sw-slide-show slider_effect_fade" data-auto="false" data-loop="true" data-effect="fade" data-delay="3000">
        <div class="swiper-wrapper">
            @foreach($sliders as $slide)
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url($slide->image) }}" data-src="{{ Storage::url($slide->image) }}" alt="{{ $slide->heading }}" class="lazyload ani-zoom">
                    </div>
                    <div class="sld_content text-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="content-sld_wrap">
                                        @if($slide->subheading)
                                        <p class="sub-title_sld-2 font-2 h3 text-white fade-item fade-item-1">{{ $slide->subheading }}</p>
                                        @endif
                                        <h1 class="title_sld text-white text-display fade-item fade-item-2">{{ $slide->heading }}</h1>
                                        @if($slide->button_text && $slide->button_url)
                                        <div class="fade-item fade-item-4">
                                            <a href="{{ $slide->button_url }}" class="tf-btn btn-white animate-btn animate-dark fw-semibold">
                                                {{ $slide->button_text }}
                                                <i class="icon icon-arrow-right"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="sw-dot-default-2 style-white tf-sw-pagination"></div>
    </div>
</div>
@endif
{{-- /Banner Slider --}}

{{-- Category — Ochaka widget-collection carousel exact structure --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="title mb-8">Shop By Categories</h1>
            <p class="s-subtitle h6">{{ \App\Models\Setting::get('homepage_subtitle_1', 'From classic rings to Celtic crosses — discover Irish jewellery for every occasion') }}</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="5" data-tablet="4" data-mobile-sm="3" data-mobile="2"
            data-space-lg="40" data-space-md="32" data-space="12" data-pagination="2" data-pagination-sm="3" data-pagination-md="4"
            data-pagination-lg="5">
            <div class="swiper-wrapper">
                @php $catImgFallbacks = ['cate-24','cate-25','cate-26','cate-27','cate-28']; @endphp
                @forelse($topCategories as $i => $cat)
                <div class="swiper-slide">
                    <a href="{{ route('shop.category', $cat->slug) }}" class="widget-collection type-space-2 hover-img">
                        <div class="collection_image img-style">
                            @if($cat->thumbnail_image ?? $cat->banner_image)
                                {{-- thumbnail_image is the dedicated homepage tile image; banner_image
                                     (the category page hero) is only a fallback for categories that
                                     haven't had a thumbnail uploaded yet. --}}
                                <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ \Illuminate\Support\Facades\Storage::url($cat->thumbnail_image ?? $cat->banner_image) }}" alt="{{ $cat->name }}">
                            @else
                                @php $fb = $catImgFallbacks[$i % count($catImgFallbacks)]; @endphp
                                <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/category/{{ $fb }}.jpg" alt="{{ $cat->name }}">
                            @endif
                        </div>
                        <p class="collection_name h5 link fw-semibold">{{ $cat->name }}</p>
                    </a>
                </div>
                @empty
                <div class="swiper-slide">
                    <p class="h6 text-center">Categories coming soon.</p>
                </div>
                @endforelse
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
</section>
{{-- /Category --}}

{{-- Featured Collections — Ochaka box-image_V02 type-space-3 exact structure --}}
@if($featuredCollections->isNotEmpty())
<div class="themesFlat">
    <div class="container">
        <div class="tf-grid-layout lg-col-2">
            @foreach($featuredCollections->take(2) as $i => $col)
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', $col->slug) }}" class="box-image_image img-style">
                    @if($col->banner_image)
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ \Illuminate\Support\Facades\Storage::url($col->banner_image) }}" alt="{{ $col->name }}" class="lazyload">
                    @else
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/section/box-image-{{ $i == 0 ? '8' : '9' }}.jpg" alt="{{ $col->name }}" class="lazyload">
                    @endif
                </a>
                <div class="box-image_content wow fadeInUp">
                    <span class="sub-text h4 text-white mb-8">Featured Collection</span>
                    <a href="{{ route('shop.collection', $col->slug) }}" class="title link text-display-2 fw-medium text-white">
                        {{ $col->name }}
                    </a>
                    <a href="{{ route('shop.collection', $col->slug) }}" class="tf-btn btn-white animate-btn animate-dark">
                        Shop now
                        <i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
{{-- /Featured Collections --}}
{{-- Best Seller — Ochaka tab-product_list / tab-pane exact structure --}}
<section class="flat-spacing flat-animate-tab">
    <div class="container">
        <div class="sect-title wow fadeInUp">
            <h1 class="title text-center mb-24">Best Seller</h1>
            <ul class="tab-product_list" role="tablist">
                <li class="nav-tab-item" role="presentation">
                    <a href="#new-arr" data-bs-toggle="tab" class="tf-btn-line sm-letter-1 tf-btn-tab fw-normal active">
                        NEW ARRIVALS
                    </a>
                </li>
                <li class="nav-tab-item" role="presentation">
                    <a href="#best-seller" data-bs-toggle="tab" class="tf-btn-line sm-letter-1 tf-btn-tab fw-normal">
                        Best seller
                    </a>
                </li>
                <li class="nav-tab-item" role="presentation">
                    <a href="#on-sale" data-bs-toggle="tab" class="tf-btn-line sm-letter-1 tf-btn-tab fw-normal">
                        On sale
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">

            {{-- New Arrivals tab --}}
            <div class="tab-pane active show" id="new-arr" role="tabpanel">
                <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                    data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
                    data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @forelse($newArrivals as $product)
                        @include('shop.partials.home-product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
                        @empty
                        <div class="swiper-slide"><p class="h6 text-center">New arrivals coming soon.</p></div>
                        @endforelse
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>

            {{-- Best Seller tab --}}
            <div class="tab-pane" id="best-seller" role="tabpanel">
                <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                    data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
                    data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @forelse($bestSellers as $product)
                        @include('shop.partials.home-product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
                        @empty
                        <div class="swiper-slide"><p class="h6 text-center">No best sellers marked yet.</p></div>
                        @endforelse
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>

            {{-- On Sale tab --}}
            <div class="tab-pane" id="on-sale" role="tabpanel">
                <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                    data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
                    data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @forelse($onSale as $product)
                        @include('shop.partials.home-product-card', ['product' => $product, 'showSale' => true, 'wishlistedIds' => $wishlistedIds])
                        @empty
                        <div class="swiper-slide"><p class="h6 text-center">No items on sale right now.</p></div>
                        @endforelse
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>

        </div>
    </div>
</section>
{{-- /Best Seller --}}

{{-- Banner Countdown — Ochaka banner-cd_v01 exact structure --}}
@if($activeCoupon)
<section class="themesFlat">
    <div class="banner-cd_v01 flex-md-nowrap">
        <div class="banner_content wow fadeInUp">
            <h1 class="title">On sale!</h1>
            <p class="sub-title">Use code <strong>{{ $activeCoupon->code }}</strong> for {{ $activeCoupon->type === 'percent' ? $activeCoupon->value.'%' : '€'.number_format((float)$activeCoupon->value,2) }} off your order</p>
            @if($activeCoupon->expires_at)
            <div class="count-down_v01">
                <div class="js-countdown cd-custom-element cd-has-zero" data-timer="{{ now()->diffInSeconds($activeCoupon->expires_at, false) }}" data-labels="Days,Hours,Mins,Secs"></div>
            </div>
            @endif
            <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn type-small-2">
                Shop now
                <i class="icon icon-arrow-right"></i>
            </a>
        </div>
        <div class="banner_img">
            @php $saleBannerRaw = \App\Models\Setting::get('sale_banner_image'); @endphp
            @if($saleBannerRaw)
            <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset('storage/' . $saleBannerRaw) }}" alt="On sale">
            @else
            <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/banner/banner-cd-V01.jpg" alt="On sale">
            @endif
        </div>
    </div>
</section>
@endif
{{-- /Banner Countdown --}}

{{-- Trending — Ochaka card-product swiper exact structure.
     NOTE (approved deviation, documented per request): no `is_trending` flag exists in the
     database yet — merchandising/collection-based flag system is a separate planned task.
     Temporarily reusing $newArrivals data here until that is finalised. --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">Trending Product</h1>
            <p class="s-subtitle h6">Customer favourites from the FADÓ workshop</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
            data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
            data-pagination-lg="4">
            <div class="swiper-wrapper">
                @forelse($newArrivals as $product)
                @include('shop.partials.home-product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
                @empty
                <div class="swiper-slide"><p class="h6 text-center">Coming soon.</p></div>
                @endforelse
            </div>
            <div class="sw-dot-default tf-sw-pagination d-xl-none"></div>
        </div>
    </div>
</section>
{{-- /Trending --}}

{{-- Banner Shop Detail — Ochaka s-banner-shop exact structure.
     Approved deviations: star rating is a static cosmetic placeholder (no review system exists);
     metal shown as plain text (no swatch-image field on Metal model); "Add to Cart" links to the
     product page instead of an instant Buy-It-Now flow (no such flow exists yet); Compare icon
     omitted (consistent with earlier decision). --}}
@if($featuredProduct)
@php
    $fpVariant = $featuredProduct->variants->first();
    $fpMetals  = $featuredProduct->variants->pluck('metal.name')->filter()->unique();
@endphp
<section class="flat-spacing bg-rose-white s-banner-shop">
    <div class="container">
        <div class="row section-image-zoom">
            <div class="col-lg-6">
                <div class="tf-product-media-wrap sticky-top">
                    <div class="product-thumbs-slider thumbs-abs">
                        <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical" data-preview="4.7">
                            <div class="swiper-wrapper stagger-wrap">
                                @forelse($featuredProduct->images as $img)
                                <div class="swiper-slide stagger-item">
                                    <div class="item">
                                        <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset($img->path) }}" alt="{{ $featuredProduct->name }}">
                                    </div>
                                </div>
                                @empty
                                <div class="swiper-slide stagger-item">
                                    <div class="item">
                                        <img class="lazyload" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/products/jewelry/product-21.jpg" alt="{{ $featuredProduct->name }}">
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="flat-wrap-media-product">
                            <div dir="ltr" class="swiper tf-product-media-main" id="featured-gallery-swiper">
                                <div class="swiper-wrapper">
                                    @forelse($featuredProduct->images as $img)
                                    <div class="swiper-slide">
                                        <a href="{{ asset($img->path) }}" target="_blank" class="item">
                                            <img class="tf-image-zoom lazyload" data-zoom="{{ asset($img->path) }}" data-src="{{ asset($img->path) }}" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" alt="{{ $featuredProduct->name }}">
                                        </a>
                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <a href="/images/ochaka/products/jewelry/product-21.jpg" target="_blank" class="item">
                                            <img class="tf-image-zoom lazyload" data-zoom="/images/ochaka/products/jewelry/product-21.jpg" data-src="/images/ochaka/products/jewelry/product-21.jpg" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" alt="{{ $featuredProduct->name }}">
                                        </a>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center">
                <div class="tf-product-info-wrap position-relative mt-lg-0">
                    <div class="tf-zoom-main sticky-top"></div>
                    <div class="tf-product-info-list other-image-zoom">

                        <h1 class="product-info-name fw-medium">
                            <a href="{{ route('shop.product', $featuredProduct) }}" class="link">
                                {{ $featuredProduct->name }}
                            </a>
                        </h1>
                        <div class="tf-product-heading p-0 border-0">
                            <div class="product-info-price price-wrap">
                                @if($fpVariant?->isOnSale())
                                    <span class="price-new price-on-sale h2 fw-4">€{{ number_format((float)$fpVariant->sale_price_eur, 2) }}</span>
                                    <span class="price-old compare-at-price h6">€{{ number_format((float)$fpVariant->price_eur, 2) }}</span>
                                @elseif($fpVariant)
                                    <span class="price-new h2 fw-4">€{{ number_format((float)$fpVariant->price_eur, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        @if($featuredProduct->short_description)
                        <div class="tf-prduct-desc">
                            <p class="h6">{{ $featuredProduct->short_description }}</p>
                        </div>
                        @endif
                        @if($fpMetals->isNotEmpty())
                        <div class="tf-product-variant">
                            <div class="variant-picker-item">
                                <div class="variant-picker-label mb-24">
                                    <div class="h6">Available in: {{ $fpMetals->join(', ') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="tf-product-total-quantity mb-0">
                            <div class="group-btn">
                                @if($fpVariant)
                                <p class="h6">{{ $fpVariant->stock }} in stock</p>
                                @endif
                            </div>
                            <div class="group-btn flex-sm-nowrap">
                                <a href="{{ route('shop.product', $featuredProduct) }}" class="tf-btn animate-btn btn-add-to-cart">
                                    Add to cart
                                    <i class="icon icon-shopping-cart-simple"></i>
                                </a>
                                <button type="button"
                                        class="hover-tooltip box-icon btn-add-wishlist flex-sm-shrink-0"
                                        onclick="event.preventDefault(); fetch('{{ route('shop.wishlist.toggle', $featuredProduct->id) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>location.reload())">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
{{-- /Banner Shop Detail --}}

{{-- Box Icon — Ochaka box-icon_V01 exact structure. Real FADÓ policy text (not fabricated). --}}
@php
    $boxIconFreeShipping = \App\Models\Setting::get('shipping_notice', 'Free delivery on orders over €75');
@endphp
<div class="themesFlat">
    <div class="container">
        <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="1" data-space-lg="97"
            data-space-md="33" data-space="13" data-pagination="1" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInLeft">
                        <span class="icon">
                            <i class="icon-package"></i>
                        </span>
                        <div class="content">
                            <h4 class="title fw-normal">{{ \App\Models\Setting::get('trust_label_1', '30 Day Returns') }}</h4>
                            <p class="text">{{ \App\Models\Setting::get('trust_sub_1', '30-day returns on unworn pieces') }}</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.1s">
                        <span class="icon">
                            <i class="icon-check-circle"></i>
                        </span>
                        <div class="content">
                            <h4 class="title fw-normal">{{ \App\Models\Setting::get('trust_label_2', 'Secure Payment') }}</h4>
                            <p class="text">{{ \App\Models\Setting::get('trust_sub_2', 'Encrypted, secure payment') }}</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.2s">
                        <span class="icon">
                            <i class="icon-boat"></i>
                        </span>
                        <div class="content">
                            <h4 class="title fw-normal">{{ \App\Models\Setting::get('trust_label_3', 'Free Delivery') }}</h4>
                            <p class="text">{{ \App\Models\Setting::get('trust_sub_3', $boxIconFreeShipping) }}</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInLeft" data-wow-delay="0.3s">
                        <span class="icon">
                            <i class="icon-headset"></i>
                        </span>
                        <div class="content">
                            <h4 class="title fw-normal">{{ \App\Models\Setting::get('trust_label_4', 'Irish Crafted') }}</h4>
                            <p class="text">{{ \App\Models\Setting::get('trust_sub_4', 'Contact us for any questions') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
</div>
{{-- /Box Icon --}}

{{-- Testimonials — wired to DB via Testimonial model --}}
@if($testimonials->isNotEmpty())
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title type-2">
            <div class="flex-sm-1 wow fadeInUp">
                <h1 class="s-title mb-8">Customer Reviews</h1>
                <p class="s-subtitle h6">{{ \App\Models\Setting::get('homepage_subtitle_2', '') }}</p>
            </div>
            <div class="group-btn-slider wow fadeInUp" data-wow-delay="0.1s">
                <div class="tf-sw-nav style-2 type-small nav-prev-swiper">
                    <i class="icon icon-caret-left"></i>
                </div>
                <div class="tf-sw-nav style-2 type-small nav-next-swiper">
                    <i class="icon icon-caret-right"></i>
                </div>
            </div>
        </div>
        <div dir="ltr" class="swiper tf-swiper" data-preview="2" data-tablet="2" data-mobile-sm="1" data-mobile="1" data-space-lg="48"
            data-space-md="32" data-space="12" data-pagination="1" data-pagination-sm="1" data-pagination-md="2" data-pagination-lg="2">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                <div class="swiper-slide">
                    <div class="testimonial-V02 type-space-2 hover-img wow fadeInUp" @if(!$loop->first) data-wow-delay="0.{{ $loop->index }}s" @endif>
                        @if($testimonial->product_name)
                        <div class="tes_product">
                            <div class="product-infor">
                                <h5 class="prd_name fw-normal" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;">{{ $testimonial->product_name }}</h5>
                            </div>
                        </div>
                        @endif
                        <div class="tes_content">
                            <div class="tes_icon">
                                <i class="icon icon-block-quote"></i>
                            </div>
                            <p class="tes_text h4">"{{ $testimonial->body }}"</p>
                            <div class="tes_author d-flex align-items-center gap-2">
                                @if($testimonial->avatar)
                                <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                @endif
                                <div>
                                    <p class="author-name h4 mb-0">{{ $testimonial->name }}</p>
                                    @if($testimonial->location)
                                    <span class="h6 text-muted">{{ $testimonial->location }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="rate_wrap">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="icon-star {{ $i <= $testimonial->rating ? 'text-star' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
{{-- /Testimonials --}}

{{-- Blog section — dynamic from posts table --}}
@if($posts->isNotEmpty())
<section class="flat-spacing pt-0">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">Our Blog</h1>
            <p class="s-subtitle h6">Stories, inspiration, and the craft behind FADÓ jewellery</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="2" data-mobile-sm="1" data-mobile="1" data-space-lg="48"
            data-space-md="32" data-space="12" data-pagination="1" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="3"
            data-auto="true" data-delay="4000" data-loop="true">
            <div class="swiper-wrapper">
                @foreach($posts as $post)
                <div class="swiper-slide">
                    <div class="article-blog type-space-2 hover-img4 wow fadeInLeft" @if(!$loop->first) data-wow-delay="{{ ($loop->index) * 0.1 }}s" @endif>
                        <a href="{{ route('blog.show', $post) }}" class="entry_image img-style4">
                            @if($post->featured_image)
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="lazyload aspect-ratio-0">
                            @else
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/blog/blog-11.jpg" alt="{{ $post->title }}" class="lazyload aspect-ratio-0">
                            @endif
                        </a>
                        <div class="entry_tag">
                            <span class="name-tag h6">{{ $post->published_at->format('F j, Y') }}</span>
                        </div>
                        <div class="blog-content">
                            <a href="{{ route('blog.show', $post) }}" class="entry_name link h4">
                                {{ $post->title }}
                            </a>
                            @if($post->excerpt)
                            <p class="text h6">{{ Str::limit($post->excerpt, 120) }}</p>
                            @endif
                            <a href="{{ route('blog.show', $post) }}" class="tf-btn-line">
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="tf-btn animate-btn">
                View All Posts
                <i class="icon icon-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif
{{-- /Blog --}}

{{-- Instagram section hidden: no live integration --}}
@if(false)
<section class="flat-spacing pb-xl-0">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="title mb-8">Shop Instagram</h1>
            <p class="s-subtitle h6">Up to 50% off Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
        </div>
    </div>
    <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2" data-space="0"
        data-pagination="2" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
        <div class="swiper-wrapper">
            <!-- item 1 -->
            <div class="swiper-slide">
                <div class="gallery-item style-2 hover-img hover-overlay">
                    <div class="image img-style">
                        <img class="lazyload" src="/images/ochaka/gallery/gallery-24.jpg" data-src="/images/ochaka/gallery/gallery-24.jpg" alt="">
                    </div>
                    <a href="#" class="box-icon hover-tooltip">
                        <span class="icon icon-instagram-logo"></span>
                        <span class="tooltip">View product</span>
                    </a>
                </div>
            </div>
            <!-- item 2 -->
            <div class="swiper-slide">
                <div class="gallery-item style-2 hover-img hover-overlay">
                    <div class="image img-style">
                        <img class="lazyload" src="/images/ochaka/gallery/gallery-25.jpg" data-src="/images/ochaka/gallery/gallery-25.jpg" alt="">
                    </div>
                    <a href="#" class="box-icon hover-tooltip">
                        <span class="icon icon-instagram-logo"></span>
                        <span class="tooltip">View product</span>
                    </a>
                </div>
            </div>
            <!-- item 3 -->
            <div class="swiper-slide">
                <div class="gallery-item style-2 hover-img hover-overlay">
                    <div class="image img-style">
                        <img class="lazyload" src="/images/ochaka/gallery/gallery-26.jpg" data-src="/images/ochaka/gallery/gallery-26.jpg" alt="">
                    </div>
                    <a href="#" class="box-icon hover-tooltip">
                        <span class="icon icon-instagram-logo"></span>
                        <span class="tooltip">View product</span>
                    </a>
                </div>
            </div>
            <!-- item 4 -->
            <div class="swiper-slide">
                <div class="gallery-item style-2 hover-img hover-overlay">
                    <div class="image img-style">
                        <img class="lazyload" src="/images/ochaka/gallery/gallery-27.jpg" data-src="/images/ochaka/gallery/gallery-27.jpg" alt="">
                    </div>
                    <a href="#" class="box-icon hover-tooltip">
                        <span class="icon icon-instagram-logo"></span>
                        <span class="tooltip">View product</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sw-dot-default tf-sw-pagination"></div>
    </div>
</section>
@endif
{{-- /Instagram --}}

@endsection
