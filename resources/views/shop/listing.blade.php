@extends('shop.layouts.app')
@php
    use Illuminate\Support\Str;
    use App\Models\Setting;
    $storeName = Setting::get('store_name', 'FADÓ Jewellery');
    $metaDesc  = $pageSubtitle ?: 'Browse our ' . strtolower($pageTitle) . ' — handcrafted Irish jewellery in sterling silver, gold and platinum.';
@endphp

@section('title', $pageTitle . ' — ' . $storeName)
@section('meta_description', Str::limit(strip_tags($metaDesc), 155))

@section('content')

{{-- Page Title — Ochaka s-page-title --}}
<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">{{ $pageTitle }}</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                @foreach($breadcrumbs as $crumb)
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    @if(!$loop->last)
                    <li><a href="{{ $crumb['url'] }}" class="h6 link">{{ $crumb['label'] }}</a></li>
                    @else
                    <li><h6 class="current-page fw-normal">{{ $crumb['label'] }}</h6></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- Active filter pills                                                        --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
@php
    $hasFilters = !empty($filters['metals'])   || !empty($filters['gemstones'])
               || !empty($filters['collections']) || !empty($filters['second_metals'])
               || !empty($filters['colours'])
               || $filters['price_min'] > 0 || $filters['price_max'] > 0
               || $filters['search'];
@endphp
@if($hasFilters)
<div class="meta-filter-bar">
    <div class="container d-flex align-items-center gap-2 flex-wrap py-2">
        <span class="h6 fw-semibold">Filters:</span>

        @foreach($filters['metals'] as $slug)
            @php
                $metal     = $metals->firstWhere('slug', $slug);
                $newMetals = array_values(array_filter($filters['metals'], fn($s) => $s !== $slug));
                $removeUrl = url()->current() . '?' . http_build_query(array_filter([
                    'metals'        => $newMetals                   ?: null,
                    'gemstones'     => $filters['gemstones']        ?: null,
                    'collections'   => $filters['collections']      ?: null,
                    'second_metals' => $filters['second_metals']    ?: null,
                    'colours'       => $filters['colours']          ?: null,
                    'price_min'     => $filters['price_min']        ?: null,
                    'price_max'     => $filters['price_max']        ?: null,
                    'search'        => $filters['search']           ?: null,
                    'sort'          => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
                ]));
            @endphp
            @if($metal)
            <a href="{{ $removeUrl }}" class="filter-tag h6">{{ $metal->name }} <i class="icon icon-close fs-10"></i></a>
            @endif
        @endforeach

        @foreach($filters['gemstones'] as $slug)
            @php
                $gem     = $gemstones->firstWhere('slug', $slug);
                $newGems = array_values(array_filter($filters['gemstones'], fn($s) => $s !== $slug));
                $removeUrl = url()->current() . '?' . http_build_query(array_filter([
                    'metals'        => $filters['metals']           ?: null,
                    'gemstones'     => $newGems                     ?: null,
                    'collections'   => $filters['collections']      ?: null,
                    'second_metals' => $filters['second_metals']    ?: null,
                    'colours'       => $filters['colours']          ?: null,
                    'price_min'     => $filters['price_min']        ?: null,
                    'price_max'     => $filters['price_max']        ?: null,
                    'search'        => $filters['search']           ?: null,
                    'sort'          => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
                ]));
            @endphp
            @if($gem)
            <a href="{{ $removeUrl }}" class="filter-tag h6">{{ $gem->name }} <i class="icon icon-close fs-10"></i></a>
            @endif
        @endforeach

        @foreach($filters['collections'] as $slug)
            @php
                $col       = $allCollections->firstWhere('slug', $slug);
                $newCols   = array_values(array_filter($filters['collections'], fn($s) => $s !== $slug));
                $removeUrl = url()->current() . '?' . http_build_query(array_filter([
                    'metals'        => $filters['metals']        ?: null,
                    'gemstones'     => $filters['gemstones']     ?: null,
                    'collections'   => $newCols                  ?: null,
                    'second_metals' => $filters['second_metals'] ?: null,
                    'colours'       => $filters['colours']       ?: null,
                    'price_min'     => $filters['price_min']     ?: null,
                    'price_max'     => $filters['price_max']     ?: null,
                    'search'        => $filters['search']        ?: null,
                    'sort'          => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
                ]));
            @endphp
            @if($col)
            <a href="{{ $removeUrl }}" class="filter-tag h6">{{ $col->name }} <i class="icon icon-close fs-10"></i></a>
            @endif
        @endforeach

        @foreach($filters['second_metals'] as $slug)
            @php
                $sm        = $metals->firstWhere('slug', $slug);
                $newSMs    = array_values(array_filter($filters['second_metals'], fn($s) => $s !== $slug));
                $removeUrl = url()->current() . '?' . http_build_query(array_filter([
                    'metals'        => $filters['metals']        ?: null,
                    'gemstones'     => $filters['gemstones']     ?: null,
                    'collections'   => $filters['collections']   ?: null,
                    'second_metals' => $newSMs                   ?: null,
                    'colours'       => $filters['colours']       ?: null,
                    'price_min'     => $filters['price_min']     ?: null,
                    'price_max'     => $filters['price_max']     ?: null,
                    'search'        => $filters['search']        ?: null,
                    'sort'          => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
                ]));
            @endphp
            @if($sm)
            <a href="{{ $removeUrl }}" class="filter-tag h6">Finish: {{ $sm->name }} <i class="icon icon-close fs-10"></i></a>
            @endif
        @endforeach

        @foreach($filters['colours'] as $colour)
            @php
                $newColours = array_values(array_filter($filters['colours'], fn($c) => $c !== $colour));
                $removeUrl  = url()->current() . '?' . http_build_query(array_filter([
                    'metals'        => $filters['metals']        ?: null,
                    'gemstones'     => $filters['gemstones']     ?: null,
                    'collections'   => $filters['collections']   ?: null,
                    'second_metals' => $filters['second_metals'] ?: null,
                    'colours'       => $newColours               ?: null,
                    'price_min'     => $filters['price_min']     ?: null,
                    'price_max'     => $filters['price_max']     ?: null,
                    'search'        => $filters['search']        ?: null,
                    'sort'          => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
                ]));
            @endphp
            <a href="{{ $removeUrl }}" class="filter-tag h6">Colour: {{ $colour }} <i class="icon icon-close fs-10"></i></a>
        @endforeach

        @if($filters['price_min'] > 0 || $filters['price_max'] > 0)
        <span class="filter-tag h6">€{{ $filters['price_min'] ?: '0' }} – €{{ $filters['price_max'] ?: '∞' }}</span>
        @endif

        <a href="{{ url()->current() }}" class="tf-btn-line ms-auto h6">Clear all filters</a>
    </div>
</div>
@endif


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- Main: sidebar + product grid — Ochaka canvas-sidebar + tf-grid-layout     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="flat-spacing">
    <div class="container">
        <div class="row">

            {{-- ── FILTER SIDEBAR — Ochaka widget-facet structure ──────────── --}}
            <div class="col-xl-3 d-none d-xl-block">
                <div class="canvas-sidebar sidebar-filter left sticky-top" style="top:24px; position:sticky">
                    <div class="canvas-body">
                        <form method="GET" action="{{ url()->current() }}" id="filterForm">

                            {{-- Preserve sort & search when filtering --}}
                            @if($filters['sort'] !== 'newest')
                                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                            @endif

                            {{-- Search --}}
                            <div class="widget-facet">
                                <div class="facet-title">
                                    <span class="h4 fw-semibold">Search</span>
                                </div>
                                <div class="collapse-body">
                                    <div class="filter-search-field">
                                        <input type="text" name="search" value="{{ $filters['search'] }}"
                                               placeholder="Search jewellery…" class="tf-field-input h6">
                                        <button type="submit" class="btn-search-field">
                                            <i class="icon icon-magnifying-glass"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Category quick-links — Ochaka group-category --}}
                            @if($topCategories->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-category" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="flt-category">
                                    <span class="h4 fw-semibold">Jewellery Type</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-category" class="collapse show">
                                    <ul class="collapse-body filter-group-check group-category">
                                        <li class="list-item">
                                            <a href="{{ route('shop.jewellery') }}"
                                               class="link h6 {{ is_null($activeCategory) && is_null($activeCollection) ? 'active fw-semibold' : '' }}">
                                                All Jewellery
                                            </a>
                                        </li>
                                        @foreach($topCategories as $cat)
                                        <li class="list-item">
                                            <a href="{{ route('shop.category', $cat->slug) }}"
                                               class="link h6 {{ optional($activeCategory)->id === $cat->id ? 'active fw-semibold' : '' }}">
                                                {{ $cat->name }}
                                            </a>
                                            @if($cat->children->isNotEmpty() && optional($activeCategory)->id === $cat->id)
                                            <ul class="sub-list mt-1 ps-3">
                                                @foreach($cat->children as $child)
                                                <li class="list-item">
                                                    <a href="{{ route('shop.category', $child->slug) }}"
                                                       class="link h6 {{ optional($activeCategory)->id === $child->id ? 'active fw-semibold' : '' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Metal filter —  Ochaka tf-check + label --}}
                            @if($metals->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-metal" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="flt-metal">
                                    <span class="h4 fw-semibold">Metal</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-metal" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($metals as $metal)
                                        <li class="list-item">
                                            <input type="checkbox" name="metals[]" value="{{ $metal->slug }}"
                                                   class="tf-check" id="metal-{{ $metal->slug }}"
                                                   {{ in_array($metal->slug, $filters['metals']) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <label for="metal-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Gemstone filter --}}
                            @if($gemstones->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-gem" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="flt-gem">
                                    <span class="h4 fw-semibold">Gemstone</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-gem" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($gemstones as $gem)
                                        <li class="list-item">
                                            <input type="checkbox" name="gemstones[]" value="{{ $gem->slug }}"
                                                   class="tf-check" id="gem-{{ $gem->slug }}"
                                                   {{ in_array($gem->slug, $filters['gemstones']) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <label for="gem-{{ $gem->slug }}" class="label">{{ $gem->name }}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Collection filter --}}
                            @if(is_null($activeCollection) && $allCollections->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-col" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="flt-col">
                                    <span class="h4 fw-semibold">Collection</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-col" class="collapse show">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($allCollections as $col)
                                        <li class="list-item">
                                            <input type="checkbox" name="collections[]" value="{{ $col->slug }}"
                                                   class="tf-check" id="col-{{ $col->slug }}"
                                                   {{ in_array($col->slug, $filters['collections']) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <label for="col-{{ $col->slug }}" class="label">{{ $col->name }}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Second metal / finish filter --}}
                            @if($metals->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-finish" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="flt-finish">
                                    <span class="h4 fw-semibold">Second Metal / Finish</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-finish" class="collapse">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($metals as $metal)
                                        <li class="list-item">
                                            <input type="checkbox" name="second_metals[]" value="{{ $metal->slug }}"
                                                   class="tf-check" id="sm-{{ $metal->slug }}"
                                                   {{ in_array($metal->slug, $filters['second_metals']) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <label for="sm-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Colour filter --}}
                            @if($colours->isNotEmpty())
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-colour" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="flt-colour">
                                    <span class="h4 fw-semibold">Colour</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-colour" class="collapse">
                                    <ul class="collapse-body filter-group-check current-scrollbar">
                                        @foreach($colours as $colour)
                                        <li class="list-item">
                                            <input type="checkbox" name="colours[]" value="{{ $colour }}"
                                                   class="tf-check" id="clr-{{ Str::slug($colour) }}"
                                                   {{ in_array($colour, $filters['colours']) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filterForm').submit()">
                                            <label for="clr-{{ Str::slug($colour) }}" class="label">{{ $colour }}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            {{-- Price range --}}
                            <div class="widget-facet">
                                <div class="facet-title" data-bs-target="#flt-price" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="flt-price">
                                    <span class="h4 fw-semibold">Price (EUR)</span>
                                    <span class="icon icon-caret-down fs-20"></span>
                                </div>
                                <div id="flt-price" class="collapse show">
                                    <div class="collapse-body widget-price filter-price">
                                        <div class="price-val-range" id="price-value-range" data-min="{{ $filters['price_min'] ?: 0 }}" data-max="{{ $filters['price_max'] ?: 5000 }}"></div>
                                        <div class="box-value-price">
                                            <span class="h6 text-main">Price:</span>
                                            <div class="price-box">
                                                <div class="price-val h6" id="price-min-value">€{{ $filters['price_min'] ?: 0 }}</div>
                                                <span class="h6">–</span>
                                                <div class="price-val h6" id="price-max-value">€{{ $filters['price_max'] ?: '5,000+' }}</div>
                                            </div>
                                        </div>
                                        {{-- Hidden inputs for form submit --}}
                                        <input type="hidden" name="price_min" id="priceMinInput" value="{{ $filters['price_min'] ?: '' }}">
                                        <input type="hidden" name="price_max" id="priceMaxInput" value="{{ $filters['price_max'] ?: '' }}">
                                        <div class="mt_12 d-flex gap-2">
                                            <input type="number" id="priceMinField" placeholder="Min €" min="0"
                                                   value="{{ $filters['price_min'] ?: '' }}"
                                                   class="tf-field-input h6" style="width:50%"
                                                   onchange="document.getElementById('priceMinInput').value=this.value">
                                            <input type="number" id="priceMaxField" placeholder="Max €" min="0"
                                                   value="{{ $filters['price_max'] ?: '' }}"
                                                   class="tf-field-input h6" style="width:50%"
                                                   onchange="document.getElementById('priceMaxInput').value=this.value">
                                        </div>
                                        <button type="submit" class="tf-btn animate-btn type-small mt_12 w-100">
                                            Apply price filter
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- ── PRODUCT GRID COL ─────────────────────────────────────────── --}}
            <div class="col-xl-9">

                {{-- Toolbar — Ochaka tf-shop-control --}}
                <div class="tf-shop-control">
                    {{-- Mobile filter button --}}
                    <div class="tf-control-filter d-xl-none">
                        <button type="button" data-bs-toggle="offcanvas" data-bs-target="#fadoFilterCanvas" class="tf-btn-filter">
                            <span class="icon icon-filter"></span>
                            <span class="text">Filter</span>
                            @if($hasFilters)
                                @php $filterCount = count($filters['metals']) + count($filters['gemstones']) + count($filters['collections']) + count($filters['second_metals']) + count($filters['colours']) + ($filters['price_min'] > 0 || $filters['price_max'] > 0 ? 1 : 0); @endphp
                                <span class="count-filter h6">{{ $filterCount }}</span>
                            @endif
                        </button>
                    </div>

                    {{-- Result count --}}
                    <div class="d-none d-xl-block">
                        <p class="h6 fw-normal text-secondary">
                            Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                            of {{ $products->total() }} {{ Str::plural('result', $products->total()) }}
                        </p>
                    </div>

                    {{-- Sort --}}
                    <div class="tf-control-sorting ms-auto">
                        <p class="h6 d-none d-lg-block">Sort by:</p>
                        <form method="GET" action="{{ url()->current() }}" id="sortForm" class="d-inline">
                            @foreach($filters['metals'] as $s)<input type="hidden" name="metals[]" value="{{ $s }}">@endforeach
                            @foreach($filters['gemstones'] as $s)<input type="hidden" name="gemstones[]" value="{{ $s }}">@endforeach
                            @foreach($filters['collections'] as $s)<input type="hidden" name="collections[]" value="{{ $s }}">@endforeach
                            @foreach($filters['second_metals'] as $s)<input type="hidden" name="second_metals[]" value="{{ $s }}">@endforeach
                            @foreach($filters['colours'] as $s)<input type="hidden" name="colours[]" value="{{ $s }}">@endforeach
                            @if($filters['price_min'] > 0)<input type="hidden" name="price_min" value="{{ $filters['price_min'] }}">@endif
                            @if($filters['price_max'] > 0)<input type="hidden" name="price_max" value="{{ $filters['price_max'] }}">@endif
                            @if($filters['search'])<input type="hidden" name="search" value="{{ $filters['search'] }}">@endif
                            <select name="sort" onchange="document.getElementById('sortForm').submit()" class="tf-sort-select h6">
                                <option value="newest"    {{ $filters['sort'] === 'newest'    ? 'selected' : '' }}>Newest first</option>
                                <option value="price_asc" {{ $filters['sort'] === 'price_asc' ? 'selected' : '' }}>Price: Low to high</option>
                                <option value="price_desc"{{ $filters['sort'] === 'price_desc'? 'selected' : '' }}>Price: High to low</option>
                                <option value="name_asc"  {{ $filters['sort'] === 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Product grid — Ochaka tf-grid-layout --}}
                @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="icon icon-gem" style="font-size:3rem; opacity:.3; display:block; margin-bottom:20px"></i>
                    <h3 class="h4 fw-normal mb-12">No pieces found</h3>
                    <p class="h6 fw-normal text-secondary mb_24">Try adjusting your filters or browse all jewellery.</p>
                    <a href="{{ url()->current() }}" class="tf-btn animate-btn">Clear filters</a>
                </div>
                @else
                <div class="wrapper-shop tf-grid-layout tf-col-3 md-col-2" id="productGrid">
                    @foreach($products as $product)
                    @php
                        $img    = $product->primaryImage;
                        $img2   = $product->images->skip(1)->first();
                        $from   = $product->variants->min('price_eur');
                        $metals = $product->variants->pluck('metal.name')->filter()->unique()->take(3);
                    @endphp
                    <div class="card-product grid wow fadeInUp" data-wow-delay="{{ ($loop->index % 3) * .06 }}s">
                        <div class="card-product_wrapper">
                            <a href="{{ route('shop.product', $product) }}" class="product-img">
                                @if($img)
                                    <img class="lazyload img-product" src="{{ Storage::url($img->path) }}" data-src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}">
                                    @if($img2)
                                    <img class="lazyload img-hover" src="{{ Storage::url($img2->path) }}" data-src="{{ Storage::url($img2->path) }}" alt="{{ $product->name }}">
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
                            <div class="price-wrap">
                                @if($from)
                                    <span class="price-new h6">From €{{ number_format((float)$from, 2) }}</span>
                                @else
                                    <span class="price-new h6 fw-normal" style="font-style:italic">Price on enquiry</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div class="d-flex justify-content-center mt_48">
                    <ul class="tf-pagination-wrap">
                        @if($products->onFirstPage())
                        <li><span class="pagination-link disabled h6">‹</span></li>
                        @else
                        <li><a href="{{ $products->previousPageUrl() }}" class="pagination-link h6">‹</a></li>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                        @if($page === $products->currentPage())
                        <li><span class="pagination-link active h6">{{ $page }}</span></li>
                        @else
                        <li><a href="{{ $url }}" class="pagination-link h6">{{ $page }}</a></li>
                        @endif
                        @endforeach

                        @if($products->hasMorePages())
                        <li><a href="{{ $products->nextPageUrl() }}" class="pagination-link h6">›</a></li>
                        @else
                        <li><span class="pagination-link disabled h6">›</span></li>
                        @endif
                    </ul>
                </div>
                @endif

                @endif {{-- end products empty check --}}
            </div>{{-- /col product grid --}}

        </div>{{-- /row --}}
    </div>{{-- /container --}}
</div>{{-- /flat-spacing --}}


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- Mobile Filter Offcanvas — Ochaka canvas-sidebar pattern                   --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="canvas-sidebar sidebar-filter canvas-filter left offcanvas offcanvas-start d-xl-none" tabindex="-1" id="fadoFilterCanvas" aria-labelledby="fadoFilterCanvasLabel">
    <div class="canvas-wrapper">
        <div class="canvas-header d-xl-none">
            <span class="title h3 fw-medium">Filter</span>
            <span class="icon-close link icon-close-popup fs-24" data-bs-dismiss="offcanvas"></span>
        </div>
        <div class="canvas-body offcanvas-body">
            <form method="GET" action="{{ url()->current() }}" id="mobileFilterForm">

                @if($filters['sort'] !== 'newest')
                    <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                @endif

                {{-- Metal --}}
                @if($metals->isNotEmpty())
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-metal" role="button" data-bs-toggle="collapse" aria-expanded="true">
                        <span class="h4 fw-semibold">Metal</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-metal" class="collapse show">
                        <ul class="collapse-body filter-group-check">
                            @foreach($metals as $metal)
                            <li class="list-item">
                                <input type="checkbox" name="metals[]" value="{{ $metal->slug }}" class="tf-check" id="m-metal-{{ $metal->slug }}"
                                       {{ in_array($metal->slug, $filters['metals']) ? 'checked' : '' }}>
                                <label for="m-metal-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Gemstone --}}
                @if($gemstones->isNotEmpty())
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-gem" role="button" data-bs-toggle="collapse" aria-expanded="true">
                        <span class="h4 fw-semibold">Gemstone</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-gem" class="collapse show">
                        <ul class="collapse-body filter-group-check">
                            @foreach($gemstones as $gem)
                            <li class="list-item">
                                <input type="checkbox" name="gemstones[]" value="{{ $gem->slug }}" class="tf-check" id="m-gem-{{ $gem->slug }}"
                                       {{ in_array($gem->slug, $filters['gemstones']) ? 'checked' : '' }}>
                                <label for="m-gem-{{ $gem->slug }}" class="label">{{ $gem->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Collection --}}
                @if(is_null($activeCollection) && $allCollections->isNotEmpty())
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-col" role="button" data-bs-toggle="collapse" aria-expanded="false">
                        <span class="h4 fw-semibold">Collection</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-col" class="collapse">
                        <ul class="collapse-body filter-group-check current-scrollbar">
                            @foreach($allCollections as $col)
                            <li class="list-item">
                                <input type="checkbox" name="collections[]" value="{{ $col->slug }}" class="tf-check" id="m-col-{{ $col->slug }}"
                                       {{ in_array($col->slug, $filters['collections']) ? 'checked' : '' }}>
                                <label for="m-col-{{ $col->slug }}" class="label">{{ $col->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Second metal --}}
                @if($metals->isNotEmpty())
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-finish" role="button" data-bs-toggle="collapse" aria-expanded="false">
                        <span class="h4 fw-semibold">Second Metal / Finish</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-finish" class="collapse">
                        <ul class="collapse-body filter-group-check">
                            @foreach($metals as $metal)
                            <li class="list-item">
                                <input type="checkbox" name="second_metals[]" value="{{ $metal->slug }}" class="tf-check" id="m-sm-{{ $metal->slug }}"
                                       {{ in_array($metal->slug, $filters['second_metals']) ? 'checked' : '' }}>
                                <label for="m-sm-{{ $metal->slug }}" class="label">{{ $metal->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Colour --}}
                @if($colours->isNotEmpty())
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-colour" role="button" data-bs-toggle="collapse" aria-expanded="false">
                        <span class="h4 fw-semibold">Colour</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-colour" class="collapse">
                        <ul class="collapse-body filter-group-check">
                            @foreach($colours as $colour)
                            <li class="list-item">
                                <input type="checkbox" name="colours[]" value="{{ $colour }}" class="tf-check" id="m-clr-{{ Str::slug($colour) }}"
                                       {{ in_array($colour, $filters['colours']) ? 'checked' : '' }}>
                                <label for="m-clr-{{ Str::slug($colour) }}" class="label">{{ $colour }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Price --}}
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#mflt-price" role="button" data-bs-toggle="collapse" aria-expanded="true">
                        <span class="h4 fw-semibold">Price (EUR)</span>
                        <span class="icon icon-caret-down fs-20"></span>
                    </div>
                    <div id="mflt-price" class="collapse show">
                        <div class="collapse-body">
                            <div class="d-flex gap-2">
                                <input type="number" name="price_min" value="{{ $filters['price_min'] ?: '' }}" min="0" placeholder="Min €"
                                       class="tf-field-input h6" style="width:50%">
                                <input type="number" name="price_max" value="{{ $filters['price_max'] ?: '' }}" min="0" placeholder="Max €"
                                       class="tf-field-input h6" style="width:50%">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="canvas-bottom">
                    <button type="submit" class="tf-btn animate-btn w-100 mb-2">Apply Filters</button>
                    <a href="{{ url()->current() }}" class="tf-btn btn-reset w-100 text-center">Clear All</a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
/* Filter tag pills */
.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px solid #bcb3ab;
    padding: 4px 12px;
    border-radius: 20px;
    text-decoration: none;
    color: inherit;
    transition: border-color .15s;
}
.filter-tag:hover { border-color: #1a1a1a; color: inherit; }

/* Sort select native fallback */
.tf-sort-select {
    padding: 6px 28px 6px 10px;
    border: 1px solid #bcb3ab;
    border-radius: 3px;
    background: #fff;
    cursor: pointer;
    outline: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
}

/* Search field in filter */
.filter-search-field { position: relative; }
.tf-field-input { width: 100%; padding: 8px 12px; border: 1px solid #bcb3ab; border-radius: 3px; background: #fff; outline: none; }
.tf-field-input:focus { border-color: #1a1a1a; }
.btn-search-field { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #bcb3ab; }

/* Pagination */
.tf-pagination-wrap { display: flex; gap: 4px; list-style: none; padding: 0; margin: 0; flex-wrap: wrap; justify-content: center; }
.pagination-link { display: flex; align-items: center; padding: 8px 14px; border: 1px solid #bcb3ab; border-radius: 3px; text-decoration: none; color: inherit; transition: all .15s; }
.pagination-link.active { background: #1a1a1a; color: #fff; border-color: #1a1a1a; font-weight: 600; }
.pagination-link.disabled { color: #bcb3ab; cursor: not-allowed; border-color: #f0f0f0; }
.pagination-link:not(.active):not(.disabled):hover { border-color: #1a1a1a; }
</style>
@endpush
