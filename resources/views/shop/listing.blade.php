@extends('shop.layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', $pageTitle . ' — FADÓ Jewellery')

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 1  SHEILA FLEET–STYLE BANNER                                            --}}
{{-- Text left, evocative product image right, deep-green background           --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="fado-collection-hero" style="
    background: var(--fado-deep-green);
    position: relative;
    overflow: hidden;
    min-height: 320px;
    display: flex;
    align-items: center;">

    {{-- Background decorative sweep --}}
    <div style="position:absolute; right:0; top:0; bottom:0; width:55%; pointer-events:none; overflow:hidden">
        @if($bannerImage)
            <img src="{{ Storage::url($bannerImage) }}" alt="{{ $pageTitle }}"
                 style="width:100%; height:100%; object-fit:cover; object-position:center; opacity:.35">
        @else
            <img src="/images/ochaka/slider/slider-22.jpg" alt="{{ $pageTitle }}"
                 style="width:100%; height:100%; object-fit:cover; object-position:center 30%; opacity:.2">
        @endif
        {{-- Gold-tone gradient sweep --}}
        <div style="position:absolute; inset:0;
                    background: linear-gradient(to right, var(--fado-deep-green) 0%, transparent 60%);"></div>
    </div>

    <div class="container position-relative" style="z-index:2; padding-top:52px; padding-bottom:52px">
        <div class="row">
            <div class="col-xl-6 col-lg-7">

                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" style="margin-bottom:20px">
                    <ol class="breadcrumb mb-0" style="background:none; padding:0; gap:4px">
                        <li class="breadcrumb-item">
                            <a href="{{ route('shop.home') }}"
                               style="color:rgba(255,255,255,.55); text-decoration:none; font-size:.75rem">Home</a>
                        </li>
                        @foreach($breadcrumbs as $crumb)
                            @if(!$loop->last)
                            <li class="breadcrumb-item" style="color:rgba(255,255,255,.35); font-size:.75rem">/</li>
                            <li class="breadcrumb-item">
                                <a href="{{ $crumb['url'] }}"
                                   style="color:rgba(255,255,255,.55); text-decoration:none; font-size:.75rem">{{ $crumb['label'] }}</a>
                            </li>
                            @else
                            <li class="breadcrumb-item" style="color:rgba(255,255,255,.35); font-size:.75rem">/</li>
                            <li class="breadcrumb-item active" aria-current="page"
                                style="color:rgba(255,255,255,.9); font-size:.75rem">{{ $crumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>

                {{-- Title block --}}
                @if($activeCollection)
                    <p style="font-size:.7rem; letter-spacing:.3em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:12px">
                        Collection
                    </p>
                @elseif($activeCategory)
                    <p style="font-size:.7rem; letter-spacing:.3em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:12px">
                        {{ $activeCategory->parent?->name ?? 'Jewellery' }}
                    </p>
                @endif

                <h1 style="font-family: Georgia, serif;
                           font-size: clamp(1.8rem, 4vw, 3rem);
                           color: #fff;
                           font-weight: 400;
                           line-height: 1.2;
                           margin-bottom: 20px">
                    {{ $pageTitle }}
                </h1>

                @if($pageSubtitle)
                <p style="font-size: 1rem; color: rgba(255,255,255,.72); line-height: 1.75; max-width: 500px; margin-bottom: 0">
                    {{ $pageSubtitle }}
                </p>
                @endif

                {{-- Product count pill --}}
                <div style="margin-top:24px">
                    <span style="display:inline-block; background:rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
                                 color:rgba(255,255,255,.8); font-size:.75rem; padding:4px 14px; border-radius:20px">
                        {{ $products->total() }} {{ Str::plural('piece', $products->total()) }}
                    </span>
                </div>
            </div>

            {{-- Right column — collection sub-nav (collections only) --}}
            @if($activeCollection && $activeCollection->products->count() === 0)
            {{-- nothing --}}
            @endif
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 2  ACTIVE FILTER PILLS (shown when any filter is applied)               --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
@php
    $hasFilters = !empty($filters['metals'])   || !empty($filters['gemstones'])
               || !empty($filters['collections']) || !empty($filters['second_metals'])
               || !empty($filters['colours'])
               || $filters['price_min'] > 0 || $filters['price_max'] > 0
               || $filters['search'];
@endphp
@if($hasFilters)
<div style="background: var(--fado-cream); border-bottom: 1px solid var(--fado-warm-grey); padding:12px 0">
    <div class="container d-flex align-items-center gap-2 flex-wrap">
        <span style="font-size:.75rem; color: var(--fado-warm-grey); font-weight:600; text-transform:uppercase; letter-spacing:.05em">Filters:</span>

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
            <a href="{{ $removeUrl }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                      padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green); text-decoration:none">
                {{ $metal->name }} <span style="opacity:.5">&times;</span>
            </a>
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
            <a href="{{ $removeUrl }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                      padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green); text-decoration:none">
                {{ $gem->name }} <span style="opacity:.5">&times;</span>
            </a>
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
            <a href="{{ $removeUrl }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                      padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green); text-decoration:none">
                {{ $col->name }} <span style="opacity:.5">&times;</span>
            </a>
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
            <a href="{{ $removeUrl }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                      padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green); text-decoration:none">
                Finish: {{ $sm->name }} <span style="opacity:.5">&times;</span>
            </a>
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
            <a href="{{ $removeUrl }}"
               style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                      padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green); text-decoration:none">
                Colour: {{ $colour }} <span style="opacity:.5">&times;</span>
            </a>
        @endforeach

        @if($filters['price_min'] > 0 || $filters['price_max'] > 0)
        <span style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--fado-warm-grey);
                     padding:4px 12px; border-radius:20px; font-size:.75rem; color: var(--fado-deep-green)">
            €{{ $filters['price_min'] ?: '0' }} – €{{ $filters['price_max'] ?: '∞' }}
        </span>
        @endif

        <a href="{{ url()->current() }}"
           style="margin-left:auto; font-size:.75rem; color: var(--fado-green-mid); text-decoration:underline">
            Clear all filters
        </a>
    </div>
</div>
@endif


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 3  MAIN LAYOUT — sidebar filters + product grid                         --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div style="background: var(--fado-near-white); padding: 48px 0 80px">
    <div class="container">
        <div class="row g-5">

            {{-- ── FILTER SIDEBAR ────────────────────────────────────────── --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="fado-filter-sidebar" style="position:sticky; top:24px">
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">

                        {{-- Preserve sort & search when filtering --}}
                        @if($filters['sort'] !== 'newest')
                            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                        @endif
                        @if($filters['search'])
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                        @endif

                        {{-- Search --}}
                        <div class="fado-filter-section" style="margin-bottom:28px">
                            <div style="position:relative">
                                <input type="text" name="search" value="{{ $filters['search'] }}"
                                       placeholder="Search jewellery…"
                                       style="width:100%; padding:9px 36px 9px 14px; border:1px solid var(--fado-warm-grey);
                                              border-radius:3px; font-size:.875rem; background:#fff; color: var(--fado-deep-green);
                                              outline:none"
                                       onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                       onblur="this.style.borderColor='var(--fado-warm-grey)'">
                                <button type="submit" style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                                                             background:none; border:none; cursor:pointer; color:var(--fado-warm-grey)">
                                    <i class="icon icon-magnifying-glass" style="font-size:.875rem"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Category quick-links --}}
                        @if($topCategories->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Jewellery Type</h6>
                            <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:4px">
                                <li>
                                    <a href="{{ route('shop.jewellery') }}"
                                       style="font-size:.875rem; text-decoration:none; padding:4px 0; display:block;
                                              color: {{ is_null($activeCategory) && is_null($activeCollection) ? 'var(--fado-green-mid)' : 'var(--fado-deep-green)' }};
                                              font-weight: {{ is_null($activeCategory) ? '600' : '400' }}">
                                        All Jewellery
                                    </a>
                                </li>
                                @foreach($topCategories as $cat)
                                <li>
                                    <a href="{{ route('shop.category', $cat->slug) }}"
                                       style="font-size:.875rem; text-decoration:none; padding:4px 0; display:block;
                                              color: {{ optional($activeCategory)->id === $cat->id ? 'var(--fado-green-mid)' : 'var(--fado-deep-green)' }};
                                              font-weight: {{ optional($activeCategory)->id === $cat->id ? '600' : '400' }}">
                                        {{ $cat->name }}
                                    </a>
                                    {{-- Children --}}
                                    @if($cat->children->isNotEmpty() && optional($activeCategory)->id === $cat->id)
                                    <ul style="list-style:none; padding:0 0 0 14px; margin:2px 0 6px">
                                        @foreach($cat->children as $child)
                                        <li>
                                            <a href="{{ route('shop.category', $child->slug) }}"
                                               style="font-size:.8125rem; text-decoration:none; padding:3px 0; display:block;
                                                      color: {{ optional($activeCategory)->id === $child->id ? 'var(--fado-green-mid)' : '#666' }}">
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
                        @endif

                        {{-- Metal filter --}}
                        @if($metals->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Metal</h6>
                            <div style="display:flex; flex-direction:column; gap:8px">
                                @foreach($metals as $metal)
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.875rem; color: var(--fado-deep-green)">
                                    <input type="checkbox" name="metals[]" value="{{ $metal->slug }}"
                                           {{ in_array($metal->slug, $filters['metals']) ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           style="accent-color: var(--fado-green-mid); width:15px; height:15px; cursor:pointer">
                                    {{ $metal->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Gemstone filter --}}
                        @if($gemstones->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Gemstone</h6>
                            <div style="display:flex; flex-direction:column; gap:8px">
                                @foreach($gemstones as $gem)
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.875rem; color: var(--fado-deep-green)">
                                    <input type="checkbox" name="gemstones[]" value="{{ $gem->slug }}"
                                           {{ in_array($gem->slug, $filters['gemstones']) ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           style="accent-color: var(--fado-green-mid); width:15px; height:15px; cursor:pointer">
                                    {{ $gem->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Collection filter (hidden when already viewing a specific collection) --}}
                        @if(is_null($activeCollection) && $allCollections->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Collection</h6>
                            <div style="display:flex; flex-direction:column; gap:8px; max-height:220px; overflow-y:auto; padding-right:4px">
                                @foreach($allCollections as $col)
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.875rem; color: var(--fado-deep-green)">
                                    <input type="checkbox" name="collections[]" value="{{ $col->slug }}"
                                           {{ in_array($col->slug, $filters['collections']) ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           style="accent-color: var(--fado-green-mid); width:15px; height:15px; cursor:pointer">
                                    {{ $col->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Second metal / finish filter --}}
                        @if($metals->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Second Metal / Finish</h6>
                            <div style="display:flex; flex-direction:column; gap:8px">
                                @foreach($metals as $metal)
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.875rem; color: var(--fado-deep-green)">
                                    <input type="checkbox" name="second_metals[]" value="{{ $metal->slug }}"
                                           {{ in_array($metal->slug, $filters['second_metals']) ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           style="accent-color: var(--fado-green-mid); width:15px; height:15px; cursor:pointer">
                                    {{ $metal->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Colour filter (only shown when colour data exists in DB) --}}
                        @if($colours->isNotEmpty())
                        <div class="fado-filter-section" style="margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--fado-cream)">
                            <h6 class="fado-filter-heading">Colour</h6>
                            <div style="display:flex; flex-direction:column; gap:8px">
                                @foreach($colours as $colour)
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.875rem; color: var(--fado-deep-green)">
                                    <input type="checkbox" name="colours[]" value="{{ $colour }}"
                                           {{ in_array($colour, $filters['colours']) ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()"
                                           style="accent-color: var(--fado-green-mid); width:15px; height:15px; cursor:pointer">
                                    {{ $colour }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Price range --}}
                        <div class="fado-filter-section" style="margin-bottom:28px">
                            <h6 class="fado-filter-heading">Price Range (EUR)</h6>
                            <div class="fado-price-range-track" style="position:relative; height:4px; background:var(--fado-cream); border-radius:2px; margin:16px 0 20px">
                                <div id="priceRangeFill" style="position:absolute; height:100%; background:var(--fado-green-mid); border-radius:2px"></div>
                                <input type="range" id="priceMin" name="price_min"
                                       min="0" max="5000" step="25"
                                       value="{{ $filters['price_min'] ?: 0 }}"
                                       style="position:absolute; width:100%; height:100%; opacity:0; cursor:pointer; top:0; left:0"
                                       oninput="updatePriceRange()">
                                <input type="range" id="priceMax" name="price_max"
                                       min="0" max="5000" step="25"
                                       value="{{ $filters['price_max'] ?: 5000 }}"
                                       style="position:absolute; width:100%; height:100%; opacity:0; cursor:pointer; top:0; left:0"
                                       oninput="updatePriceRange()">
                                <div id="priceThumbMin" style="position:absolute; top:50%; transform:translate(-50%,-50%); width:16px; height:16px; background:var(--fado-green-mid); border:2px solid #fff; border-radius:50%; box-shadow:0 1px 4px rgba(0,0,0,.2); pointer-events:none"></div>
                                <div id="priceThumbMax" style="position:absolute; top:50%; transform:translate(-50%,-50%); width:16px; height:16px; background:var(--fado-green-mid); border:2px solid #fff; border-radius:50%; box-shadow:0 1px 4px rgba(0,0,0,.2); pointer-events:none"></div>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center">
                                <span style="font-size:.8125rem; color: var(--fado-deep-green)">
                                    € <strong id="priceMinLabel">{{ $filters['price_min'] ?: 0 }}</strong>
                                </span>
                                <span style="font-size:.8125rem; color: var(--fado-deep-green)">
                                    € <strong id="priceMaxLabel">{{ $filters['price_max'] ?: 5000 }}</strong>
                                </span>
                            </div>
                            <button type="submit" style="margin-top:12px; width:100%; padding:8px; background: var(--fado-deep-green);
                                                         color:#fff; border:none; border-radius:2px; font-size:.8125rem;
                                                         cursor:pointer; transition: background .2s"
                                    onmouseover="this.style.background='var(--fado-green-mid)'"
                                    onmouseout="this.style.background='var(--fado-deep-green)'">
                                Apply price filter
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ── PRODUCT GRID ───────────────────────────────────────────── --}}
            <div class="col-lg-9">

                {{-- Toolbar — sort + mobile filter toggle + result count --}}
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;
                            margin-bottom:28px; flex-wrap:wrap">

                    {{-- Mobile filter toggle --}}
                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#fadoFilterCanvas"
                            class="d-lg-none btn-fado-outline"
                            style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                                   border-radius:2px; font-size:.8125rem; text-decoration:none">
                        <i class="icon icon-funnel"></i> Filters
                        @if($hasFilters)
                            @php
                                $filterCount = count($filters['metals']) + count($filters['gemstones'])
                                    + count($filters['collections']) + count($filters['second_metals'])
                                    + count($filters['colours'])
                                    + ($filters['price_min'] > 0 || $filters['price_max'] > 0 ? 1 : 0);
                            @endphp
                            <span style="background:var(--fado-green-mid); color:#fff; width:18px; height:18px;
                                         border-radius:50%; font-size:.65rem; display:inline-flex; align-items:center; justify-content:center">
                                {{ $filterCount }}
                            </span>
                        @endif
                    </button>

                    <p style="font-size:.8125rem; color: var(--fado-warm-grey); margin:0; flex:1">
                        Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                        of {{ $products->total() }} {{ Str::plural('result', $products->total()) }}
                    </p>

                    {{-- Sort dropdown --}}
                    <form method="GET" action="{{ url()->current() }}" id="sortForm" style="margin:0">
                        @foreach($filters['metals'] as $s)
                            <input type="hidden" name="metals[]" value="{{ $s }}">
                        @endforeach
                        @foreach($filters['gemstones'] as $s)
                            <input type="hidden" name="gemstones[]" value="{{ $s }}">
                        @endforeach
                        @foreach($filters['collections'] as $s)
                            <input type="hidden" name="collections[]" value="{{ $s }}">
                        @endforeach
                        @foreach($filters['second_metals'] as $s)
                            <input type="hidden" name="second_metals[]" value="{{ $s }}">
                        @endforeach
                        @foreach($filters['colours'] as $s)
                            <input type="hidden" name="colours[]" value="{{ $s }}">
                        @endforeach
                        @if($filters['price_min'] > 0)<input type="hidden" name="price_min" value="{{ $filters['price_min'] }}">@endif
                        @if($filters['price_max'] > 0)<input type="hidden" name="price_max" value="{{ $filters['price_max'] }}">@endif
                        @if($filters['search'])<input type="hidden" name="search" value="{{ $filters['search'] }}">@endif

                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                                style="padding:8px 32px 8px 12px; border:1px solid var(--fado-warm-grey);
                                       border-radius:3px; font-size:.8125rem; background:#fff;
                                       color: var(--fado-deep-green); cursor:pointer;
                                       appearance:none; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E\");
                                       background-repeat:no-repeat; background-position:right 10px center; outline:none">
                            <option value="newest"    {{ $filters['sort'] === 'newest'    ? 'selected' : '' }}>Newest first</option>
                            <option value="price_asc" {{ $filters['sort'] === 'price_asc' ? 'selected' : '' }}>Price: Low to high</option>
                            <option value="price_desc"{{ $filters['sort'] === 'price_desc'? 'selected' : '' }}>Price: High to low</option>
                            <option value="name_asc"  {{ $filters['sort'] === 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                        </select>
                    </form>
                </div>

                {{-- Product grid --}}
                @if($products->isEmpty())
                <div style="text-align:center; padding:80px 24px">
                    <i class="icon icon-gem" style="font-size:3rem; color: var(--fado-warm-grey); display:block; margin-bottom:20px"></i>
                    <h3 style="font-family: Georgia, serif; color: var(--fado-deep-green); font-size:1.5rem; font-weight:400; margin-bottom:12px">
                        No pieces found
                    </h3>
                    <p style="color:#888; margin-bottom:24px">Try adjusting your filters or browse all jewellery.</p>
                    <a href="{{ url()->current() }}" class="btn-fado-outline"
                       style="display:inline-block; padding:10px 28px; border-radius:2px; text-decoration:none">
                        Clear filters
                    </a>
                </div>
                @else
                <div class="row g-4" id="productGrid">
                    @foreach($products as $product)
                    @php
                        $img    = $product->primaryImage;
                        $from   = $product->variants->min('price_eur');
                        $metals = $product->variants->pluck('metal.name')->filter()->unique()->take(3);
                    @endphp
                    <div class="col-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ ($loop->index % 3) * .06 }}s">
                        <a href="{{ route('shop.product', $product) }}" class="fado-product-card d-block text-decoration-none">

                            {{-- Image tile --}}
                            <div style="background: var(--fado-cream); border-radius:3px; overflow:hidden;
                                        aspect-ratio:3/4; margin-bottom:14px; position:relative">
                                @if($img)
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                                         style="width:100%; height:100%; object-fit:cover; object-position:center top;
                                                transition: transform .4s ease">
                                @else
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                        <i class="icon icon-gem" style="font-size:3rem; color: var(--fado-warm-grey)"></i>
                                    </div>
                                @endif

                                {{-- Wishlist button (appears on hover) --}}
                                <div class="fado-product-actions"
                                     style="position:absolute; top:12px; right:12px; display:flex; flex-direction:column; gap:8px; opacity:0; transition:opacity .2s">
                                    <button type="button"
                                            style="background:#fff; border:none; width:36px; height:36px; border-radius:50%;
                                                   display:flex; align-items:center; justify-content:center;
                                                   box-shadow:0 2px 8px rgba(0,0,0,.12); cursor:pointer; color:var(--fado-deep-green)"
                                            title="Save to wishlist">
                                        <i class="icon icon-heart" style="font-size:.875rem"></i>
                                    </button>
                                </div>

                                {{-- Metal variants pill strip at bottom of image --}}
                                @if($metals->isNotEmpty())
                                <div style="position:absolute; bottom:0; left:0; right:0; padding:8px 10px;
                                            background:linear-gradient(transparent, rgba(4,71,5,.5));
                                            display:flex; gap:4px; flex-wrap:wrap">
                                    @foreach($metals as $metalName)
                                    <span style="font-size:.65rem; background:rgba(255,255,255,.18); color:#fff;
                                                 border:1px solid rgba(255,255,255,.3); padding:2px 8px; border-radius:10px;
                                                 backdrop-filter:blur(4px)">
                                        {{ $metalName }}
                                    </span>
                                    @endforeach
                                    @if($product->variants->count() > 3)
                                    <span style="font-size:.65rem; background:rgba(255,255,255,.18); color:#fff;
                                                 border:1px solid rgba(255,255,255,.3); padding:2px 8px; border-radius:10px">
                                        +{{ $product->variants->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>

                            {{-- Product name --}}
                            <p class="fado-product-name"
                               style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green);
                                      margin-bottom:4px; line-height:1.4; transition:color .2s">
                                {{ $product->name }}
                            </p>

                            {{-- Price --}}
                            <p style="font-size:.875rem; color:var(--fado-warm-grey); margin:0">
                                @if($from)
                                    From <strong style="color:var(--fado-deep-green)">€{{ number_format((float)$from, 2) }}</strong>
                                @else
                                    <span style="font-style:italic">Price on enquiry</span>
                                @endif
                            </p>
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div style="margin-top:48px; display:flex; justify-content:center">
                    <nav aria-label="Products pagination">
                        <ul class="pagination" style="gap:4px; list-style:none; padding:0; margin:0; display:flex; flex-wrap:wrap; justify-content:center">

                            {{-- Prev --}}
                            @if($products->onFirstPage())
                            <li><span style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-cream); border-radius:3px; color:var(--fado-warm-grey); font-size:.875rem; cursor:not-allowed">‹</span></li>
                            @else
                            <li><a href="{{ $products->previousPageUrl() }}" style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-warm-grey); border-radius:3px; color:var(--fado-deep-green); font-size:.875rem; text-decoration:none">‹</a></li>
                            @endif

                            {{-- Page numbers --}}
                            @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                            @if($page === $products->currentPage())
                            <li><span style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-green-mid); border-radius:3px; background:var(--fado-green-mid); color:#fff; font-size:.875rem; font-weight:600">{{ $page }}</span></li>
                            @else
                            <li><a href="{{ $url }}" style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-warm-grey); border-radius:3px; color:var(--fado-deep-green); font-size:.875rem; text-decoration:none">{{ $page }}</a></li>
                            @endif
                            @endforeach

                            {{-- Next --}}
                            @if($products->hasMorePages())
                            <li><a href="{{ $products->nextPageUrl() }}" style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-warm-grey); border-radius:3px; color:var(--fado-deep-green); font-size:.875rem; text-decoration:none">›</a></li>
                            @else
                            <li><span style="display:flex; align-items:center; padding:8px 14px; border:1px solid var(--fado-cream); border-radius:3px; color:var(--fado-warm-grey); font-size:.875rem; cursor:not-allowed">›</span></li>
                            @endif

                        </ul>
                    </nav>
                </div>
                @endif

                @endif {{-- end products empty check --}}
            </div>{{-- /col product grid --}}

        </div>{{-- /row --}}
    </div>{{-- /container --}}
</div>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 4  MOBILE FILTER OFFCANVAS                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="fadoFilterCanvas" aria-labelledby="fadoFilterCanvasLabel">
    <div class="offcanvas-header" style="background:var(--fado-deep-green); padding:20px 24px">
        <h5 id="fadoFilterCanvasLabel" style="color:#fff; margin:0; font-family:Georgia,serif; font-weight:400">
            Filter Jewellery
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="padding:24px; overflow-y:auto">
        <form method="GET" action="{{ url()->current() }}" id="mobileFilterForm">

            @if($filters['sort'] !== 'newest')
                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
            @endif

            {{-- Metal --}}
            @if($metals->isNotEmpty())
            <div style="margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid var(--fado-cream)">
                <h6 class="fado-filter-heading">Metal</h6>
                <div style="display:flex; flex-direction:column; gap:10px">
                    @foreach($metals as $metal)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.9rem; color:var(--fado-deep-green)">
                        <input type="checkbox" name="metals[]" value="{{ $metal->slug }}"
                               {{ in_array($metal->slug, $filters['metals']) ? 'checked' : '' }}
                               style="accent-color:var(--fado-green-mid); width:16px; height:16px; cursor:pointer">
                        {{ $metal->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Gemstone --}}
            @if($gemstones->isNotEmpty())
            <div style="margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid var(--fado-cream)">
                <h6 class="fado-filter-heading">Gemstone</h6>
                <div style="display:flex; flex-direction:column; gap:10px">
                    @foreach($gemstones as $gem)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.9rem; color:var(--fado-deep-green)">
                        <input type="checkbox" name="gemstones[]" value="{{ $gem->slug }}"
                               {{ in_array($gem->slug, $filters['gemstones']) ? 'checked' : '' }}
                               style="accent-color:var(--fado-green-mid); width:16px; height:16px; cursor:pointer">
                        {{ $gem->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Collection (hidden when viewing a specific collection) --}}
            @if(is_null($activeCollection) && $allCollections->isNotEmpty())
            <div style="margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid var(--fado-cream)">
                <h6 class="fado-filter-heading">Collection</h6>
                <div style="display:flex; flex-direction:column; gap:10px; max-height:200px; overflow-y:auto">
                    @foreach($allCollections as $col)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.9rem; color:var(--fado-deep-green)">
                        <input type="checkbox" name="collections[]" value="{{ $col->slug }}"
                               {{ in_array($col->slug, $filters['collections']) ? 'checked' : '' }}
                               style="accent-color:var(--fado-green-mid); width:16px; height:16px; cursor:pointer">
                        {{ $col->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Second metal / finish --}}
            @if($metals->isNotEmpty())
            <div style="margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid var(--fado-cream)">
                <h6 class="fado-filter-heading">Second Metal / Finish</h6>
                <div style="display:flex; flex-direction:column; gap:10px; max-height:200px; overflow-y:auto">
                    @foreach($metals as $metal)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.9rem; color:var(--fado-deep-green)">
                        <input type="checkbox" name="second_metals[]" value="{{ $metal->slug }}"
                               {{ in_array($metal->slug, $filters['second_metals']) ? 'checked' : '' }}
                               style="accent-color:var(--fado-green-mid); width:16px; height:16px; cursor:pointer">
                        {{ $metal->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Colour (only when colour data exists) --}}
            @if($colours->isNotEmpty())
            <div style="margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid var(--fado-cream)">
                <h6 class="fado-filter-heading">Colour</h6>
                <div style="display:flex; flex-direction:column; gap:10px">
                    @foreach($colours as $colour)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:.9rem; color:var(--fado-deep-green)">
                        <input type="checkbox" name="colours[]" value="{{ $colour }}"
                               {{ in_array($colour, $filters['colours']) ? 'checked' : '' }}
                               style="accent-color:var(--fado-green-mid); width:16px; height:16px; cursor:pointer">
                        {{ $colour }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Price --}}
            <div style="margin-bottom:24px">
                <h6 class="fado-filter-heading">Price (EUR)</h6>
                <div class="d-flex gap-3 mt-2">
                    <div style="flex:1">
                        <label style="font-size:.75rem; color:var(--fado-warm-grey); display:block; margin-bottom:4px">Min</label>
                        <input type="number" name="price_min" value="{{ $filters['price_min'] ?: '' }}" min="0" placeholder="0"
                               style="width:100%; padding:8px 10px; border:1px solid var(--fado-warm-grey); border-radius:3px; font-size:.875rem">
                    </div>
                    <div style="flex:1">
                        <label style="font-size:.75rem; color:var(--fado-warm-grey); display:block; margin-bottom:4px">Max</label>
                        <input type="number" name="price_max" value="{{ $filters['price_max'] ?: '' }}" min="0" placeholder="5000"
                               style="width:100%; padding:8px 10px; border:1px solid var(--fado-warm-grey); border-radius:3px; font-size:.875rem">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" style="flex:1; padding:12px; background:var(--fado-deep-green); color:#fff;
                                            border:none; border-radius:2px; font-size:.875rem; font-weight:600; cursor:pointer">
                    Apply Filters
                </button>
                <a href="{{ url()->current() }}" style="flex:1; padding:12px; border:1px solid var(--fado-warm-grey);
                                                        border-radius:2px; font-size:.875rem; text-align:center;
                                                        color:var(--fado-deep-green); text-decoration:none">
                    Clear All
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('css')
<style>
.fado-filter-heading {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--fado-deep-green);
    margin-bottom: 14px;
}
.fado-product-card:hover .fado-product-name   { color: var(--fado-green-mid) !important; }
.fado-product-card:hover img                  { transform: scale(1.04); }
.fado-product-card:hover .fado-product-actions { opacity: 1 !important; }
</style>
@endpush

@push('scripts')
<script>
// Dual-handle price range slider (visual only — native inputs stay under it)
function updatePriceRange() {
    const min = parseInt(document.getElementById('priceMin').value);
    const max = parseInt(document.getElementById('priceMax').value);
    const MAX = 5000;

    // Enforce min < max
    if (min > max) {
        document.getElementById('priceMin').value = max;
        document.getElementById('priceMax').value = min;
        return updatePriceRange();
    }

    const minPct = (min / MAX) * 100;
    const maxPct = (max / MAX) * 100;

    document.getElementById('priceMinLabel').textContent = min;
    document.getElementById('priceMaxLabel').textContent = max === MAX ? '5,000+' : max;
    document.getElementById('priceThumbMin').style.left = minPct + '%';
    document.getElementById('priceThumbMax').style.left = maxPct + '%';
    document.getElementById('priceRangeFill').style.left  = minPct + '%';
    document.getElementById('priceRangeFill').style.width = (maxPct - minPct) + '%';
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('priceMin')) updatePriceRange();
});
</script>
@endpush
