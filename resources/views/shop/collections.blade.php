@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'Collections — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Explore all FADÓ Jewellery collections — Claddagh, Trinity, High Crosses, The Garden Collection and more. Handcrafted Irish jewellery.')
@section('canonical', route('shop.collections'))

@section('content')

{{-- Hero banner --}}
<div style="background:var(--fado-deep-green); padding:64px 0; position:relative; overflow:hidden">
    <div style="position:absolute; inset:0; background:url('/images/ochaka/slider/slider-23.jpg') center/cover no-repeat; opacity:.15; pointer-events:none"></div>
    <div class="container position-relative" style="z-index:2">
        <nav aria-label="breadcrumb" style="margin-bottom:20px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.home') }}" style="color:rgba(255,255,255,.55); text-decoration:none">Home</a></li>
                <li style="color:rgba(255,255,255,.3)">/</li>
                <li style="color:rgba(255,255,255,.9)">Collections</li>
            </ol>
        </nav>
        <h1 style="font-family:Georgia,serif; font-size:clamp(2rem,4vw,3rem); color:#fff; font-weight:400; margin-bottom:16px">
            Our Collections
        </h1>
        <p style="color:rgba(255,255,255,.72); max-width:560px; font-size:1rem; line-height:1.75; margin:0">
            From the ancient symbolism of the Claddagh to the delicate wildflowers of the Jewellery Garden — explore every facet of FADÓ Irish jewellery.
        </p>
    </div>
</div>

{{-- Collections grid --}}
<section style="padding:64px 0 80px; background:var(--fado-near-white)">
    <div class="container">

        @if($collections->isEmpty())
            <div class="text-center py-5">
                <p style="color:var(--fado-warm-grey)">Collections are being added. Check back soon.</p>
            </div>
        @else

        {{-- Group: Irish Heritage --}}
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
            ['label' => 'Irish Heritage', 'items' => $heritage, 'imgs' => ['/images/ochaka/section/box-image-8.jpg', '/images/ochaka/section/box-image-9.jpg', '/images/ochaka/slider/slider-22.jpg', '/images/ochaka/products/jewelry/product-1.jpg', '/images/ochaka/products/jewelry/product-5.jpg', '/images/ochaka/products/jewelry/product-9.jpg', '/images/ochaka/products/jewelry/product-13.jpg', '/images/ochaka/products/jewelry/product-17.jpg']],
            ['label' => 'Fine Collections', 'items' => $fine,  'imgs' => ['/images/ochaka/products/jewelry/product-21.jpg', '/images/ochaka/products/jewelry/product-3.jpg']],
            ['label' => 'The Jewellery Garden', 'items' => $garden, 'imgs' => ['/images/ochaka/products/jewelry/product-7.jpg', '/images/ochaka/products/jewelry/product-11.jpg', '/images/ochaka/products/jewelry/product-15.jpg', '/images/ochaka/products/jewelry/product-19.jpg']],
            ['label' => 'More Collections', 'items' => $other, 'imgs' => []],
        ] as $group)
        @if($group['items']->isNotEmpty())
        <div style="margin-bottom:56px">
            <h2 style="font-family:Georgia,serif; font-size:1.5rem; font-weight:400; color:var(--fado-deep-green);
                       padding-bottom:16px; border-bottom:1px solid var(--fado-cream); margin-bottom:28px">
                {{ $group['label'] }}
            </h2>
            <div class="row g-4">
                @foreach($group['items'] as $i => $col)
                @php $fallback = $group['imgs'][$i % count($group['imgs'])] ?? '/images/ochaka/products/jewelry/product-1.jpg'; @endphp
                <div class="col-sm-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($i % 3) * .06 }}s">
                    <a href="{{ route('shop.collection', $col->slug) }}"
                       class="d-block fado-collection-banner text-decoration-none"
                       style="position:relative; border-radius:4px; overflow:hidden; aspect-ratio:4/3">
                        @if($col->banner_image)
                            <img src="{{ Storage::url($col->banner_image) }}" alt="{{ $col->name }}"
                                 style="width:100%; height:100%; object-fit:cover; transition:transform .5s ease">
                        @else
                            <img src="{{ $fallback }}" alt="{{ $col->name }}"
                                 style="width:100%; height:100%; object-fit:cover; object-position:center top; transition:transform .5s ease">
                        @endif
                        <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(4,71,5,.82) 0%, rgba(4,71,5,.05) 65%)"></div>
                        <div style="position:absolute; bottom:0; left:0; padding:20px 24px">
                            <h3 style="font-family:Georgia,serif; font-size:1.25rem; color:#fff; font-weight:400; margin-bottom:6px">
                                {{ $col->name }}
                            </h3>
                            <span style="font-size:.75rem; font-weight:600; color:var(--fado-green-light);
                                         letter-spacing:.08em; text-transform:uppercase">
                                Shop collection →
                            </span>
                        </div>
                    </a>
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

@push('css')
<style>
.fado-collection-banner:hover img { transform: scale(1.05); }
</style>
@endpush
