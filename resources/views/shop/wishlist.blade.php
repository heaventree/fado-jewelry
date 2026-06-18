@extends('shop.layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Wishlist — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey); padding:24px 0">
    <div class="container">
        <nav aria-label="breadcrumb" style="margin-bottom:6px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.home') }}" style="color:var(--fado-warm-grey); text-decoration:none">Home</a></li>
                <li style="color:var(--fado-warm-grey)">/</li>
                <li style="color:var(--fado-deep-green); font-weight:600">Wishlist</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:var(--fado-deep-green); margin:0">
                Wishlist
                @if($items->isNotEmpty())
                    <span style="font-size:1rem; color:var(--fado-warm-grey); font-weight:400; margin-left:8px">
                        ({{ $items->count() }} {{ $items->count() === 1 ? 'piece' : 'pieces' }})
                    </span>
                @endif
            </h1>
            @guest
            <p style="font-size:.8125rem; color:#888; margin:0">
                <a href="{{ route('login') }}" style="color:var(--fado-green-mid)">Sign in</a>
                to save your wishlist permanently.
            </p>
            @endguest
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('wishlist_removed'))
<div style="background:var(--fado-warm-grey); color:#fff; text-align:center; padding:10px; font-size:.875rem">
    {{ session('wishlist_removed') }}
</div>
@endif
@if(session('wishlist_added'))
<div style="background:var(--fado-green-mid); color:#fff; text-align:center; padding:10px; font-size:.875rem">
    {{ session('wishlist_added') }}
</div>
@endif

<div style="background:var(--fado-near-white); padding:48px 0 80px; min-height:60vh">
    <div class="container">

        @if($items->isEmpty())

        {{-- ── Empty state ─────────────────────────────────────────────────── --}}
        <div style="text-align:center; padding:80px 24px">
            <i class="icon icon-heart"
               style="font-size:4rem; color:var(--fado-warm-grey); display:block; margin-bottom:24px"></i>
            <h2 style="font-family:Georgia,serif; font-size:1.5rem; font-weight:400;
                       color:var(--fado-deep-green); margin-bottom:12px">
                Your wishlist is empty
            </h2>
            <p style="color:#888; margin-bottom:32px; max-width:400px; margin-left:auto; margin-right:auto">
                Save pieces you love and come back to them later.
                Click the <i class="icon icon-heart" style="font-size:.875rem"></i> on any product to add it here.
            </p>
            <a href="{{ route('shop.jewellery') }}"
               style="display:inline-block; padding:14px 36px; background:var(--fado-deep-green);
                      color:#fff; text-decoration:none; border-radius:2px; font-size:.9375rem;
                      font-weight:600; transition:background .2s"
               onmouseover="this.style.background='var(--fado-green-mid)'"
               onmouseout="this.style.background='var(--fado-deep-green)'">
                Browse Jewellery
            </a>
        </div>

        @else

        {{-- ── Wishlist grid ────────────────────────────────────────────────── --}}
        <div class="row g-4">
            @foreach($items as $item)
            @php
                $product = $item['product'];
                $variant = $item['variant'] ?? $product->variants->first();
                $img     = $product->primaryImage;
                $from    = $product->variants->min('price_eur');
                $metals  = $product->variants->pluck('metal.name')->filter()->unique()->take(2);
            @endphp

            <div class="col-6 col-md-4 col-xl-3 wow fadeInUp" data-wow-delay="{{ ($loop->index % 4) * .06 }}s">
                <div class="fado-wishlist-card"
                     style="background:#fff; border:1px solid var(--fado-cream); border-radius:3px; overflow:hidden;
                            transition:box-shadow .2s"
                     onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'"
                     onmouseout="this.style.boxShadow='none'">

                    {{-- Image --}}
                    <div style="position:relative; background:var(--fado-cream); aspect-ratio:3/4; overflow:hidden">
                        <a href="{{ route('shop.product', $product) }}" style="display:block; width:100%; height:100%">
                            @if($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                                     style="width:100%; height:100%; object-fit:cover; object-position:center top;
                                            transition:transform .4s ease">
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

                        {{-- Remove button (top-right) --}}
                        <form method="POST" action="{{ route('shop.wishlist.remove') }}"
                              style="position:absolute; top:10px; right:10px">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                    title="Remove from wishlist"
                                    style="background:#fff; border:none; width:34px; height:34px; border-radius:50%;
                                           display:flex; align-items:center; justify-content:center;
                                           box-shadow:0 2px 8px rgba(0,0,0,.12); cursor:pointer;
                                           color:var(--fado-green-mid); transition:color .2s"
                                    onmouseover="this.style.color='#dc3545'"
                                    onmouseout="this.style.color='var(--fado-green-mid)'">
                                <i class="icon icon-heart-fill" style="font-size:.8rem"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Info --}}
                    <div style="padding:14px 16px 16px">
                        <a href="{{ route('shop.product', $product) }}" class="fado-wishlist-name"
                           style="display:block; font-size:.9rem; font-weight:600; color:var(--fado-deep-green);
                                  text-decoration:none; margin-bottom:4px; line-height:1.35; transition:color .2s">
                            {{ $product->name }}
                        </a>

                        @if($variant?->metal)
                        <p style="font-size:.75rem; color:#999; margin-bottom:8px">
                            {{ $variant->metal->name }}{{ $variant->gemstone ? ' / ' . $variant->gemstone->name : '' }}
                        </p>
                        @endif

                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap">
                            <span style="font-size:.9rem; font-weight:700; color:var(--fado-deep-green)">
                                @if($from)
                                    {{ $currency->format((float)$from) }}
                                @else
                                    <span style="font-style:italic; font-weight:400; color:#999">Price on enquiry</span>
                                @endif
                            </span>

                            <a href="{{ route('shop.product', $product) }}"
                               style="font-size:.75rem; font-weight:600; color:var(--fado-green-mid);
                                      text-decoration:none; white-space:nowrap; letter-spacing:.03em">
                                View →
                            </a>
                        </div>

                        {{-- Add to bag shortcut --}}
                        @if($variant && $variant->stock > 0)
                        <form method="POST" action="{{ route('shop.cart.add') }}" style="margin-top:10px">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                            <input type="hidden" name="quantity"   value="1">
                            <button type="submit"
                                    style="width:100%; padding:8px; background:var(--fado-deep-green); color:#fff;
                                           border:none; border-radius:2px; font-size:.8125rem; font-weight:600;
                                           cursor:pointer; transition:background .2s; letter-spacing:.03em"
                                    onmouseover="this.style.background='var(--fado-green-mid)'"
                                    onmouseout="this.style.background='var(--fado-deep-green)'">
                                Add to Bag
                            </button>
                        </form>
                        @elseif($variant && $variant->stock === 0)
                        <p style="margin-top:10px; font-size:.75rem; color:var(--fado-warm-grey);
                                  text-align:center; padding:8px 0">
                            Out of stock
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Actions footer --}}
        <div style="margin-top:40px; display:flex; gap:16px; flex-wrap:wrap; align-items:center">
            <a href="{{ route('shop.jewellery') }}"
               style="font-size:.875rem; color:var(--fado-green-mid); text-decoration:none">
                ← Continue browsing
            </a>
            @guest
            <span style="font-size:.8125rem; color:#aaa">|</span>
            <p style="font-size:.8125rem; color:#888; margin:0">
                <a href="{{ route('login') }}" style="color:var(--fado-green-mid)">Sign in</a>
                to save your wishlist across devices.
            </p>
            @endguest
        </div>

        @endif

    </div>
</div>

@endsection

@push('css')
<style>
.fado-wishlist-card:hover .fado-wishlist-name { color: var(--fado-green-mid) !important; }
.fado-wishlist-card:hover img { transform: scale(1.04); }
</style>
@endpush
