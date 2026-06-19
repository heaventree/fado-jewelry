@php
    $fbUrl  = \App\Models\Setting::get('facebook_url');
    $igUrl  = \App\Models\Setting::get('instagram_url');
    $twUrl  = \App\Models\Setting::get('twitter_url');
    $phone  = \App\Models\Setting::get('store_phone');
    $email  = \App\Models\Setting::get('store_email', 'info@fadojewellery.ie');
    $addr   = \App\Models\Setting::get('store_address');
@endphp

<footer class="tf-footer style-color-white fado-footer-bg">
    <div class="footer-body">
        <div class="container">
            <div class="row">

                {{-- ── Brand column ─────────────────────────────────────────── --}}
                <div class="col-xl-4 col-sm-6 mb_30 mb-xl-0">
                    <div class="footer-infor">
                        <a href="{{ route('shop.home') }}" class="logo-site" style="text-decoration:none; margin-bottom:16px; display:inline-block">
                            <div class="fado-wordmark text-white">FADÓ</div>
                            <div class="text-small-3 text-uppercase" style="letter-spacing:.18em; color:rgba(255,255,255,.65)">Fine Irish Jewellery</div>
                        </a>
                        <p class="h6" style="color:rgba(255,255,255,.65); max-width:280px; line-height:1.7; margin-bottom:20px">
                            Handcrafted in the Irish tradition. Timeless pieces inspired by Ireland's heritage, landscape, and spirit.
                        </p>
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
                        <ul class="tf-social-icon_2 mt-3">
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
                                <p class="h6 caption" style="color:rgba(255,255,255,.65)">
                                    Be first to hear about new collections, exclusive offers, and FADÓ events.
                                </p>
                                <form action="{{ route('shop.newsletter.subscribe') }}" method="POST" class="form-newsletter mt-3">
                                    @csrf
                                    <div class="d-flex gap-0">
                                        <input type="email" name="email" class="form-control rounded-0"
                                               placeholder="Your email address" required
                                               style="background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.2); color:#fff; font-size:.875rem">
                                        <button type="submit" class="tf-btn animate-btn rounded-0" style="white-space:nowrap">Subscribe</button>
                                    </div>
                                </form>
                                <p class="h6 mt-2" style="color:rgba(255,255,255,.4)">
                                    By subscribing you agree to our <a href="{{ route('shop.privacy') }}" class="link" style="color:rgba(255,255,255,.5); text-decoration:underline">Privacy Policy</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer bottom bar --}}
    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <p class="mb-0 h6 text-main">© {{ date('Y') }} FADÓ Jewellery. All rights reserved.</p>
                <p class="mb-0 h6 text-main" style="opacity:.55">Secure payments: Visa · Mastercard · PayPal · Amex</p>
            </div>
        </div>
    </div>
</footer>
