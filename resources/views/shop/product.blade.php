@extends('shop.layouts.app')
@php
    use Illuminate\Support\Str;
    use App\Models\Setting;
    $storeName   = Setting::get('store_name', 'FADÓ Jewellery');
    $metaDesc    = $product->short_description
                   ?: Str::limit(strip_tags($product->description ?? ''), 155)
                   ?: 'Fine Irish jewellery — ' . $product->name . ' — handcrafted in sterling silver, gold and platinum.';
    $ogImage     = $product->primaryImage ? asset($product->primaryImage->path) : asset('favicon.ico');
@endphp

@section('title', $product->name . ' — ' . $storeName)
@section('meta_description', $metaDesc)
@section('canonical', route('shop.product', $product->slug))
@section('og_type', 'product')
@section('og_image', $ogImage)

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">{{ $product->name }}</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                @if($product->categories->isNotEmpty())
                    @php $cat = $product->categories->first(); @endphp
                    @if($cat->parent)
                        <li><a href="{{ route('shop.category', $cat->parent->slug) }}" class="h6 link">{{ $cat->parent->name }}</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    @endif
                    <li><a href="{{ route('shop.category', $cat->slug) }}" class="h6 link">{{ $cat->name }}</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                @else
                    <li><a href="{{ route('shop.jewellery') }}" class="h6 link">Jewellery</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                @endif
                <li><h6 class="current-page fw-normal">{{ $product->name }}</h6></li>
            </ul>
        </div>
        @if($product->collections->isNotEmpty())
        <div class="d-flex gap-8 flex-wrap mt-8">
            @foreach($product->collections as $col)
            <a href="{{ route('shop.collection', $col->slug) }}" class="h6 link fw-semibold">
                {{ $col->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

@if(session('cart_success'))
<div class="tf-notice-wrap">
    <div class="container">
        <p class="h6 text-center fw-semibold">
            {{ session('cart_success') }}
            <a href="{{ route('shop.cart') }}" class="link fw-bold ms-12">View bag →</a>
        </p>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MAIN PRODUCT SECTION                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="flat-single-product flat-spacing-3 section-image-zoom">
    <div class="container">
        <div class="row">

            {{-- ── PRODUCT IMAGES (Ochaka tf-product-media exact) ─────────────── --}}
            <div class="col-md-6">
                <div class="tf-product-media-wrap sticky-top">
                    <div class="product-thumbs-slider">
                        <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical" data-preview="4.7">
                            <div class="swiper-wrapper stagger-wrap">
                                @forelse($product->images as $img)
                                <div class="swiper-slide stagger-item">
                                    <div class="item">
                                        <img class="lazyload" data-src="{{ asset($img->path) }}" src="{{ asset($img->path) }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                @empty
                                <div class="swiper-slide stagger-item">
                                    <div class="item">
                                        <img class="lazyload" data-src="/images/ochaka/products/jewelry/product-5.jpg" src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="flat-wrap-media-product">
                            <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started">
                                <div class="swiper-wrapper">
                                    @forelse($product->images as $img)
                                    <div class="swiper-slide">
                                        <a href="{{ asset($img->path) }}" target="_blank" class="item">
                                            <img class="tf-image-zoom lazyload" data-zoom="{{ asset($img->path) }}" data-src="{{ asset($img->path) }}" src="{{ asset($img->path) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <a href="/images/ochaka/products/jewelry/product-5.jpg" target="_blank" class="item">
                                            <img class="tf-image-zoom lazyload" data-zoom="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Product Images --}}

            {{-- ── PRODUCT INFO (Ochaka tf-product-info exact) ────────────────── --}}
            <div class="col-md-6">
                <div class="tf-product-info-wrap position-relative">
                    <div class="tf-product-info-list other-image-zoom">

                        <h2 class="product-info-name">{{ $product->name }}</h2>

                        @if($product->short_description)
                        <p class="h6 fw-normal" style="color:#555; margin-bottom:16px">{{ $product->short_description }}</p>
                        @endif

                        <div class="tf-product-heading">
                            <div class="product-info-price price-wrap">
                                @if($defaultVariant && $defaultVariant->isOnSale())
                                    <span id="variantPrice" class="price-new price-on-sale h2 fw-4">€{{ number_format((float) $defaultVariant->sale_price_eur, 2) }}</span>
                                    <span id="variantPriceOld" class="price-old compare-at-price h6">€{{ number_format((float) $defaultVariant->price_eur, 2) }}</span>
                                @elseif($defaultVariant)
                                    <span id="variantPrice" class="price-new h2 fw-4">€{{ number_format((float) $defaultVariant->price_eur, 2) }}</span>
                                    <span id="variantPriceOld" class="price-old compare-at-price h6" style="display:none"></span>
                                @else
                                    <span id="variantPrice" class="price-new h2 fw-4">Price on enquiry</span>
                                    <span id="variantPriceOld" class="price-old compare-at-price h6" style="display:none"></span>
                                @endif
                                <span id="stockStatus" class="h6 fw-semibold"
                                      style="margin-left:12px; color:{{ $defaultVariant && $defaultVariant->stock > 0 ? 'var(--fado-green-mid)' : '#dc3545' }}">
                                    @if($defaultVariant)
                                        @if($defaultVariant->stock > 0)
                                            {{ $defaultVariant->stock < 5 ? 'Only ' . $defaultVariant->stock . ' left' : 'In Stock' }}
                                        @else
                                            Out of Stock
                                        @endif
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($defaultVariant?->sku)
                        <p class="h6" style="color:var(--fado-warm-grey); margin-bottom:4px">
                            SKU: <span id="variantSku">{{ $defaultVariant->sku }}</span>
                        </p>
                        @endif

                        <form method="POST" action="{{ route('shop.cart.add') }}" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $defaultVariant?->id }}">
                            <input type="hidden" name="size_id" id="selectedSizeId" value="">

                            <div class="tf-product-variant">

                                {{-- Metal selector --}}
                                @if($metals->isNotEmpty())
                                <div class="variant-picker-item variant-metal" style="margin-bottom:20px">
                                    <div class="variant-picker-label" style="margin-bottom:8px">
                                        <div class="h4 fw-semibold">Metal</div>
                                    </div>
                                    <div class="tf-select select-square">
                                        <select id="metalSelect" onchange="selectMetal(parseInt(this.value), this)">
                                            @foreach($metals as $metal)
                                            <option value="{{ $metal->id }}" {{ $defaultVariant?->metal_id === $metal->id ? 'selected' : '' }}>
                                                {{ $metal->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                {{-- Gemstone selector (options depend on selected metal) --}}
                                <div class="variant-picker-item variant-gemstone" id="gemstoneWrap" style="margin-bottom:20px; {{ $gemstones->isEmpty() ? 'display:none' : '' }}">
                                    <div class="variant-picker-label" style="margin-bottom:8px">
                                        <div class="h4 fw-semibold">Gemstone</div>
                                    </div>
                                    <div class="tf-select select-square">
                                        <select id="gemstoneSelect" onchange="selectGemstone(parseInt(this.value), this)"></select>
                                    </div>
                                </div>

                                {{-- Ring size selector --}}
                                @if($product->sizes->isNotEmpty())
                                <div class="variant-picker-item variant-size" style="margin-bottom:20px">
                                    <div class="variant-picker-label d-flex justify-content-between align-items-center" style="margin-bottom:8px">
                                        <div class="h4 fw-semibold">Ring Size (US)</div>
                                        <a href="#"
                                           onclick="document.getElementById('sizeGuideAccordion').open=true; return false;"
                                           class="size-guide link h6 fw-medium">
                                            <i class="icon icon-ruler"></i> Size Guide
                                        </a>
                                    </div>
                                    <div class="tf-select select-square">
                                        <select id="sizeSelect" onchange="selectSize(this.value ? parseInt(this.options[this.selectedIndex].dataset.sizeId) : null, this)">
                                            <option value="">Select a size</option>
                                            @foreach($product->sizes as $size)
                                            <option value="{{ $size->us_size }}"
                                                    data-size-id="{{ $size->id }}"
                                                    {{ $size->stock == 0 ? 'disabled' : '' }}>
                                                US {{ number_format((float)$size->us_size, 1) }}{{ $size->stock == 0 ? ' — Sold Out' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- ── SPEC TABLE — real fields only: Metal/Second Metal/Gemstone/Colour from product_variants. No weight or purity column exists in the schema, so they are not shown here. ── --}}
                            <div id="variantSpecTable" style="margin:0 0 20px; border:1px solid var(--fado-cream); border-radius:8px; overflow:hidden">
                                <table style="width:100%; font-size:.875rem">
                                    <tbody>
                                        <tr style="border-bottom:1px solid var(--fado-cream)">
                                            <td style="padding:10px 14px; color:var(--fado-warm-grey); font-weight:600; width:45%">Metal</td>
                                            <td id="specMetal" style="padding:10px 14px">{{ $defaultVariant?->metal?->name ?? '—' }}</td>
                                        </tr>
                                        <tr id="specSecondMetalRow" style="border-bottom:1px solid var(--fado-cream); {{ $defaultVariant?->secondMetal ? '' : 'display:none' }}">
                                            <td style="padding:10px 14px; color:var(--fado-warm-grey); font-weight:600">Second Metal / Finish</td>
                                            <td id="specSecondMetal" style="padding:10px 14px">{{ $defaultVariant?->secondMetal?->name ?? '—' }}</td>
                                        </tr>
                                        <tr id="specGemstoneRow" style="border-bottom:1px solid var(--fado-cream); {{ $defaultVariant?->gemstone ? '' : 'display:none' }}">
                                            <td style="padding:10px 14px; color:var(--fado-warm-grey); font-weight:600">Gemstone</td>
                                            <td id="specGemstone" style="padding:10px 14px">{{ $defaultVariant?->gemstone?->name ?? '—' }}</td>
                                        </tr>
                                        <tr id="specColourRow" style="{{ $defaultVariant?->colour ? '' : 'display:none' }}">
                                            <td style="padding:10px 14px; color:var(--fado-warm-grey); font-weight:600">Colour</td>
                                            <td id="specColour" style="padding:10px 14px">{{ $defaultVariant?->colour ?? '—' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tf-product-total-quantity">
                                <div class="group-btn">
                                    <div class="wg-quantity">
                                        <button type="button" class="btn-quantity btn-decrease" onclick="stepQty(-1)">
                                            <i class="icon icon-minus"></i>
                                        </button>
                                        <input class="quantity-product" type="number" id="qtyInput" name="quantity" value="1" min="1" max="10">
                                        <button type="button" class="btn-quantity btn-increase" onclick="stepQty(1)">
                                            <i class="icon icon-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" id="addToCartBtn" class="tf-btn animate-btn btn-add-to-cart">
                                        ADD TO BAG
                                        <i class="icon icon-shopping-cart-simple"></i>
                                    </button>
                                    @php $isWishlisted = app(\App\Services\WishlistService::class)->has($product->id); @endphp
                                    <button type="button"
                                            id="wishlistTextBtn"
                                            class="hover-tooltip tooltip-top box-icon btn-add-wishlist"
                                            onclick="toggleWishlist(this)"
                                            data-product-id="{{ $product->id }}"
                                            data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}">
                                        <span class="icon {{ $isWishlisted ? 'icon-heart-fill' : 'icon-heart' }}"></span>
                                        <span class="tooltip">{{ $isWishlisted ? 'Saved to Wishlist' : 'Add to Wishlist' }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <hr style="border-color:var(--fado-cream); margin:28px 0">

                {{-- ── PRODUCT DESCRIPTION TABS ──────────────────────────────────────
                    Source: resources/theme-reference/product-detail.html:1008-1100
                    (`flat-animate-tab tab-style-1` / `menu-tab menu-tab-1` / `tab-link` / `tab-content` /
                    `tab-pane wd-product-descriptions` / `tab-descriptions` / `tab-policy` — classes and
                    structure copied verbatim). Deviations: the reference's single "Shipping, Return & Refund
                    Policy" tab is split into two tabs (Delivery & Shipping / Returns & Exchanges) per this
                    task's explicit 3-tab requirement; the reference's "Customer Reviews" and "FAQs" tabs are
                    Lorem-ipsum/demo-only content with no real FADO equivalent (no review system is built —
                    reviews_enabled setting exists but is off — and no FAQ content exists yet), so they are
                    omitted rather than faked. Tab switching uses Bootstrap's `data-bs-toggle="tab"`, already
                    loaded site-wide (same plugin used by `data-bs-toggle="modal"`/`"offcanvas"` elsewhere). --}}
                <div class="flat-animate-tab tab-style-1">
                    <ul class="menu-tab menu-tab-1" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#descriptions" class="tab-link active" data-bs-toggle="tab">Description</a>
                        </li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#delivery" class="tab-link" data-bs-toggle="tab">Delivery & Shipping</a>
                        </li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#returns" class="tab-link" data-bs-toggle="tab">Returns & Exchanges</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane wd-product-descriptions active show" id="descriptions" role="tabpanel">
                            <div class="tab-descriptions">
                                @if($product->description)
                                    {!! $product->description !!}
                                @else
                                    <p class="h6 desc">No description available yet for this product.</p>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane wd-product-descriptions" id="delivery" role="tabpanel">
                            <div class="tab-policy">
                                <div class="">
                                    <h5 class="mb_16 text-black">Delivery & Shipping</h5>
                                    <p class="h6">{{ \App\Models\Setting::get('shipping_notice', 'Free delivery on orders over €75') }}. Orders are dispatched within 2–3 working days and typically arrive within 5–7 working days across Ireland and Europe.</p>
                                    <p class="h6">Express delivery (1–2 working days) is available at checkout. International shipping to the US and worldwide is also available.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane wd-product-descriptions" id="returns" role="tabpanel">
                            <div class="tab-policy">
                                <div class="">
                                    <h5 class="mb_16 text-black">Returns & Exchanges</h5>
                                    <p class="h6">We offer a 30-day return policy on all unworn items in their original packaging. For size exchanges, please contact us within 30 days of receiving your order.</p>
                                    <p class="h6">Custom or engraved pieces cannot be returned unless faulty.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ring size guide (shown only if product has sizes) — kept as a contextual reveal, not a tab, since the "Size Guide" link beside the Ring Size selector above opens it directly via element id --}}
                @if($product->sizes->isNotEmpty())
                <div class="fado-accordion" style="margin-top:20px">
                    <details class="fado-accordion-item" id="sizeGuideAccordion">
                        <summary class="fado-accordion-trigger">
                            <span>Ring Size Guide</span>
                            <i class="icon icon-caret-down fado-accordion-icon"></i>
                        </summary>
                        <div class="fado-accordion-body" style="color:#555">
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
                </div>
                @endif

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
        <h2 class="h2 fw-normal text-center mb-32">You might also like</h2>
        <div class="wrapper-shop tf-grid-layout tf-col-2 md-col-2 xl-col-4">
            @foreach($related as $rel)
            @php
                $relImg  = $rel->primaryImage;
                $relImg2 = $rel->images->skip(1)->first();
                $relFrom = $rel->variants->min('price_eur');
            @endphp
            <div class="card-product grid wow fadeInUp">
                <div class="card-product_wrapper">
                    <a href="{{ route('shop.product', $rel) }}" class="product-img">
                        @if($relImg)
                            <img class="lazyload img-product" src="{{ asset($relImg->path) }}" data-src="{{ asset($relImg->path) }}" alt="{{ $rel->name }}">
                            @if($relImg2)
                            <img class="lazyload img-hover" src="{{ asset($relImg2->path) }}" data-src="{{ asset($relImg2->path) }}" alt="{{ $rel->name }}">
                            @endif
                        @else
                            <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $rel->name }}">
                        @endif
                    </a>
                    <ul class="product-action_list">
                        <li>
                            <a href="{{ route('shop.product', $rel) }}" class="hover-tooltip tooltip-left box-icon">
                                <span class="icon icon-bag"></span>
                                <span class="tooltip">Shop now</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop.product', $rel) }}" class="hover-tooltip tooltip-left box-icon">
                                <span class="icon icon-view"></span>
                                <span class="tooltip">Quick view</span>
                            </a>
                        </li>
                        <li class="wishlist">
                            <a href="{{ route('shop.wishlist.toggle') }}"
                               class="hover-tooltip tooltip-left box-icon"
                               onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify({product_id: {{ $rel->id }}})}).then(() => location.reload())">
                                <span class="icon icon-heart"></span>
                                <span class="tooltip">Add to Wishlist</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-product_info">
                    <a href="{{ route('shop.product', $rel) }}" class="name-product h4 link">{{ $rel->name }}</a>
                    <div class="price-wrap">
                        @if($relFrom)
                            <span class="price-new h6">From €{{ number_format((float)$relFrom, 2) }}</span>
                        @else
                            <span class="price-new h6 fw-normal" style="font-style:italic">Price on enquiry</span>
                        @endif
                    </div>
                </div>
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
    font-size: 1rem;
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
    font-size: 1rem !important;
}
.fado-accordion-body p { margin-bottom: 10px; }
.fado-accordion-body p:last-child { margin-bottom: 0; }

/* ── Thumbnail active state ── */

/* ── Related product hover ── */
.fado-related-card:hover .fado-related-name { color: var(--fado-green-mid) !important; }
.fado-related-card:hover img { transform: scale(1.04); }

/* ── Qty input – hide browser spinners ── */
#qtyInput::-webkit-inner-spin-button,
#qtyInput::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
#qtyInput { -moz-appearance: textfield; }

/* ── Description tabs — scoped override ──────────────────────────────────
   styles.css:15732-15779 sizes .menu-tab-1 .tab-link at 32px/24px nowrap,
   tuned for the reference theme's short single-word demo labels. Our real
   labels ("Delivery & Shipping", "Returns & Exchanges") don't fit at that
   size and overflowed into a horizontal scrollbar. Scoped to this page only
   (not editing styles.css) so other tab instances elsewhere are unaffected. */
.flat-single-product .flat-animate-tab .menu-tab-1 {
    flex-wrap: wrap;
    overflow: visible;
}
.flat-single-product .flat-animate-tab .menu-tab-1 .tab-link {
    font-size: 18px;
    padding: 0 16px 14px;
}
@media (max-width: 575px) {
    .flat-single-product .flat-animate-tab .menu-tab-1 .tab-link {
        font-size: 15px;
        padding: 0 10px 12px;
    }
}
</style>
@endpush


@push('scripts')
<script>
// ── Image data from PHP ────────────────────────────────────────────────────
const variantData = @json($variantData);

// ── Variant switching ──────────────────────────────────────────────────────
function formatPrice(eur) {
    return '€' + parseFloat(eur).toLocaleString('en-IE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function applyVariant(variant) {
    if (!variant) return;

    // Price (sale-aware)
    const priceEl = document.getElementById('variantPrice');
    const priceOldEl = document.getElementById('variantPriceOld');
    const onSale = variant.sale_price_eur !== null && parseFloat(variant.sale_price_eur) < parseFloat(variant.price_eur);
    if (priceEl) {
        priceEl.textContent = formatPrice(onSale ? variant.sale_price_eur : variant.price_eur);
        priceEl.classList.toggle('price-on-sale', onSale);
    }
    if (priceOldEl) {
        if (onSale) {
            priceOldEl.textContent = formatPrice(variant.price_eur);
            priceOldEl.style.display = '';
        } else {
            priceOldEl.style.display = 'none';
        }
    }

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

    // Spec table (real fields only — metal/second metal/gemstone/colour)
    const specMetal = document.getElementById('specMetal');
    if (specMetal) specMetal.textContent = variant.metal_name || '—';

    const secondRow = document.getElementById('specSecondMetalRow');
    const secondCell = document.getElementById('specSecondMetal');
    if (variant.second_metal_name) {
        if (secondCell) secondCell.textContent = variant.second_metal_name;
        if (secondRow) secondRow.style.display = '';
    } else if (secondRow) {
        secondRow.style.display = 'none';
    }

    const gemRow = document.getElementById('specGemstoneRow');
    const gemCell = document.getElementById('specGemstone');
    if (variant.gemstone_name) {
        if (gemCell) gemCell.textContent = variant.gemstone_name;
        if (gemRow) gemRow.style.display = '';
    } else if (gemRow) {
        gemRow.style.display = 'none';
    }

    const colourRow = document.getElementById('specColourRow');
    const colourCell = document.getElementById('specColour');
    if (variant.colour) {
        if (colourCell) colourCell.textContent = variant.colour;
        if (colourRow) colourRow.style.display = '';
    } else if (colourRow) {
        colourRow.style.display = 'none';
    }

    // Hidden variant_id for cart form
    const hiddenEl = document.getElementById('selectedVariantId');
    if (hiddenEl) hiddenEl.value = variant.id;

    // Disable add to cart if out of stock
    const btn = document.getElementById('addToCartBtn');
    if (btn) {
        btn.disabled = variant.stock <= 0;
        btn.innerHTML = variant.stock <= 0
            ? 'OUT OF STOCK'
            : 'ADD TO BAG <i class="icon icon-shopping-cart-simple"></i>';
        btn.style.opacity = variant.stock <= 0 ? '.5' : '1';
        btn.style.cursor = variant.stock <= 0 ? 'not-allowed' : 'pointer';
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

function rebuildGemstonePicker(metalId) {
    const wrap = document.getElementById('gemstoneWrap');
    const sel  = document.getElementById('gemstoneSelect');
    if (!sel) return null;

    const gems = variantData
        .filter(v => v.metal_id === metalId && v.gemstone_id)
        .reduce((acc, v) => {
            if (!acc.find(g => g.id === v.gemstone_id)) {
                acc.push({ id: v.gemstone_id, name: v.gemstone_name });
            }
            return acc;
        }, []);

    if (gems.length > 0) {
        sel.innerHTML = gems.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
        if (wrap) wrap.style.display = '';
        return gems[0].id;
    } else {
        sel.innerHTML = '';
        if (wrap) wrap.style.display = 'none';
        return null;
    }
}

function selectMetal(metalId, el) {
    const gemId = rebuildGemstonePicker(metalId);
    applyVariant(findBestVariant(metalId, gemId));
}

function selectGemstone(gemstoneId, el) {
    const metalSel = document.getElementById('metalSelect');
    const metalId  = metalSel ? parseInt(metalSel.value) : null;
    applyVariant(findBestVariant(metalId, gemstoneId));
}

function selectSize(sizeId, el) {
    document.getElementById('selectedSizeId').value = sizeId || '';
}

// ── Quantity stepper ───────────────────────────────────────────────────────
function stepQty(delta) {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    const val = Math.max(1, Math.min(10, parseInt(input.value || 1) + delta));
    input.value = val;
}

// ── Wishlist toggle (AJAX) ─────────────────────────────────────────────────
function toggleWishlist(triggerBtn) {
    const productId = triggerBtn.dataset.productId;
    const variantId = document.getElementById('selectedVariantId')?.value || null;

    fetch('{{ route('shop.wishlist.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, variant_id: variantId || undefined }),
    })
    .then(r => r.json())
    .then(data => {
        const added = data.added;

        // Update both buttons
        [document.getElementById('wishlistOverlayBtn'), document.getElementById('wishlistTextBtn')]
            .forEach(btn => { if (btn) btn.dataset.wishlisted = added ? 'true' : 'false'; });

        // Overlay icon
        const overlayIcon = document.querySelector('#wishlistOverlayBtn i');
        if (overlayIcon) {
            overlayIcon.className = 'icon ' + (added ? 'icon-heart-fill' : 'icon-heart');
            overlayIcon.style.color = added ? 'var(--fado-green-mid)' : 'inherit';
        }

        // Text button
        const textBtn = document.getElementById('wishlistTextBtn');
        if (textBtn) {
            const icon = textBtn.querySelector('.icon');
            const tip  = textBtn.querySelector('.tooltip');
            if (icon) icon.className = 'icon ' + (added ? 'icon-heart-fill' : 'icon-heart');
            if (tip)  tip.textContent = added ? 'Saved to Wishlist' : 'Add to Wishlist';
            textBtn.dataset.wishlisted = added ? 'true' : 'false';
        }

        // Header count badge — update without reload
        const badge = document.querySelector('.fado-header-icons .icon-heart')?.closest('a')?.querySelector('.fado-cart-count');
        if (data.count > 0) {
            if (badge) { badge.textContent = data.count; }
            // If no badge exists yet, a page reload will show it (next navigation)
        } else if (badge) {
            badge.remove();
        }
    })
    .catch(() => {
        // Fallback: navigate to wishlist page
        window.location.href = '{{ route('shop.wishlist') }}';
    });
}

// ── Init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Apply default variant display (first variant) and build initial gemstone picker
    if (variantData.length > 0) {
        const metalSel = document.getElementById('metalSelect');
        const metalId  = metalSel ? parseInt(metalSel.value) : null;
        const gemId    = rebuildGemstonePicker(metalId);
        applyVariant(findBestVariant(metalId, gemId));
    }
});
</script>
@endpush
