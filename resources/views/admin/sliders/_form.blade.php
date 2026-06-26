@php $s = $slider ?? null; @endphp

<div class="row g-3 mb-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Heading <span class="text-danger">*</span></label>
        <input type="text" name="heading" class="form-control" value="{{ old('heading', $s?->heading) }}" required maxlength="200">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $s?->sort_order ?? 0) }}" min="0">
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Subheading</label>
    <input type="text" name="subheading" class="form-control" value="{{ old('subheading', $s?->subheading) }}" maxlength="200">
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Button Text</label>
        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $s?->button_text) }}" maxlength="50" placeholder="Shop Now">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Button URL</label>
        <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $s?->button_url) }}" maxlength="500" placeholder="/shop">
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Image{{ $s ? '' : ' *' }}</label>
    @if($s?->image)
        <div class="mb-2">
            <img src="{{ Storage::url($s->image) }}" alt="" class="rounded" style="max-height:120px">
            <x-file-size :path="$s->image" />
        </div>
    @endif
    <input type="file" name="image" class="form-control" accept="image/*" {{ $s ? '' : 'required' }}>
    <div class="form-text">Recommended: 1920×800px. Max 5MB.</div>
</div>
<div class="mb-3 form-check form-switch">
    <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
           {{ old('active', $s?->active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="active">Active</label>
</div>
