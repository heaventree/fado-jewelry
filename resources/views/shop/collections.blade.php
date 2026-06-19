@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'Collections — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Explore all FADÓ Jewellery collections — Claddagh, Trinity, High Crosses, The Garden Collection and more. Handcrafted Irish jewellery.')
@section('canonical', route('shop.collections'))

@section('content')

{{-- Page Title — Ochaka s-page-title --}}
<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Our Collections</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Collections</h6></li>
            </ul>
        </div>
    </div>
</section>

{{-- Collections grid --}}
<section class="flat-spacing">
    <div class="container">

        @if($collections->isEmpty())
            <div class="text-center py-5">
                <p class="h5 fw-normal text-main">Collections are being added. Check back soon.</p>
            </div>
        @else

        @php
            $heritageSlugs = ['claddagh','corrib-claddagh','trinity','an-ri','high-crosses','newgrange','irish-folklore','shamrock'];
            $fineSlugs     = ['livia','sheelin'];
            $gardenSlugs   = ['the-garden-collection','garden-daisy','garden-wild-daisy','garden-bluebell','garden-forget-me-not','garden-butterfly','garden-bee'];

            $heritage = $collections->filter(fn($c) => in_array($c->slug, $heritageSlugs));
            $fine     = $collections->filter(fn($c) => in_array($c->slug, $fineSlugs));
            $garden   = $collections->filter(fn($c) => in_array($c->slug, $gardenSlugs));
            $other    = $collections->filter(fn($c) => !in_array($c->slug, array_merge($heritageSlugs, $fineSlugs, $gardenSlugs)));
        @endphp

        @foreach([
            ['label' => 'Irish Heritage',       'items' => $heritage],
            ['label' => 'Fine Collections',     'items' => $fine],
            ['label' => 'The Jewellery Garden', 'items' => $garden],
            ['label' => 'More Collections',     'items' => $other],
        ] as $group)
        @if($group['items']->isNotEmpty())
        <div class="mb_56">
            <div class="sect-title mb_30">
                <h2 class="title">{{ $group['label'] }}</h2>
            </div>
            <div class="tf-grid-layout lg-col-3 md-col-2">
                @foreach($group['items'] as $i => $col)
                <div class="box-image_V02 type-space-3 hover-img wow fadeInUp" data-wow-delay="{{ ($i % 3) * .06 }}s">
                    <a href="{{ route('shop.collection', $col->slug) }}" class="box-image_image img-style">
                        @if($col->banner_image)
                            <img src="{{ Storage::url($col->banner_image) }}" alt="{{ $col->name }}" class="lazyload">
                        @else
                            <img src="/images/ochaka/products/jewelry/product-{{ (($i % 8) + 1) }}.jpg"
                                 alt="{{ $col->name }}" class="lazyload">
                        @endif
                    </a>
                    <div class="box-image_content">
                        <a href="{{ route('shop.collection', $col->slug) }}" class="tf-btn btn-white animate-btn animate-dark">
                            <span class="h5 fw-medium">{{ $col->name }}</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach

        @endif
    </div>
</section>

@endsection
