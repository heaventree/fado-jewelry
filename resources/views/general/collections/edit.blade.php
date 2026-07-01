@extends('layouts.vertical', ['title' => 'Edit: ' . $collection->name])

@section('content')

<form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="row g-3">

    {{-- ── LEFT COLUMN ──────────────────────────────────────────────────────── --}}
    <div class="col-xl-3 col-lg-4">

        {{-- Current banner --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Banner Image</h4></div>
            <div class="card-body text-center">
                @if($collection->banner_image)
                <div class="rounded overflow-hidden mb-2" style="height:160px">
                    <img id="banner-preview-img"
                         src="{{ Storage::url($collection->banner_image) }}"
                         alt="{{ $collection->name }}"
                         class="img-fluid w-100" style="height:160px;object-fit:cover">
                </div>
                <div class="form-check mb-2 text-start">
                    <input class="form-check-input" type="checkbox" name="remove_banner" value="1" id="remove_banner">
                    <label class="form-check-label text-danger fs-12" for="remove_banner">Remove banner image</label>
                </div>
                @else
                <div class="rounded bg-light-subtle d-flex align-items-center justify-content-center overflow-hidden mb-2"
                     style="height:160px">
                    <iconify-icon id="banner-placeholder" icon="solar:gallery-add-broken" class="fs-48 text-muted"></iconify-icon>
                    <img id="banner-preview-img" src="#" alt="" class="img-fluid d-none w-100"
                         style="height:160px;object-fit:cover">
                </div>
                @endif
                <p class="text-muted fs-12 mb-0">Recommended: 1600 × 500 px</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="card mb-3">
            <div class="card-body text-center py-3">
                <h3 class="mb-0 fw-semibold">{{ $collection->products_count }}</h3>
                <p class="text-muted mb-0 fs-13">products in this collection</p>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────────────────────────────────── --}}
    <div class="col-xl-9 col-lg-8">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Core fields --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Collection Information</h4></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="name">Collection Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $collection->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="slug">URL Slug</label>
                        <input type="text" id="slug" name="slug"
                               class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $collection->slug) }}">
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner upload --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $collection->banner_image ? 'Replace' : 'Upload' }} Banner Image</h4>
            </div>
            <div class="card-body">
                <input type="file" id="banner_image" name="banner_image"
                       class="form-control @error('banner_image') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text">
                    Full-width hero for the collection page. PNG or JPG, max 8 MB.
                    Recommended: <strong>1600 × 500 px</strong>.
                </div>
                @error('banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @include('general.partials.image-quality-select')
            </div>
        </div>

        {{-- Banner overlay text --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Banner Overlay Text</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="banner_title">Headline</label>
                        <input type="text" id="banner_title" name="banner_title"
                               class="form-control @error('banner_title') is-invalid @enderror"
                               value="{{ old('banner_title', $collection->banner_title) }}"
                               placeholder="e.g. The Claddagh Collection — Love, Loyalty &amp; Friendship">
                        @error('banner_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="banner_description">Subtitle / Description</label>
                        <textarea id="banner_description" name="banner_description" rows="3"
                                  class="form-control @error('banner_description') is-invalid @enderror"
                                  placeholder="A short evocative description of the collection and its story.">{{ old('banner_description', $collection->banner_description) }}</textarea>
                        @error('banner_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Live banner layout preview --}}
                @if($collection->banner_image || $collection->banner_title)
                <div class="mt-3 rounded overflow-hidden position-relative"
                     style="min-height:120px;background:#044705;display:flex;align-items:center;">
                    @if($collection->banner_image)
                    <img src="{{ Storage::url($collection->banner_image) }}" alt=""
                         class="position-absolute w-100 h-100"
                         style="object-fit:cover;opacity:.35;top:0;left:0">
                    @endif
                    <div class="position-relative p-4 text-white" style="max-width:60%">
                        <p class="mb-1 fs-11 text-uppercase opacity-75 fw-semibold letter-spacing-1">Collection</p>
                        <h4 class="text-white mb-1">{{ $collection->banner_title ?? $collection->name }}</h4>
                        @if($collection->banner_description)
                        <p class="mb-0 fs-13 opacity-75">{{ $collection->banner_description }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-text mt-1">Preview of the collection page banner. Save to refresh.</div>
                @endif
            </div>
        </div>

    </div>
</div>
</form>

{{-- Delete form — MUST be outside the main edit form to avoid nested form bug --}}
<div class="row g-3">
    <div class="col-xl-3 col-lg-4"></div>
    <div class="col-xl-9 col-lg-8">
        <div class="card mb-4 border-danger-subtle">
            <div class="card-header bg-danger-subtle">
                <h4 class="card-title mb-0 text-danger">Danger Zone</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <p class="mb-0 fw-medium">Delete this collection</p>
                        <p class="text-muted fs-12 mb-0">Products are detached from the collection but not deleted.</p>
                    </div>
                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST"
                          onsubmit="return confirm('Permanently delete &quot;{{ addslashes($collection->name) }}&quot;?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm text-nowrap">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle me-1"></iconify-icon>
                            Delete Collection
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
<script>
(function () {
    document.getElementById('banner_image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('banner-preview-img');
            const placeholder = document.getElementById('banner-placeholder');
            img.src = e.target.result;
            img.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
}());
</script>
@endsection
