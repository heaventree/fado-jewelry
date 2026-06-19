@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', Setting::get('meta_title', Setting::get('store_name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery'))
@section('meta_description', Setting::get('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.'))
@section('canonical', route('shop.home'))

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 1  HERO SLIDER — Ochaka tf-slideshow type-abs exact structure            --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="tf-slideshow type-abs tf-btn-swiper-main hover-sw-nav">
    <div dir="ltr" class="swiper tf-swiper sw-slide-show slider_effect_fade" data-auto="true" data-loop="true" data-effect="fade" data-delay="3000">
        <div class="swiper-wrapper">

            {{-- Slide 1 --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/Banner 1.png') }}" data-src="{{ Storage::url('banners/Banner 1.png') }}" alt="FADÓ Jewellery — Claddagh Collection" class="lazyload">
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/Banner 2.png') }}" data-src="{{ Storage::url('banners/Banner 2.png') }}" alt="The Jewellery Garden by FADÓ" class="lazyload">
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/Banner 3.png') }}" data-src="{{ Storage::url('banners/Banner 3.png') }}" alt="Handcrafted Irish Gold" class="lazyload">
                    </div>
                </div>
            </div>

            {{-- Slide 4 --}}
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="sld_image">
                        <img src="{{ Storage::url('banners/Banner 4.png') }}" data-src="{{ Storage::url('banners/Banner 4.png') }}" alt="FADÓ Fine Irish Jewellery" class="lazyload">
                    </div>
                </div>
            </div>

        </div>
        <div class="sw-dot-default-2 style-white tf-sw-pagination"></div>
    </div>
    <div class="nav-sw style-2 nav-next-slider"><span class="icon icon-arrow-right"></span></div>
    <div class="nav-sw style-2 nav-prev-slider"><span class="icon icon-arrow-left"></span></div>
</div>
{{-- /Hero Slider --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 2  SHOP BY CATEGORY — Ochaka widget-collection carousel exact structure   --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="title mb-8">Shop By Categories</h1>
            <p class="s-subtitle h6">From classic rings to Celtic crosses — discover Irish jewellery for every occasion</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="5" data-tablet="4" data-mobile-sm="3" data-mobile="2"
            data-space-lg="40" data-space-md="32" data-space="12" data-pagination="2" data-pagination-sm="3" data-pagination-md="4"
            data-pagination-lg="5">
            <div class="swiper-wrapper">
                @php $catImgFallbacks = ['cate-24','cate-25','cate-26','cate-27','cate-28','cate-29','cate-30','cate-31']; @endphp
                @foreach($topCategories as $i => $cat)
                <div class="swiper-slide">
                    <a href="{{ route('shop.category', $cat->slug) }}" class="widget-collection type-space-2 hover-img">
                        <div class="collection_image img-style">
                            @if($cat->banner_image)
                                <img class="lazyload" src="{{ Storage::url($cat->banner_image) }}" data-src="{{ Storage::url($cat->banner_image) }}" alt="{{ $cat->name }}">
                            @else
                                @php $fb = $catImgFallbacks[$i % count($catImgFallbacks)]; @endphp
                                <img class="lazyload" src="/images/ochaka/category/{{ $fb }}.jpg" data-src="/images/ochaka/category/{{ $fb }}.jpg" alt="{{ $cat->name }}">
                            @endif
                        </div>
                        <p class="collection_name h5 link fw-semibold">
                            {{ $cat->name }}
                        </p>
                    </a>
                </div>
                @endforeach
                @if($topCategories->isEmpty())
                {{-- Fallback: no categories yet in DB --}}
                @foreach([['slug'=>'rings','name'=>'Rings','img'=>'cate-24'],['slug'=>'earrings','name'=>'Earrings','img'=>'cate-25'],['slug'=>'pendants','name'=>'Pendants','img'=>'cate-27'],['slug'=>'crosses','name'=>'Crosses','img'=>'cate-28']] as $ph)
                <div class="swiper-slide">
                    <a href="{{ route('shop.category', $ph['slug']) }}" class="widget-collection type-space-2 hover-img">
                        <div class="collection_image img-style">
                            <img class="lazyload" src="/images/ochaka/category/{{ $ph['img'] }}.jpg" data-src="/images/ochaka/category/{{ $ph['img'] }}.jpg" alt="{{ $ph['name'] }}">
                        </div>
                        <p class="collection_name h5 link fw-semibold">{{ $ph['name'] }}</p>
                    </a>
                </div>
                @endforeach
                @endif
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
</section>
{{-- /Category --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 3  FEATURED COLLECTIONS — Ochaka box-image_V02 type-space-3 exact        --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="themesFlat">
    <div class="container">
        <div class="tf-grid-layout lg-col-2">
            @php
                $featuredPairs = $featuredCollections->take(2);
                $boxFallbacks  = [
                    ['img' => '/images/ochaka/section/box-image-8.jpg', 'sub' => 'Irish Heritage'],
                    ['img' => '/images/ochaka/section/box-image-9.jpg', 'sub' => 'Irish Heritage'],
                ];
            @endphp
            @foreach($featuredPairs as $i => $col)
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', $col->slug) }}" class="box-image_image img-style">
                    @if($col->banner_image)
                        <img src="{{ Storage::url($col->banner_image) }}" data-src="{{ Storage::url($col->banner_image) }}" alt="{{ $col->name }}" class="lazyload">
                    @else
                        <img src="{{ $boxFallbacks[$i]['img'] }}" data-src="{{ $boxFallbacks[$i]['img'] }}" alt="{{ $col->name }}" class="lazyload">
                    @endif
                </a>
                <div class="box-image_content wow fadeInUp">
                    <span class="sub-text h4 text-white mb-8">{{ $boxFallbacks[$i]['sub'] ?? 'Collection' }}</span>
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
            @if($featuredPairs->isEmpty())
            {{-- Fallback until collections are seeded --}}
            @foreach([['claddagh','Claddagh Collection','/images/ochaka/section/box-image-8.jpg'],['trinity','Trinity Collection','/images/ochaka/section/box-image-9.jpg']] as [$slug,$name,$img])
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', $slug) }}" class="box-image_image img-style">
                    <img src="{{ $img }}" data-src="{{ $img }}" alt="{{ $name }}" class="lazyload">
                </a>
                <div class="box-image_content wow fadeInUp">
                    <span class="sub-text h4 text-white mb-8">Irish Heritage</span>
                    <a href="{{ route('shop.collection', $slug) }}" class="title link text-display-2 fw-medium text-white">{{ $name }}</a>
                    <a href="{{ route('shop.collection', $slug) }}" class="tf-btn btn-white animate-btn animate-dark">Shop now <i class="icon icon-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
{{-- /Box Image --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 4  NEW ARRIVALS — Ochaka Best Seller tabs + Swiper exact structure        --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing flat-animate-tab">
    <div class="container">
        <div class="sect-title wow fadeInUp">
            <h1 class="title text-center mb-24">New Arrivals</h1>
            <ul class="tab-product_list" role="tablist">
                <li class="nav-tab-item" role="presentation">
                    <a href="#tab-new-arr" data-bs-toggle="tab" class="tf-btn-line sm-letter-1 tf-btn-tab fw-normal active">
                        NEW ARRIVALS
                    </a>
                </li>
                <li class="nav-tab-item" role="presentation">
                    <a href="#tab-collections" data-bs-toggle="tab" class="tf-btn-line sm-letter-1 tf-btn-tab fw-normal">
                        COLLECTIONS
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            {{-- Tab: New Arrivals --}}
            <div class="tab-pane active show" id="tab-new-arr" role="tabpanel">
                <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                    data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
                    data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @if($newArrivals->isNotEmpty())
                            @foreach($newArrivals as $product)
                            @php
                                $img  = $product->primaryImage?->path;
                                $img2 = $product->images->skip(1)->first()?->path;
                                $from = $product->variants->min('price_eur');
                            @endphp
                            <div class="swiper-slide">
                                <div class="card-product">
                                    <div class="card-product_wrapper d-flex">
                                        <a href="{{ route('shop.product', $product) }}" class="product-img">
                                            @if($img)
                                                <img class="lazyload img-product" src="{{ Storage::url($img) }}" data-src="{{ Storage::url($img) }}" alt="{{ $product->name }}">
                                                @if($img2)
                                                <img class="lazyload img-hover" src="{{ Storage::url($img2) }}" data-src="{{ Storage::url($img2) }}" alt="{{ $product->name }}">
                                                @endif
                                            @else
                                                <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                                            @endif
                                        </a>
                                        <ul class="product-action_list">
                                            <li>
                                                <a href="{{ route('shop.product', $product) }}" class="hover-tooltip tooltip-left box-icon">
                                                    <span class="icon icon-view"></span>
                                                    <span class="tooltip">Quick view</span>
                                                </a>
                                            </li>
                                            <li class="wishlist">
                                                <a href="{{ route('shop.wishlist.toggle', $product->id) }}"
                                                   class="hover-tooltip tooltip-left box-icon"
                                                   onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>location.reload())">
                                                    <span class="icon icon-heart"></span>
                                                    <span class="tooltip">Add to Wishlist</span>
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
                        @else
                            {{-- Placeholder products until real data is imported --}}
                            @foreach([
                                ['img' => '/images/ochaka/products/jewelry/product-5.jpg',  'img2' => '/images/ochaka/products/jewelry/product-6.jpg',  'name' => 'Sterling Silver Claddagh Ring',  'price' => '€85'],
                                ['img' => '/images/ochaka/products/jewelry/product-7.jpg',  'img2' => '/images/ochaka/products/jewelry/product-8.jpg',  'name' => 'Gold Claddagh Pendant',          'price' => '€145'],
                                ['img' => '/images/ochaka/products/jewelry/product-9.jpg',  'img2' => '/images/ochaka/products/jewelry/product-10.jpg', 'name' => 'Trinity Knot Earrings',          'price' => '€65'],
                                ['img' => '/images/ochaka/products/jewelry/product-11.jpg', 'img2' => '/images/ochaka/products/jewelry/product-12.jpg', 'name' => 'Celtic Cross Pendant',           'price' => '€95'],
                            ] as $ph)
                            <div class="swiper-slide">
                                <div class="card-product">
                                    <div class="card-product_wrapper d-flex">
                                        <a href="{{ route('shop.jewellery') }}" class="product-img">
                                            <img class="lazyload img-product" src="{{ $ph['img'] }}" data-src="{{ $ph['img'] }}" alt="{{ $ph['name'] }}">
                                            <img class="lazyload img-hover" src="{{ $ph['img2'] }}" data-src="{{ $ph['img2'] }}" alt="{{ $ph['name'] }}">
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
                        @endif
                    </div>
                    <div class="sw-dot-default tf-sw-pagination d-xl-none"></div>
                </div>
            </div>

            {{-- Tab: Collections --}}
            <div class="tab-pane" id="tab-collections" role="tabpanel">
                <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                    data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
                    data-pagination-lg="4">
                    <div class="swiper-wrapper">
                        @foreach([
                            ['slug' => 'claddagh',             'name' => 'Claddagh Collection',       'img' => '/images/ochaka/products/jewelry/product-13.jpg', 'img2' => '/images/ochaka/products/jewelry/product-14.jpg'],
                            ['slug' => 'trinity',              'name' => 'Trinity Collection',        'img' => '/images/ochaka/products/jewelry/product-15.jpg', 'img2' => '/images/ochaka/products/jewelry/product-16.jpg'],
                            ['slug' => 'newgrange',            'name' => 'Newgrange Collection',      'img' => '/images/ochaka/products/jewelry/product-17.jpg', 'img2' => '/images/ochaka/products/jewelry/product-18.jpg'],
                            ['slug' => 'the-garden-collection','name' => 'The Garden Collection',     'img' => '/images/ochaka/products/jewelry/product-19.jpg', 'img2' => '/images/ochaka/products/jewelry/product-20.jpg'],
                        ] as $col)
                        <div class="swiper-slide">
                            <div class="card-product">
                                <div class="card-product_wrapper d-flex">
                                    <a href="{{ route('shop.collection', $col['slug']) }}" class="product-img">
                                        <img class="lazyload img-product" src="{{ $col['img'] }}" data-src="{{ $col['img'] }}" alt="{{ $col['name'] }}">
                                        <img class="lazyload img-hover" src="{{ $col['img2'] }}" data-src="{{ $col['img2'] }}" alt="{{ $col['name'] }}">
                                    </a>
                                </div>
                                <div class="card-product_info">
                                    <a href="{{ route('shop.collection', $col['slug']) }}" class="name-product h4 link">{{ $col['name'] }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="sw-dot-default tf-sw-pagination d-xl-none"></div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- /New Arrivals --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 5  GARDEN COLLECTION SPOTLIGHT — Ochaka banner-cd style (image + content) --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="themesFlat">
    <div class="banner-cd_v01 flex-md-nowrap">
        <div class="banner_content wow fadeInUp">
            <p class="sub-title font-2 h3">Wedding &amp; Engagement</p>
            <h1 class="title">The Jewellery Garden by FADÓ</h1>
            <p class="sub-title">Ireland's wildflower meadows — bluebells, daisies, forget-me-nots, butterflies and bees — captured in sterling silver and gold.</p>
            <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="tf-btn animate-btn type-small-2">
                Explore the Collection
                <i class="icon icon-arrow-right"></i>
            </a>
        </div>
        <div class="banner_img">
            <img class="lazyload" src="/images/ochaka/products/jewelry/product-21.jpg" data-src="/images/ochaka/products/jewelry/product-21.jpg" alt="The Jewellery Garden by FADÓ">
        </div>
    </div>
</section>
{{-- /Garden Collection --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 6  TRENDING / ABOUT — Ochaka Trending Product section style               --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">Our Irish Heritage</h1>
            <p class="s-subtitle h6">FADÓ — meaning "long ago" in Irish — honours centuries of Irish craftsmanship</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper wow fadeInUp" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
            data-space-lg="48" data-space-md="30" data-space="12" data-pagination="2" data-pagination-sm="2" data-pagination-md="3"
            data-pagination-lg="4">
            <div class="swiper-wrapper">
                @foreach([
                    ['slug' => 'an-ri',         'name' => 'An Rí Collection',     'img' => '/images/ochaka/products/jewelry/product-13.jpg', 'img2' => '/images/ochaka/products/jewelry/product-14.jpg'],
                    ['slug' => 'high-crosses',   'name' => 'High Crosses',         'img' => '/images/ochaka/products/jewelry/product-15.jpg', 'img2' => '/images/ochaka/products/jewelry/product-16.jpg'],
                    ['slug' => 'irish-folklore', 'name' => 'Irish Folklore',       'img' => '/images/ochaka/products/jewelry/product-17.jpg', 'img2' => '/images/ochaka/products/jewelry/product-18.jpg'],
                    ['slug' => 'shamrock',       'name' => 'Shamrock Collection',  'img' => '/images/ochaka/products/jewelry/product-19.jpg', 'img2' => '/images/ochaka/products/jewelry/product-20.jpg'],
                ] as $col)
                <div class="swiper-slide">
                    <div class="card-product">
                        <div class="card-product_wrapper d-flex">
                            <a href="{{ route('shop.collection', $col['slug']) }}" class="product-img">
                                <img class="lazyload img-product" src="{{ $col['img'] }}" data-src="{{ $col['img'] }}" alt="{{ $col['name'] }}">
                                <img class="lazyload img-hover" src="{{ $col['img2'] }}" data-src="{{ $col['img2'] }}" alt="{{ $col['name'] }}">
                            </a>
                        </div>
                        <div class="card-product_info">
                            <a href="{{ route('shop.collection', $col['slug']) }}" class="name-product h4 link">{{ $col['name'] }}</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="sw-dot-default tf-sw-pagination d-xl-none"></div>
        </div>
    </div>
</section>
{{-- /Heritage Collections --}}


{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 7  CONSULTATION CTA — Ochaka banner-cd style                              --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-spacing bg-rose-white s-banner-shop">
    <div class="container">
        <div class="sect-title text-center wow fadeInUp">
            <h1 class="s-title mb-8">Not sure what to choose?</h1>
            <p class="s-subtitle h6">Our jewellery experts are here to help you find the perfect piece</p>
        </div>
        <div class="text-center wow fadeInUp">
            <a href="{{ route('shop.contact') }}#consultation" class="tf-btn animate-btn fw-semibold">
                Book a Free Consultation <i class="icon icon-arrow-right"></i>
            </a>
            <span class="d-inline-block mx-3"></span>
            <a href="{{ route('shop.contact') }}" class="tf-btn btn-white animate-btn animate-dark">
                Contact Us <i class="icon icon-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
{{-- /Consultation CTA --}}

@endsection
