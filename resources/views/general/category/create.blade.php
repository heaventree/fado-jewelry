@extends('layouts.vertical', ['title' => 'Add Category'])

@section('content')

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- ── LEFT COLUMN ──────────────────────────────────────────────────────── --}}
    <div class="col-xl-3 col-lg-4">

        {{-- Banner preview --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Banner Preview</h4></div>
            <div class="card-body text-center">
                <div id="banner-preview-wrap" class="rounded bg-light-subtle d-flex align-items-center justify-content-center overflow-hidden mb-2"
                     style="height:160px">
                    <iconify-icon id="banner-placeholder" icon="solar:gallery-add-broken" class="fs-48 text-muted"></iconify-icon>
                    <img id="banner-preview-img" src="#" alt="" class="img-fluid d-none" style="max-height:160px;object-fit:cover;width:100%">
                </div>
                <p class="text-muted fs-12 mb-0">Recommended: 1600 × 500 px (wide landscape)</p>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────────────────────────────────── --}}
    <div class="col-xl-9 col-lg-8">

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
            <div class="card-header"><h4 class="card-title mb-0">Category Information</h4></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Category Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Rings" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="slug">URL Slug</label>
                        <input type="text" id="slug" name="slug"
                               class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug') }}" placeholder="auto-generated">
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', 0) }}" min="0">
                        <div class="form-text">Lower numbers appear first.</div>
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="parent_id">Parent Category</label>
                        <select id="parent_id" name="parent_id"
                                class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">— Top level (no parent) —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Leave blank for top-level navigation items (Jewellery, Collections, etc.).</div>
                        @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner image --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Banner Image</h4></div>
            <div class="card-body">
                <input type="file" id="banner_image" name="banner_image"
                       class="form-control @error('banner_image') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text">Used as the full-width hero banner on the category page. PNG or JPG, max 8 MB. Recommended: 1600 × 500 px.</div>
                @error('banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Thumbnail image --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Homepage Thumbnail</h4></div>
            <div class="card-body">
                <input type="file" id="thumbnail_image" name="thumbnail_image"
                       class="form-control @error('thumbnail_image') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text">Shown in the "Shop By Categories" tile on the homepage — separate from the banner image above. If left blank, the banner image is used as a fallback. PNG or JPG, max 8 MB. Recommended: 800 × 800 px (square).</div>
                @error('thumbnail_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Banner overlay text — Sheila Fleet style --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Banner Overlay Text
                    <small class="text-muted fw-normal fs-13">— displayed over the banner image on the category page</small>
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="banner_title">Headline</label>
                        <input type="text" id="banner_title" name="banner_title"
                               class="form-control @error('banner_title') is-invalid @enderror"
                               value="{{ old('banner_title') }}"
                               placeholder="e.g. Fine Irish Rings — Crafted to Last a Lifetime">
                        @error('banner_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="banner_description">Subtitle / Description</label>
                        <textarea id="banner_description" name="banner_description" rows="3"
                                  class="form-control @error('banner_description') is-invalid @enderror"
                                  placeholder="A sentence or two that sets the tone for this category.">{{ old('banner_description') }}</textarea>
                        @error('banner_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="alert alert-light border mt-3 mb-0 fs-12">
                    <iconify-icon icon="solar:info-circle-broken" class="align-middle me-1 text-primary"></iconify-icon>
                    The banner uses a Sheila Fleet-style layout: headline and description on the left, product imagery on the right, with a textured or evocative background.
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Save Category</button>
        </div>

    </div>
</div>
</form>

@endsection

@section('script-bottom')
<script>
(function () {
    // Slug auto-generation
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let slugEdited = slugInput.value !== '';

    nameInput.addEventListener('input', function () {
        if (slugEdited) return;
        slugInput.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
    });

    slugInput.addEventListener('input', function () {
        slugEdited = this.value !== '';
    });

    // Banner image preview
    document.getElementById('banner_image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('banner-preview-img');
            const placeholder = document.getElementById('banner-placeholder');
            img.src = e.target.result;
            img.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
}());
</script>
@endsection
