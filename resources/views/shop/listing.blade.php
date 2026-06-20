{{--
    Source: resources/theme-reference/shop-left-sidebar.html (lines 475-1682, 3454-3461)
        — page title/breadcrumbs, left sidebar widget-facet filters, toolbar, 3-col grid wrapper, pagination.
    Source: resources/theme-reference/shop-hover-02.html (lines 1424-1538)
        — product card hover markup used in place of the left-sidebar reference's card
          (bottom slide-up "Quick View" bar instead of an inline quick-view icon), per explicit instruction.
    Deviations from the reference (data-driven, not restructuring):
        - Sidebar facets "Availability" and "Size" omitted — not in FADO's filter spec
          (category, collection, metal, second metal/finish, gemstone, price, colour) and would
          require fabricated counts/options.
        - "Compare" action icon omitted — no compare feature exists in this build.
        - Price slider replaced with two real number inputs (min/max) — same widget-facet container,
          Ochaka has no dual-slider component wired to a backend in this template set.
        - Sort dropdown items are real links carrying the existing query string + new sort value,
          not JS-only demo links.
--}}
@extends('shop.layouts.app')

@section('title', $pageTitle . ' — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<section class="s-page-title"@if($bannerImage) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($bannerImage) }}'); background-size:cover; background-position:center;"@endif>
    <div class="container">
        <div class="content">
            <h1 class="title-page">{{ $pageTitle }}</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                @foreach($breadcrumbs as $crumb)
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        @if(!$loop->last && isset($crumb['url']))
                            <a href="{{ $crumb['url'] }}" class="h6 link">{{ $crumb['label'] }}</a>
                        @else
                            <h6 class="current-page fw-normal">{{ $crumb['label'] }}</h6>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
{{-- /Page Title --}}

<div class="flat-spacing">
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                <div class="canvas-sidebar sidebar-filter canvas-filter left">
                    <div class="canvas-wrapper">
                        <div class="canvas-header d-xl-none">
                            <span class="title h3 fw-medium">Filter</span>
                            <span class="icon-close link icon-close-popup fs-24 close-filter"></span>
                        </div>
                        <form class="canvas-body" method="GET" action="{{ url()->current() }}">
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#category" role="button" data-bs-toggle="collapse"
                                    aria-expanded="true" aria-controls="category">
                                    <span class="h4 fw-semibold">Category</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="category" class="collapse show">
                                    <ul class="collapse-body filter-group-check group-category">
                                        @foreach($topCategories as $cat)
                                            <li class="list-item">
                                                <a href="{{ route('shop.category', $cat->slug) }}"
                                                   class="link h6 @if($activeCategory && $activeCategory->id === $cat->id) fw-semibold @endif">
                                                    {{ $cat->name }}<span class="count">{{ $cat->products_count ?? $cat->products()->where('is_active', true)->count() }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#collection" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="collection">
                                    <span class="h4 fw-semibold">Collection</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="collection" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($allCollections as $col)
                                            <li class="list-item">
                                                <input type="checkbox" name="collections[]" class="tf-check" id="collection-{{ $col->slug }}"
                                                    value="{{ $col->slug }}" @checked(in_array($col->slug, $filters['collections'] ?? []))>
                                                <label for="collection-{{ $col->slug }}" class="label">{{ $col->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#price" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="price">
                                    <span class="h4 fw-semibold">Price</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="price" class="collapse show">
                                    <div class="collapse-body widget-price filter-price">
                                        <div class="box-value-price">
                                            <span class="h6 text-main">Price (€):</span>
                                            <div class="price-box">
                                                <input type="number" min="0" name="price_min" class="price-val" value="{{ $filters['price_min'] ?: '' }}" placeholder="Min">
                                                <span>-</span>
                                                <input type="number" min="0" name="price_max" class="price-val" value="{{ $filters['price_max'] ?: '' }}" placeholder="Max">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#metal" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="metal">
                                    <span class="h4 fw-semibold">Metal</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="metal" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($metals as $metal)
                                            <li class="list-item">
                                                <input type="checkbox" name="metals[]" class="tf-check" id="metal-{{ $metal->slug }}"
                                                    value="{{ $metal->slug }}" @checked(in_array($metal->slug, $filters['metals'] ?? []))>
                                                <label for="metal-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#second-metal" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="second-metal">
                                    <span class="h4 fw-semibold">Second Metal / Finish</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="second-metal" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($metals as $metal)
                                            <li class="list-item">
                                                <input type="checkbox" name="second_metals[]" class="tf-check" id="second-metal-{{ $metal->slug }}"
                                                    value="{{ $metal->slug }}" @checked(in_array($metal->slug, $filters['second_metals'] ?? []))>
                                                <label for="second-metal-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#gemstone" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="gemstone">
                                    <span class="h4 fw-semibold">Gemstone</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="gemstone" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($gemstones as $gem)
                                            <li class="list-item">
                                                <input type="checkbox" name="gemstones[]" class="tf-check" id="gemstone-{{ $gem->slug }}"
                                                    value="{{ $gem->slug }}" @checked(in_array($gem->slug, $filters['gemstones'] ?? []))>
                                                <label for="gemstone-{{ $gem->slug }}" class="label">{{ $gem->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @if($colours->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#colour" role="button" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="colour">
                                    <span class="h4 fw-semibold">Colour</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="colour" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($colours as $colour)
                                            <li class="list-item">
                                                <input type="checkbox" name="colours[]" class="tf-check" id="colour-{{ \Illuminate\Support\Str::slug($colour) }}"
                                                    value="{{ $colour }}" @checked(in_array($colour, $filters['colours'] ?? []))>
                                                <label for="colour-{{ \Illuminate\Support\Str::slug($colour) }}" class="label">{{ $colour }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            <div class="canvas-bottom">
                                <button type="submit" class="tf-btn btn-fill animate-btn justify-content-center w-100 mb-8">Apply Filters</button>
                                <a id="reset-filter" href="{{ url()->current() }}" class="tf-btn btn-reset">Reset Filters</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="tf-shop-control">
                    <div class="tf-control-filter d-xl-none">
                        <button type="button" id="filterShop" class="tf-btn-filter">
                            <span class="icon icon-filter"></span><span class="text">Filter</span>
                        </button>
                    </div>
                    <div class="tf-control-sorting">
                        <p class="h6 d-none d-lg-block">Sort by:</p>
                        <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                            <div class="btn-select">
                                <span class="text-sort-value">
                                    @switch($filters['sort'])
                                        @case('price_asc') Price, low to high @break
                                        @case('price_desc') Price, high to low @break
                                        @case('name_asc') Alphabetically, A-Z @break
                                        @default Newest @break
                                    @endswitch
                                </span>
                                <span class="icon icon-caret-down"></span>
                            </div>
                            <div class="dropdown-menu">
                                @foreach(['newest' => 'Newest', 'name_asc' => 'Alphabetically, A-Z', 'price_asc' => 'Price, low to high', 'price_desc' => 'Price, high to low'] as $value => $label)
                                    <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['sort', 'page']), ['sort' => $value])) }}"
                                       class="select-item @if($filters['sort'] === $value) active @endif" data-sort-value="{{ $value }}">
                                        <span class="text-value-item">{{ $label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-control-shop gridLayout-wrapper">
                    <div class="meta-filter-shop">
                        <div class="count-text">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</div>
                    </div>
                    <div class="wrapper-shop tf-grid-layout tf-col-3" id="gridLayout">
                        @forelse($products as $product)
                            @php
                                $img  = $product->primaryImage?->path;
                                $img2 = $product->images->skip(1)->first()?->path;
                                $variantForSale = $product->variants->first(fn ($v) => $v->isOnSale());
                                $from = $product->variants->min('price_eur');
                            @endphp
                            <div class="card-product grid">
                                <div class="card-product_wrapper">
                                    <a href="{{ route('shop.product', $product) }}" class="product-img">
                                        @if($img)
                                            <img class="lazyload img-product" src="{{ asset($img) }}" data-src="{{ asset($img) }}" alt="{{ $product->name }}">
                                            @if($img2)
                                            <img class="lazyload img-hover" src="{{ asset($img2) }}" data-src="{{ asset($img2) }}" alt="{{ $product->name }}">
                                            @endif
                                        @else
                                            <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                    <ul class="product-action_list">
                                        <li>
                                            <a href="{{ route('shop.product', $product) }}" class="hover-tooltip tooltip-left box-icon">
                                                <span class="icon icon-shopping-cart-simple"></span>
                                                <span class="tooltip">Add to cart</span>
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
                                    @if($product->is_bestseller)
                                    <ul class="product-badge_list">
                                        <li class="product-badge_item h6 hot">Bestseller</li>
                                    </ul>
                                    @elseif($variantForSale)
                                    <ul class="product-badge_list">
                                        <li class="product-badge_item flash-sale">Sale</li>
                                    </ul>
                                    @endif
                                    <div class="product-action_bot">
                                        <a href="{{ route('shop.product', $product) }}" class="tf-btn btn-white animate-btn animate-dark">
                                            Quick View
                                            <i class="icon icon-view"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-product_info">
                                    <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
                                    <div class="price-wrap">
                                        @if($variantForSale)
                                            <span class="price-old h6 fw-normal">€{{ number_format((float)$variantForSale->price_eur, 2) }}</span>
                                            <span class="price-new h6">€{{ number_format((float)$variantForSale->sale_price_eur, 2) }}</span>
                                        @elseif($from)
                                            <span class="price-new h6">From €{{ number_format((float)$from, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="h5">No products match these filters.</p>
                        @endforelse
                    </div>
                    @if($products->lastPage() > 1)
                    <div class="wd-full wg-pagination">
                        @if(!$products->onFirstPage())
                            <a href="{{ $products->previousPageUrl() }}" class="pagination-item h6 direct"><i class="icon icon-caret-left"></i></a>
                        @endif
                        @for($page = 1; $page <= $products->lastPage(); $page++)
                            @if($page === $products->currentPage())
                                <span class="pagination-item h6 active">{{ $page }}</span>
                            @else
                                <a href="{{ $products->url($page) }}" class="pagination-item h6">{{ $page }}</a>
                            @endif
                        @endfor
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="pagination-item h6 direct"><i class="icon icon-caret-right"></i></a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /Section Product --}}

@endsection
