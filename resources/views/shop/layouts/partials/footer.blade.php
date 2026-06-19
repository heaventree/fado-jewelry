@php
    $fbUrl  = \App\Models\Setting::get('facebook_url');
    $igUrl  = \App\Models\Setting::get('instagram_url');
    $twUrl  = \App\Models\Setting::get('twitter_url');
    $phone  = \App\Models\Setting::get('store_phone');
    $email  = \App\Models\Setting::get('store_email', 'info@fadojewellery.ie');
    $addr   = \App\Models\Setting::get('store_address');
    $activeCurrencyCode  = session('fado_currency', 'EUR');
    $availableCurrencies = \App\Models\Currency::orderBy('is_default','desc')->orderBy('code')->get();
@endphp

{{-- Ochaka footer: tf-footer style-color-white bg-black --}}
<footer class="tf-footer style-color-white bg-black">
    <div class="footer-body">
        <div class="container">
            <div class="row">

                {{-- ── Brand column ─────────────────────────────────────────── --}}
                <div class="col-xl-4 col-sm-6 mb_30 mb-xl-0">
                    <div class="footer-infor">
                        <a href="{{ route('shop.home') }}" class="logo-site" style="margin-bottom:16px; display:inline-block">
                            <div class="fado-wordmark">FADÓ</div>
                            <div class="text-small-3 text-uppercase" style="letter-spacing:.18em">Fine Irish Jewellery</div>
                        </a>
                        <ul class="footer-contact mb-0">
                            @if($addr)
                            <li>
                                <i class="icon icon-map-pin"></i>
                                <span class="br-line"></span>
                                <span class="h6 text-main">{{ $addr }}</span>
                            </li>
                            @endif
                            @if($phone)
                            <li>
                                <i class="icon icon-phone"></i>
                                <span class="br-line"></span>
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="h6 link text-main">{{ $phone }}</a>
                            </li>
                            @endif
                            <li>
                                <i class="icon icon-envelope-simple"></i>
                                <span class="br-line"></span>
                                <a href="mailto:{{ $email }}" class="h6 link text-main">{{ $email }}</a>
                            </li>
                        </ul>
                        <ul class="tf-social-icon_2">
                            @if($fbUrl)
                            <li><a href="{{ $fbUrl }}" target="_blank" rel="noopener" class="link text-white"><i class="icon-fb"></i></a></li>
                            @endif
                            @if($igUrl)
                            <li><a href="{{ $igUrl }}" target="_blank" rel="noopener" class="link text-white"><i class="icon-instagram-logo"></i></a></li>
                            @endif
                            @if($twUrl)
                            <li><a href="{{ $twUrl }}" target="_blank" rel="noopener" class="link text-white"><i class="icon-x"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- ── Jewellery links ────────────────────────────────────── --}}
                <div class="col-xl-2 col-sm-6 mb_30 mb-xl-0">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Jewellery</p>
                        <div class="tf-collapse-content">
                            <ul class="footer-menu-list">
                                <li><a href="{{ route('shop.category', 'rings') }}" class="link h6">Rings</a></li>
                                <li><a href="{{ route('shop.category', 'pendants') }}" class="link h6">Pendants</a></li>
                                <li><a href="{{ route('shop.category', 'earrings') }}" class="link h6">Earrings</a></li>
                                <li><a href="{{ route('shop.category', 'crosses') }}" class="link h6">Crosses</a></li>
                                <li><a href="{{ route('shop.category', 'bracelets-bangles') }}" class="link h6">Bracelets</a></li>
                                <li><a href="{{ route('shop.category', 'cufflinks') }}" class="link h6">Cufflinks</a></li>
                                <li><a href="{{ route('shop.jewellery') }}" class="link h6">View all →</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ── Collections + Information ──────────────────────────── --}}
                <div class="col-xl-2 col-sm-6 mb_30 mb-sm-0">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Collections</p>
                        <div class="tf-collapse-content">
                            <ul class="footer-menu-list">
                                <li><a href="{{ route('shop.collection', 'claddagh') }}" class="link h6">Claddagh</a></li>
                                <li><a href="{{ route('shop.collection', 'trinity') }}" class="link h6">Trinity</a></li>
                                <li><a href="{{ route('shop.collection', 'the-garden-collection') }}" class="link h6">Garden Collection</a></li>
                                <li><a href="{{ route('shop.collection', 'newgrange') }}" class="link h6">Newgrange</a></li>
                                <li><a href="{{ route('shop.collection', 'an-ri') }}" class="link h6">An Rí</a></li>
                                <li><a href="{{ route('shop.collections') }}" class="link h6">All collections →</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-col-block mt-4">
                        <p class="footer-heading footer-heading-mobile">Information</p>
                        <div class="tf-collapse-content">
                            <ul class="footer-menu-list">
                                <li><a href="{{ route('shop.about') }}" class="link h6">About Us</a></li>
                                <li><a href="{{ route('shop.contact') }}" class="link h6">Contact</a></li>
                                <li><a href="{{ url('/terms') }}" class="link h6">Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('shop.privacy') }}" class="link h6">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ── Newsletter ─────────────────────────────────────────── --}}
                <div class="col-xl-4 col-sm-6">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Newsletter</p>
                        <div class="tf-collapse-content">
                            <div class="footer-newsletter">
                                <p class="h6 caption">
                                    Be first to hear about new collections, exclusive offers, and FADÓ events.
                                </p>
                                <form action="{{ route('shop.newsletter.subscribe') }}" method="POST" class="form-newsletter mt-3">
                                    @csrf
                                    <div class="form-content_fieldset form-get_email">
                                        <div class="fieldset-input_email">
                                            <div class="form__label-row">
                                                <div class="entry__field ip">
                                                    <input class="input style-stroke-2" type="email" name="email"
                                                           placeholder="Enter your email" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fiedset-button_submit">
                                            <button type="submit" class="tf-btn btn-white animate-btn animate-dark type-small-2">
                                                Subscribe <i class="icon icon-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer bottom — Ochaka inner-bottom structure --}}
    <div class="footer-bottom">
        <div class="container">
            <div class="inner-bottom">
                <ul class="list-hor">
                    <li><a href="{{ route('shop.about') }}" class="h6 link text-main">About FADÓ</a></li>
                    <li class="br-line type-vertical"></li>
                    <li><a href="{{ route('shop.contact') }}" class="h6 link text-main">Contact</a></li>
                </ul>
                <div class="list-hor flex-wrap">
                    <span class="h6 text-main">© {{ date('Y') }} FADÓ Jewellery</span>
                </div>
                <div class="list-hor">
                    <div class="tf-currencies">
                        <form action="{{ route('shop.currency.switch') }}" method="POST">
                            @csrf
                            <select name="currency" onchange="this.form.submit()"
                                    class="tf-dropdown-select style-default color-white-2 type-currencies">
                                @foreach($availableCurrencies as $cur)
                                    <option value="{{ $cur->code }}" {{ $activeCurrencyCode === $cur->code ? 'selected' : '' }}>{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
