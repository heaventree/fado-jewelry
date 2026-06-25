@extends('layouts.vertical', ['title' => 'Settings'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <iconify-icon icon="solar:check-circle-broken" class="align-middle me-1"></iconify-icon>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-semibold mb-1">
            <iconify-icon icon="solar:settings-bold-duotone" class="text-primary me-1"></iconify-icon>
            Site Settings
        </h4>
        <p class="text-muted mb-0 fs-13">Store-wide configuration for FADÓ Jewellery.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
@csrf
@method('PATCH')

<div class="row g-4">

{{-- ── Left column ──────────────────────────────────────────────────────────── --}}
<div class="col-lg-8">

    {{-- Store identity --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:shop-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Store Identity
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Store Name <span class="text-danger">*</span></label>
                <input type="text" name="store_name"
                       value="{{ old('store_name', $settings['store_name'] ?? 'FADÓ Jewellery') }}"
                       class="form-control @error('store_name') is-invalid @enderror"
                       maxlength="120" required>
                <div class="form-text">Appears in the browser tab, emails, and page titles.</div>
                @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Tagline</label>
                <input type="text" name="store_tagline"
                       value="{{ old('store_tagline', $settings['store_tagline'] ?? '') }}"
                       class="form-control @error('store_tagline') is-invalid @enderror"
                       maxlength="200" placeholder="Fine Irish Jewellery">
                @error('store_tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Contact details --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:phone-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Contact Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">General Contact Email <span class="text-danger">*</span></label>
                    <input type="email" name="store_email"
                           value="{{ old('store_email', $settings['store_email'] ?? '') }}"
                           class="form-control @error('store_email') is-invalid @enderror"
                           placeholder="info@fadojewellery.ie" required>
                    <div class="form-text">Shown on the contact page.</div>
                    @error('store_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="store_phone"
                           value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                           class="form-control @error('store_phone') is-invalid @enderror"
                           placeholder="+353 ...">
                    @error('store_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Orders Notification Email <span class="text-danger">*</span></label>
                    <input type="email" name="orders_email"
                           value="{{ old('orders_email', $settings['orders_email'] ?? '') }}"
                           class="form-control @error('orders_email') is-invalid @enderror"
                           placeholder="orders@fadojewellery.ie" required>
                    <div class="form-text">New order confirmations are sent here.</div>
                    @error('orders_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Consultation Enquiry Email <span class="text-danger">*</span></label>
                    <input type="email" name="consultation_email"
                           value="{{ old('consultation_email', $settings['consultation_email'] ?? '') }}"
                           class="form-control @error('consultation_email') is-invalid @enderror"
                           placeholder="info@fadojewellery.ie" required>
                    <div class="form-text">Consultation form submissions are forwarded here.</div>
                    @error('consultation_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="store_address" rows="3"
                          class="form-control @error('store_address') is-invalid @enderror"
                          placeholder="Street, City, County, Eircode">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
                @error('store_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- SEO --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:global-bold-duotone" class="text-primary fs-18"></iconify-icon>
                SEO &amp; Meta
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Default Meta Title</label>
                <input type="text" name="meta_title"
                       value="{{ old('meta_title', $settings['meta_title'] ?? '') }}"
                       class="form-control @error('meta_title') is-invalid @enderror"
                       maxlength="120" placeholder="FADÓ Jewellery — Fine Irish Jewellery">
                <div class="form-text">Used on pages that don't have their own meta title. Keep under 60 characters.</div>
                @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Default Meta Description</label>
                <textarea name="meta_description" rows="3"
                          class="form-control @error('meta_description') is-invalid @enderror"
                          maxlength="300"
                          placeholder="Handcrafted Irish jewellery. Explore our collections…">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                <div class="form-text">Used on pages that don't have their own description. Keep under 160 characters.</div>
                @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Google Analytics ID</label>
                <input type="text" name="google_analytics_id"
                       value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                       class="form-control @error('google_analytics_id') is-invalid @enderror"
                       placeholder="G-XXXXXXXXXX" style="max-width:240px">
                <div class="form-text">GA4 measurement ID (starts with G-) or Universal Analytics ID (UA-).</div>
                @error('google_analytics_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Social --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:share-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Social Media
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Facebook URL</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <iconify-icon icon="ri:facebook-fill" class="fs-16"></iconify-icon>
                    </span>
                    <input type="url" name="facebook_url"
                           value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                           class="form-control @error('facebook_url') is-invalid @enderror"
                           placeholder="https://facebook.com/fadojewellery">
                    @error('facebook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Instagram URL</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <iconify-icon icon="ri:instagram-line" class="fs-16"></iconify-icon>
                    </span>
                    <input type="url" name="instagram_url"
                           value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                           class="form-control @error('instagram_url') is-invalid @enderror"
                           placeholder="https://instagram.com/fadojewellery">
                    @error('instagram_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">X / Twitter URL</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <iconify-icon icon="ri:twitter-x-line" class="fs-16"></iconify-icon>
                    </span>
                    <input type="url" name="twitter_url"
                           value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}"
                           class="form-control @error('twitter_url') is-invalid @enderror"
                           placeholder="https://x.com/fadojewellery">
                    @error('twitter_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>


    {{-- Payments --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:card-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Payment
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="cod_enabled" id="cod_enabled"
                           value="1" {{ ($settings['cod_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="cod_enabled">
                        Enable order-on-request checkout (phone / payment link)
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Payment notice text</label>
                <textarea name="payment_method_label" rows="3"
                          class="form-control @error('payment_method_label') is-invalid @enderror"
                          maxlength="500"
                          placeholder="Pay by card — our team will contact you…">{{ old('payment_method_label', $settings['payment_method_label'] ?? '') }}</textarea>
                <div class="form-text">Shown to customers during checkout below the Payment heading.</div>
                @error('payment_method_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Stripe Publishable Key</label>
                    <input type="text" name="stripe_publishable_key"
                           value="{{ old('stripe_publishable_key', $settings['stripe_publishable_key'] ?? '') }}"
                           class="form-control @error('stripe_publishable_key') is-invalid @enderror"
                           placeholder="pk_live_…">
                    @error('stripe_publishable_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Stripe Secret Key</label>
                    <input type="password" name="stripe_secret_key"
                           value="{{ old('stripe_secret_key', $settings['stripe_secret_key'] ?? '') }}"
                           class="form-control @error('stripe_secret_key') is-invalid @enderror"
                           placeholder="sk_live_…">
                    <div class="form-text text-warning">
                        <iconify-icon icon="solar:danger-triangle-broken" class="align-middle"></iconify-icon>
                        Prefer storing secrets in <code>.env</code> as <code>STRIPE_SECRET_KEY</code>.
                    </div>
                    @error('stripe_secret_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Shipping --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:delivery-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Shipping
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Free shipping threshold (€)</label>
                    <input type="number" name="free_shipping_threshold" min="0" step="0.01"
                           value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '75') }}"
                           class="form-control @error('free_shipping_threshold') is-invalid @enderror">
                    <div class="form-text">Orders above this amount get free delivery.</div>
                    @error('free_shipping_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Ireland rate (€)</label>
                    <input type="number" name="shipping_rate_ireland" min="0" step="0.01"
                           value="{{ old('shipping_rate_ireland', $settings['shipping_rate_ireland'] ?? '5.95') }}"
                           class="form-control @error('shipping_rate_ireland') is-invalid @enderror">
                    @error('shipping_rate_ireland')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">International rate (€)</label>
                    <input type="number" name="shipping_rate_international" min="0" step="0.01"
                           value="{{ old('shipping_rate_international', $settings['shipping_rate_international'] ?? '12.95') }}"
                           class="form-control @error('shipping_rate_international') is-invalid @enderror">
                    @error('shipping_rate_international')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Shipping notice (shown in header)</label>
                <input type="text" name="shipping_notice" maxlength="200"
                       value="{{ old('shipping_notice', $settings['shipping_notice'] ?? '') }}"
                       class="form-control @error('shipping_notice') is-invalid @enderror"
                       placeholder="Free delivery on orders over €75">
                @error('shipping_notice')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Display --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:widget-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Display
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Products per page</label>
                    <input type="number" name="products_per_page" min="4" max="96"
                           value="{{ old('products_per_page', $settings['products_per_page'] ?? '16') }}"
                           class="form-control @error('products_per_page') is-invalid @enderror">
                    @error('products_per_page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">New arrivals count</label>
                    <input type="number" name="new_arrivals_count" min="1" max="24"
                           value="{{ old('new_arrivals_count', $settings['new_arrivals_count'] ?? '8') }}"
                           class="form-control @error('new_arrivals_count') is-invalid @enderror">
                    @error('new_arrivals_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Featured collections</label>
                    <input type="number" name="featured_collections_count" min="1" max="12"
                           value="{{ old('featured_collections_count', $settings['featured_collections_count'] ?? '6') }}"
                           class="form-control @error('featured_collections_count') is-invalid @enderror">
                    @error('featured_collections_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Related products</label>
                    <input type="number" name="related_products_count" min="1" max="12"
                           value="{{ old('related_products_count', $settings['related_products_count'] ?? '4') }}"
                           class="form-control @error('related_products_count') is-invalid @enderror">
                    @error('related_products_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Homepage featured product</label>
                    <select name="featured_product_id" class="form-select @error('featured_product_id') is-invalid @enderror">
                        <option value="">— None —</option>
                        @foreach(\App\Models\Product::where('is_active', true)->orderBy('name')->get() as $p)
                        <option value="{{ $p->id }}" {{ (string) old('featured_product_id', $settings['featured_product_id'] ?? '') === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('featured_product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex gap-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="wishlist_enabled" id="wishlist_enabled"
                           value="1" {{ ($settings['wishlist_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="wishlist_enabled">Enable wishlist</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="reviews_enabled" id="reviews_enabled"
                           value="1" {{ ($settings['reviews_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="reviews_enabled">Enable product reviews</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:box-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Orders &amp; Email
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">From name (order emails)</label>
                    <input type="text" name="order_email_from_name" maxlength="100"
                           value="{{ old('order_email_from_name', $settings['order_email_from_name'] ?? '') }}"
                           class="form-control @error('order_email_from_name') is-invalid @enderror"
                           placeholder="FADÓ Jewellery">
                    @error('order_email_from_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">From address (order emails)</label>
                    <input type="email" name="order_email_from_address" maxlength="200"
                           value="{{ old('order_email_from_address', $settings['order_email_from_address'] ?? '') }}"
                           class="form-control @error('order_email_from_address') is-invalid @enderror"
                           placeholder="noreply@fadojewellery.ie">
                    @error('order_email_from_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-0" style="max-width:200px">
                <label class="form-label fw-semibold">Low stock threshold</label>
                <input type="number" name="low_stock_threshold" min="0"
                       value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? '3') }}"
                       class="form-control @error('low_stock_threshold') is-invalid @enderror">
                <div class="form-text">Variants with stock ≤ this are flagged as low stock in the admin.</div>
                @error('low_stock_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Consultation --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:calendar-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Consultation
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="consultation_enabled" id="consultation_enabled"
                       value="1" {{ ($settings['consultation_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="consultation_enabled">
                    Enable consultation booking feature
                </label>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Intro text (shown above the consultation form)</label>
                <textarea name="consultation_intro_text" rows="3"
                          class="form-control @error('consultation_intro_text') is-invalid @enderror"
                          maxlength="500"
                          placeholder="Book a private consultation with our jewellery specialists…">{{ old('consultation_intro_text', $settings['consultation_intro_text'] ?? '') }}</textarea>
                @error('consultation_intro_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Homepage --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:home-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Homepage
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Category Section Subtitle</label>
                <input type="text" name="homepage_subtitle_1" maxlength="300"
                       value="{{ old('homepage_subtitle_1', $settings['homepage_subtitle_1'] ?? '') }}"
                       class="form-control @error('homepage_subtitle_1') is-invalid @enderror"
                       placeholder="From classic rings to Celtic crosses…">
                @error('homepage_subtitle_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Reviews Section Subtitle</label>
                <input type="text" name="homepage_subtitle_2" maxlength="300"
                       value="{{ old('homepage_subtitle_2', $settings['homepage_subtitle_2'] ?? '') }}"
                       class="form-control @error('homepage_subtitle_2') is-invalid @enderror"
                       placeholder="What our customers say">
                @error('homepage_subtitle_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Section Subtitle 3 (spare)</label>
                <input type="text" name="homepage_subtitle_3" maxlength="300"
                       value="{{ old('homepage_subtitle_3', $settings['homepage_subtitle_3'] ?? '') }}"
                       class="form-control @error('homepage_subtitle_3') is-invalid @enderror">
                @error('homepage_subtitle_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <hr>
            <p class="fw-semibold text-muted fs-13 mb-2">Trust Strip Labels</p>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Label 1</label>
                    <input type="text" name="trust_label_1" maxlength="100" value="{{ old('trust_label_1', $settings['trust_label_1'] ?? '') }}" class="form-control" placeholder="30 Day Returns">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Sublabel 1</label>
                    <input type="text" name="trust_sub_1" maxlength="200" value="{{ old('trust_sub_1', $settings['trust_sub_1'] ?? '') }}" class="form-control" placeholder="30-day returns on unworn pieces">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Label 2</label>
                    <input type="text" name="trust_label_2" maxlength="100" value="{{ old('trust_label_2', $settings['trust_label_2'] ?? '') }}" class="form-control" placeholder="Secure Payment">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Sublabel 2</label>
                    <input type="text" name="trust_sub_2" maxlength="200" value="{{ old('trust_sub_2', $settings['trust_sub_2'] ?? '') }}" class="form-control" placeholder="Encrypted, secure payment">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Label 3</label>
                    <input type="text" name="trust_label_3" maxlength="100" value="{{ old('trust_label_3', $settings['trust_label_3'] ?? '') }}" class="form-control" placeholder="Free Delivery">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Sublabel 3</label>
                    <input type="text" name="trust_sub_3" maxlength="200" value="{{ old('trust_sub_3', $settings['trust_sub_3'] ?? '') }}" class="form-control" placeholder="Free delivery on orders over €75">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Label 4</label>
                    <input type="text" name="trust_label_4" maxlength="100" value="{{ old('trust_label_4', $settings['trust_label_4'] ?? '') }}" class="form-control" placeholder="Irish Crafted">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Sublabel 4</label>
                    <input type="text" name="trust_sub_4" maxlength="200" value="{{ old('trust_sub_4', $settings['trust_sub_4'] ?? '') }}" class="form-control" placeholder="Handcrafted in Ireland">
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label fw-semibold">Sale Banner Image URL</label>
                <input type="text" name="sale_banner_image" maxlength="500"
                       value="{{ old('sale_banner_image', $settings['sale_banner_image'] ?? '') }}"
                       class="form-control @error('sale_banner_image') is-invalid @enderror"
                       placeholder="/images/ochaka/banner/banner-cd-V01.jpg">
                @error('sale_banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Newsletter Intro Text</label>
                <input type="text" name="newsletter_intro" maxlength="300"
                       value="{{ old('newsletter_intro', $settings['newsletter_intro'] ?? '') }}"
                       class="form-control @error('newsletter_intro') is-invalid @enderror"
                       placeholder="Become the first to know about new collections and offers.">
                @error('newsletter_intro')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Product --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:bag-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Product Page
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Delivery &amp; Shipping Info</label>
                <textarea name="delivery_info" rows="4"
                          class="form-control @error('delivery_info') is-invalid @enderror"
                          placeholder="Orders dispatched within 2–3 working days…">{{ old('delivery_info', $settings['delivery_info'] ?? '') }}</textarea>
                <div class="form-text">Shown on the product page Delivery tab.</div>
                @error('delivery_info')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Returns &amp; Exchanges Policy</label>
                <textarea name="returns_policy" rows="4"
                          class="form-control @error('returns_policy') is-invalid @enderror"
                          placeholder="30-day return policy on all unworn items…">{{ old('returns_policy', $settings['returns_policy'] ?? '') }}</textarea>
                <div class="form-text">Shown on the product page Returns tab.</div>
                @error('returns_policy')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- About --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary fs-18"></iconify-icon>
                About Us Page
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Hero Image URL</label>
                <input type="text" name="about_hero_image" maxlength="500"
                       value="{{ old('about_hero_image', $settings['about_hero_image'] ?? '') }}"
                       class="form-control @error('about_hero_image') is-invalid @enderror"
                       placeholder="/images/ochaka/section/about-us.jpg">
                @error('about_hero_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Hero Heading</label>
                <input type="text" name="about_heading" maxlength="200"
                       value="{{ old('about_heading', $settings['about_heading'] ?? '') }}"
                       class="form-control @error('about_heading') is-invalid @enderror"
                       placeholder="HANDCRAFTED IRISH JEWELLERY WITH HERITAGE & HEART">
                @error('about_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Brand Story</label>
                <textarea name="about_story" rows="6"
                          class="form-control @error('about_story') is-invalid @enderror"
                          placeholder="Your brand story…">{{ old('about_story', $settings['about_story'] ?? '') }}</textarea>
                @error('about_story')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <label class="form-label">Gallery Image 1 URL</label>
                    <input type="text" name="about_gallery_1" maxlength="500" value="{{ old('about_gallery_1', $settings['about_gallery_1'] ?? '') }}" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Gallery Image 2 URL</label>
                    <input type="text" name="about_gallery_2" maxlength="500" value="{{ old('about_gallery_2', $settings['about_gallery_2'] ?? '') }}" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Gallery Image 3 URL</label>
                    <input type="text" name="about_gallery_3" maxlength="500" value="{{ old('about_gallery_3', $settings['about_gallery_3'] ?? '') }}" class="form-control">
                </div>
            </div>
            <hr>
            <p class="fw-semibold text-muted fs-13 mb-2">Craft Values Strip</p>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Value 1 Title</label>
                    <input type="text" name="craft_value_1_title" maxlength="100" value="{{ old('craft_value_1_title', $settings['craft_value_1_title'] ?? '') }}" class="form-control" placeholder="Handcrafted with care">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Value 1 Text</label>
                    <input type="text" name="craft_value_1_text" maxlength="200" value="{{ old('craft_value_1_text', $settings['craft_value_1_text'] ?? '') }}" class="form-control">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Value 2 Title</label>
                    <input type="text" name="craft_value_2_title" maxlength="100" value="{{ old('craft_value_2_title', $settings['craft_value_2_title'] ?? '') }}" class="form-control" placeholder="Dublin hallmarked">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Value 2 Text</label>
                    <input type="text" name="craft_value_2_text" maxlength="200" value="{{ old('craft_value_2_text', $settings['craft_value_2_text'] ?? '') }}" class="form-control">
                </div>
            </div>
            <div class="row g-3 mb-0">
                <div class="col-sm-6">
                    <label class="form-label">Value 3 Title</label>
                    <input type="text" name="craft_value_3_title" maxlength="100" value="{{ old('craft_value_3_title', $settings['craft_value_3_title'] ?? '') }}" class="form-control" placeholder="Gift-ready packaging">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Value 3 Text</label>
                    <input type="text" name="craft_value_3_text" maxlength="200" value="{{ old('craft_value_3_text', $settings['craft_value_3_text'] ?? '') }}" class="form-control">
                </div>
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Contact Page
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-0">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Store Latitude</label>
                    <input type="text" name="store_lat" maxlength="20"
                           value="{{ old('store_lat', $settings['store_lat'] ?? '') }}"
                           class="form-control @error('store_lat') is-invalid @enderror"
                           placeholder="53.3498">
                    @error('store_lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Store Longitude</label>
                    <input type="text" name="store_lng" maxlength="20"
                           value="{{ old('store_lng', $settings['store_lng'] ?? '') }}"
                           class="form-control @error('store_lng') is-invalid @enderror"
                           placeholder="-6.2603">
                    @error('store_lng')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-text">Used for the Google Maps embed on the Contact page.</div>
        </div>
    </div>

    {{-- Legal --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:document-bold-duotone" class="text-primary fs-18"></iconify-icon>
                Legal Pages
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Privacy Policy (HTML)</label>
                <textarea name="privacy_policy" rows="10"
                          class="form-control @error('privacy_policy') is-invalid @enderror"
                          placeholder="Your privacy policy content…">{{ old('privacy_policy', $settings['privacy_policy'] ?? '') }}</textarea>
                <div class="form-text">Rendered as raw HTML on the Privacy Policy page. Use &lt;h5&gt;/&lt;p&gt; tags for formatting.</div>
                @error('privacy_policy')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Terms &amp; Conditions (HTML)</label>
                <textarea name="terms_conditions" rows="10"
                          class="form-control @error('terms_conditions') is-invalid @enderror"
                          placeholder="Your terms and conditions content…">{{ old('terms_conditions', $settings['terms_conditions'] ?? '') }}</textarea>
                <div class="form-text">Rendered as raw HTML on the Terms &amp; Conditions page.</div>
                @error('terms_conditions')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">FAQ Sidebar Banner Image URL</label>
                <input type="text" name="faq_banner_image" maxlength="500"
                       value="{{ old('faq_banner_image', $settings['faq_banner_image'] ?? '') }}"
                       class="form-control @error('faq_banner_image') is-invalid @enderror"
                       placeholder="/images/ochaka/blog/side-banner.jpg">
                @error('faq_banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

</div>

{{-- ── Right column ─────────────────────────────────────────────────────────── --}}
<div class="col-lg-4">

    {{-- Save --}}
    <div class="card mb-4 border border-primary border-opacity-25">
        <div class="card-body">
            <button type="submit" class="btn btn-primary w-100">
                <iconify-icon icon="solar:diskette-broken" class="align-middle me-1"></iconify-icon>
                Save Settings
            </button>
        </div>
    </div>

    {{-- Maintenance mode --}}
    <div class="card mb-4 {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'border border-warning' : '' }}">
        <div class="card-header">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:danger-triangle-bold-duotone"
                              class="{{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'text-warning' : 'text-muted' }} fs-18"></iconify-icon>
                Maintenance Mode
            </h5>
        </div>
        <div class="card-body">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode"
                       value="1" {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="maintenance_mode">
                    Enable maintenance mode
                </label>
            </div>
            <p class="text-muted fs-12 mb-0">
                When enabled, the shop front end shows a maintenance page to visitors.
                Admin panel remains accessible.
            </p>
            @if(($settings['maintenance_mode'] ?? '0') === '1')
            <div class="alert alert-warning mt-3 mb-0 py-2 fs-13">
                <iconify-icon icon="solar:danger-triangle-broken" class="align-middle me-1"></iconify-icon>
                Site is currently in maintenance mode.
            </div>
            @endif
        </div>
    </div>

    {{-- Quick reference --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0 fs-13 text-muted">Current Values</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-borderless mb-0 fs-12">
                <tbody>
                    <tr>
                        <td class="text-muted ps-3 py-2">Store</td>
                        <td class="fw-medium pe-3 py-2">{{ $settings['store_name'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">Contact</td>
                        <td class="pe-3 py-2">{{ $settings['store_email'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">Orders</td>
                        <td class="pe-3 py-2">{{ $settings['orders_email'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">GA ID</td>
                        <td class="pe-3 py-2">{{ $settings['google_analytics_id'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">Maintenance</td>
                        <td class="pe-3 py-2">
                            @if(($settings['maintenance_mode'] ?? '0') === '1')
                                <span class="badge bg-warning-subtle text-warning">ON</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Off</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

</form>

@endsection
