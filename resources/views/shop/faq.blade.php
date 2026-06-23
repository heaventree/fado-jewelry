@extends('shop.layouts.app')

@section('title', 'FAQ — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Frequently Asked Questions</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Frequently Asked Questions</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- FAQ -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <ul class="faq-list">
                            <li class="faq-item" id="myAccount">
                                <h2 class="faq_title">My Account</h2>
                                <div class="faq_wrap" id="my-account">
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title" data-bs-target="#faq-1-1" role="button" data-bs-toggle="collapse"
                                            aria-expanded="true" aria-controls="faq-1-1">
                                            <span class="text h5">1. How do I create an account?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-1-1" class="collapse show" data-bs-parent="#my-account">
                                            <div class="accordion-body">
                                                <p class="h6">Click "Login" in the top right corner, then select "Register". You will need your name, email address and a password. Once registered you can save your wishlist, track orders and enjoy faster checkout.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-1-2" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-1-2">
                                            <span class="text h5">2. What if I forget my password?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-1-2" class="collapse" data-bs-parent="#my-account">
                                            <div class="accordion-body">
                                                <p class="h6">Click "Forgot your password?" on the login page and enter your email address. We will send you a link to reset your password.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-1-3" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-1-3">
                                            <span class="text h5">3. Can I checkout as a guest?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-1-3" class="collapse" data-bs-parent="#my-account">
                                            <div class="accordion-body">
                                                <p class="h6">Yes. You do not need an account to place an order. However, creating an account lets you track your orders and save your shipping details for next time.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="faq-item" id="ordersPurchases">
                                <h2 class="faq_title">Orders &amp; Purchases</h2>
                                <div class="faq_wrap" id="order-and-purchase">
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-2-1" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-2-1">
                                            <span class="text h5">1. How do I place an order?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-2-1" class="collapse" data-bs-parent="#order-and-purchase">
                                            <div class="accordion-body">
                                                <p class="h6">Browse our collections, select your preferred metal and size, then add the item to your bag. When you are ready, proceed to checkout, fill in your shipping details and confirm payment.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-2-2" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-2-2">
                                            <span class="text h5">2. Can I change or cancel my order?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-2-2" class="collapse" data-bs-parent="#order-and-purchase">
                                            <div class="accordion-body">
                                                <p class="h6">Please contact us as soon as possible. If your order has not yet been dispatched we will do our best to make changes or cancel it for you.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-2-3" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-2-3">
                                            <span class="text h5">3. What ring sizes do you offer?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-2-3" class="collapse" data-bs-parent="#order-and-purchase">
                                            <div class="accordion-body">
                                                <p class="h6">We offer US ring sizes from 5 to 10. If you are unsure of your size, please contact us and we can help you find the right fit.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="faq-item" id="shippingTracking">
                                <h2 class="faq_title">Shipping &amp; Delivery</h2>
                                <div class="faq_wrap" id="shipping-and-tracking">
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-3-1" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-3-1">
                                            <span class="text h5">1. Do you offer free shipping?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-3-1" class="collapse" data-bs-parent="#shipping-and-tracking">
                                            <div class="accordion-body">
                                                <p class="h6">Yes, we offer free shipping on orders over the threshold shown at checkout. Shipping rates for Ireland and international orders are calculated at checkout.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-3-2" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-3-2">
                                            <span class="text h5">2. How long does delivery take?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-3-2" class="collapse" data-bs-parent="#shipping-and-tracking">
                                            <div class="accordion-body">
                                                <p class="h6">Ireland deliveries typically arrive within 2-4 business days. International orders usually take 5-10 business days depending on the destination.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-3-3" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-3-3">
                                            <span class="text h5">3. Do you ship internationally?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-3-3" class="collapse" data-bs-parent="#shipping-and-tracking">
                                            <div class="accordion-body">
                                                <p class="h6">Yes, we ship to the UK, EU, US, Canada, Australia and more. International shipping rates are calculated at checkout.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="faq-item" id="returnsRefunds">
                                <h2 class="faq_title">Returns &amp; Refunds</h2>
                                <div class="faq_wrap" id="return-and-refund">
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-4-1" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-4-1">
                                            <span class="text h5">1. What is your return policy?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-4-1" class="collapse" data-bs-parent="#return-and-refund">
                                            <div class="accordion-body">
                                                <p class="h6">We accept returns within 30 days of delivery for unworn items in their original packaging. Please contact us to arrange a return.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-4-2" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-4-2">
                                            <span class="text h5">2. How do I get a refund?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-4-2" class="collapse" data-bs-parent="#return-and-refund">
                                            <div class="accordion-body">
                                                <p class="h6">Once we receive your returned item and inspect it, we will process your refund to the original payment method within 5-7 business days.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="faq-item" id="otherTopic">
                                <h2 class="faq_title">Other Questions</h2>
                                <div class="faq_wrap" id="orther-topic">
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-5-1" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-5-1">
                                            <span class="text h5">1. Can I book a consultation?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-5-1" class="collapse" data-bs-parent="#orther-topic">
                                            <div class="accordion-body">
                                                <p class="h6">Yes! Visit our <a href="{{ route('shop.contact') }}#consultation" class="link fw-semibold">Contact page</a> to book a one-to-one consultation with our team.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title collapsed" data-bs-target="#faq-5-2" role="button" data-bs-toggle="collapse"
                                            aria-expanded="false" aria-controls="faq-5-2">
                                            <span class="text h5">2. Are your pieces hallmarked?</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-5-2" class="collapse" data-bs-parent="#orther-topic">
                                            <div class="accordion-body">
                                                <p class="h6">Yes, all our gold and silver pieces are hallmarked at the Dublin Assay Office, guaranteeing the quality of the metal used.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="blog-sidebar sidebar-content-wrap sticky-top">
                            <div class="sidebar-item">
                                <h4 class="sb-title">Frequently asked questions</h4>
                            </div>
                            <div class="sidebar-item">
                                <h4 class="sb-title">Category</h4>
                                <ul class="sb-category">
                                    <li><a href="#myAccount" class="h6 link">My account</a></li>
                                    <li><a href="#ordersPurchases" class="h6 link">Orders &amp; purchases</a></li>
                                    <li><a href="#shippingTracking" class="h6 link">Shipping &amp; Delivery</a></li>
                                    <li><a href="#returnsRefunds" class="h6 link">Returns &amp; Refunds</a></li>
                                    <li><a href="#otherTopic" class="h6 link">Other questions</a></li>
                                </ul>
                            </div>
                            <div class="sidebar-item">
                                <div class="sb-banner hover-img">
                                    <a href="{{ route('shop.jewellery') }}" class="image img-style">
                                        <img src="/images/ochaka/blog/side-banner.jpg" data-src="/images/ochaka/blog/side-banner.jpg" alt="Banner">
                                    </a>
                                    <div class="content">
                                        <h5 class="sub-title text-primary">Browse our range</h5>
                                        <h2 class="fw-semibold title">
                                            <a href="{{ route('shop.jewellery') }}" class="text-white link">
                                                Fine Irish Jewellery
                                            </a>
                                        </h2>
                                        <a href="{{ route('shop.jewellery') }}" class="tf-btn btn-white animate-btn animate-dark">
                                            Shop now
                                            <i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /FAQ -->
@endsection
