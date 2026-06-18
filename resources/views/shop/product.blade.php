@extends('shop.layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', $product->name . ' — FADÓ Jewellery')
@section('meta_description', $product->short_description ?: 'Fine Irish jewellery — ' . $product->name . ' — handcrafted in sterling silver, gold and platinum.')

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- BREADCRUMB + FLASH                                                         --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey); padding:12px 0">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <nav aria-label="breadcrumb">
                <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                    <li><a href="{{ route('shop.home') }}" style="color:var(--fado-warm-grey); text-decoration:none">Home</a></li>
                    <li style="color:var(--fado-warm-grey)">/</li>
                    @if($product->categories->isNotEmpty())
                        @php $cat = $product->categories->first(); @endphp
                        @if($cat->parent)
                            <li><a href="{{ route('shop.category', $cat->parent->slug) }}" style="color:var(--fado-warm-grey); text-decoration:none">{{ $cat->parent->name }}</a></li>
                            <li style="color:var(--fado-warm-grey)">/</li>
                        @endif
                        <li><a href="{{ route('shop.category', $cat->slug) }}" style="color:var(--fado-warm-grey); text-decoration:none">{{ $cat->name }}</a></li>
                        <li style="color:var(--fado-warm-grey)">/</li>
                    @else
                        <li><a href="{{ route('shop.jewellery') }}" style="color:var(--fado-warm-grey); text-decoration:none">Jewellery</a></li>
                        <li style="color:var(--fado-warm-grey)">/</li>
                    @endif
                    <li style="color:var(--fado-deep-green); font-weight:600">{{ $product->name }}</li>
                </ol>
            </nav>

            @if($product->collections->isNotEmpty())
            <div class="d-flex gap-2 flex-wrap">
                @foreach($product->collections as $col)
                <a href="{{ route('shop.collection', $col->slug) }}"
                   style="font-size:.7rem; letter-spacing:.1em; text-transform:uppercase;
                          color:var(--fado-gold); text-decoration:none; font-weight:600">
                    {{ $col->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Cart success flash --}}
@if(session('cart_success'))
<div style="background:var(--fado-green-mid); color:#fff; text-align:center; padding:12px; font-size:.875rem; font-weight:600">
    {{ session('cart_success') }}
    <a href="{{ route('shop.cart') }}" style="color:#fff; text-decoration:underline; margin-left:12px">View bag →</a>
</div>
@endif


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MAIN PRODUCT SECTION                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding:48px 0 80px; background:var(--fado-near-white)">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">

            {{-- ── VERTICAL THUMBNAIL STRIP (desktop only) ─────────────────────── --}}
            <div class="col-auto d-none d-lg-flex flex-column align-items-center"
                 style="width:88px; padding-top:0">

                {{-- Scroll up --}}
                <button id="thumbUp" onclick="scrollThumbs(-1)"
                        style="background:none; border:1px solid var(--fado-cream); border-radius:2px;
                               width:72px; height:32px; cursor:pointer; color:var(--fado-deep-green);
                               display:flex; align-items:center; justify-content:center;
                               margin-bottom:6px; transition:border-color .2s; flex-shrink:0"
                        onmouseover="this.style.borderColor='var(--fado-green-mid)'"
                        onmouseout="this.style.borderColor='var(--fado-cream)'"
                        disabled>
                    <i class="icon icon-caret-up" style="font-size:.75rem"></i>
                </button>

                {{-- Thumb list container (clipped height) --}}
                <div style="overflow:hidden; height:480px; flex-shrink:0">
                    <div id="thumbList" style="display:flex; flex-direction:column; gap:8px; transition:transform .3s ease">
                        @forelse($product->images as $i => $img)
                        <div class="fado-thumb {{ $i === 0 ? 'active' : '' }}"
                             onclick="selectImage({{ $i }})"
                             style="width:72px; height:90px; border-radius:2px; overflow:hidden;
                                    cursor:pointer; flex-shrink:0; border:2px solid {{ $i === 0 ? 'var(--fado-green-mid)' : 'transparent' }};
                                    transition:border-color .2s; background:var(--fado-cream)">
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                                 style="width:100%; height:100%; object-fit:cover; object-position:center top">
                        </div>
                        @empty
                        <div style="width:72px; height:90px; border-radius:2px; background:var(--fado-cream);
                                    display:flex; align-items:center; justify-content:center;
                                    border:2px solid var(--fado-green-mid)">
                            <i class="icon icon-gem" style="color:var(--fado-warm-grey)"></i>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Scroll down --}}
                <button id="thumbDown" onclick="scrollThumbs(1)"
                        style="background:none; border:1px solid var(--fado-cream); border-radius:2px;
                               width:72px; height:32px; cursor:pointer; color:var(--fado-deep-green);
                               display:flex; align-items:center; justify-content:center;
                               margin-top:6px; transition:border-color .2s; flex-shrink:0"
                        onmouseover="this.style.borderColor='var(--fado-green-mid)'"
                        onmouseout="this.style.borderColor='var(--fado-cream)'"
                        {{ $product->images->count() <= 4 ? 'disabled' : '' }}>
                    <i class="icon icon-caret-down" style="font-size:.75rem"></i>
                </button>

            </div>

            {{-- ── HERO IMAGE ──────────────────────────────────────────────────── --}}
            <div class="col-12 col-lg">
                <div style="position:relative; background:var(--fado-cream); border-radius:4px;
                            overflow:hidden; aspect-ratio:3/4; max-height:600px">
                    @if($product->images->isNotEmpty())
                    <img id="heroImage" src="{{ Storage::url($product->images->first()->path) }}"
                         alt="{{ $product->name }}"
                         style="width:100%; height:100%; object-fit:cover; object-position:center top;
                                transition:opacity .25s ease">
                    @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;
                                flex-direction:column; gap:16px">
                        <i class="icon icon-gem" style="font-size:4rem; color:var(--fado-warm-grey)"></i>
                        <p style="color:var(--fado-warm-grey); font-size:.875rem; margin:0">Image coming soon</p>
                    </div>
                    @endif

                    {{-- Wishlist overlay button --}}
                    <div style="position:absolute; top:16px; right:16px">
                        <form method="POST" action="{{ route('shop.wishlist') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                    style="background:#fff; border:none; width:42px; height:42px; border-radius:50%;
                                           display:flex; align-items:center; justify-content:center;
                                           box-shadow:0 2px 12px rgba(0,0,0,.12); cursor:pointer;
                                           color:var(--fado-deep-green); transition:color .2s"
                                    title="Save to wishlist"
                                    onmouseover="this.style.color='var(--fado-green-mid)'"
                                    onmouseout="this.style.color='var(--fado-deep-green)'">
                                <i class="icon icon-heart" style="font-size:1rem"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Mobile thumbnail strip (horizontal scroll) --}}
                @if($product->images->count() > 1)
                <div class="d-flex d-lg-none gap-2 mt-3" style="overflow-x:auto; padding-bottom:4px">
                    @foreach($product->images as $i => $img)
                    <div class="fado-thumb-mob {{ $i === 0 ? 'active' : '' }}"
                         onclick="selectImage({{ $i }})"
                         style="flex-shrink:0; width:64px; height:80px; border-radius:2px; overflow:hidden;
                                cursor:pointer; border:2px solid {{ $i === 0 ? 'var(--fado-green-mid)' : 'transparent' }};
                                background:var(--fado-cream); transition:border-color .2s">
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                             style="width:100%; height:100%; object-fit:cover; object-position:center top">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ── PRODUCT INFO PANEL ──────────────────────────────────────────── --}}
            <div class="col-12 col-lg-5">

                {{-- Name --}}
                <h1 style="font-family:Georgia,serif; font-size:clamp(1.5rem,2.5vw,2rem);
                           color:var(--fado-deep-green); font-weight:400; line-height:1.25;
                           margin-bottom:12px">
                    {{ $product->name }}
                </h1>

                @if($product->short_description)
                <p style="color:#555; font-size:.9375rem; line-height:1.75; margin-bottom:20px">
                    {{ $product->short_description }}
                </p>
                @endif

                {{-- Price + stock --}}
                <div style="display:flex; align-items:baseline; gap:14px; margin-bottom:8px">
                    <span id="variantPrice"
                          style="font-size:1.875rem; font-weight:700; color:var(--fado-deep-green); letter-spacing:-.02em">
                        @if($defaultVariant)
                            €{{ number_format((float) $defaultVariant->price_eur, 2) }}
                        @else
                            Price on enquiry
                        @endif
                    </span>
                    <span id="stockStatus"
                          style="font-size:.8125rem; font-weight:600;
                                 color:{{ $defaultVariant && $defaultVariant->stock > 0 ? 'var(--fado-green-mid)' : '#dc3545' }}">
                        @if($defaultVariant)
                            @if($defaultVariant->stock > 0)
                                {{ $defaultVariant->stock < 5 ? 'Only ' . $defaultVariant->stock . ' left' : 'In Stock' }}
                            @else
                                Out of Stock
                            @endif
                        @endif
                    </span>
                </div>

                @if($defaultVariant?->sku)
                <p style="font-size:.75rem; color:var(--fado-warm-grey); margin-bottom:20px">
                    SKU: <span id="variantSku">{{ $defaultVariant->sku }}</span>
                </p>
                @endif

                <hr style="border-color:var(--fado-cream); margin:24px 0">

                {{-- ── VARIANT SELECTOR FORM ──────────────────────────────── --}}
                <form method="POST" action="{{ route('shop.cart.add') }}" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $defaultVariant?->id }}">
                    <input type="hidden" name="size_id" id="selectedSizeId" value="">

                    {{-- Metal selector --}}
                    @if($metals->isNotEmpty())
                    <div style="margin-bottom:20px">
                        <label style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                      text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:8px">
                            Metal
                        </label>
                        <div style="position:relative">
                            <select id="metalSelect" onchange="onMetalChange()"
                                    style="width:100%; padding:11px 40px 11px 14px;
                                           border:1px solid var(--fado-warm-grey); border-radius:3px;
                                           font-size:.9375rem; color:var(--fado-deep-green); background:#fff;
                                           appearance:none; cursor:pointer; outline:none;
                                           background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E\");
                                           background-repeat:no-repeat; background-position:right 14px center"
                                    onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                    onblur="this.style.borderColor='var(--fado-warm-grey)'">
                                @foreach($metals as $metal)
                                <option value="{{ $metal->id }}"
                                        {{ $defaultVariant?->metal_id === $metal->id ? 'selected' : '' }}>
                                    {{ $metal->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    {{-- Gemstone selector --}}
                    <div id="gemstoneWrap" style="margin-bottom:20px; {{ $gemstones->isEmpty() ? 'display:none' : '' }}">
                        <label style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                      text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:8px">
                            Gemstone
                        </label>
                        <div style="position:relative">
                            <select id="gemstoneSelect" onchange="onGemstoneChange()"
                                    style="width:100%; padding:11px 40px 11px 14px;
                                           border:1px solid var(--fado-warm-grey); border-radius:3px;
                                           font-size:.9375rem; color:var(--fado-deep-green); background:#fff;
                                           appearance:none; cursor:pointer; outline:none;
                                           background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E\");
                                           background-repeat:no-repeat; background-position:right 14px center"
                                    onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                    onblur="this.style.borderColor='var(--fado-warm-grey)'">
                                <option value="">No gemstone</option>
                                @foreach($gemstones as $gem)
                                <option value="{{ $gem->id }}">{{ $gem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Ring size selector --}}
                    @if($product->sizes->isNotEmpty())
                    <div style="margin-bottom:20px">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
                            <label style="font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green)">
                                Ring Size (US)
                            </label>
                            <a href="#size-guide"
                               onclick="document.getElementById('sizeGuideAccordion').open=true; return false;"
                               style="font-size:.75rem; color:var(--fado-green-mid); text-decoration:none">
                                Size guide
                            </a>
                        </div>
                        <div style="position:relative">
                            <select id="sizeSelect"
                                    onchange="document.getElementById('selectedSizeId').value = this.options[this.selectedIndex].dataset.sizeId || ''"
                                    style="width:100%; padding:11px 40px 11px 14px;
                                           border:1px solid var(--fado-warm-grey); border-radius:3px;
                                           font-size:.9375rem; color:var(--fado-deep-green); background:#fff;
                                           appearance:none; cursor:pointer; outline:none;
                                           background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23BCB3AB'/%3E%3C/svg%3E\");
                                           background-repeat:no-repeat; background-position:right 14px center"
                                    onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                    onblur="this.style.borderColor='var(--fado-warm-grey)'">
                                <option value="">Select a size</option>
                                @foreach($product->sizes as $size)
                                <option value="{{ $size->us_size }}"
                                        data-size-id="{{ $size->id }}"
                                        {{ $size->stock == 0 ? 'disabled' : '' }}>
                                    US {{ number_format((float)$size->us_size, 1) }}{{ $size->stock == 0 ? ' — Sold Out' : ($size->stock < 3 ? ' — Almost gone' : '') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    {{-- Quantity + Add to Cart --}}
                    <div style="display:flex; gap:12px; align-items:stretch; margin-bottom:14px">
                        {{-- Qty stepper --}}
                        <div style="display:flex; border:1px solid var(--fado-warm-grey); border-radius:3px; overflow:hidden; flex-shrink:0">
                            <button type="button" onclick="stepQty(-1)"
                                    style="width:40px; background:#fff; border:none; cursor:pointer;
                                           font-size:1.25rem; color:var(--fado-deep-green); line-height:1">−</button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="10"
                                   style="width:44px; border:none; border-left:1px solid var(--fado-cream);
                                          border-right:1px solid var(--fado-cream); text-align:center;
                                          font-size:.9375rem; color:var(--fado-deep-green); outline:none">
                            <button type="button" onclick="stepQty(1)"
                                    style="width:40px; background:#fff; border:none; cursor:pointer;
                                           font-size:1.25rem; color:var(--fado-deep-green); line-height:1">+</button>
                        </div>

                        {{-- Add to Cart --}}
                        <button type="submit" id="addToCartBtn"
                                style="flex:1; padding:12px 20px; background:var(--fado-deep-green); color:#fff;
                                       border:none; border-radius:3px; font-size:.9375rem; font-weight:600;
                                       cursor:pointer; letter-spacing:.03em; transition:background .2s"
                                onmouseover="this.style.background='var(--fado-green-mid)'"
                                onmouseout="this.style.background='var(--fado-deep-green)'">
                            Add to Bag
                        </button>
                    </div>

                    {{-- Wishlist text button --}}
                    <button type="button" onclick="addToWishlist()"
                            style="width:100%; padding:11px; background:transparent;
                                   border:1px solid var(--fado-warm-grey); border-radius:3px;
                                   font-size:.875rem; color:var(--fado-deep-green); cursor:pointer;
                                   display:flex; align-items:center; justify-content:center; gap:8px;
                                   transition:border-color .2s"
                            onmouseover="this.style.borderColor='var(--fado-green-mid)'"
                            onmouseout="this.style.borderColor='var(--fado-warm-grey)'">
                        <i class="icon icon-heart" style="font-size:.875rem"></i>
                        Save to Wishlist
                    </button>
                </form>

                <hr style="border-color:var(--fado-cream); margin:28px 0">

                {{-- ── ACCORDION DETAILS ──────────────────────────────────── --}}
                <div class="fado-accordion">

                    {{-- Description accordion --}}
                    @if($product->description)
                    <details class="fado-accordion-item" open>
                        <summary class="fado-accordion-trigger">
                            <span>Description</span>
                            <i class="icon icon-caret-down fado-accordion-icon"></i>
                        </summary>
                        <div class="fado-accordion-body" style="font-size:.9rem; color:#555; line-height:1.8">
                            {!! $product->description !!}
                        </div>
                    </details>
                    @endif

                    {{-- Delivery --}}
                    <details class="fado-accordion-item">
                        <summary class="fado-accordion-trigger">
                            <span>Delivery & Shipping</span>
                            <i class="icon icon-caret-down fado-accordion-icon"></i>
                        </summary>
                        <div class="fado-accordion-body" style="font-size:.9rem; color:#555; line-height:1.8">
                            <p>Free standard delivery on all orders over €75. Orders are dispatched within 2–3 working days and typically arrive within 5–7 working days across Ireland and Europe.</p>
                            <p>Express delivery (1–2 working days) is available at checkout. International shipping to the US and worldwide is also available.</p>
                        </div>
                    </details>

                    {{-- Returns --}}
                    <details class="fado-accordion-item">
                        <summary class="fado-accordion-trigger">
                            <span>Returns & Exchanges</span>
                            <i class="icon icon-caret-down fado-accordion-icon"></i>
                        </summary>
                        <div class="fado-accordion-body" style="font-size:.9rem; color:#555; line-height:1.8">
                            <p>We offer a 30-day return policy on all unworn items in their original packaging. For size exchanges, please contact us within 30 days of receiving your order.</p>
                            <p>Custom or engraved pieces cannot be returned unless faulty.</p>
                        </div>
                    </details>

                    {{-- Ring size guide (shown only if product has sizes) --}}
                    @if($product->sizes->isNotEmpty())
                    <details class="fado-accordion-item" id="sizeGuideAccordion">
                        <summary class="fado-accordion-trigger">
                            <span>Ring Size Guide</span>
                            <i class="icon icon-caret-down fado-accordion-icon"></i>
                        </summary>
                        <div class="fado-accordion-body" style="font-size:.85rem; color:#555">
                            <p style="margin-bottom:12px">US ring sizes available for this piece:</p>
                            <div style="display:flex; flex-wrap:wrap; gap:6px">
                                @foreach($product->sizes as $size)
                                <span style="padding:4px 10px; border-radius:20px; font-size:.8125rem; font-weight:600;
                                             background:{{ $size->stock > 0 ? 'var(--fado-cream)' : '#f5f5f5' }};
                                             color:{{ $size->stock > 0 ? 'var(--fado-deep-green)' : 'var(--fado-warm-grey)' }};
                                             border:1px solid {{ $size->stock > 0 ? 'var(--fado-warm-grey)' : 'var(--fado-cream)' }};
                                             text-decoration:{{ $size->stock > 0 ? 'none' : 'line-through' }}">
                                    US {{ number_format((float)$size->us_size, 1) }}
                                </span>
                                @endforeach
                            </div>
                            <p style="font-size:.8125rem; color:var(--fado-warm-grey); margin-top:12px; margin-bottom:0">
                                Not sure of your size?
                                <a href="{{ route('shop.contact') }}" style="color:var(--fado-green-mid)">Contact us</a>
                                and we'll help you find the perfect fit.
                            </p>
                        </div>
                    </details>
                    @endif

                </div>

                {{-- Meta info --}}
                <div style="margin-top:24px; font-size:.8125rem; color:var(--fado-warm-grey); display:flex; flex-direction:column; gap:4px">
                    @if($product->categories->isNotEmpty())
                    <span>
                        Category:
                        @foreach($product->categories as $cat)
                            <a href="{{ route('shop.category', $cat->slug) }}"
                               style="color:var(--fado-green-mid); text-decoration:none">{{ $cat->name }}</a>{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </span>
                    @endif
                    @if($product->collections->isNotEmpty())
                    <span>
                        Collection:
                        @foreach($product->collections as $col)
                            <a href="{{ route('shop.collection', $col->slug) }}"
                               style="color:var(--fado-green-mid); text-decoration:none">{{ $col->name }}</a>{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </span>
                    @endif
                </div>

            </div>{{-- /info panel --}}
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- RELATED PRODUCTS                                                            --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
@if($related->isNotEmpty())
<section style="padding:56px 0 80px; background:#fff; border-top:1px solid var(--fado-cream)">
    <div class="container">
        <h2 style="font-family:Georgia,serif; font-size:1.5rem; font-weight:400;
                   color:var(--fado-deep-green); margin-bottom:32px; text-align:center">
            You might also like
        </h2>
        <div class="row g-4">
            @foreach($related as $rel)
            @php
                $relImg  = $rel->primaryImage;
                $relFrom = $rel->variants->min('price_eur');
                $relMets = $rel->variants->pluck('metal.name')->filter()->unique()->take(2);
            @endphp
            <div class="col-6 col-lg-3">
                <a href="{{ route('shop.product', $rel) }}" class="fado-related-card d-block text-decoration-none">
                    <div style="background:var(--fado-cream); border-radius:3px; overflow:hidden;
                                aspect-ratio:3/4; margin-bottom:12px; position:relative">
                        @if($relImg)
                            <img src="{{ Storage::url($relImg->path) }}" alt="{{ $rel->name }}"
                                 style="width:100%; height:100%; object-fit:cover; object-position:center top;
                                        transition:transform .4s ease">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center">
                                <i class="icon icon-gem" style="font-size:2.5rem; color:var(--fado-warm-grey)"></i>
                            </div>
                        @endif
                        @if($relMets->isNotEmpty())
                        <div style="position:absolute; bottom:0; left:0; right:0; padding:6px 8px;
                                    background:linear-gradient(transparent, rgba(4,71,5,.5)); display:flex; gap:4px">
                            @foreach($relMets as $m)
                            <span style="font-size:.6rem; background:rgba(255,255,255,.18); color:#fff;
                                         border:1px solid rgba(255,255,255,.3); padding:2px 6px; border-radius:10px">
                                {{ $m }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <p class="fado-related-name"
                       style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green);
                              margin-bottom:4px; line-height:1.4; transition:color .2s">
                        {{ $rel->name }}
                    </p>
                    <p style="font-size:.8125rem; color:var(--fado-warm-grey); margin:0">
                        @if($relFrom)
                            From <strong style="color:var(--fado-deep-green)">€{{ number_format((float)$relFrom, 2) }}</strong>
                        @else
                            <span style="font-style:italic">Price on enquiry</span>
                        @endif
                    </p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection


@push('css')
<style>
/* ── Accordion ── */
.fado-accordion-item {
    border-bottom: 1px solid var(--fado-cream);
}
.fado-accordion-item:first-child {
    border-top: 1px solid var(--fado-cream);
}
.fado-accordion-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    cursor: pointer;
    list-style: none;
    font-size: .875rem;
    font-weight: 600;
    color: var(--fado-deep-green);
    letter-spacing: .02em;
    user-select: none;
}
.fado-accordion-trigger::-webkit-details-marker { display: none; }
.fado-accordion-icon { transition: transform .25s ease; font-size: .75rem; }
details[open] .fado-accordion-icon { transform: rotate(180deg); }
.fado-accordion-body {
    padding-bottom: 20px;
}
.fado-accordion-body p { margin-bottom: 10px; }
.fado-accordion-body p:last-child { margin-bottom: 0; }

/* ── Thumbnail active state ── */
.fado-thumb.active { border-color: var(--fado-green-mid) !important; }
.fado-thumb-mob.active { border-color: var(--fado-green-mid) !important; }

/* ── Related product hover ── */
.fado-related-card:hover .fado-related-name { color: var(--fado-green-mid) !important; }
.fado-related-card:hover img { transform: scale(1.04); }

/* ── Qty input – hide browser spinners ── */
#qtyInput::-webkit-inner-spin-button,
#qtyInput::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
#qtyInput { -moz-appearance: textfield; }
</style>
@endpush


@push('scripts')
<script>
// ── Image data from PHP ────────────────────────────────────────────────────
const productImages = @json($imageData);
const variantData   = @json($variantData);

// ── Thumbnail state ────────────────────────────────────────────────────────
let activeThumbIndex = 0;
let thumbOffset      = 0;
const THUMB_STEP     = 98; // 90px height + 8px gap
const VISIBLE_THUMBS = 4;

function selectImage(index) {
    activeThumbIndex = index;

    if (productImages[index]) {
        const hero = document.getElementById('heroImage');
        if (hero) {
            hero.style.opacity = '0';
            setTimeout(() => {
                hero.src = productImages[index].url;
                hero.style.opacity = '1';
            }, 150);
        }
    }

    // Update desktop thumb borders
    document.querySelectorAll('.fado-thumb').forEach((el, i) => {
        el.style.borderColor = i === index ? 'var(--fado-green-mid)' : 'transparent';
        el.classList.toggle('active', i === index);
    });

    // Update mobile thumb borders
    document.querySelectorAll('.fado-thumb-mob').forEach((el, i) => {
        el.style.borderColor = i === index ? 'var(--fado-green-mid)' : 'transparent';
        el.classList.toggle('active', i === index);
    });
}

function scrollThumbs(direction) {
    const total    = productImages.length;
    const maxOff   = Math.max(0, (total - VISIBLE_THUMBS) * THUMB_STEP);
    thumbOffset    = Math.max(0, Math.min(maxOff, thumbOffset + direction * THUMB_STEP));
    const list     = document.getElementById('thumbList');
    if (list) list.style.transform = `translateY(-${thumbOffset}px)`;

    const upBtn   = document.getElementById('thumbUp');
    const downBtn = document.getElementById('thumbDown');
    if (upBtn)   upBtn.disabled   = thumbOffset === 0;
    if (downBtn) downBtn.disabled = thumbOffset >= maxOff;
}

// ── Variant switching ──────────────────────────────────────────────────────
function formatPrice(eur) {
    return '€' + parseFloat(eur).toLocaleString('en-IE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function applyVariant(variant) {
    if (!variant) return;

    // Price
    const priceEl = document.getElementById('variantPrice');
    if (priceEl) priceEl.textContent = formatPrice(variant.price_eur);

    // Stock
    const stockEl = document.getElementById('stockStatus');
    if (stockEl) {
        if (variant.stock > 0) {
            stockEl.textContent = variant.stock < 5 ? 'Only ' + variant.stock + ' left' : 'In Stock';
            stockEl.style.color = variant.stock < 5 ? 'var(--fado-gold)' : 'var(--fado-green-mid)';
        } else {
            stockEl.textContent = 'Out of Stock';
            stockEl.style.color = '#dc3545';
        }
    }

    // SKU
    const skuEl = document.getElementById('variantSku');
    if (skuEl && variant.sku) skuEl.textContent = variant.sku;

    // Hidden variant_id for cart form
    const hiddenEl = document.getElementById('selectedVariantId');
    if (hiddenEl) hiddenEl.value = variant.id;

    // Disable add to cart if out of stock
    const btn = document.getElementById('addToCartBtn');
    if (btn) {
        btn.disabled = variant.stock <= 0;
        btn.style.background = variant.stock <= 0 ? 'var(--fado-warm-grey)' : 'var(--fado-deep-green)';
        btn.textContent = variant.stock <= 0 ? 'Out of Stock' : 'Add to Bag';
        btn.onmouseover = variant.stock > 0 ? () => btn.style.background = 'var(--fado-green-mid)' : null;
        btn.onmouseout  = variant.stock > 0 ? () => btn.style.background = 'var(--fado-deep-green)' : null;
    }
}

function findBestVariant(metalId, gemstoneId) {
    metalId = parseInt(metalId) || null;
    gemstoneId = parseInt(gemstoneId) || null;

    // Exact match
    let v = variantData.find(v => v.metal_id === metalId && v.gemstone_id === gemstoneId);
    if (v) return v;

    // If gemstone not matched, fall back to same metal with no gemstone
    v = variantData.find(v => v.metal_id === metalId && !v.gemstone_id);
    if (v) return v;

    // Fall back to any variant for this metal
    return variantData.find(v => v.metal_id === metalId) || variantData[0] || null;
}

function onMetalChange() {
    const metalId      = parseInt(document.getElementById('metalSelect')?.value) || null;
    const gemstoneWrap = document.getElementById('gemstoneWrap');
    const gemstoneEl   = document.getElementById('gemstoneSelect');

    // Rebuild gemstone options for selected metal
    if (gemstoneEl) {
        const gems = variantData
            .filter(v => v.metal_id === metalId && v.gemstone_id)
            .reduce((acc, v) => {
                if (!acc.find(g => g.id === v.gemstone_id)) {
                    acc.push({ id: v.gemstone_id, name: v.gemstone_name });
                }
                return acc;
            }, []);

        if (gems.length > 0) {
            gemstoneEl.innerHTML = '<option value="">No gemstone</option>' +
                gems.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
            if (gemstoneWrap) gemstoneWrap.style.display = 'block';
        } else {
            if (gemstoneWrap) gemstoneWrap.style.display = 'none';
        }
    }

    const gemId = parseInt(gemstoneEl?.value) || null;
    applyVariant(findBestVariant(metalId, gemId));
}

function onGemstoneChange() {
    const metalId    = parseInt(document.getElementById('metalSelect')?.value) || null;
    const gemstoneId = parseInt(document.getElementById('gemstoneSelect')?.value) || null;
    applyVariant(findBestVariant(metalId, gemstoneId));
}

// ── Quantity stepper ───────────────────────────────────────────────────────
function stepQty(delta) {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    const val = Math.max(1, Math.min(10, parseInt(input.value || 1) + delta));
    input.value = val;
}

// ── Wishlist stub (Phase 3 Step 6) ────────────────────────────────────────
function addToWishlist() {
    const btn = event.currentTarget;
    btn.innerHTML = '<i class="icon icon-heart" style="font-size:.875rem; color:var(--fado-green-mid)"></i> Saved!';
    setTimeout(() => {
        btn.innerHTML = '<i class="icon icon-heart" style="font-size:.875rem"></i> Save to Wishlist';
    }, 2000);
}

// ── Init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Initialise thumb scroll state
    scrollThumbs(0); // sets button disabled states without moving

    // Apply default variant display (first variant)
    if (variantData.length > 0) {
        const metalId    = parseInt(document.getElementById('metalSelect')?.value) || null;
        const gemstoneId = parseInt(document.getElementById('gemstoneSelect')?.value) || null;
        applyVariant(findBestVariant(metalId, gemstoneId));
    }
});
</script>
@endpush
