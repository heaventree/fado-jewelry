{{--
    Source: resources/theme-reference/home-jewelry.html, the `<footer class="tf-footer style-color-white bg-black">`
    block (lines 2425-2666).

    Deviations from the reference (documented):
    - Footer contact details (address/phone/email) and social links are pulled from Setting::get() rather than
      the demo's hardcoded "8500 Lorem Street..." / themesflat@support.com — CLAUDE.md requires no hardcoded
      contact info; each social icon is only rendered if the corresponding *_url setting has a value.
      The reference's TikTok icon is dropped — there is no `tiktok_url` setting (only facebook/instagram/twitter
      are defined), so including it would be a fabricated link.
    - The "Shopping" / "Information" footer-menu-list columns are trimmed to links that resolve to real, built
      routes (About Us, Contact, Wishlist, Privacy). The reference's "Shop by Brand", "Track order", and
      "Help Center" items have no corresponding page in this build and are omitted rather than left as dead
      links to faq.html-equivalent pages.
    - The Brevo/Sendinblue newsletter form markup (`#sib-form`, posting to a themesflat-owned sibforms.com
      endpoint) is a third-party integration that was never wired up for FADO — replaced with a plain POST to
      the real `shop.newsletter.subscribe` route, keeping the same input/button classes
      (`input style-stroke-2`, `tf-btn btn-white animate-btn`) so it renders identically.
    - The footer-bottom payment-method icon list (Visa/Mastercard/Amex/Discover/PayPal) is omitted — per
      CLAUDE.md, the Stripe integration and payment method are still pending a decision with the client, so
      displaying specific card-network logos would assert payment methods that aren't confirmed.
    - The footer-bottom currency/language `<select>` pair is omitted here (the language select has no i18n
      behind it, and the currency switcher already lives in the header/mobile-menu); "Help & FAQs" / "Factory"
      links are dropped as there's no FAQ or factory page in scope.
--}}
<footer class="tf-footer style-color-white bg-black">
    <div class="footer-body">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb_30 mb-xl-0">
                    <div class="footer-infor">
                        <a href="{{ route('shop.home') }}" class="logo-site">
                            <img src="{{ asset('images/logo-white.svg') }}" alt="{{ \App\Models\Setting::get('store_name', 'FADÓ') }}" class="fado-logo">
                        </a>
                        <ul class="footer-contact mb-0">
                            @if($address = \App\Models\Setting::get('store_address'))
                            <li>
                                <i class="icon icon-map-pin"></i>
                                <span class="br-line"></span>
                                <a href="https://www.google.com/maps?q={{ urlencode($address) }}" target="_blank" class="h6 link text-main">
                                    {{ $address }}
                                </a>
                            </li>
                            @endif
                            @if($phone = \App\Models\Setting::get('store_phone'))
                            <li>
                                <i class="icon icon-phone"></i>
                                <span class="br-line"></span>
                                <a href="tel:{{ $phone }}" class="h6 link text-main">{{ $phone }}</a>
                            </li>
                            @endif
                            @if($email = \App\Models\Setting::get('store_email'))
                            <li>
                                <i class="icon icon-envelope-simple"></i>
                                <span class="br-line"></span>
                                <a href="mailto:{{ $email }}" class="h6 link text-main">{{ $email }}</a>
                            </li>
                            @endif
                        </ul>
                        <ul class="tf-social-icon_2">
                            @if($fb = \App\Models\Setting::get('facebook_url'))
                            <li><a href="{{ $fb }}" target="_blank" class="link text-white"><i class="icon-fb"></i></a></li>
                            @endif
                            @if($ig = \App\Models\Setting::get('instagram_url'))
                            <li><a href="{{ $ig }}" target="_blank" class="link text-white"><i class="icon-instagram-logo"></i></a></li>
                            @endif
                            @if($tw = \App\Models\Setting::get('twitter_url'))
                            <li><a href="{{ $tw }}" target="_blank" class="link text-white"><i class="icon-x"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb_30 mb-xl-0">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Shopping</p>
                        <div class="tf-collapse-content">
                            <ul class="footer-menu-list">
                                <li><a href="{{ route('shop.jewellery') }}" class="link h6">Jewellery</a></li>
                                <li><a href="{{ route('shop.collections') }}" class="link h6">Collections</a></li>
                                <li><a href="{{ route('shop.wishlist') }}" class="link h6">My Wishlist</a></li>
                                <li><a href="{{ route('shop.cart') }}" class="link h6">My Bag</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb_30 mb-sm-0">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Information</p>
                        <div class="tf-collapse-content">
                            <ul class="footer-menu-list">
                                <li><a href="{{ route('shop.about') }}" class="link h6">About Us</a></li>
                                <li><a href="{{ route('shop.contact') }}" class="link h6">Contact</a></li>
                                <li><a href="{{ route('shop.contact') }}#consultation" class="link h6">Book a Consultation</a></li>
                                <li><a href="{{ route('shop.faq') }}" class="link h6">Help & FAQs</a></li>
                                <li><a href="{{ route('shop.privacy') }}" class="link h6">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="footer-col-block">
                        <p class="footer-heading footer-heading-mobile">Newsletter</p>
                        <div class="tf-collapse-content">
                            <div class="footer-newsletter">
                                <p class="h6 caption">
                                    Become the first to know about new collections and offers.
                                </p>
                                <form action="{{ route('shop.newsletter.subscribe') }}" method="POST" class="sib-form sib-form_footer">
                                    @csrf
                                    <div class="form-content_fieldset form-get_email">
                                        <div class="fieldset-input_email">
                                            <div class="sib-input sib-form-block">
                                                <div class="form__entry entry_block">
                                                    <div class="form__label-row">
                                                        <div class="entry__field ip">
                                                            <input class="input style-stroke-2" type="email" id="EMAIL" name="email" autocomplete="off" placeholder="Enter your email" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fiedset-button_submit">
                                            <div class="sib-form-block">
                                                <button class="sib-form-block__button tf-btn btn-white animate-btn animate-dark type-small-2" type="submit">
                                                    Subscribe
                                                    <i class="icon icon-arrow-right"></i>
                                                </button>
                                            </div>
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
    <div class="footer-bottom">
        <div class="container">
            <div class="inner-bottom">
                <span class="h6 text-main">&copy; {{ now()->year }} {{ \App\Models\Setting::get('store_name', 'FADÓ Jewellery') }}. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>
