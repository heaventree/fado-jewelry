@extends('layouts.vertical', ['title' => 'Add Product'])

@section('content')

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- ── LEFT COLUMN: status / categories / collections ──────────────────── --}}
    <div class="col-xl-3 col-lg-4">

        {{-- Status --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Status</h4></div>
            <div class="card-body">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Active (visible on site)</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_bestseller" name="is_bestseller" value="1"
                           {{ old('is_bestseller') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_bestseller">Mark as Bestseller</label>
                </div>
            </div>
        </div>

        {{-- Categories --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Categories</h4></div>
            <div class="card-body" style="max-height:300px;overflow-y:auto">
                @foreach($categories as $parent)
                    @if($parent->children->isNotEmpty())
                        <p class="text-uppercase fw-semibold fs-11 text-muted mb-1 mt-2">{{ $parent->name }}</p>
                        @foreach($parent->children as $child)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="category_ids[]" value="{{ $child->id }}"
                                       id="cat-{{ $child->id }}">
                                <label class="form-check-label" for="cat-{{ $child->id }}">{{ $child->name }}</label>
                            </div>
                        @endforeach
                    @else
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="category_ids[]" value="{{ $parent->id }}"
                                   id="cat-{{ $parent->id }}">
                            <label class="form-check-label" for="cat-{{ $parent->id }}">{{ $parent->name }}</label>
                        </div>
                    @endif
                @endforeach
                @if($categories->isEmpty())
                    <p class="text-muted fs-13 mb-0">No categories yet.</p>
                @endif
            </div>
        </div>

        {{-- Collections --}}
        @if($collections->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Collections</h4></div>
            <div class="card-body" style="max-height:200px;overflow-y:auto">
                @foreach($collections as $col)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="collection_ids[]" value="{{ $col->id }}"
                               id="col-{{ $col->id }}">
                        <label class="form-check-label" for="col-{{ $col->id }}">{{ $col->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Submit (sticky shortcut) --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>

    </div>

    {{-- ── RIGHT COLUMN: main form ──────────────────────────────────────────── --}}
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

        {{-- Product info --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Product Information</h4></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Claddagh Ring" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="slug">URL Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug') }}" placeholder="auto-generated">
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Leave blank to auto-generate from name.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="short_description">Short Description</label>
                        <input type="text" id="short_description" name="short_description"
                               class="form-control @error('short_description') is-invalid @enderror"
                               value="{{ old('short_description') }}"
                               placeholder="One-line summary shown in listings (max 500 chars)" maxlength="500">
                        @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Full Description</label>
                        <textarea id="description" name="description" rows="8"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Full product description — HTML supported">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Product Images</h4></div>
            <div class="card-body">
                <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                <div class="form-text">First image selected becomes the primary image. PNG, JPG, WebP — max 5 MB each.</div>
                @error('images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Variants --}}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Metal &amp; Gemstone Variants</h4>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariantRow()">
                    <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>Add Variant
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th style="min-width:160px">Metal <span class="text-danger">*</span></th>
                                <th style="min-width:150px">Gemstone</th>
                                <th style="min-width:150px">Second Metal</th>
                                <th style="min-width:120px">SKU</th>
                                <th style="min-width:110px">Price (€) <span class="text-danger">*</span></th>
                                <th style="min-width:110px">Sale Price (€)</th>
                                <th style="min-width:110px">Colour</th>
                                <th style="min-width:90px">Stock</th>
                                <th style="width:60px" class="text-center">Active</th>
                                <th style="width:48px"></th>
                            </tr>
                        </thead>
                        <tbody id="variants-body">
                            {{-- Existing rows re-populated on validation failure --}}
                            @foreach(old('variants', []) as $i => $v)
                            <tr>
                                <td>
                                    <select name="variants[{{ $i }}][metal_id]" class="form-select form-select-sm" required>
                                        <option value="">— Metal —</option>
                                        @foreach($metals as $metal)
                                            <option value="{{ $metal->id }}" {{ (string)($v['metal_id'] ?? '') === (string)$metal->id ? 'selected' : '' }}>
                                                {{ $metal->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="variants[{{ $i }}][gemstone_id]" class="form-select form-select-sm">
                                        <option value="">None</option>
                                        @foreach($gemstones as $gem)
                                            <option value="{{ $gem->id }}" {{ (string)($v['gemstone_id'] ?? '') === (string)$gem->id ? 'selected' : '' }}>
                                                {{ $gem->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="variants[{{ $i }}][second_metal_id]" class="form-select form-select-sm">
                                        <option value="">None</option>
                                        @foreach($metals as $metal)
                                            <option value="{{ $metal->id }}" {{ (string)($v['second_metal_id'] ?? '') === (string)$metal->id ? 'selected' : '' }}>
                                                {{ $metal->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="variants[{{ $i }}][sku]" class="form-control form-control-sm" value="{{ $v['sku'] ?? '' }}" placeholder="SKU"></td>
                                <td><input type="number" name="variants[{{ $i }}][price_eur]" class="form-control form-control-sm" value="{{ $v['price_eur'] ?? '' }}" step="0.01" min="0" required></td>
                                <td><input type="number" name="variants[{{ $i }}][sale_price_eur]" class="form-control form-control-sm" value="{{ $v['sale_price_eur'] ?? '' }}" step="0.01" min="0" placeholder="0.00"></td>
                                <td><input type="text" name="variants[{{ $i }}][colour]" class="form-control form-control-sm" value="{{ $v['colour'] ?? '' }}" placeholder="e.g. Rose"></td>
                                <td><input type="number" name="variants[{{ $i }}][stock]" class="form-control form-control-sm" value="{{ $v['stock'] ?? 0 }}" min="0"></td>
                                <td class="text-center"><input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" class="form-check-input" {{ !empty($v['is_active']) ? 'checked' : '' }}></td>
                                <td><button type="button" class="btn btn-link text-danger p-0" onclick="removeVariantRow(this)" title="Remove"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="variants-empty" class="text-center text-muted py-3 fs-13 {{ count(old('variants', [])) > 0 ? 'd-none' : '' }}">
                    No variants yet — click <strong>Add Variant</strong> to add metal/gemstone combinations.
                </div>
            </div>
        </div>

        {{-- Ring sizes --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Ring Sizes (US) <small class="text-muted fw-normal fs-13">— only fill in if this is a ring</small></h4>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($ringSizes as $size)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="border rounded p-2 text-center">
                            <div class="form-check d-flex justify-content-center mb-1">
                                <input class="form-check-input size-check" type="checkbox"
                                       name="sizes[{{ $size }}][enabled]" value="1"
                                       id="size-{{ $size }}"
                                       data-target="size-stock-{{ $size }}"
                                       {{ old("sizes.$size.enabled") ? 'checked' : '' }}>
                                <label class="form-check-label ms-1 fw-semibold" for="size-{{ $size }}">
                                    {{ number_format($size, $size == (int)$size ? 0 : 1) }}
                                </label>
                            </div>
                            <input type="number" id="size-stock-{{ $size }}"
                                   name="sizes[{{ $size }}][stock]"
                                   class="form-control form-control-sm text-center"
                                   value="{{ old("sizes.$size.stock", 0) }}"
                                   min="0" placeholder="Qty"
                                   {{ old("sizes.$size.enabled") ? '' : 'disabled' }}>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Save Product</button>
        </div>

    </div>
</div>
</form>

{{-- Variant row template — Blade runs server-side so metals/gemstones are populated --}}
<template id="variant-row-template">
    <tr>
        <td>
            <select name="variants[__INDEX__][metal_id]" class="form-select form-select-sm" required>
                <option value="">— Metal —</option>
                @foreach($metals as $metal)
                <option value="{{ $metal->id }}">{{ $metal->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="variants[__INDEX__][gemstone_id]" class="form-select form-select-sm">
                <option value="">None</option>
                @foreach($gemstones as $gem)
                <option value="{{ $gem->id }}">{{ $gem->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="variants[__INDEX__][second_metal_id]" class="form-select form-select-sm">
                <option value="">None</option>
                @foreach($metals as $metal)
                <option value="{{ $metal->id }}">{{ $metal->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="variants[__INDEX__][sku]" class="form-control form-control-sm" placeholder="SKU"></td>
        <td><input type="number" name="variants[__INDEX__][price_eur]" class="form-control form-control-sm" step="0.01" min="0" required placeholder="0.00"></td>
        <td><input type="number" name="variants[__INDEX__][sale_price_eur]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
        <td><input type="text" name="variants[__INDEX__][colour]" class="form-control form-control-sm" placeholder="e.g. Rose"></td>
        <td><input type="number" name="variants[__INDEX__][stock]" class="form-control form-control-sm" min="0" value="0"></td>
        <td class="text-center"><input type="checkbox" name="variants[__INDEX__][is_active]" value="1" class="form-check-input" checked></td>
        <td><button type="button" class="btn btn-link text-danger p-0" onclick="removeVariantRow(this)" title="Remove"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon></button></td>
    </tr>
</template>

@endsection

@section('script-bottom')
<script>
(function () {
    // ── Slug auto-generation ──────────────────────────────────────────────────
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

    // ── Variant rows ─────────────────────────────────────────────────────────
    let variantIndex = document.querySelectorAll('#variants-body tr').length;

    window.addVariantRow = function () {
        const template = document.getElementById('variant-row-template');
        const html = template.innerHTML.replace(/__INDEX__/g, variantIndex++);
        const tbody = document.getElementById('variants-body');
        tbody.insertAdjacentHTML('beforeend', html);
        document.getElementById('variants-empty').classList.add('d-none');
    };

    window.removeVariantRow = function (btn) {
        btn.closest('tr').remove();
        if (document.querySelectorAll('#variants-body tr').length === 0) {
            document.getElementById('variants-empty').classList.remove('d-none');
        }
    };

    // ── Ring size checkboxes enable/disable stock input ───────────────────────
    document.querySelectorAll('.size-check').forEach(function (cb) {
        const stockInput = document.getElementById('size-stock-' + cb.id.replace('size-', ''));
        cb.addEventListener('change', function () {
            stockInput.disabled = !this.checked;
            if (this.checked) stockInput.focus();
        });
    });
}());
</script>
@endsection
