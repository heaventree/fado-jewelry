@extends('layouts.vertical', ['title' => 'About Us Page'])

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
            <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary me-1"></iconify-icon>
            About Us Page
        </h4>
        <p class="text-muted mb-0 fs-13">Edit the About Us page hero, story, gallery, and craft values.</p>
    </div>
</div>

<form action="{{ route('admin.pages.about.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Hero Section</h5></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Hero Image</label>
            <input type="file" name="about_hero_image_file" class="form-control" accept="image/*">
            @if($settings['about_hero_image'])
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $settings['about_hero_image']) }}" alt="Hero" class="rounded" style="max-height:120px">
                    <x-file-size :path="$settings['about_hero_image']" />
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Page Heading</label>
            <input type="text" name="about_heading" class="form-control" value="{{ $settings['about_heading'] }}">
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Brand Story</h5></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Story Text</label>
            <textarea name="about_story" class="form-control" rows="8">{{ $settings['about_story'] }}</textarea>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Gallery Images</h5></div>
    <div class="card-body">
        <div class="row g-3">
            @for($g = 1; $g <= 3; $g++)
            <div class="col-md-4">
                <label class="form-label fw-semibold">Gallery Image {{ $g }}</label>
                <input type="file" name="about_gallery_{{ $g }}_file" class="form-control" accept="image/*">
                @if($settings['about_gallery_' . $g])
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $settings['about_gallery_' . $g]) }}" alt="Gallery {{ $g }}" class="rounded" style="max-height:120px">
                        <x-file-size :path="$settings['about_gallery_' . $g]" />
                    </div>
                @endif
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Craft Values</h5></div>
    <div class="card-body">
        @for($i = 1; $i <= 3; $i++)
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Value {{ $i }} Title</label>
                <input type="text" name="craft_value_{{ $i }}_title" class="form-control" value="{{ $settings['craft_value_' . $i . '_title'] }}">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Value {{ $i }} Text</label>
                <input type="text" name="craft_value_{{ $i }}_text" class="form-control" value="{{ $settings['craft_value_' . $i . '_text'] }}">
            </div>
        </div>
        @endfor
    </div>
</div>

@include('admin.pages._seo', ['seoPrefix' => 'about', 'hasOgImage' => true])

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
