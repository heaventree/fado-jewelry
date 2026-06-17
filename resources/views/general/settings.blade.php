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
                    <input type="email" name="contact_email"
                           value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                           class="form-control @error('contact_email') is-invalid @enderror"
                           placeholder="info@fadojewellery.ie" required>
                    <div class="form-text">Shown on the contact page.</div>
                    @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="contact_phone"
                           value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                           class="form-control @error('contact_phone') is-invalid @enderror"
                           placeholder="+353 ...">
                    @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                <textarea name="contact_address" rows="3"
                          class="form-control @error('contact_address') is-invalid @enderror"
                          placeholder="Street, City, County, Eircode">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                <input type="text" name="google_analytics"
                       value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                       class="form-control @error('google_analytics') is-invalid @enderror"
                       placeholder="G-XXXXXXXXXX" style="max-width:240px">
                <div class="form-text">GA4 measurement ID (starts with G-) or Universal Analytics ID (UA-).</div>
                @error('google_analytics')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Social --}}
    <div class="card">
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
                        <td class="pe-3 py-2">{{ $settings['contact_email'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">Orders</td>
                        <td class="pe-3 py-2">{{ $settings['orders_email'] ?? '—' }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted ps-3 py-2">GA ID</td>
                        <td class="pe-3 py-2">{{ $settings['google_analytics'] ?: '—' }}</td>
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
