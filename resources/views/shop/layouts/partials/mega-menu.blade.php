<ul class="box-nav-menu">

    {{-- ── Jewellery ──────────────────────────────────────────────────────── --}}
    <li class="menu-item">
        <a href="{{ route('shop.jewellery') }}" class="item-link">Jewellery<i class="icon icon-caret-down"></i></a>
        <div class="sub-menu mega-menu">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Shop by Type</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.category', 'rings') }}" class="sub-menu_link">Rings</a></li>
                                <li><a href="{{ route('shop.category', 'crosses') }}" class="sub-menu_link">Crosses</a></li>
                                <li><a href="{{ route('shop.category', 'pendants') }}" class="sub-menu_link">Pendants</a></li>
                                <li><a href="{{ route('shop.category', 'earrings') }}" class="sub-menu_link">Earrings</a></li>
                                <li><a href="{{ route('shop.category', 'bracelets-bangles') }}" class="sub-menu_link">Bracelets &amp; Bangles</a></li>
                                <li><a href="{{ route('shop.category', 'cufflinks') }}" class="sub-menu_link">Cufflinks</a></li>
                                <li><a href="{{ route('shop.category', 'brooches') }}" class="sub-menu_link">Brooches</a></li>
                                <li><a href="{{ route('shop.category', 'tie-tacks') }}" class="sub-menu_link">Tie-tacks</a></li>
                                <li><a href="{{ route('shop.jewellery') }}" class="sub-menu_link fw-semibold" style="color:var(--fado-green-mid)">View all jewellery →</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Shop by Metal</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.jewellery', ['metal' => 'sterling-silver']) }}" class="sub-menu_link">Sterling Silver</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => '9ct-yellow-gold']) }}" class="sub-menu_link">9ct Yellow Gold</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => '9ct-white-gold']) }}" class="sub-menu_link">9ct White Gold</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => '9ct-rose-gold']) }}" class="sub-menu_link">9ct Rose Gold</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => '18ct-yellow-gold']) }}" class="sub-menu_link">18ct Yellow Gold</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => 'platinum']) }}" class="sub-menu_link">Platinum</a></li>
                                <li><a href="{{ route('shop.jewellery', ['metal' => 'two-tone']) }}" class="sub-menu_link">Two-tone</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>

    {{-- ── Collections ─────────────────────────────────────────────────────── --}}
    <li class="menu-item">
        <a href="{{ route('shop.collections') }}" class="item-link">Collections<i class="icon icon-caret-down"></i></a>
        <div class="sub-menu mega-menu">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Irish Heritage</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'claddagh') }}" class="sub-menu_link">Claddagh</a></li>
                                <li><a href="{{ route('shop.collection', 'corrib-claddagh') }}" class="sub-menu_link">Corrib Claddagh</a></li>
                                <li><a href="{{ route('shop.collection', 'trinity') }}" class="sub-menu_link">Trinity</a></li>
                                <li><a href="{{ route('shop.collection', 'an-ri') }}" class="sub-menu_link">An Rí</a></li>
                                <li><a href="{{ route('shop.collection', 'high-crosses') }}" class="sub-menu_link">High Crosses</a></li>
                                <li><a href="{{ route('shop.collection', 'newgrange') }}" class="sub-menu_link">Newgrange</a></li>
                                <li><a href="{{ route('shop.collection', 'irish-folklore') }}" class="sub-menu_link">Irish Folklore</a></li>
                                <li><a href="{{ route('shop.collection', 'shamrock') }}" class="sub-menu_link">Shamrock</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Fine Collections</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'livia') }}" class="sub-menu_link">Livia</a></li>
                                <li><a href="{{ route('shop.collection', 'sheelin') }}" class="sub-menu_link">Sheelin</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">The Jewellery Garden</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'the-garden-collection') }}" class="sub-menu_link fw-semibold">The Garden Collection by FADÓ</a></li>
                            </ul>
                            <h4 class="menu-heading mt-3">Flora</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'garden-daisy') }}" class="sub-menu_link">Daisy</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="sub-menu_link">Wild Daisy</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-bluebell') }}" class="sub-menu_link">Bluebell</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="sub-menu_link">Forget Me Not</a></li>
                            </ul>
                            <h4 class="menu-heading mt-3">Fauna</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'garden-butterfly') }}" class="sub-menu_link">Butterfly</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-bee') }}" class="sub-menu_link">Bee</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>

    {{-- ── Wedding / Engagement ─────────────────────────────────────────────── --}}
    <li class="menu-item">
        <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="item-link">Wedding / Engagement<i class="icon icon-caret-down"></i></a>
        <div class="sub-menu mega-menu">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">The Garden Collection</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'the-garden-collection') }}" class="sub-menu_link">All Garden Pieces</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Flora</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'garden-daisy') }}" class="sub-menu_link">Daisy</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="sub-menu_link">Wild Daisy</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-bluebell') }}" class="sub-menu_link">Bluebell</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="sub-menu_link">Forget Me Not</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mega-menu-item">
                            <h4 class="menu-heading">Fauna</h4>
                            <ul class="sub-menu_list">
                                <li><a href="{{ route('shop.collection', 'garden-butterfly') }}" class="sub-menu_link">Butterfly</a></li>
                                <li><a href="{{ route('shop.collection', 'garden-bee') }}" class="sub-menu_link">Bee</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>

    {{-- ── About Us ──────────────────────────────────────────────────────────── --}}
    <li class="menu-item mn-none">
        <a href="{{ route('shop.about') }}" class="item-link">About Us</a>
    </li>

    {{-- ── Contact ───────────────────────────────────────────────────────────── --}}
    <li class="menu-item mn-none">
        <a href="{{ route('shop.contact') }}" class="item-link">Contact</a>
    </li>

</ul>
