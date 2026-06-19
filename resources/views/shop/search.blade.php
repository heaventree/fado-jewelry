@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
@endphp

@section('title', $query ? 'Search: ' . $query . ' — ' . Setting::get('store_name', 'FADÓ Jewellery') : 'Search — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, follow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Search</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">{{ $query ? 'Results for "' . $query . '"' : 'Search' }}</h6></li>
            </ul>
        </div>
        <div class="form-search mt_20">
            <form action="{{ route('shop.search') }}" method="GET">
                <fieldset>
                    <input type="search" name="q" value="{{ $query }}" autofocus
                           placeholder="Search by name, style, or description…">
                </fieldset>
                <button type="submit" class="tf-btn btn-fill animate-btn">
                    <i class="icon icon-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">

        @if(!$query)
        {{-- No query --}}
        <div class="text-center py-5">
            <i class="icon icon-magnifying-glass" style="font-size:3.5rem; opacity:.3; display:block; margin-bottom:20px"></i>
            <h3 class="h3 fw-normal mb-12">What are you looking for?</h3>
            <p class="h6 text-main mb-32">Search across our full range of rings, pendants, earrings, and more.</p>
            <div class="d-flex gap-12 flex-wrap justify-content-center">
                @foreach(['Claddagh', 'Diamond ring', 'Sterling silver', 'Pendant', 'Gold earrings'] as $suggestion)
                <a href="{{ route('shop.search', ['q' => $suggestion]) }}" class="tf-btn style-line h6">
                    {{ $suggestion }}
                </a>
                @endforeach
            </div>
        </div>

        @elseif(strlen($query) < 2)
        {{-- Too short --}}
        <div class="text-center py-5">
            <p class="h6 text-main">Please enter at least 2 characters to search.</p>
        </div>

        @elseif($products->isEmpty())
        {{-- No results --}}
        <div class="text-center py-5">
            <i class="icon icon-magnifying-glass" style="font-size:3rem; opacity:.3; display:block; margin-bottom:16px"></i>
            <h3 class="h3 fw-normal mb-10">No results for "{{ $query }}"</h3>
            <p class="h6 text-main mb-28">Try a different search term, or browse our collections below.</p>
            <div class="d-flex gap-12 justify-content-center flex-wrap">
                <a href="{{ route('shop.jewellery') }}" class="tf-btn animate-btn">Browse All Jewellery</a>
                <a href="{{ route('shop.collections') }}" class="tf-btn style-line">View Collections</a>
            </div>
        </div>

        @else
        {{-- Results --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-12 mb-28 pb-20 border-bottom">
            <p class="h6 text-black">
                <strong>{{ number_format($total) }}</strong>
                {{ $total === 1 ? 'result' : 'results' }}
                for <em>"{{ $query }}"</em>
            </p>
            <a href="{{ route('shop.jewellery') }}" class="h6 link">← Browse all jewellery</a>
        </div>

        <div class="tf-grid-layout tf-col-4 gap-30 d-grid">
            @foreach($products as $product)
            @php
                $img    = $product->primaryImage;
                $from   = $product->variants->min('price_eur');
                $metals = $product->variants->pluck('metal.name')->filter()->unique()->take(3)->values();
            @endphp
            <div class="card-product">
                <div class="card-product-wrapper">
                    <a href="{{ route('shop.product', $product) }}" class="product-img">
                        @if($img)
                            <img class="lazyload img-product" data-src="{{ Storage::url($img->path) }}"
                                 src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}">
                        @else
                            <img class="img-product" src="/images/demo/product-placeholder.jpg" alt="{{ $product->name }}">
                        @endif
                    </a>
                    @if($metals->isNotEmpty())
                    <div class="list-product-btn absolute-2">
                        <div class="d-flex gap-4 flex-wrap">
                            @foreach($metals as $m)
                            <span class="h6 text-small bg-white text-main px-8 py-2 rounded">{{ $m }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-product-info">
                    <a href="{{ route('shop.product', $product) }}" class="title link fw-medium">
                        {{ $product->name }}
                    </a>
                    <span class="price current-price">
                        @if($from)
                            From {{ app(\App\Services\CurrencyService::class)->format((float)$from) }}
                        @else
                            <span class="h6 fw-normal text-main">Price on enquiry</span>
                        @endif
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="wg-pagination mt-40">
            {{ $products->links() }}
        </div>
        @endif

        @endif

    </div>
</section>

@endsection
