<nav class="fado-nav d-none d-xl-block" aria-label="Main navigation">
    <div class="container">
        <ul class="nav-ul">

            {{-- ── Jewellery ─────────────────────────────────────────────── --}}
            <li>
                <a href="{{ route('shop.jewellery') }}">
                    Jewellery <i class="icon icon-caret-down caret"></i>
                </a>
                <div class="fado-mega-menu">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8">
                                <p class="fado-mega-col-heading">Shop by Type</p>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <a href="{{ route('shop.category', 'rings') }}" class="fado-mega-link">Rings</a>
                                        <a href="{{ route('shop.category', 'crosses') }}" class="fado-mega-link">Crosses</a>
                                        <a href="{{ route('shop.category', 'pendants') }}" class="fado-mega-link">Pendants</a>
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="{{ route('shop.category', 'earrings') }}" class="fado-mega-link">Earrings</a>
                                        <a href="{{ route('shop.category', 'bracelets-bangles') }}" class="fado-mega-link">Bracelets &amp; Bangles</a>
                                        <a href="{{ route('shop.category', 'cufflinks') }}" class="fado-mega-link">Cufflinks</a>
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="{{ route('shop.category', 'brooches') }}" class="fado-mega-link">Brooches</a>
                                        <a href="{{ route('shop.category', 'tie-tacks') }}" class="fado-mega-link">Tie-tacks</a>
                                        <a href="{{ route('shop.jewellery') }}" class="fado-mega-link-all mt-3">
                                            View all jewellery →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" style="border-left: 1px solid var(--fado-cream);">
                                <p class="fado-mega-col-heading">Shop by Metal</p>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ route('shop.jewellery', ['metal' => 'sterling-silver']) }}" class="fado-mega-link">Sterling Silver</a>
                                        <a href="{{ route('shop.jewellery', ['metal' => '9ct-yellow-gold']) }}" class="fado-mega-link">9ct Yellow Gold</a>
                                        <a href="{{ route('shop.jewellery', ['metal' => '9ct-white-gold']) }}" class="fado-mega-link">9ct White Gold</a>
                                        <a href="{{ route('shop.jewellery', ['metal' => '18ct-yellow-gold']) }}" class="fado-mega-link">18ct Yellow Gold</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('shop.jewellery', ['metal' => 'platinum']) }}" class="fado-mega-link">Platinum</a>
                                        <a href="{{ route('shop.jewellery', ['metal' => '9ct-rose-gold']) }}" class="fado-mega-link">Rose Gold</a>
                                        <a href="{{ route('shop.jewellery', ['metal' => 'two-tone']) }}" class="fado-mega-link">Two-tone</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            {{-- ── Collections ───────────────────────────────────────────── --}}
            <li>
                <a href="{{ route('shop.collections') }}">
                    Collections <i class="icon icon-caret-down caret"></i>
                </a>
                <div class="fado-mega-menu">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="fado-mega-col-heading">Irish Heritage</p>
                                <a href="{{ route('shop.collection', 'claddagh') }}" class="fado-mega-link">Claddagh</a>
                                <a href="{{ route('shop.collection', 'corrib-claddagh') }}" class="fado-mega-link">Corrib Claddagh</a>
                                <a href="{{ route('shop.collection', 'trinity') }}" class="fado-mega-link">Trinity</a>
                                <a href="{{ route('shop.collection', 'an-ri') }}" class="fado-mega-link">An Rí</a>
                                <a href="{{ route('shop.collection', 'high-crosses') }}" class="fado-mega-link">High Crosses</a>
                                <a href="{{ route('shop.collection', 'newgrange') }}" class="fado-mega-link">Newgrange</a>
                                <a href="{{ route('shop.collection', 'irish-folklore') }}" class="fado-mega-link">Irish Folklore</a>
                                <a href="{{ route('shop.collection', 'shamrock') }}" class="fado-mega-link">Shamrock</a>
                            </div>
                            <div class="col-lg-4">
                                <p class="fado-mega-col-heading">Fine Collections</p>
                                <a href="{{ route('shop.collection', 'livia') }}" class="fado-mega-link">Livia</a>
                                <a href="{{ route('shop.collection', 'sheelin') }}" class="fado-mega-link">Sheelin</a>
                            </div>
                            <div class="col-lg-4" style="border-left: 1px solid var(--fado-cream);">
                                <p class="fado-mega-col-heading">The Jewellery Garden</p>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mega-link fw-semibold" style="color: var(--fado-deep-green)">
                                    The Garden Collection by FADÓ
                                </a>
                                <p class="fado-mega-subgroup mt-2">Flora</p>
                                <a href="{{ route('shop.collection', 'garden-daisy') }}" class="fado-mega-link">Daisy</a>
                                <a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="fado-mega-link">Wild Daisy</a>
                                <a href="{{ route('shop.collection', 'garden-bluebell') }}" class="fado-mega-link">Bluebell</a>
                                <a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="fado-mega-link">Forget Me Not</a>
                                <p class="fado-mega-subgroup">Fauna</p>
                                <a href="{{ route('shop.collection', 'garden-butterfly') }}" class="fado-mega-link">Butterfly</a>
                                <a href="{{ route('shop.collection', 'garden-bee') }}" class="fado-mega-link">Bee</a>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mega-link-all">View full Garden Collection →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            {{-- ── Wedding / Engagement ──────────────────────────────────── --}}
            <li>
                <a href="{{ route('shop.collection', 'the-garden-collection') }}">
                    Wedding / Engagement <i class="icon icon-caret-down caret"></i>
                </a>
                <div class="fado-mega-menu">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="fado-mega-col-heading">The Garden Collection by FADÓ</p>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mega-link">All Garden Pieces</a>
                            </div>
                            <div class="col-lg-4">
                                <p class="fado-mega-col-heading">Flora</p>
                                <a href="{{ route('shop.collection', 'garden-daisy') }}" class="fado-mega-link">Daisy</a>
                                <a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="fado-mega-link">Wild Daisy</a>
                                <a href="{{ route('shop.collection', 'garden-bluebell') }}" class="fado-mega-link">Bluebell</a>
                                <a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="fado-mega-link">Forget Me Not</a>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mega-link-all">All Flora →</a>
                            </div>
                            <div class="col-lg-4">
                                <p class="fado-mega-col-heading">Fauna</p>
                                <a href="{{ route('shop.collection', 'garden-butterfly') }}" class="fado-mega-link">Butterfly</a>
                                <a href="{{ route('shop.collection', 'garden-bee') }}" class="fado-mega-link">Bee</a>
                                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-mega-link-all">All Fauna →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            {{-- ── About Us (no dropdown) ───────────────────────────────── --}}
            <li>
                <a href="{{ route('shop.about') }}">About Us</a>
            </li>

            {{-- ── Contact (no dropdown) ────────────────────────────────── --}}
            <li>
                <a href="{{ route('shop.contact') }}">Contact</a>
            </li>

        </ul>
    </div>
</nav>
