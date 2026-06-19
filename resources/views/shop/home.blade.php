@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', Setting::get('meta_title', Setting::get('store_name', 'FADÓ Jewellery') . ' — Fine Irish Jewellery'))
@section('meta_description', Setting::get('meta_description', 'Handcrafted Irish jewellery. Explore our collections of rings, pendants, earrings and more.'))
@section('canonical', route('shop.home'))

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 1  HERO SLIDER                                                          --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="fado-hero-slider position-relative" style="overflow:hidden">
    <div dir="ltr" class="swiper fado-swiper-hero" id="fadoHeroSwiper">
        <div class="swiper-wrapper">

            {{-- Slide 1 — Claddagh (BannerTop-one.jpg) --}}
            {{-- Ochaka pattern: image fills slide, sld_content absolute-centred on top, NO colour overlay --}}
            <div class="swiper-slide">
                <div class="fado-slide fado-hero-slide" style="position:relative; overflow:hidden;">
                    <img src="{{ Storage::url('banners/BannerTop-one.jpg') }}"
                         alt="FADÓ Jewellery — Claddagh Collection"
                         class="fado-hero-bg-img">
                    {{-- Thin scrim on text side only — matches Ochaka has-overlay approach (~0.35 max) --}}
                    <div class="fado-hero-overlay" style="background: linear-gradient(to right, rgba(0,0,0,.4) 0%, rgba(0,0,0,.15) 38%, transparent 62%)"></div>
                    <div class="container fado-hero-content position-relative" style="z-index:2">
                        <div class="row align-items-center">
                            <div class="col-xl-5 col-lg-6">
                                <p style="font-size:.75rem; letter-spacing:.35em; text-transform:uppercase;
                                          color: var(--fado-gold); margin-bottom:16px" class="wow fadeInUp" data-wow-delay=".1s">
                                    Fine Irish Jewellery
                                </p>
                                <h1 style="font-family: Georgia, serif; font-size: clamp(2.4rem, 5vw, 4rem);
                                           color:#fff; line-height:1.15; font-weight:400; margin-bottom:20px; text-shadow:0 1px 8px rgba(0,0,0,.25)"
                                    class="wow fadeInUp" data-wow-delay=".2s">
                                    The Claddagh<br>Collection
                                </h1>
                                <p style="color:rgba(255,255,255,.9); font-size:1.0625rem; line-height:1.75; max-width:420px; margin-bottom:36px; text-shadow:0 1px 4px rgba(0,0,0,.2)"
                                   class="wow fadeInUp" data-wow-delay=".3s">
                                    Love, loyalty and friendship — Ireland's most enduring symbol, crafted in sterling silver, gold and platinum.
                                </p>
                                <div class="d-flex gap-3 wow fadeInUp" data-wow-delay=".4s">
                                    <a href="{{ route('shop.collection', 'claddagh') }}"
                                       class="btn-fado-primary" style="display:inline-block; padding:14px 32px; text-decoration:none; border-radius:2px; font-weight:600; letter-spacing:.04em">
                                        Shop Claddagh
                                    </a>
                                    <a href="{{ route('shop.collections') }}"
                                       style="display:inline-block; padding:13px 32px; text-decoration:none; border-radius:2px;
                                              border: 1.5px solid rgba(255,255,255,.6); color:#fff; font-size:.875rem; font-weight:500;
                                              transition: border-color .2s, background .2s"
                                       onmouseover="this.style.borderColor='#fff'; this.style.background='rgba(255,255,255,.12)'"
                                       onmouseout="this.style.borderColor='rgba(255,255,255,.6)'; this.style.background='transparent'">
                                        All Collections
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 — Garden Collection (BannerTop-Two.jpg) --}}
            <div class="swiper-slide">
                <div class="fado-slide fado-hero-slide" style="position:relative; overflow:hidden;">
                    <img src="{{ Storage::url('banners/BannerTop-Two.jpg') }}"
                         alt="The Jewellery Garden by FADÓ"
                         class="fado-hero-bg-img">
                    {{-- Very light uniform scrim — enough for centred white text, image still dominates --}}
                    <div class="fado-hero-overlay" style="background: rgba(0,0,0,.22)"></div>
                    <div class="container fado-hero-content position-relative" style="z-index:2">
                        <div class="row justify-content-center text-center">
                            <div class="col-xl-7 col-lg-8">
                                <p style="font-size:.75rem; letter-spacing:.35em; text-transform:uppercase;
                                          color: rgba(255,255,255,.85); margin-bottom:16px" class="wow fadeInUp" data-wow-delay=".1s">
                                    The Jewellery Garden by FADÓ
                                </p>
                                <h1 style="font-family: Georgia, serif; font-size: clamp(2.4rem, 5vw, 4rem);
                                           color:#fff; line-height:1.15; font-weight:400; margin-bottom:20px; text-shadow:0 1px 8px rgba(0,0,0,.3)"
                                    class="wow fadeInUp" data-wow-delay=".2s">
                                    Nature, Captured<br>in Gold &amp; Silver
                                </h1>
                                <p style="color:rgba(255,255,255,.9); font-size:1.0625rem; line-height:1.75; margin-bottom:36px; text-shadow:0 1px 4px rgba(0,0,0,.2)"
                                   class="wow fadeInUp" data-wow-delay=".3s">
                                    Bluebells, wild daisies, butterflies and bees — Ireland's garden in every piece. Perfect for weddings and engagements.
                                </p>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}"
                                   class="btn-fado-primary wow fadeInUp" data-wow-delay=".4s"
                                   style="display:inline-block; padding:14px 40px; text-decoration:none; border-radius:2px; font-weight:600">
                                    Explore the Garden Collection
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3 — Irish Gold (BannerTop-three.jpg) --}}
            <div class="swiper-slide">
                <div class="fado-slide fado-hero-slide" style="position:relative; overflow:hidden;">
                    <img src="{{ Storage::url('banners/BannerTop-three.jpg') }}"
                         alt="Handcrafted Irish Gold"
                         class="fado-hero-bg-img">
                    {{-- Thin right-side scrim for right-aligned text --}}
                    <div class="fado-hero-overlay" style="background: linear-gradient(to left, rgba(0,0,0,.4) 0%, rgba(0,0,0,.15) 38%, transparent 62%)"></div>
                    <div class="container fado-hero-content position-relative" style="z-index:2">
                        <div class="row justify-content-end">
                            <div class="col-xl-5 col-lg-6">
                                <p style="font-size:.75rem; letter-spacing:.35em; text-transform:uppercase;
                                          color: var(--fado-gold); margin-bottom:16px" class="wow fadeInUp" data-wow-delay=".1s">
                                    A gift they'll treasure forever
                                </p>
                                <h1 style="font-family: Georgia, serif; font-size: clamp(2.4rem, 5vw, 4rem);
                                           color:#fff; line-height:1.15; font-weight:400; margin-bottom:20px; text-shadow:0 1px 8px rgba(0,0,0,.25)"
                                    class="wow fadeInUp" data-wow-delay=".2s">
                                    Handcrafted<br>Irish Gold
                                </h1>
                                <p style="color:rgba(255,255,255,.9); font-size:1.0625rem; line-height:1.75; max-width:400px; margin-bottom:36px; text-shadow:0 1px 4px rgba(0,0,0,.2)"
                                   class="wow fadeInUp" data-wow-delay=".3s">
                                    From 9ct to 18ct gold and platinum — every piece crafted in the Irish tradition for those who matter most.
                                </p>
                                <div class="d-flex gap-3 wow fadeInUp" data-wow-delay=".4s">
                                    <a href="{{ route('shop.jewellery', ['metal' => '9ct-yellow-gold']) }}"
                                       class="btn-fado-primary" style="display:inline-block; padding:14px 32px; text-decoration:none; border-radius:2px; font-weight:600">
                                        Shop Gold Jewellery
                                    </a>
                                    <a href="{{ route('shop.collections') }}"
                                       style="display:inline-block; padding:13px 32px; text-decoration:none; border-radius:2px;
                                              border: 1.5px solid rgba(255,255,255,.6); color:#fff; font-size:.875rem; font-weight:500;
                                              transition: border-color .2s, background .2s"
                                       onmouseover="this.style.borderColor='#fff'; this.style.background='rgba(255,255,255,.12)'"
                                       onmouseout="this.style.borderColor='rgba(255,255,255,.6)'; this.style.background='transparent'">
                                        View Collections
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Slider controls --}}
        <div class="swiper-button-prev fado-swiper-btn" style="left:24px"></div>
        <div class="swiper-button-next fado-swiper-btn" style="right:24px"></div>
        <div class="fado-swiper-pagination swiper-pagination"></div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 2  TRUST STRIP                                                          --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div style="background: var(--fado-cream); border-bottom: 1px solid var(--fado-warm-grey)">
    <div class="container">
        <div class="row text-center g-0">
            @foreach([
                ['icon' => 'icon-truck-simple', 'label' => 'Free Delivery',    'sub' => \App\Models\Setting::get('shipping_notice', 'Free delivery on orders over €75')],
                ['icon' => 'icon-shield-check',  'label' => 'Hallmark Certified', 'sub' => 'Assay Office Ireland'],
                ['icon' => 'icon-arrow-counter-clockwise', 'label' => '30-Day Returns', 'sub' => 'Hassle-free exchanges'],
                ['icon' => 'icon-gift',           'label' => 'Gift Packaging',  'sub' => 'Luxury box included'],
            ] as $trust)
            <div class="col-6 col-md-3" style="padding:20px 16px; border-right: 1px solid var(--fado-warm-grey)">
                <i class="icon {{ $trust['icon'] }}" style="font-size:1.4rem; color: var(--fado-deep-green); margin-bottom:8px; display:block"></i>
                <div style="font-size:.8125rem; font-weight:700; color: var(--fado-deep-green); letter-spacing:.04em">{{ $trust['label'] }}</div>
                <div style="font-size:.75rem; color: var(--fado-warm-grey); margin-top:2px">{{ $trust['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 3  SHOP BY CATEGORY                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 72px 0 60px; background: var(--fado-near-white)">
    <div class="container">
        <div class="text-center mb-5 wow fadeInUp">
            <p style="font-size:.7rem; letter-spacing:.25em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:10px">Browse</p>
            <h2 style="font-family: Georgia, serif; font-size: clamp(1.6rem, 3vw, 2.25rem); color: var(--fado-deep-green); font-weight:400">Shop by Jewellery Type</h2>
        </div>

        <div dir="ltr" class="swiper fado-category-swiper wow fadeInUp" data-wow-delay=".1s">
            <div class="swiper-wrapper">
                @foreach([
                    ['slug' => 'rings',              'label' => 'Rings',              'img' => '/images/ochaka/products/jewelry/product-5.jpg'],
                    ['slug' => 'earrings',           'label' => 'Earrings',           'img' => '/images/ochaka/products/jewelry/product-9.jpg'],
                    ['slug' => 'pendants',           'label' => 'Pendants',           'img' => '/images/ochaka/products/jewelry/product-13.jpg'],
                    ['slug' => 'crosses',            'label' => 'Crosses',            'img' => '/images/ochaka/products/jewelry/product-17.jpg'],
                    ['slug' => 'bracelets-bangles',  'label' => 'Bracelets',          'img' => '/images/ochaka/products/jewelry/product-3.jpg'],
                    ['slug' => 'cufflinks',          'label' => 'Cufflinks',          'img' => '/images/ochaka/products/jewelry/product-7.jpg'],
                    ['slug' => 'brooches',           'label' => 'Brooches',           'img' => '/images/ochaka/products/jewelry/product-11.jpg'],
                ] as $cat)
                <div class="swiper-slide">
                    <a href="{{ route('shop.category', $cat['slug']) }}"
                       class="d-block text-center text-decoration-none fado-cat-card">
                        <div style="width:100%; aspect-ratio:1/1; border-radius:50%; overflow:hidden;
                                    background: var(--fado-cream); margin-bottom:14px;
                                    transition: box-shadow .25s, transform .25s"
                             class="fado-cat-img">
                            <img src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}"
                                 style="width:100%; height:100%; object-fit:cover; object-position:center top">
                        </div>
                        <p style="font-size:.9375rem; font-weight:600; color: var(--fado-deep-green);
                                  letter-spacing:.03em; margin:0; transition: color .2s">{{ $cat['label'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="fado-swiper-pagination swiper-pagination" style="position:static; margin-top:28px"></div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 4  FEATURED COLLECTIONS  (2-col banner pair)                            --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 0 0 72px; background: var(--fado-near-white)">
    <div class="container">
        <div class="text-center mb-5 wow fadeInUp">
            <p style="font-size:.7rem; letter-spacing:.25em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:10px">Heritage &amp; Craft</p>
            <h2 style="font-family: Georgia, serif; font-size: clamp(1.6rem, 3vw, 2.25rem); color: var(--fado-deep-green); font-weight:400">Featured Collections</h2>
        </div>

        <div class="row g-4">
            {{-- Collection 1 — Claddagh --}}
            {{-- Ochaka box-image_V02: text bottom-left, NO overlay div — image IS the card --}}
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".1s">
                <a href="{{ route('shop.collection', 'claddagh') }}" class="d-block fado-collection-banner text-decoration-none"
                   style="position:relative; border-radius:4px; overflow:hidden; aspect-ratio:4/3">
                    <img src="/images/ochaka/section/box-image-8.jpg" alt="Claddagh Collection"
                         style="width:100%; height:100%; object-fit:cover; transition: transform .5s ease">
                    {{-- Thin bottom gradient only — just enough to read text, Ochaka uses none but these images may be lighter --}}
                    <div style="position:absolute; inset:0; background: linear-gradient(to top, rgba(0,0,0,.55) 0%, rgba(0,0,0,.18) 35%, transparent 60%)"></div>
                    <div style="position:absolute; bottom:0; left:0; padding:28px 32px">
                        <p style="font-size:.7rem; letter-spacing:.2em; text-transform:uppercase; color:rgba(255,255,255,.75); margin-bottom:8px">Irish Heritage</p>
                        <h3 style="font-family: Georgia, serif; font-size:1.75rem; color:#fff; font-weight:400; margin-bottom:12px; text-shadow:0 1px 6px rgba(0,0,0,.3)">Claddagh Collection</h3>
                        <span style="font-size:.8125rem; font-weight:600; color:#fff; letter-spacing:.08em; text-transform:uppercase">
                            Shop now <i class="icon icon-arrow-right" style="font-size:.75rem"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Collection 2 — Trinity --}}
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                <a href="{{ route('shop.collection', 'trinity') }}" class="d-block fado-collection-banner text-decoration-none"
                   style="position:relative; border-radius:4px; overflow:hidden; aspect-ratio:4/3">
                    <img src="/images/ochaka/section/box-image-9.jpg" alt="Trinity Collection"
                         style="width:100%; height:100%; object-fit:cover; transition: transform .5s ease">
                    <div style="position:absolute; inset:0; background: linear-gradient(to top, rgba(0,0,0,.55) 0%, rgba(0,0,0,.18) 35%, transparent 60%)"></div>
                    <div style="position:absolute; bottom:0; left:0; padding:28px 32px">
                        <p style="font-size:.7rem; letter-spacing:.2em; text-transform:uppercase; color:rgba(255,255,255,.75); margin-bottom:8px">Irish Heritage</p>
                        <h3 style="font-family: Georgia, serif; font-size:1.75rem; color:#fff; font-weight:400; margin-bottom:12px; text-shadow:0 1px 6px rgba(0,0,0,.3)">Trinity Collection</h3>
                        <span style="font-size:.8125rem; font-weight:600; color:#fff; letter-spacing:.08em; text-transform:uppercase">
                            Shop now <i class="icon icon-arrow-right" style="font-size:.75rem"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Collection 3 — Newgrange (wide) --}}
            {{-- Left-side scrim only, image visible across the full width --}}
            <div class="col-12 wow fadeInUp" data-wow-delay=".1s">
                <a href="{{ route('shop.collection', 'newgrange') }}" class="d-block fado-collection-banner text-decoration-none"
                   style="position:relative; border-radius:4px; overflow:hidden; height:280px">
                    <img src="/images/ochaka/slider/slider-22.jpg" alt="Newgrange Collection"
                         style="width:100%; height:100%; object-fit:cover; object-position:center 30%; transition: transform .5s ease">
                    {{-- Subtle left scrim only — image shows on the right 40% completely unobscured --}}
                    <div style="position:absolute; inset:0; background: linear-gradient(to right, rgba(0,0,0,.5) 0%, rgba(0,0,0,.2) 40%, transparent 65%)"></div>
                    <div style="position:absolute; top:50%; left:0; transform:translateY(-50%); padding:0 40px">
                        <p style="font-size:.7rem; letter-spacing:.2em; text-transform:uppercase; color:rgba(255,255,255,.75); margin-bottom:8px">Spiral of Life</p>
                        <h3 style="font-family: Georgia, serif; font-size:clamp(1.5rem, 3vw, 2.25rem); color:#fff; font-weight:400; margin-bottom:16px; text-shadow:0 1px 6px rgba(0,0,0,.3)">Newgrange Collection</h3>
                        <a href="{{ route('shop.collection', 'newgrange') }}"
                           style="display:inline-block; padding:10px 28px; background:#fff; color: var(--fado-deep-green);
                                  border-radius:2px; font-size:.8125rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; text-decoration:none">
                            Explore the collection
                        </a>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 5  NEW ARRIVALS  (dynamic from DB)                                      --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 72px 0; background: var(--fado-soft-white)">
    <div class="container">
        <div class="text-center mb-5 wow fadeInUp">
            <p style="font-size:.7rem; letter-spacing:.25em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:10px">Just in</p>
            <h2 style="font-family: Georgia, serif; font-size: clamp(1.6rem, 3vw, 2.25rem); color: var(--fado-deep-green); font-weight:400">New Arrivals</h2>
        </div>

        @if($newArrivals->isNotEmpty())
        <div class="row g-4">
            @foreach($newArrivals->take(4) as $product)
            @php
                $img  = $product->primaryImage?->path;
                $from = $product->variants->min('price_eur');
            @endphp
            <div class="col-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ $loop->index * .08 }}s">
                <a href="{{ route('shop.product', $product) }}" class="d-block fado-product-card text-decoration-none">
                    <div style="background: var(--fado-cream); border-radius:4px; overflow:hidden;
                                aspect-ratio:3/4; margin-bottom:14px; position:relative">
                        @if($img)
                            <img src="{{ Storage::url($img) }}" alt="{{ $product->name }}"
                                 style="width:100%; height:100%; object-fit:cover; transition: transform .4s ease">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;
                                        background: var(--fado-cream)">
                                <i class="icon icon-gem" style="font-size:3rem; color: var(--fado-warm-grey)"></i>
                            </div>
                        @endif
                        <div style="position:absolute; top:12px; right:12px; opacity:0; transition: opacity .2s"
                             class="fado-product-wishlist">
                            <button style="background:#fff; border:none; width:36px; height:36px; border-radius:50%;
                                           display:flex; align-items:center; justify-content:center;
                                           box-shadow: 0 2px 8px rgba(0,0,0,.12); cursor:pointer; color: var(--fado-deep-green)"
                                    title="Add to wishlist">
                                <i class="icon icon-heart" style="font-size:.875rem"></i>
                            </button>
                        </div>
                    </div>
                    <p style="font-size:.9375rem; font-weight:600; color: var(--fado-deep-green); margin-bottom:4px;
                               transition: color .2s" class="fado-product-name">{{ $product->name }}</p>
                    <p style="font-size:.875rem; color: var(--fado-warm-grey); margin:0">
                        @if($from)
                            From €{{ number_format((float)$from, 2) }}
                        @endif
                    </p>
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5 wow fadeInUp">
            <a href="{{ route('shop.jewellery') }}"
               class="btn-fado-outline" style="display:inline-block; padding:12px 40px; text-decoration:none; border-radius:2px">
                View all jewellery
            </a>
        </div>

        @else
        {{-- No products yet — show placeholder grid --}}
        <div class="row g-4">
            @foreach(range(1,4) as $n)
            <div class="col-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ ($n-1) * .08 }}s">
                <a href="{{ route('shop.jewellery') }}" class="d-block fado-product-card text-decoration-none">
                    <div style="background: var(--fado-cream); border-radius:4px; overflow:hidden; aspect-ratio:3/4; margin-bottom:14px">
                        <img src="/images/ochaka/products/jewelry/product-{{ $n * 2 + 3 }}.jpg" alt="Jewellery"
                             style="width:100%; height:100%; object-fit:cover; object-position:center top; transition: transform .4s ease">
                    </div>
                    <p style="font-size:.9375rem; font-weight:600; color: var(--fado-deep-green); margin-bottom:4px">
                        {{ ['Sterling Silver Ring', 'Gold Claddagh Pendant', 'Emerald Earrings', 'Trinity Bracelet'][$n-1] }}
                    </p>
                    <p style="font-size:.875rem; color: var(--fado-warm-grey); margin:0">From €85</p>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('shop.jewellery') }}" class="btn-fado-outline"
               style="display:inline-block; padding:12px 40px; text-decoration:none; border-radius:2px">
                View all jewellery
            </a>
        </div>
        @endif
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 6  GARDEN COLLECTION SPOTLIGHT                                          --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 80px 0; background: var(--fado-mint-pale)">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".1s">
                <div style="position:relative">
                    <div style="border-radius:4px; overflow:hidden; aspect-ratio:4/5">
                        <img src="/images/ochaka/products/jewelry/product-21.jpg" alt="The Jewellery Garden by FADÓ"
                             style="width:100%; height:100%; object-fit:cover; object-position:center top">
                    </div>
                    {{-- Floating badge --}}
                    <div style="position:absolute; top:28px; right:-20px; background: var(--fado-deep-green);
                                color:#fff; padding:16px 20px; border-radius:4px; text-align:center;
                                box-shadow: 0 8px 32px rgba(0,0,0,.2)">
                        <div style="font-size:.65rem; letter-spacing:.2em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:4px">Exclusive</div>
                        <div style="font-family: Georgia, serif; font-size:1rem; line-height:1.3">The<br>Jewellery<br>Garden</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                <p style="font-size:.7rem; letter-spacing:.3em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:16px">Wedding &amp; Engagement</p>
                <h2 style="font-family: Georgia, serif; font-size: clamp(1.8rem, 3.5vw, 2.75rem);
                           color: var(--fado-deep-green); font-weight:400; line-height:1.2; margin-bottom:24px">
                    The Jewellery Garden<br>by FADÓ
                </h2>
                <p style="font-size:1.0625rem; color:#555; line-height:1.8; margin-bottom:28px">
                    Ireland's wildflower meadows — bluebells, daisies, forget-me-nots, butterflies and bees — captured in sterling silver and gold. An exclusive collection celebrating the beauty of the natural world.
                </p>
                <p style="font-size:1.0625rem; color:#555; line-height:1.8; margin-bottom:36px">
                    Ideal for engagements, weddings, anniversaries, and any occasion that deserves something truly unique.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop.collection', 'the-garden-collection') }}"
                       class="btn-fado-primary" style="display:inline-block; padding:13px 32px; text-decoration:none; border-radius:2px; font-weight:600">
                        Explore the Collection
                    </a>
                    <a href="{{ route('shop.contact') }}#consultation"
                       class="btn-fado-outline" style="display:inline-block; padding:12px 32px; text-decoration:none; border-radius:2px">
                        Book a Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 7  ABOUT BAND                                                            --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 80px 0; background: var(--fado-soft-white); border-top: 1px solid var(--fado-cream); border-bottom: 1px solid var(--fado-cream)">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-lg-5 text-center text-lg-start wow fadeInUp">
                <p style="font-size:.7rem; letter-spacing:.3em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:16px">Our Story</p>
                <h2 style="font-family: Georgia, serif; font-size: clamp(1.6rem, 3vw, 2.5rem); color: var(--fado-deep-green);
                           font-weight:400; margin-bottom:24px; line-height:1.4">
                    Irish Jewellery,<br>Crafted with Heart
                </h2>
                <p style="font-size:1.0625rem; color:#555; line-height:1.8; margin-bottom:32px">
                    FADÓ — meaning "long ago" in Irish — honours centuries of Irish craftsmanship. Each piece is designed to carry meaning, to be worn every day, and to be passed down through generations.
                </p>
                <a href="{{ route('shop.about') }}"
                   class="btn-fado-outline" style="display:inline-block; padding:12px 32px; text-decoration:none; border-radius:2px">
                    Our Story →
                </a>
            </div>
            <div class="col-lg-4 d-none d-lg-block wow fadeInUp" data-wow-delay=".15s">
                {{-- Simple decorative accent — three stacked value chips --}}
                <div style="display:flex; flex-direction:column; gap:16px">
                    @foreach([
                        ['icon' => 'icon-medal', 'title' => 'Hallmark Certified',    'sub' => 'Assay Office Ireland — every piece certified'],
                        ['icon' => 'icon-hands-clapping', 'title' => 'Handcrafted Heritage', 'sub' => 'Designed and finished in the Irish tradition'],
                        ['icon' => 'icon-heart',  'title' => 'Made to be Treasured',  'sub' => 'Pieces designed to be worn and passed on'],
                    ] as $v)
                    <div style="display:flex; align-items:flex-start; gap:16px; background:#fff;
                                border-radius:6px; padding:18px 20px; border: 1px solid var(--fado-cream);
                                box-shadow: 0 2px 8px rgba(0,0,0,.04)">
                        <div style="width:40px; height:40px; border-radius:50%; background: var(--fado-mint-pale);
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0">
                            <i class="icon {{ $v['icon'] }}" style="color: var(--fado-green-mid); font-size:1.1rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem; font-weight:700; color: var(--fado-deep-green); margin-bottom:2px">{{ $v['title'] }}</div>
                            <div style="font-size:.8125rem; color:#777; line-height:1.5">{{ $v['sub'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 8  CONSULTATION CTA                                                      --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section style="padding: 72px 0; background: var(--fado-cream)">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 wow fadeInUp">
                <p style="font-size:.7rem; letter-spacing:.3em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:12px">Personal Service</p>
                <h2 style="font-family: Georgia, serif; font-size: clamp(1.5rem, 3vw, 2.25rem); color: var(--fado-deep-green); font-weight:400; margin-bottom:16px">
                    Not sure what to choose?<br>Book a free consultation.
                </h2>
                <p style="font-size:1rem; color:#666; line-height:1.75; max-width:540px">
                    Our jewellery experts are here to help you find the perfect piece — whether it's for an engagement, anniversary, or simply a well-deserved treat. By phone, email, or in person.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end wow fadeInUp" data-wow-delay=".1s">
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                    <a href="{{ route('shop.contact') }}#consultation"
                       class="btn-fado-primary" style="display:inline-block; padding:14px 32px; text-decoration:none; border-radius:2px; font-weight:600; text-align:center">
                        Book an appointment
                    </a>
                    <a href="{{ route('shop.contact') }}"
                       class="btn-fado-outline" style="display:inline-block; padding:13px 32px; text-decoration:none; border-radius:2px; text-align:center">
                        Contact us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
<style>
/* Hero slider controls */
.fado-swiper-btn {
    width: 44px !important; height: 44px !important;
    background: rgba(255,255,255,.15) !important;
    border-radius: 50% !important;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.3) !important;
    transition: background .2s !important;
}
.fado-swiper-btn:hover { background: rgba(255,255,255,.25) !important; }
.fado-swiper-btn::after { font-size: 14px !important; color: #fff !important; }
.fado-swiper-pagination .swiper-pagination-bullet {
    background: rgba(255,255,255,.5) !important;
    opacity: 1 !important;
    width: 8px !important; height: 8px !important;
}
.fado-swiper-pagination .swiper-pagination-bullet-active {
    background: #fff !important;
    width: 24px !important; border-radius: 4px !important;
    transition: width .3s !important;
}

/* Category hover */
.fado-cat-card:hover .fado-cat-img {
    box-shadow: 0 8px 32px rgba(4,71,5,.2);
    transform: scale(1.03);
}
.fado-cat-card:hover p { color: var(--fado-green-mid) !important; }

/* Collection banner hover */
.fado-collection-banner:hover img { transform: scale(1.04); }

/* Product card hover */
.fado-product-card:hover .fado-product-name { color: var(--fado-green-mid) !important; }
.fado-product-card:hover img { transform: scale(1.04); }
.fado-product-card:hover .fado-product-wishlist { opacity: 1 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Hero slider
    new Swiper('#fadoHeroSwiper', {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: { delay: 5500, disableOnInteraction: false },
        speed: 800,
        navigation: {
            nextEl: '#fadoHeroSwiper .swiper-button-next',
            prevEl: '#fadoHeroSwiper .swiper-button-prev',
        },
        pagination: {
            el: '#fadoHeroSwiper .swiper-pagination',
            clickable: true,
        },
    });

    // Category swiper
    new Swiper('.fado-category-swiper', {
        slidesPerView: 2.4,
        spaceBetween: 16,
        pagination: { el: '.fado-category-swiper .swiper-pagination', clickable: true },
        breakpoints: {
            480:  { slidesPerView: 3,   spaceBetween: 20 },
            768:  { slidesPerView: 4,   spaceBetween: 24 },
            1200: { slidesPerView: 7,   spaceBetween: 32 },
        },
    });

});
</script>
@endpush
