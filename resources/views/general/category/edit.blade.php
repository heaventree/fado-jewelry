@extends('layouts.vertical', ['title' => 'Edit: ' . $category->name])

@section('content')

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="row g-3">

    {{-- ── LEFT COLUMN ──────────────────────────────────────────────────────── --}}
    <div class="col-xl-3 col-lg-4">

        {{-- Current / preview banner --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Banner Image</h4></div>
            <div class="card-body text-center">
                @if($category->banner_image)
                <div id="banner-preview-wrap" class="rounded overflow-hidden mb-2" style="height:160px">
                    <img id="banner-preview-img"
                         src="{{ Storage::url($category->banner_image) }}"
                         alt="{{ $category->name }}"
                         class="img-fluid w-100" style="height:160px;object-fit:cover">
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remove_banner" value="1" id="remove_banner">
                    <label class="form-check-label text-danger fs-12" for="remove_banner">Remove banner image</label>
                </div>
                @else
                <div id="banner-preview-wrap" class="rounded bg-light-subtle d-flex align-items-center justify-content-center overflow-hidden mb-2"
                     style="height:160px">
                    <iconify-icon id="banner-placeholder" icon="solar:gallery-add-broken" class="fs-48 text-muted"></iconify-icon>
                    <img id="banner-preview-img" src="#" alt="" class="img-fluid d-none w-100" style="height:160px;object-fit:cover">
                </div>
                @endif
                <p class="text-muted fs-12 mb-0">Recommended: 1600 × 500 px</p>
            </div>
        </div>

        {{-- Product count info --}}
        <div class="card mb-3">
            <div class="card-body text-center py-3">
                <h3 class="mb-0 fw-semibold">{{ $category->products()->count() }}</h3>
                <p class="text-muted mb-0 fs-13">products in this category</p>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Back to List</a>
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
            <div class="card-header"><h4 class="card-title mb-0">Category Information</h4></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Category Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="slug">URL Slug</label>
                        <input type="text" id="slug" name="slug"
                               class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $category->slug) }}">
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="parent_id">Parent Category</label>
                        <select id="parent_id" name="parent_id"
                                class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">— Top level (no parent) —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Top-level categories appear directly in the main navigation.</div>
                        @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner image upload --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">{{ $category->banner_image ? 'Replace' : 'Upload' }} Banner Image</h4></div>
            <div class="card-body">
                <input type="file" id="banner_image" name="banner_image"
                       class="form-control @error('banner_image') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text">Full-width hero banner for the category page. PNG or JPG, max 8 MB. Recommended: 1600 × 500 px.</div>
                @error('banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                               value="{{ old('banner_title', $category->banner_title) }}"
                               placeholder="e.g. Fine Irish Rings — Crafted to Last a Lifetime">
                        @error('banner_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="banner_description">Subtitle / Description</label>
                        <textarea id="banner_description" name="banner_description" rows="3"
                                  class="form-control @error('banner_description') is-invalid @enderror"
                                  placeholder="A sentence or two that sets the tone for this category.">{{ old('banner_description', $category->banner_description) }}</textarea>
                        @error('banner_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Live preview of the banner layout --}}
                @if($category->banner_image || $category->banner_title)
                <div class="mt-3 rounded overflow-hidden position-relative"
                     style="min-height:120px;background:#044705;display:flex;align-items:center;">
                    @if($category->banner_image)
                    <img src="{{ Storage::url($category->banner_image) }}" alt=""
                         class="position-absolute w-100 h-100"
                         style="object-fit:cover;opacity:.35;top:0;left:0">
                    @endif
                    <div class="position-relative p-4 text-white" style="max-width:55%">
                        <h4 class="text-white mb-1">{{ $category->banner_title ?? $category->name }}</h4>
                        @if($category->banner_description)
                        <p class="mb-0 fs-13 opacity-75">{{ $category->banner_description }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-text mt-1">Preview of how the banner will look on the front-end category page.</div>
                @endif
            </div>
        </div>

    </div>
</div>
</form>

{{-- Delete form — outside main edit form to avoid nested form bug --}}
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
                        <p class="mb-0 fw-medium">Delete this category</p>
                        <p class="text-muted fs-12 mb-0">Only possible if there are no sub-categories. Products will be detached but not deleted.</p>
                    </div>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                          onsubmit="return confirm('Permanently delete \"{{ addslashes($category->name) }}\"?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm text-nowrap">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle me-1"></iconify-icon>
                            Delete Category
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
    // Banner image live preview on file select
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
