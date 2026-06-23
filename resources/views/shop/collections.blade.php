@extends('shop.layouts.app')

@section('title', 'Collections — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Collections</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Collections</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Collections -->
        <section class="flat-spacing">
            <div class="container">
                <div class="tf-grid-layout lg-col-3 md-col-2">
                    @forelse($collections as $col)
                    <div class="box-image_V02 type-space-3 hover-img wow fadeInUp" data-wow-delay="{{ ($loop->index % 3) * 0.06 }}s">
                        <a href="{{ route('shop.collection', $col->slug) }}" class="box-image_image img-style">
                            @if($col->banner_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($col->banner_image) }}" data-src="{{ \Illuminate\Support\Facades\Storage::url($col->banner_image) }}" alt="{{ $col->name }}" class="lazyload">
                            @else
                                <img src="/images/ochaka/collections/cls-header-{{ ($loop->index % 2) + 1 }}.jpg" data-src="/images/ochaka/collections/cls-header-{{ ($loop->index % 2) + 1 }}.jpg" alt="{{ $col->name }}" class="lazyload">
                            @endif
                        </a>
                        <div class="box-image_content wow fadeInUp">
                            <a href="{{ route('shop.collection', $col->slug) }}" class="title link text-display-2 fw-medium text-white">
                                {{ $col->name }}
                            </a>
                            <span class="sub-text h6 text-white">{{ $col->products_count }} {{ \Illuminate\Support\Str::plural('product', $col->products_count) }}</span>
                            <a href="{{ route('shop.collection', $col->slug) }}" class="tf-btn btn-white animate-btn animate-dark">
                                Shop now
                                <i class="icon icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p class="h6 text-main">No collections available yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!-- /Collections -->
@endsection
