@php $f = $faq ?? null; @endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
    <input type="text" name="question" class="form-control" value="{{ old('question', $f?->question) }}" required maxlength="300">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
    <textarea name="answer" rows="5" class="form-control" required maxlength="5000">{{ old('answer', $f?->answer) }}</textarea>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $f?->sort_order ?? 0) }}" min="0">
    </div>
</div>
<div class="mb-3 form-check form-switch">
    <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
           {{ old('active', $f?->active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="active">Active</label>
</div>
