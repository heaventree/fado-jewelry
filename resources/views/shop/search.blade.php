@extends('shop.layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
@endphp

@section('title', $query ? 'Search: ' . $query . ' — ' . Setting::get('store_name', 'FADÓ Jewellery') : 'Search — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, follow')

@section('content')

{{-- Page Title — Ochaka s-page-title with inline search --}}
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

<div class="flat-spacing" style="min-height:60vh">
    <div class="container">

        @if(!$query)
        {{-- ── No query yet — show prompt ──────────────────────────────────── --}}
        <div style="text-align:center; padding:60px 24px">
            <i class="icon icon-magnifying-glass"
               style="font-size:3.5rem; color:var(--fado-warm-grey); display:block; margin-bottom:20px; opacity:.5"></i>
            <h2 style="font-family:Georgia,serif; font-size:1.5rem; font-weight:400;
                       color:var(--fado-deep-green); margin-bottom:12px">
                What are you looking for?
            </h2>
            <p style="color:#888; max-width:380px; margin:0 auto 32px; line-height:1.7">
                Search across our full range of rings, pendants, earrings, and more.
            </p>
            <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center">
                @foreach(['Claddagh', 'Diamond ring', 'Sterling silver', 'Pendant', 'Gold earrings'] as $suggestion)
                <a href="{{ route('shop.search', ['q' => $suggestion]) }}"
                   style="padding:8px 16px; background:var(--fado-cream); border-radius:20px;
                          font-size:.8125rem; color:var(--fado-deep-green); text-decoration:none;
                          border:1px solid var(--fado-warm-grey); transition:all .15s"
                   onmouseover="this.style.background='var(--fado-deep-green)'; this.style.color='#fff'; this.style.borderColor='var(--fado-deep-green)'"
                   onmouseout="this.style.background='var(--fado-cream)'; this.style.color='var(--fado-deep-green)'; this.style.borderColor='var(--fado-warm-grey)'">
                    {{ $suggestion }}
                </a>
                @endforeach
            </div>
        </div>

        @elseif(strlen($query) < 2)
        {{-- ── Query too short ─────────────────────────────────────────────── --}}
        <div style="text-align:center; padding:60px 24px">
            <p style="color:#888">Please enter at least 2 characters to search.</p>
        </div>

        @elseif($products->isEmpty())
        {{-- ── No results ───────────────────────────────────────────────────── --}}
        <div style="text-align:center; padding:60px 24px">
            <i class="icon icon-magnifying-glass"
               style="font-size:3rem; color:var(--fado-warm-grey); display:block; margin-bottom:16px; opacity:.5"></i>
            <h2 style="font-family:Georgia,serif; font-size:1.375rem; font-weight:400;
                       color:var(--fado-deep-green); margin-bottom:10px">
                No results for "{{ $query }}"
            </h2>
            <p style="color:#888; max-width:380px; margin:0 auto 28px; line-height:1.7">
                Try a different search term, or browse our collections below.
            </p>
            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap">
                <a href="{{ route('shop.jewellery') }}"
                   style="padding:12px 24px; background:var(--fado-deep-green); color:#fff;
                          text-decoration:none; border-radius:2px; font-size:.875rem; font-weight:600">
                    Browse All Jewellery
                </a>
                <a href="{{ route('shop.collections') }}"
                   style="padding:12px 24px; border:1.5px solid var(--fado-deep-green); color:var(--fado-deep-green);
                          text-decoration:none; border-radius:2px; font-size:.875rem; font-weight:600">
                    View Collections
                </a>
            </div>
        </div>

        @else
        {{-- ── Results ──────────────────────────────────────────────────────── --}}

        {{-- Result count bar --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
                    margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--fado-cream)">
            <p style="font-size:.9375rem; color:var(--fado-deep-green); margin:0">
                <strong>{{ number_format($total) }}</strong>
                {{ $total === 1 ? 'result' : 'results' }}
                for <em>"{{ $query }}"</em>
            </p>
            <a href="{{ route('shop.jewellery') }}"
               style="font-size:.8125rem; color:var(--fado-warm-grey); text-decoration:none">
                ← Browse all jewellery
            </a>
        </div>

        {{-- Product grid (same style as listing.blade.php) --}}
        <div class="row g-4">
            @foreach($products as $product)
            @php
                $img     = $product->primaryImage;
                $from    = $product->variants->min('price_eur');
                $metals  = $product->variants->pluck('metal.name')->filter()->unique()->take(3)->values();
            @endphp
            <div class="col-6 col-md-4 col-xl-3">
                <div class="fado-product-card"
                     style="background:#fff; border:1px solid var(--fado-cream); border-radius:3px;
                            overflow:hidden; transition:box-shadow .2s; height:100%"
                     onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'"
                     onmouseout="this.style.boxShadow='none'">

                    {{-- Image --}}
                    <div style="position:relative; background:var(--fado-cream); aspect-ratio:3/4; overflow:hidden">
                        <a href="{{ route('shop.product', $product) }}" style="display:block; height:100%">
                            @if($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                                     style="width:100%; height:100%; object-fit:cover; object-position:center top;
                                            transition:transform .45s ease">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                    <i class="icon icon-gem" style="font-size:2.5rem; color:var(--fado-warm-grey)"></i>
                                </div>
                            @endif
                        </a>

                        {{-- Metal pills --}}
                        @if($metals->isNotEmpty())
                        <div style="position:absolute; bottom:0; left:0; right:0; padding:6px 8px;
                                    background:linear-gradient(transparent, rgba(4,71,5,.5));
                                    display:flex; gap:4px; flex-wrap:wrap; pointer-events:none">
                            @foreach($metals as $m)
                            <span style="font-size:.6rem; background:rgba(255,255,255,.18); color:#fff;
                                         border:1px solid rgba(255,255,255,.3); padding:2px 6px; border-radius:10px">
                                {{ $m }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="padding:12px 14px 14px">
                        <a href="{{ route('shop.product', $product) }}"
                           style="display:block; font-size:.9rem; font-weight:600; color:var(--fado-deep-green);
                                  text-decoration:none; margin-bottom:6px; line-height:1.35;
                                  transition:color .2s">
                            {{ $product->name }}
                        </a>
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap">
                            <span style="font-size:.8125rem; font-weight:700; color:var(--fado-deep-green)">
                                @if($from)
                                    From {{ app(\App\Services\CurrencyService::class)->format((float)$from) }}
                                @else
                                    <span style="font-style:italic; font-weight:400; color:#999; font-size:.75rem">Price on enquiry</span>
                                @endif
                            </span>
                            <a href="{{ route('shop.product', $product) }}"
                               style="font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
                                      color:var(--fado-green-mid); text-decoration:none">
                                View →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
        @endif

        @endif

    </div>
</div>{{-- /flat-spacing --}}

@endsection

@push('css')
<style>
.fado-product-card:hover img { transform: scale(1.04); }
.fado-product-card:hover a[href] { color: var(--fado-green-mid) !important; }
</style>
@endpush
