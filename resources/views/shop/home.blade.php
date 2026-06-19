@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', Setting::get('meta_title', Setting::get('store_name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery'))
@section('meta_description', Setting::get('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.'))
@section('canonical', route('shop.home'))

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 1  HERO SLIDER — exact Ochaka tf-slideshow type-abs structure           --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tf-slideshow type-abs tf-btn-swiper-main">
    <div dir="ltr" class="swiper tf-swiper sw-slide-show slider_effect_fade"
         data-auto="false" data-loop="true" data-effect="fade" data-delay="3000">
        <div class="swiper-wrapper">

            {{-- Slide 1 — Claddagh --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/BannerTop-one.jpg') }}"
                             data-src="{{ Storage::url('banners/BannerTop-one.jpg') }}"
                             alt="FADÓ Jewellery — Claddagh Collection"
                             class="lazyload ani-zoom">
                    </div>
                    <div class="sld_content text-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="content-sld_wrap">
                                        <p class="sub-title_sld-2 font-2 h3 text-white fade-item fade-item-1">
                                            Fine Irish Jewellery
                                        </p>
                                        <h1 class="title_sld text-white text-display fade-item fade-item-2">
                                            The Claddagh Collection
                                        </h1>
                                        <p class="sub-text_sld h5 text-white fade-item fade-item-3">
                                            Love, loyalty and friendship — Ireland's most enduring symbol, crafted in sterling silver, gold and platinum.
                                        </p>
                                        <div class="fade-item fade-item-4">
                                            <a href="{{ route('shop.collection', 'claddagh') }}"
                                               class="tf-btn btn-white animate-btn animate-dark fw-semibold">
                                                Shop Claddagh <i class="icon icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 — Garden Collection (centre-aligned, style-3) --}}
            <div class="swiper-slide">
                <div class="slider-wrap style-3">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/BannerTop-Two.jpg') }}"
                             data-src="{{ Storage::url('banners/BannerTop-Two.jpg') }}"
                             alt="The Jewellery Garden by FADÓ"
                             class="lazyload ani-zoom">
                    </div>
                    <div class="sld_content text-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="content-sld_wrap">
                                        <p class="sub-title_sld-2 font-2 h3 text-white fade-item fade-item-1">
                                            The Jewellery Garden by FADÓ
                                        </p>
                                        <h1 class="title_sld text-white text-display fade-item fade-item-2">
                                            Nature, Captured in Gold &amp; Silver
                                        </h1>
                                        <p class="sub-text_sld h5 text-white fade-item fade-item-3">
                                            Bluebells, wild daisies, butterflies and bees — Ireland's garden in every piece.
                                        </p>
                                        <div class="fade-item fade-item-4">
                                            <a href="{{ route('shop.collection', 'the-garden-collection') }}"
                                               class="tf-btn btn-white animate-btn animate-dark fw-semibold">
                                                Explore the Garden Collection <i class="icon icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3 — Irish Gold --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/BannerTop-three.jpg') }}"
                             data-src="{{ Storage::url('banners/BannerTop-three.jpg') }}"
                             alt="Handcrafted Irish Gold"
                             class="lazyload ani-zoom">
                    </div>
                    <div class="sld_content text-center">
                        <div class="container">
                            <div class="row justify-content-end">
                                <div class="col-md-5">
                                    <div class="content-sld_wrap">
                                        <p class="sub-title_sld-2 font-2 h3 text-white fade-item fade-item-1">
                                            A gift they'll treasure forever
                                        </p>
                                        <h1 class="title_sld text-white text-display fade-item fade-item-2">
                                            Handcrafted Irish Gold
                                        </h1>
                                        <p class="sub-text_sld h5 text-white fade-item fade-item-3">
                                            From 9ct to 18ct gold and platinum — every piece crafted in the Irish tradition.
                                        </p>
                                        <div class="fade-item fade-item-4">
                                            <a href="{{ route('shop.jewellery', ['metal' => '9ct-yellow-gold']) }}"
                                               class="tf-btn btn-white animate-btn animate-dark fw-semibold">
                                                Shop Gold Jewellery <i class="icon icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="sw-dot-default-2 style-white tf-sw-pagination"></div>
    </div>
</div>
{{-- /Hero Slider --}}


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 2  TRUST STRIP                                                          --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing-2" style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey)">
    <div class="container">
        <div class="row text-center g-0">
            @foreach([
                ['icon' => 'icon-truck-simple', 'label' => 'Free Delivery',      'sub' => Setting::get('shipping_notice', 'Free delivery on orders over €75')],
                ['icon' => 'icon-shield-check',  'label' => 'Hallmark Certified', 'sub' => 'Assay Office Ireland'],
                ['icon' => 'icon-arrow-counter-clockwise', 'label' => '30-Day Returns', 'sub' => 'Hassle-free exchanges'],
                ['icon' => 'icon-gift',           'label' => 'Gift Packaging',    'sub' => 'Luxury box included'],
            ] as $trust)
            <div class="col-6 col-md-3" style="padding:20px 16px; border-right:1px solid var(--fado-warm-grey)">
                <i class="icon {{ $trust['icon'] }}" style="font-size:1.4rem; color:var(--fado-green-mid); margin-bottom:8px; display:block"></i>
                <div class="h6 fw-bold" style="color:var(--fado-deep-green); letter-spacing:.04em">{{ $trust['label'] }}</div>
                <div class="text-small-3" style="color:var(--fado-warm-grey)">{{ $trust['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 3  SHOP BY CATEGORY — Ochaka widget-collection carousel                 --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">Shop by Jewellery Type</h1>
            <p class="s-subtitle h6">From classic rings to Celtic crosses — discover Irish jewellery for every occasion</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper wow fadeInUp"
             data-preview="5" data-tablet="4" data-mobile-sm="3" data-mobile="2"
             data-space-lg="40" data-space-md="32" data-space="12"
             data-pagination="2" data-pagination-sm="3" data-pagination-md="4" data-pagination-lg="5">
            <div class="swiper-wrapper">
                @foreach([
                    ['slug' => 'rings',             'label' => 'Rings',           'img' => '/images/ochaka/category/cate-24.jpg'],
                    ['slug' => 'earrings',          'label' => 'Earrings',        'img' => '/images/ochaka/category/cate-25.jpg'],
                    ['slug' => 'pendants',          'label' => 'Pendants',        'img' => '/images/ochaka/category/cate-28.jpg'],
                    ['slug' => 'crosses',           'label' => 'Crosses',         'img' => '/images/ochaka/category/cate-26.jpg'],
                    ['slug' => 'bracelets-bangles', 'label' => 'Bracelets',       'img' => '/images/ochaka/category/cate-27.jpg'],
                    ['slug' => 'cufflinks',         'label' => 'Cufflinks',       'img' => '/images/ochaka/products/jewelry/product-7.jpg'],
                    ['slug' => 'brooches',          'label' => 'Brooches',        'img' => '/images/ochaka/products/jewelry/product-11.jpg'],
                ] as $cat)
                <div class="swiper-slide">
                    <a href="{{ route('shop.category', $cat['slug']) }}" class="widget-collection type-space-2 hover-img">
                        <div class="collection_image img-style">
                            <img class="lazyload" src="{{ $cat['img'] }}" data-src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}">
                        </div>
                        <p class="collection_name h5 link fw-semibold">{{ $cat['label'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 4  FEATURED COLLECTIONS — Ochaka box-image_V02 type-space-3 hover-img  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="themesFlat">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp mb-4">
            <h1 class="s-title mb-8">Featured Collections</h1>
            <p class="s-subtitle h6">Ireland's heritage and craft — worn every day</p>
        </div>
        <div class="tf-grid-layout lg-col-2">

            {{-- Claddagh --}}
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', 'claddagh') }}" class="box-image_image img-style">
                    <img src="/images/ochaka/section/box-image-8.jpg"
                         data-src="/images/ochaka/section/box-image-8.jpg"
                         alt="Claddagh Collection" class="lazyload">
                </a>
                <div class="box-image_content wow fadeInUp">
                    <span class="sub-text h4 text-white mb-8">Irish Heritage</span>
                    <a href="{{ route('shop.collection', 'claddagh') }}"
                       class="title link text-display-2 fw-medium text-white">Claddagh Collection</a>
                    <a href="{{ route('shop.collection', 'claddagh') }}"
                       class="tf-btn btn-white animate-btn animate-dark">
                        Shop now <i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Trinity --}}
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', 'trinity') }}" class="box-image_image img-style">
                    <img src="/images/ochaka/section/box-image-9.jpg"
                         data-src="/images/ochaka/section/box-image-9.jpg"
                         alt="Trinity Collection" class="lazyload">
                </a>
                <div class="box-image_content wow fadeInUp">
                    <span class="sub-text h4 text-white mb-8">Irish Heritage</span>
                    <a href="{{ route('shop.collection', 'trinity') }}"
                       class="title link text-display-2 fw-medium text-white">Trinity Collection</a>
                    <a href="{{ route('shop.collection', 'trinity') }}"
                       class="tf-btn btn-white animate-btn animate-dark">
                        Shop now <i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Newgrange — full-width third card --}}
        <div class="box-image_V02 type-space-3 hover-img mt-4">
            <a href="{{ route('shop.collection', 'newgrange') }}" class="box-image_image img-style">
                <img src="/images/ochaka/slider/slider-22.jpg"
                     data-src="/images/ochaka/slider/slider-22.jpg"
                     alt="Newgrange Collection" class="lazyload">
            </a>
            <div class="box-image_content wow fadeInUp">
                <span class="sub-text h4 text-white mb-8">Spiral of Life</span>
                <a href="{{ route('shop.collection', 'newgrange') }}"
                   class="title link text-display-2 fw-medium text-white">Newgrange Collection</a>
                <a href="{{ route('shop.collection', 'newgrange') }}"
                   class="tf-btn btn-white animate-btn animate-dark">
                    Explore the collection <i class="icon icon-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
</div>
{{-- /Featured Collections --}}


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 5  NEW ARRIVALS — Ochaka card-product structure                         --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing flat-animate-tab" style="background:var(--fado-soft-white)">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">New Arrivals</h1>
            <p class="s-subtitle h6">The latest pieces added to the FADÓ collection</p>
        </div>

        @if($newArrivals->isNotEmpty())
        <div class="row g-4">
            @foreach($newArrivals->take(4) as $product)
            @php
                $img  = $product->primaryImage?->path;
                $from = $product->variants->min('price_eur');
            @endphp
            <div class="col-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ $loop->index * .08 }}s">
                <div class="card-product">
                    <div class="card-product_wrapper d-flex">
                        <a href="{{ route('shop.product', $product) }}" class="product-img img-style">
                            @if($img)
                                <img class="lazyload img-product" src="{{ Storage::url($img) }}" data-src="{{ Storage::url($img) }}" alt="{{ $product->name }}">
                            @else
                                <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                            @endif
                        </a>
                        <ul class="product-action_list">
                            <li class="wishlist">
                                <a href="{{ route('shop.wishlist.toggle', $product->id) }}"
                                   class="hover-tooltip tooltip-left box-icon"
                                   onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>location.reload())">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('shop.product', $product) }}" class="hover-tooltip tooltip-left box-icon">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">View product</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-product_info">
                        <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
                        <div class="price-wrap mb-0">
                            @if($from)
                                <span class="price-new h6">From €{{ number_format((float)$from, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Placeholder grid --}}
        <div class="row g-4">
            @foreach([
                ['img' => '/images/ochaka/products/jewelry/product-5.jpg',  'name' => 'Sterling Silver Claddagh Ring',  'price' => '€85'],
                ['img' => '/images/ochaka/products/jewelry/product-9.jpg',  'name' => 'Gold Claddagh Pendant',          'price' => '€145'],
                ['img' => '/images/ochaka/products/jewelry/product-13.jpg', 'name' => 'Trinity Knot Earrings',          'price' => '€65'],
                ['img' => '/images/ochaka/products/jewelry/product-17.jpg', 'name' => 'Celtic Cross Pendant',           'price' => '€95'],
            ] as $ph)
            <div class="col-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ $loop->index * .08 }}s">
                <div class="card-product">
                    <div class="card-product_wrapper d-flex">
                        <a href="{{ route('shop.jewellery') }}" class="product-img img-style">
                            <img class="lazyload img-product" src="{{ $ph['img'] }}" data-src="{{ $ph['img'] }}" alt="{{ $ph['name'] }}">
                        </a>
                    </div>
                    <div class="card-product_info">
                        <a href="{{ route('shop.jewellery') }}" class="name-product h4 link">{{ $ph['name'] }}</a>
                        <div class="price-wrap mb-0">
                            <span class="price-new h6">From {{ $ph['price'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-5 wow fadeInUp">
            <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">View all jewellery <i class="icon icon-arrow-right"></i></a>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 6  GARDEN COLLECTION SPOTLIGHT                                          --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing" style="background:var(--fado-mint-pale)">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".1s">
                <div class="img-style" style="border-radius:4px; overflow:hidden; aspect-ratio:4/5">
                    <img class="lazyload" src="/images/ochaka/products/jewelry/product-21.jpg"
                         data-src="/images/ochaka/products/jewelry/product-21.jpg"
                         alt="The Jewellery Garden by FADÓ">
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                <p class="sub-title_sld-2 font-2 h3" style="color:var(--fado-green-mid)">Wedding &amp; Engagement</p>
                <h2 class="s-title mb-16">The Jewellery Garden<br>by FADÓ</h2>
                <p class="h5 mb-16" style="color:#555; line-height:1.8">
                    Ireland's wildflower meadows — bluebells, daisies, forget-me-nots, butterflies and bees — captured in sterling silver and gold.
                </p>
                <p class="h5 mb-28" style="color:#555; line-height:1.8">
                    Ideal for engagements, weddings, anniversaries, and any occasion that deserves something truly unique.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="tf-btn animate-btn fw-semibold">
                        Explore the Collection <i class="icon icon-arrow-right"></i>
                    </a>
                    <a href="{{ route('shop.contact') }}#consultation" class="tf-btn animate-btn type-small style-2">
                        Book a Consultation <i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 7  ABOUT BAND                                                            --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing" style="background:var(--fado-soft-white); border-top:1px solid var(--fado-cream); border-bottom:1px solid var(--fado-cream)">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-lg-5 text-center text-lg-start wow fadeInUp">
                <p class="sub-title_sld-2 font-2 h3" style="color:var(--fado-gold)">Our Story</p>
                <h2 class="s-title mb-16">Irish Jewellery,<br>Crafted with Heart</h2>
                <p class="h5 mb-28" style="color:#555; line-height:1.8">
                    FADÓ — meaning "long ago" in Irish — honours centuries of Irish craftsmanship. Each piece is designed to carry meaning, to be worn every day, and to be passed down through generations.
                </p>
                <a href="{{ route('shop.about') }}" class="tf-btn animate-btn type-small style-2">
                    Our Story <i class="icon icon-arrow-right"></i>
                </a>
            </div>
            <div class="col-lg-4 d-none d-lg-block wow fadeInUp" data-wow-delay=".15s">
                <div class="d-flex flex-column gap-3">
                    @foreach([
                        ['icon' => 'icon-medal',          'title' => 'Hallmark Certified',    'sub' => 'Assay Office Ireland — every piece certified'],
                        ['icon' => 'icon-hands-clapping', 'title' => 'Handcrafted Heritage',  'sub' => 'Designed and finished in the Irish tradition'],
                        ['icon' => 'icon-heart',          'title' => 'Made to be Treasured',  'sub' => 'Pieces designed to be worn and passed on'],
                    ] as $v)
                    <div class="d-flex align-items-start gap-3 p-4" style="background:#fff; border-radius:6px; border:1px solid var(--fado-cream); box-shadow:0 2px 8px rgba(0,0,0,.04)">
                        <div style="width:40px; height:40px; border-radius:50%; background:var(--fado-mint-pale); display:flex; align-items:center; justify-content:center; flex-shrink:0">
                            <i class="icon {{ $v['icon'] }}" style="color:var(--fado-green-mid); font-size:1.1rem"></i>
                        </div>
                        <div>
                            <div class="h6 fw-bold mb-1" style="color:var(--fado-deep-green)">{{ $v['title'] }}</div>
                            <div class="text-small-3" style="color:#777; line-height:1.5">{{ $v['sub'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 8  CONSULTATION CTA                                                      --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing" style="background:var(--fado-cream)">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 wow fadeInUp">
                <p class="sub-title_sld-2 font-2 h3" style="color:var(--fado-gold)">Personal Service</p>
                <h2 class="s-title mb-16">Not sure what to choose?<br>Book a free consultation.</h2>
                <p class="h5" style="color:#666; line-height:1.75; max-width:540px">
                    Our jewellery experts are here to help you find the perfect piece — whether it's for an engagement, anniversary, or simply a well-deserved treat. By phone, email, or in person.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end wow fadeInUp" data-wow-delay=".1s">
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                    <a href="{{ route('shop.contact') }}#consultation"
                       class="tf-btn animate-btn fw-semibold">
                        Book an appointment <i class="icon icon-arrow-right"></i>
                    </a>
                    <a href="{{ route('shop.contact') }}" class="tf-btn animate-btn type-small style-2">
                        Contact us <i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
