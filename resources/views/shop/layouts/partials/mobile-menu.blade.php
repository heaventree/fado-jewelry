{{-- Ochaka mobile offcanvas menu — id must match btn-mobile-menu href="#mobileMenu" --}}
<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close-popup" data-bs-dismiss="offcanvas">
        <i class="icon-close"></i>
    </span>
    <div class="canvas-header">
        <div class="fado-wordmark" style="font-size:1.5rem">FADÓ</div>
        @auth
            <a href="{{ route('shop.account.index') }}" class="tf-btn type-small style-2">
                My Account <i class="icon icon-user"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="tf-btn type-small style-2">
                Login <i class="icon icon-user"></i>
            </a>
        @endauth
        <span class="br-line"></span>
    </div>
    <div class="canvas-body">
        <div class="mb-content-top">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation">

                {{-- Jewellery --}}
                <li class="nav-mb-item">
                    <a href="{{ route('shop.jewellery') }}" class="nav-mb-item_link">
                        Jewellery
                        <span class="btn-open-sub"><i class="icon-plus"></i></span>
                    </a>
                    <div class="sub-nav-mobile">
                        <ul class="sub-nav-list">
                            <li><a href="{{ route('shop.category', 'rings') }}" class="nav-mb-item_link sub">Rings</a></li>
                            <li><a href="{{ route('shop.category', 'crosses') }}" class="nav-mb-item_link sub">Crosses</a></li>
                            <li><a href="{{ route('shop.category', 'pendants') }}" class="nav-mb-item_link sub">Pendants</a></li>
                            <li><a href="{{ route('shop.category', 'earrings') }}" class="nav-mb-item_link sub">Earrings</a></li>
                            <li><a href="{{ route('shop.category', 'bracelets-bangles') }}" class="nav-mb-item_link sub">Bracelets &amp; Bangles</a></li>
                            <li><a href="{{ route('shop.category', 'cufflinks') }}" class="nav-mb-item_link sub">Cufflinks</a></li>
                            <li><a href="{{ route('shop.category', 'brooches') }}" class="nav-mb-item_link sub">Brooches</a></li>
                            <li><a href="{{ route('shop.category', 'tie-tacks') }}" class="nav-mb-item_link sub">Tie-tacks</a></li>
                            <li><a href="{{ route('shop.jewellery') }}" class="nav-mb-item_link sub fw-semibold">View all jewellery</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Collections --}}
                <li class="nav-mb-item">
                    <a href="{{ route('shop.collections') }}" class="nav-mb-item_link">
                        Collections
                        <span class="btn-open-sub"><i class="icon-plus"></i></span>
                    </a>
                    <div class="sub-nav-mobile">
                        <ul class="sub-nav-list">
                            <li class="nav-mb-item_title">Irish Heritage</li>
                            <li><a href="{{ route('shop.collection', 'claddagh') }}" class="nav-mb-item_link sub">Claddagh</a></li>
                            <li><a href="{{ route('shop.collection', 'corrib-claddagh') }}" class="nav-mb-item_link sub">Corrib Claddagh</a></li>
                            <li><a href="{{ route('shop.collection', 'trinity') }}" class="nav-mb-item_link sub">Trinity</a></li>
                            <li><a href="{{ route('shop.collection', 'an-ri') }}" class="nav-mb-item_link sub">An Rí</a></li>
                            <li><a href="{{ route('shop.collection', 'high-crosses') }}" class="nav-mb-item_link sub">High Crosses</a></li>
                            <li><a href="{{ route('shop.collection', 'newgrange') }}" class="nav-mb-item_link sub">Newgrange</a></li>
                            <li><a href="{{ route('shop.collection', 'irish-folklore') }}" class="nav-mb-item_link sub">Irish Folklore</a></li>
                            <li><a href="{{ route('shop.collection', 'shamrock') }}" class="nav-mb-item_link sub">Shamrock</a></li>
                            <li class="nav-mb-item_title">Fine Collections</li>
                            <li><a href="{{ route('shop.collection', 'livia') }}" class="nav-mb-item_link sub">Livia</a></li>
                            <li><a href="{{ route('shop.collection', 'sheelin') }}" class="nav-mb-item_link sub">Sheelin</a></li>
                            <li class="nav-mb-item_title">The Jewellery Garden</li>
                            <li><a href="{{ route('shop.collection', 'the-garden-collection') }}" class="nav-mb-item_link sub fw-semibold">The Garden Collection by FADÓ</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-daisy') }}" class="nav-mb-item_link sub">Daisy</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-wild-daisy') }}" class="nav-mb-item_link sub">Wild Daisy</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-bluebell') }}" class="nav-mb-item_link sub">Bluebell</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-forget-me-not') }}" class="nav-mb-item_link sub">Forget Me Not</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-butterfly') }}" class="nav-mb-item_link sub">Butterfly</a></li>
                            <li><a href="{{ route('shop.collection', 'garden-bee') }}" class="nav-mb-item_link sub">Bee</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Wedding / Engagement --}}
                <li class="nav-mb-item">
                    <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="nav-mb-item_link">Wedding / Engagement</a>
                </li>

                {{-- About / Contact --}}
                <li class="nav-mb-item">
                    <a href="{{ route('shop.about') }}" class="nav-mb-item_link">About Us</a>
                </li>
                <li class="nav-mb-item">
                    <a href="{{ route('shop.contact') }}" class="nav-mb-item_link">Contact</a>
                </li>
                <li class="nav-mb-item">
                    <a href="{{ route('shop.contact') }}#consultation" class="nav-mb-item_link">Book an Appointment</a>
                </li>

            </ul>
        </div>

        <div class="group-btn">
            <a href="{{ route('shop.wishlist') }}" class="tf-btn type-small style-2">
                Wishlist <i class="icon icon-heart"></i>
            </a>
            <div data-bs-dismiss="offcanvas">
                <a href="#search" data-bs-toggle="modal" class="tf-btn type-small style-2">
                    Search <i class="icon icon-magnifying-glass"></i>
                </a>
            </div>
        </div>

        <div class="flow-us-wrap">
            <h5 class="title">Follow us on</h5>
            <ul class="tf-social-icon">
                @php
                    $fb = \App\Models\Setting::get('facebook_url');
                    $ig = \App\Models\Setting::get('instagram_url');
                    $tw = \App\Models\Setting::get('twitter_url');
                @endphp
                @if($fb)
                <li><a href="{{ $fb }}" target="_blank" rel="noopener" class="social-facebook"><span class="icon"><i class="icon-fb"></i></span></a></li>
                @endif
                @if($ig)
                <li><a href="{{ $ig }}" target="_blank" rel="noopener" class="social-instagram"><span class="icon"><i class="icon-instagram-logo"></i></span></a></li>
                @endif
                @if($tw)
                <li><a href="{{ $tw }}" target="_blank" rel="noopener" class="social-x"><span class="icon"><i class="icon-x"></i></span></a></li>
                @endif
            </ul>
        </div>

    </div>

    <div class="canvas-footer">
        @php
            $activeCurrencyCode  = session('fado_currency', 'EUR');
            $availableCurrencies = \App\Models\Currency::orderBy('is_default','desc')->orderBy('code')->get();
        @endphp
        <form action="{{ route('shop.currency.switch') }}" method="POST">
            @csrf
            <div class="tf-currencies">
                <select name="currency" onchange="this.form.submit()" class="tf-dropdown-select style-default type-currencies">
                    @foreach($availableCurrencies as $cur)
                        <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>
