{{--
    Shared <ul class="box-nav-menu"> content used by BOTH header variants in header.blade.php
    (header-abs-3's header-inner nav and header-fixed's inline nav use the identical menu markup
    in the Ochaka reference — see home-jewelry.html lines 123-455 vs 474-809). Extracted here so the
    real categories/collections aren't built twice in markup.
--}}
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
                </div>
            </div>
        </div>
        @endif
    </li>

    {{-- About Us — plain dropdown-free link (no sub-menu, single page) --}}
    <li class="menu-item">
        <a href="{{ route('shop.about') }}" class="item-link">About Us</a>
    </li>

</ul>
