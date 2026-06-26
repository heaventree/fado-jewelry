@extends('layouts.vertical', ['title' => 'FAQ Page'])

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
            <iconify-icon icon="solar:question-circle-bold-duotone" class="text-primary me-1"></iconify-icon>
            FAQ Page
        </h4>
        <p class="text-muted mb-0 fs-13">Edit the FAQ page sidebar banner image and SEO settings.</p>
    </div>
</div>

<form action="{{ route('admin.pages.faq.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">FAQ Sidebar</h5></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Sidebar Banner Image</label>
            <input type="file" name="faq_banner_image_file" class="form-control" accept="image/*">
            @if($settings['faq_banner_image'])
                <img src="{{ asset('storage/' . $settings['faq_banner_image']) }}" alt="FAQ Banner" class="mt-2 rounded" style="max-height:120px">
            @endif
        </div>
    </div>
</div>

@include('admin.pages._seo', ['seoPrefix' => 'faq', 'hasOgImage' => false])

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
