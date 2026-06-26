@extends('layouts.vertical', ['title' => 'Sitemap'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-semibold mb-1">
            <iconify-icon icon="solar:sitemap-bold-duotone" class="text-primary me-1"></iconify-icon>
            XML Sitemap
        </h4>
        <p class="text-muted mb-0 fs-13">Your sitemap is generated dynamically and always up to date.</p>
    </div>
    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-primary">
        <iconify-icon icon="solar:eye-bold-duotone" class="me-1"></iconify-icon>
        View Sitemap
    </a>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Sitemap URL</h5></div>
    <div class="card-body">
        <div class="input-group mb-3">
            <input type="text" class="form-control" value="{{ url('/sitemap.xml') }}" readonly>
            <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ url('/sitemap.xml') }}')">
                <iconify-icon icon="solar:copy-bold-duotone" class="me-1"></iconify-icon> Copy
            </button>
        </div>
        <div class="alert alert-info mb-0 fs-13">
            <iconify-icon icon="solar:info-circle-broken" class="align-middle me-1"></iconify-icon>
            Submit this URL to <strong>Google Search Console</strong> and <strong>Bing Webmaster Tools</strong> so search engines can discover all your pages.
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">URLs Included</h5></div>
    <div class="card-body">
        <div class="row g-3 text-center">
            <div class="col-sm-3">
                <h3 class="fw-semibold mb-1">{{ $counts['products'] }}</h3>
                <p class="text-muted mb-0 fs-13">Products</p>
            </div>
            <div class="col-sm-3">
                <h3 class="fw-semibold mb-1">{{ $counts['categories'] }}</h3>
                <p class="text-muted mb-0 fs-13">Categories</p>
            </div>
            <div class="col-sm-3">
                <h3 class="fw-semibold mb-1">{{ $counts['collections'] }}</h3>
                <p class="text-muted mb-0 fs-13">Collections</p>
            </div>
            <div class="col-sm-3">
                <h3 class="fw-semibold mb-1">{{ $counts['posts'] }}</h3>
                <p class="text-muted mb-0 fs-13">Blog Posts</p>
            </div>
        </div>
        <hr>
        <p class="text-center mb-0">
            <strong>{{ $totalUrls }}</strong> total URLs in sitemap
            <span class="text-muted">(includes {{ 7 }} static pages)</span>
        </p>
    </div>
</div>

<a href="{{ route('admin.pages.sitemap') }}" class="btn btn-outline-secondary">
    <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
    Refresh Counts
</a>

@endsection
