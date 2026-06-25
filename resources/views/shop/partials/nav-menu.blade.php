{{--
    Shared <ul class="box-nav-menu"> content used by BOTH header variants in header.blade.php
    (header-abs-3's header-inner nav and header-fixed's inline nav use the identical menu markup
    in the Ochaka reference — see home-jewelry.html lines 123-455 vs 474-809). Extracted here so the
    real categories/collections aren't built twice in markup.

    Image columns added per task: "mega menu with images" —
    - Jewellery mega menu's col-6 image tiles: source home-jewelry.html:275-304
      (`<div class="col-6"><ul class="list-hor"><li class="wg-cls hover-img">`), using the first 2
      real categories that have a banner_image set. If no category has one yet, the column is
      simply omitted (no fabricated stock photo) — flagged in the task summary.
    - Collections mega menu's col-6 "New Arrivals" tiles: source shop-hover-02.html:374-413
      (`<div class="col-4"><div class="mega-menu-item"><ul class="list-ver"><li class="prd-recent
      hover-img">`), using the 2 most recently added active products with a primary image.
--}}
@php
    $menuCategoryTiles = $navCategories->flatMap(fn ($c) => $c->children->isNotEmpty() ? $c->children->prepend($c) : collect([$c]))
        ->filter(fn ($c) => $c->banner_image)
        ->take(2);

    $menuRecentProducts = \App\Models\Product::with('primaryImage')
        ->where('is_active', true)
        ->latest()
        ->limit(2)
        ->get()
        ->filter(fn ($p) => $p->primaryImage);
@endphp
<ul class="box-nav-menu">

    {{-- Jewellery — real categories, mega-menu structure verified at home-jewelry.html:217-309 --}}
    <li class="menu-item">
        <a href="{{ route('shop.jewellery') }}" class="item-link">Jewellery<i class="icon icon-caret-down"></i></a>
        @if($navCategories->isNotEmpty())
        <div class="sub-menu mega-menu">
            <div class="container">
                <div class="row">
                    @foreach($navCategories->chunk((int) ceil($navCategories->count() / 3)) as $chunk)
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <ul class="sub-menu_list">
                                @foreach($chunk as $cat)
                                <li><a href="{{ route('shop.category', $cat->slug) }}" class="sub-menu_link">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    @if($menuCategoryTiles->isNotEmpty())
                    <div class="col-6">
                        <ul class="list-hor">
                            @foreach($menuCategoryTiles as $tile)
                            <li class="wg-cls hover-img">
                                <a href="{{ route('shop.category', $tile->slug) }}" class="image img-style">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($tile->banner_image) }}" data-src="{{ \Illuminate\Support\Facades\Storage::url($tile->banner_image) }}" alt="{{ $tile->name }}" class="lazyload">
                                </a>
                                <div class="cls-content">
                                    <h4 class="tag_cls">{{ $tile->name }}</h4>
                                    <span class="br-line type-vertical"></span>
                                    <a href="{{ route('shop.category', $tile->slug) }}" class="tf-btn-line">
                                        Shop now
                                    </a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </li>

    {{-- Collections — real collections, same mega-menu structure --}}
    <li class="menu-item">
        <a href="{{ route('shop.collections') }}" class="item-link">Collections<i class="icon icon-caret-down"></i></a>
        @if($navCollections->isNotEmpty())
        <div class="sub-menu mega-menu">
            <div class="container">
                <div class="row">
                    @foreach($navCollections->chunk((int) ceil($navCollections->count() / 3)) as $chunk)
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <ul class="sub-menu_list">
                                @foreach($chunk as $col)
                                <li><a href="{{ route('shop.collection', $col->slug) }}" class="sub-menu_link">{{ $col->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    @if($menuRecentProducts->isNotEmpty())
                    <div class="col-6">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">New Arrivals</h4>
                            <ul class="list-ver">
                                @foreach($menuRecentProducts as $i => $product)
                                <li class="prd-recent hover-img">
                                    <a href="{{ route('shop.product', $product) }}" class="image img-style">
                                        <img src="{{ asset($product->primaryImage->path) }}" data-src="{{ asset($product->primaryImage->path) }}" alt="{{ $product->name }}" class="lazyload">
                                    </a>
                                    <div class="content">
                                        <a href="{{ route('shop.product', $product) }}" class="name-prd h6 fw-medium link">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->lowestPrice)
                                        <span class="price-wrap h6 fw-semibold text-black">
                                            From €{{ number_format((float) $product->lowestPrice, 2) }}
                                        </span>
                                        @endif
                                    </div>
                                </li>
                                @if(!$loop->last)<li class="br-line"></li>@endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </li>

    {{-- Remaining nav items from header menu (About Us, Contact, etc.) --}}
    @php
        $headerMenu = \App\Models\Menu::getByLocation('header');
        $headerPlainItems = $headerMenu?->items->filter(fn ($i) => !in_array($i->url, ['/jewellery', '/collections'])) ?? collect();
    @endphp
    @foreach($headerPlainItems as $navItem)
    <li class="menu-item">
        <a href="{{ $navItem->getResolvedUrl() }}" class="item-link" @if($navItem->target === '_blank') target="_blank" @endif>{{ $navItem->label }}</a>
    </li>
    @endforeach

</ul>
