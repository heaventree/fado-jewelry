@php $t = $testimonial ?? null; @endphp

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $t?->name) }}" required maxlength="100">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Location</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $t?->location) }}" maxlength="100" placeholder="Dublin, Ireland">
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Review Text <span class="text-danger">*</span></label>
    <textarea name="body" rows="4" class="form-control" required maxlength="1000">{{ old('body', $t?->body) }}</textarea>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
        <select name="rating" class="form-select" required>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ (int) old('rating', $t?->rating ?? 5) === $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
            @endfor
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Product Name</label>
        <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $t?->product_name) }}" maxlength="200" placeholder="Claddagh Ring">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $t?->sort_order ?? 0) }}" min="0">
    </div>
</div>
<div class="mb-3 form-check form-switch">
    <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
           {{ old('active', $t?->active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="active">Active</label>
</div>
