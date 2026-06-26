@extends('shop.layouts.app')

@php
    $storeName = \App\Models\Setting::get('store_name', 'FADÓ Jewellery');
    $storePhone = \App\Models\Setting::get('store_phone');
    $storeEmail = \App\Models\Setting::get('store_email', 'info@fadojewellery.ie');
    $storeAddr  = \App\Models\Setting::get('store_address');
@endphp

@section('title', \App\Models\Setting::get('about_meta_title') ?: 'About Us — ' . $storeName)
@section('meta_description', \App\Models\Setting::get('about_meta_description') ?: 'Learn about ' . $storeName . ', a fine Irish jewellery brand handcrafting Claddagh rings, Celtic crosses, and contemporary pieces in sterling silver and gold.')
@if(\App\Models\Setting::get('about_og_image'))
@section('og_image', asset('storage/' . \App\Models\Setting::get('about_og_image')))
@endif

@section('content')
        <!-- Page Title -->
        <section class="page-title-image">
            <div class="page_image overflow-hidden">
                @php $aboutHeroRaw = \App\Models\Setting::get('about_hero_image'); @endphp
                @if($aboutHeroRaw)
                <img class="lazyload ani-zoom" src="{{ asset('storage/' . $aboutHeroRaw) }}" data-src="{{ asset('storage/' . $aboutHeroRaw) }}" alt="About {{ $storeName }}">
                @else
                <img class="lazyload ani-zoom" src="/images/ochaka/section/about-us.jpg" data-src="/images/ochaka/section/about-us.jpg" alt="About {{ $storeName }}">
                @endif
            </div>
            <div class="page_content">
                <div class="container">
                    <div class="content">
                        <h1 class="heading fw-bold">
                            {{ \App\Models\Setting::get('about_heading', 'HANDCRAFTED IRISH JEWELLERY WITH HERITAGE & HEART') }}
                        </h1>
                        <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">
                            Our shop
                            <i class="icon icon-caret-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Hero Section -->
        <section class="s-intro flat-spacing">
            <div class="container">
                <p class="brand-name">{{ $storeName }}</p>
                <div class="box-intro">
                    <h4 class="slogan fw-normal">FINE IRISH JEWELLERY</h4>
                    <p class="intro-text">
                        {!! nl2br(e(\App\Models\Setting::get('about_story', $storeName . ' is a proudly Irish jewellery brand, drawing inspiration from Ireland\'s rich heritage of Celtic artistry, ancient symbolism and natural beauty. Every piece is designed and crafted with care — from classic Claddagh rings and Trinity knot pendants to contemporary collections inspired by Ireland\'s wildflower meadows and rolling landscapes.'))) !!}
                    </p>
                </div>
            </div>
        </section>
        <!-- /Hero Section -->
        <!-- About -->
        <section class="s-about">
            <div class="container">
                <div class="tf-grid-layout tf-col-2 md-col-3 xl-col-4">
                    @php
                        $galDefaults = ['/images/ochaka/section/gallery-modal-2.jpg', '/images/ochaka/section/gallery-modal-1.jpg', '/images/ochaka/section/gallery-modal-3.jpg'];
                        $aboutGal1 = ($v = \App\Models\Setting::get('about_gallery_1')) ? asset('storage/' . $v) : $galDefaults[0];
                        $aboutGal2 = ($v = \App\Models\Setting::get('about_gallery_2')) ? asset('storage/' . $v) : $galDefaults[1];
                        $aboutGal3 = ($v = \App\Models\Setting::get('about_gallery_3')) ? asset('storage/' . $v) : $galDefaults[2];
                    @endphp
                    <div class="item_2 image d-none d-md-block">
                        <img class="lazyload" src="{{ $aboutGal1 }}" data-src="{{ $aboutGal1 }}" alt="Craftsmanship">
                    </div>
                    <div class="wd-2-cols">
                        <div class="content-blog text-md-start">
                            <div class="box-intro">
                                <h4 class="slogan fw-normal">ROOTED IN IRISH TRADITION</h4>
                                <p class="intro-text">
                                    Our collections celebrate Ireland's most enduring symbols — the Claddagh, the Trinity knot, the High Cross,
                                    and the spirals of Newgrange. Each design is researched, sketched, and brought to life by skilled craftspeople
                                    using traditional techniques passed down through generations.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="item_1 image">
                        <img class="lazyload" src="{{ $aboutGal2 }}" data-src="{{ $aboutGal2 }}" alt="Heritage">
                    </div>
                    <div class="d-md-none d-xl-block">
                        <img class="lazyload d-md-none" src="{{ $aboutGal1 }}" data-src="{{ $aboutGal1 }}" alt="Detail">
                    </div>
                    <div class="item_3 image">
                        <img class="lazyload" src="{{ $aboutGal3 }}" data-src="{{ $aboutGal3 }}" alt="Workshop">
                    </div>
                    <div class="item_4 image">
                        @php $aboutGal4 = ($v = \App\Models\Setting::get('about_gallery_4')) ? asset('storage/' . $v) : '/images/ochaka/section/gallery-modal-4.jpg'; @endphp
                        <img class="lazyload" src="{{ $aboutGal4 }}" data-src="{{ $aboutGal4 }}" alt="Finished piece">
                    </div>
                </div>
            </div>
        </section>
        <!-- /About -->
        <!-- Brand Story -->
        <section class="flat-spacing">
            <div class="container">
                <div class="sect-title text-center">
                    <h1 class="s-title mb-8">Our Craft</h1>
                    <p class="s-subtitle h6">Sterling silver, gold, and platinum — handcrafted in Ireland</p>
                </div>
                <div class="box-intro has-mb text-center">
                    <h4 class="slogan fw-normal">QUALITY MATERIALS, TIMELESS DESIGN</h4>
                    <p class="intro-text">
                        We work with sterling silver, 9ct and 14ct gold, and platinum to create jewellery that lasts a lifetime. Every stone is
                        hand-set, every surface finished by hand. Our pieces are hallmarked in Dublin's Assay Office — a guarantee of quality
                        that has stood for over 300 years.
                    </p>
                </div>
                <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space-lg="48"
                    data-space-md="15" data-space="10" data-pagination="1" data-pagination-sm="1" data-pagination-md="2" data-pagination-lg="3">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="wg-icon-image hover-img">
                                <div class="image img-style">
                                    <img class="lazyload" src="/images/ochaka/section/story-1.jpg" data-src="/images/ochaka/section/story-1.jpg" alt="">
                                </div>
                                <div class="box-icon">
                                    <span class="icon">
                                        <i class="icon icon-heart fs-40"></i>
                                    </span>
                                    <div class="content">
                                        <h3 class="caption fw-normal">{{ \App\Models\Setting::get('craft_value_1_title', 'Handcrafted with care') }}</h3>
                                        <p class="sub-text">{{ \App\Models\Setting::get('craft_value_1_text', 'Every piece made by skilled Irish artisans') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="wg-icon-image hover-img">
                                <div class="image img-style">
                                    <img class="lazyload" src="/images/ochaka/section/story-2.jpg" data-src="/images/ochaka/section/story-2.jpg" alt="">
                                </div>
                                <div class="box-icon">
                                    <span class="icon">
                                        <i class="icon icon-check-circle fs-40"></i>
                                    </span>
                                    <div class="content">
                                        <h3 class="caption fw-normal">{{ \App\Models\Setting::get('craft_value_2_title', 'Dublin hallmarked') }}</h3>
                                        <p class="sub-text">{{ \App\Models\Setting::get('craft_value_2_text', 'Guaranteed quality since 1637') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="wg-icon-image hover-img">
                                <div class="image img-style">
                                    <img class="lazyload" src="/images/ochaka/section/story-3.jpg" data-src="/images/ochaka/section/story-3.jpg" alt="">
                                </div>
                                <div class="box-icon">
                                    <span class="icon">
                                        <i class="icon icon-package fs-40"></i>
                                    </span>
                                    <div class="content">
                                        <h3 class="caption fw-normal">{{ \App\Models\Setting::get('craft_value_3_title', 'Gift-ready packaging') }}</h3>
                                        <p class="sub-text">{{ \App\Models\Setting::get('craft_value_3_text', 'Presented in our signature green box') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sw-dot-default tf-sw-pagination"></div>
                </div>
            </div>
        </section>
        <!-- /Brand Story -->
        <!-- CTA -->
        <section class="flat-spacing pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <h1 class="s-title mb-8">Visit Us or Get in Touch</h1>
                        @if($storeAddr)
                        <p class="h6 text-main mb-4">{{ $storeAddr }}</p>
                        @endif
                        @if($storePhone)
                        <p class="h6 mb-4"><a href="tel:{{ preg_replace('/\s+/', '', $storePhone) }}" class="link">{{ $storePhone }}</a></p>
                        @endif
                        <p class="h6 mb-4"><a href="mailto:{{ $storeEmail }}" class="link">{{ $storeEmail }}</a></p>
                        <a href="{{ route('shop.contact') }}" class="tf-btn animate-btn">
                            Contact Us
                            <i class="icon icon-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /CTA -->
@endsection
