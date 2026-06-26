@extends('layouts.vertical', ['title' => 'Contact Page'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <iconify-icon icon="solar:check-circle-broken" class="align-middle me-1"></iconify-icon>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-semibold mb-1">
            <iconify-icon icon="solar:mailbox-bold-duotone" class="text-primary me-1"></iconify-icon>
            Contact Page
        </h4>
        <p class="text-muted mb-0 fs-13">Edit the Contact page map coordinates and SEO settings.</p>
    </div>
</div>

<form action="{{ route('admin.pages.contact.update') }}" method="POST">
@csrf

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Map Location</h5></div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Store Latitude</label>
                <input type="text" name="store_lat" class="form-control" value="{{ $settings['store_lat'] }}" placeholder="53.3498">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Store Longitude</label>
                <input type="text" name="store_lng" class="form-control" value="{{ $settings['store_lng'] }}" placeholder="-6.2603">
            </div>
        </div>
        <p class="text-muted fs-13 mb-0">Find your coordinates at maps.google.com — right click on your location and copy the coordinates.</p>
    </div>
</div>

@include('admin.pages._seo', ['seoPrefix' => 'contact', 'hasOgImage' => false])

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
