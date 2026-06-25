@extends('layouts.vertical', ['title' => 'Home Page Content'])

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
            <iconify-icon icon="solar:home-2-bold-duotone" class="text-primary me-1"></iconify-icon>
            Home Page Content
        </h4>
        <p class="text-muted mb-0 fs-13">Edit homepage sections, trust strip, and sale banner.</p>
    </div>
</div>

<form action="{{ route('admin.pages.home.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Section Subtitles</h5></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Subtitle 1</label>
            <input type="text" name="homepage_subtitle_1" class="form-control" value="{{ $settings['homepage_subtitle_1'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Subtitle 2</label>
            <input type="text" name="homepage_subtitle_2" class="form-control" value="{{ $settings['homepage_subtitle_2'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Subtitle 3</label>
            <input type="text" name="homepage_subtitle_3" class="form-control" value="{{ $settings['homepage_subtitle_3'] }}">
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Trust Strip</h5></div>
    <div class="card-body">
        @for($i = 1; $i <= 4; $i++)
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Label {{ $i }}</label>
                <input type="text" name="trust_label_{{ $i }}" class="form-control" value="{{ $settings['trust_label_' . $i] }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Sublabel {{ $i }}</label>
                <input type="text" name="trust_sub_{{ $i }}" class="form-control" value="{{ $settings['trust_sub_' . $i] }}">
            </div>
        </div>
        @endfor
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Sale Banner</h5></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Sale Banner Image</label>
            <input type="file" name="sale_banner_image_file" class="form-control" accept="image/*">
            @if($settings['sale_banner_image'])
                <img src="{{ asset('storage/' . $settings['sale_banner_image']) }}" alt="Sale Banner" class="mt-2 rounded" style="max-height:120px">
            @endif
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
