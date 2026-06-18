<footer class="fado-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">

            {{-- ── Brand column ──────────────────────────────────────────── --}}
            <div class="col-xl-4 col-sm-6">
                <a href="{{ route('shop.home') }}" class="d-inline-block mb-3" style="text-decoration:none">
                    <div style="font-family: Georgia, serif; font-size: 1.75rem; font-weight: 700; color:#fff; letter-spacing: .12em; line-height:1">FADÓ</div>
                    <div style="font-size:.65rem; letter-spacing:.22em; color: var(--fado-gold); text-transform:uppercase; margin-top:3px">Fine Irish Jewellery</div>
                </a>
                <p style="font-size:.875rem; color:rgba(255,255,255,.65); max-width:280px; line-height:1.7">
                    Handcrafted in the Irish tradition. Timeless pieces inspired by Ireland's heritage, landscape, and spirit.
                </p>

                @php
                    $fbUrl  = \App\Models\Setting::get('facebook_url');
                    $igUrl  = \App\Models\Setting::get('instagram_url');
                    $twUrl  = \App\Models\Setting::get('twitter_url');
                    $phone  = \App\Models\Setting::get('contact_phone');
                    $email  = \App\Models\Setting::get('contact_email', 'info@fadojewellery.ie');
                @endphp

                <div class="d-flex gap-2 mt-3">
                    @if($fbUrl)
                        <a href="{{ $fbUrl }}" target="_blank" rel="noopener" class="fado-social-icon" title="Facebook">
                            <i class="icon-fb"></i>
                        </a>
                    @endif
                    @if($igUrl)
                        <a href="{{ $igUrl }}" target="_blank" rel="noopener" class="fado-social-icon" title="Instagram">
                            <i class="icon-instagram-logo"></i>
                        </a>
                    @endif
                    @if($twUrl)
                        <a href="{{ $twUrl }}" target="_blank" rel="noopener" class="fado-social-icon" title="X / Twitter">
                            <i class="icon-x"></i>
                        </a>
                    @endif
                </div>

                @if($phone || $email)
                    <ul style="list-style:none; padding:0; margin-top:18px">
                        @if($phone)
                        <li style="margin-bottom:8px">
                            <i class="icon icon-phone" style="color: var(--fado-gold); margin-right:8px; font-size:.875rem"></i>
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" style="font-size:.875rem">{{ $phone }}</a>
                        </li>
                        @endif
                        <li>
                            <i class="icon icon-envelope-simple" style="color: var(--fado-gold); margin-right:8px; font-size:.875rem"></i>
                            <a href="mailto:{{ $email }}" style="font-size:.875rem">{{ $email }}</a>
                        </li>
                    </ul>
                @endif
            </div>

            {{-- ── Shop links ─────────────────────────────────────────────── --}}
            <div class="col-xl-2 col-sm-6">
                <p class="fado-footer-heading">Jewellery</p>
                <a href="{{ route('shop.category', 'rings') }}" class="fado-footer-link">Rings</a>
                <a href="{{ route('shop.category', 'pendants') }}" class="fado-footer-link">Pendants</a>
                <a href="{{ route('shop.category', 'earrings') }}" class="fado-footer-link">Earrings</a>
                <a href="{{ route('shop.category', 'crosses') }}" class="fado-footer-link">Crosses</a>
                <a href="{{ route('shop.category', 'bracelets-bangles') }}" class="fado-footer-link">Bracelets</a>
                <a href="{{ route('shop.jewellery') }}" class="fado-footer-link" style="color: var(--fado-green-light); margin-top:6px">View all →</a>
            </div>

            {{-- ── Collections ────────────────────────────────────────────── --}}
            <div class="col-xl-2 col-sm-6">
                <p class="fado-footer-heading">Collections</p>
                <a href="{{ route('shop.collection', 'claddagh') }}" class="fado-footer-link">Claddagh</a>
                <a href="{{ route('shop.collection', 'trinity') }}" class="fado-footer-link">Trinity</a>
                <a href="{{ route('shop.collection', 'the-garden-collection') }}" class="fado-footer-link">Garden Collection</a>
                <a href="{{ route('shop.collection', 'newgrange') }}" class="fado-footer-link">Newgrange</a>
                <a href="{{ route('shop.collection', 'an-ri') }}" class="fado-footer-link">An Rí</a>
                <a href="{{ route('shop.collections') }}" class="fado-footer-link" style="color: var(--fado-green-light); margin-top:6px">All collections →</a>
            </div>

            {{-- ── Newsletter ──────────────────────────────────────────────── --}}
            <div class="col-xl-4 col-sm-6">
                <p class="fado-footer-heading">Stay in touch</p>
                <p style="font-size:.875rem; color:rgba(255,255,255,.65); line-height:1.7">
                    Be first to hear about new collections, exclusive offers, and FADÓ events.
                </p>
                <form class="fado-newsletter-form" action="{{ route('shop.newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
                <p style="font-size:.75rem; color:rgba(255,255,255,.4); margin-top:10px">
                    By subscribing you agree to our
                    <a href="{{ route('shop.privacy') }}" style="color:rgba(255,255,255,.5); text-decoration:underline">Privacy Policy</a>.
                </p>

                <div class="mt-4">
                    <p class="fado-footer-heading" style="margin-bottom:10px">Information</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('shop.about') }}" class="fado-footer-link">About Us</a>
                        <a href="{{ route('shop.contact') }}" class="fado-footer-link">Contact</a>
                        <a href="{{ url('/terms') }}" class="fado-footer-link">Terms</a>
                        <a href="{{ route('shop.privacy') }}" class="fado-footer-link">Privacy</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Footer bottom bar ───────────────────────────────────────────────── --}}
    <div class="fado-footer-bottom">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span>© {{ date('Y') }} FADÓ Jewellery. All rights reserved.</span>
            <div class="d-flex align-items-center gap-2" style="opacity:.55">
                <span style="font-size:.75rem">Secure payments:</span>
                <i class="icon icon-credit-card" style="font-size:1rem"></i>
                <span style="font-size:.75rem">Visa · Mastercard · PayPal · Amex</span>
            </div>
        </div>
    </div>
</footer>
