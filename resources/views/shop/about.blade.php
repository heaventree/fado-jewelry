@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'About Us — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Learn about FADÓ Jewellery — fine Irish jewellery handcrafted in the Irish tradition. Our story, values and collections.')
@section('canonical', route('shop.about'))

@section('content')

{{-- Page Title — Ochaka s-page-title --}}
<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">About Us</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">About Us</h6></li>
            </ul>
        </div>
    </div>
</section>

{{-- Intro — Ochaka s-intro flat-spacing --}}
<section class="s-intro flat-spacing">
    <div class="container">
        <p class="brand-name">FADÓ Jewellery</p>
        <div class="box-intro">
            <h4 class="slogan fw-normal">FINE IRISH JEWELLERY, CRAFTED WITH HEART</h4>
            <p class="intro-text">
                FADÓ — Irish for "long ago" — creates jewellery that carries the spirit of Ireland wherever you go.
                Every piece draws on centuries of Irish craft: from the ancient Claddagh tradition to the geometric
                intricacies of High Cross stonework and the wildflower beauty of the Irish countryside.
            </p>
        </div>
    </div>
</section>

{{-- Story — image + text --}}
<section class="flat-spacing bg-rose-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="img-style hover-img">
                    <img src="/images/ochaka/slider/slider-22.jpg" data-src="/images/ochaka/slider/slider-22.jpg"
                         alt="FADÓ Jewellery atelier" class="lazyload" style="width:100%;height:auto;display:block">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="sect-title mb_30">
                    <h1 class="s-title mb-8">Rooted in Irish Tradition</h1>
                    <p class="s-subtitle h6">Made for today, made to last a lifetime</p>
                </div>
                <p class="h6 fw-normal text-main mb_16">
                    FADÓ Jewellery was born from a love of Ireland's rich cultural heritage. Each piece in our collection
                    draws on centuries of Irish craft — from the ancient Claddagh tradition to the geometric intricacies
                    of High Cross stonework and the wildflower beauty of the Irish countryside.
                </p>
                <p class="h6 fw-normal text-main mb_16">
                    Each piece is individually crafted in sterling silver, gold, and platinum, then hallmarked
                    by the Assay Office of Ireland — your guarantee of authentic Irish quality.
                </p>
                <p class="h6 fw-normal text-main mb_30">
                    Whether you are celebrating an anniversary, searching for the perfect gift, or simply
                    treating yourself to something beautiful, our jewellery is made to be worn, treasured,
                    and passed down.
                </p>
                <a href="{{ route('shop.jewellery') }}" class="tf-btn btn-fill animate-btn">
                    Explore Our Collections <i class="icon icon-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Values strip --}}
<section class="flat-spacing">
    <div class="container">
        <div class="sect-title text-center mb_30">
            <h1 class="s-title mb-8">Our Promise</h1>
            <p class="s-subtitle h6">What makes FADÓ jewellery different</p>
        </div>
        <div class="tf-grid-layout lg-col-4 md-col-2">
            @foreach([
                ['icon' => 'icon-medal',      'title' => 'Hallmark Certified',  'body' => 'Every piece is assayed and hallmarked by the Assay Office of Ireland, guaranteeing authentic Irish gold and silver.'],
                ['icon' => 'icon-hand-heart', 'title' => 'Handcrafted Quality', 'body' => 'Made by skilled craftspeople using time-honoured techniques. Each piece is finished by hand and inspected before leaving our studio.'],
                ['icon' => 'icon-leaf',       'title' => 'Irish Heritage',      'body' => 'Our designs draw directly from Irish mythology, landscape, and tradition — the Claddagh, Trinity Knot, High Cross, and more.'],
                ['icon' => 'icon-gift',       'title' => 'Gift Ready',          'body' => 'Every order arrives in our signature FADÓ gift box — ready to give as it arrives, with no extra wrapping needed.'],
            ] as $v)
            <div class="wg-icon-box">
                <div class="icon-box">
                    <span class="icon"><i class="icon {{ $v['icon'] }}"></i></span>
                </div>
                <div class="content">
                    <h3 class="caption fw-normal">{{ $v['title'] }}</h3>
                    <p class="sub-text">{{ $v['body'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Collections gallery --}}
<section class="flat-spacing bg-rose-white">
    <div class="container">
        <div class="sect-title text-center mb_30">
            <h1 class="s-title mb-8">Our Collections</h1>
            <p class="s-subtitle h6">Stories told in silver and gold</p>
        </div>
        <div class="tf-grid-layout lg-col-3 md-col-2">
            @foreach([
                ['title' => 'Claddagh',             'sub' => 'Love, loyalty & friendship', 'slug' => 'claddagh'],
                ['title' => 'High Crosses',          'sub' => 'Ancient Celtic stonework',   'slug' => 'high-crosses'],
                ['title' => 'The Garden Collection', 'sub' => 'Flora & fauna of Ireland',   'slug' => 'the-garden-collection'],
                ['title' => 'Trinity Knot',          'sub' => 'Eternal Irish symbol',       'slug' => 'trinity'],
                ['title' => 'Newgrange',             'sub' => 'Prehistoric heritage',       'slug' => 'newgrange'],
                ['title' => 'Irish Folklore',        'sub' => 'Myth & legend in silver',    'slug' => 'irish-folklore'],
            ] as $col)
            <div class="box-image_V02 type-space-3 hover-img">
                <a href="{{ route('shop.collection', $col['slug']) }}" class="box-image_image img-style">
                    <img src="/images/ochaka/slider/slider-22.jpg" data-src="/images/ochaka/slider/slider-22.jpg"
                         alt="{{ $col['title'] }}" class="lazyload">
                </a>
                <div class="box-image_content">
                    <a href="{{ route('shop.collection', $col['slug']) }}" class="tf-btn btn-white animate-btn animate-dark">
                        <span class="h5 fw-medium">{{ $col['title'] }}</span>
                    </a>
                    <p class="h6 fw-normal text-main mt-2">{{ $col['sub'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt_30">
            <a href="{{ route('shop.collections') }}" class="tf-btn btn-line animate-btn">
                View All Collections <i class="icon icon-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- Consultation CTA --}}
@if(Setting::get('consultation_enabled', '1'))
<section class="flat-spacing bg-black s-banner-shop">
    <div class="container">
        <div class="text-center">
            <h2 class="title text-white mb-8">Book a Personal Consultation</h2>
            <p class="s-subtitle h6 text-white mb_30">
                {{ Setting::get('consultation_intro_text', 'Book a private consultation with our jewellery specialists — in-store, by phone, or video call.') }}
            </p>
            <a href="{{ route('shop.contact') }}#consultation" class="tf-btn btn-white animate-btn animate-dark">
                Book an Appointment <i class="icon icon-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

@endsection
