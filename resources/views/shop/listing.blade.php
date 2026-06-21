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
                        <form class="canvas-body" method="GET" action="{{ url()->current() }}" id="shop-filter-form">
                            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                            @if(!empty($filters['search']))
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            @endif
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
                @include('shop.partials.listing-results', ['products' => $products, 'filters' => $filters])
            </div>
        </div>
    </div>
</div>
{{-- /Section Product --}}

@push('scripts')
<script src="{{ asset('js/shop-filters.js') }}"></script>
@endpush

@endsection
