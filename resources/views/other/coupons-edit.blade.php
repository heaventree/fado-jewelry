@extends('layouts.vertical', ['title' => 'Edit Coupon'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css'])
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Edit Coupon</h4>
        <p class="text-muted mb-0 fs-13">
            <code>{{ $coupon->code }}</code> &nbsp;·&nbsp;
            Used {{ $coupon->times_used }} {{ Str::plural('time', $coupon->times_used) }}
        </p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">
        <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
        All Coupons
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
<div class="card-body p-4">

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
@csrf
@method('PATCH')

{{-- Code --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
    <input type="text" name="code" value="{{ strtoupper(old('code', $coupon->code)) }}"
           class="form-control @error('code') is-invalid @enderror"
           style="text-transform:uppercase" maxlength="64" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

{{-- Type + Value --}}
<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
        <select name="type" id="coupon-type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
            <option value="fixed"   {{ old('type', $coupon->type) === 'fixed'   ? 'selected' : '' }}>Fixed Amount (€)</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text" id="value-prefix">{{ $coupon->type === 'percent' ? '%' : '€' }}</span>
            <input type="number" name="value" value="{{ old('value', $coupon->value) }}"
                   class="form-control @error('value') is-invalid @enderror"
                   min="0.01" step="0.01" required>
            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- Minimum order --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Minimum Order Amount</label>
    <div class="input-group" style="max-width:240px">
        <span class="input-group-text">€</span>
        <input type="number" name="minimum_order" value="{{ old('minimum_order', $coupon->minimum_order) }}"
               class="form-control @error('minimum_order') is-invalid @enderror"
               min="0" step="0.01" placeholder="No minimum">
        @error('minimum_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Usage limit + expiry --}}
<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Usage Limit</label>
        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
               class="form-control @error('usage_limit') is-invalid @enderror"
               min="1" step="1" placeholder="Unlimited">
        @if($coupon->times_used > 0)
            <div class="form-text text-warning">
                <iconify-icon icon="solar:info-circle-broken" class="align-middle me-1"></iconify-icon>
                Already used {{ $coupon->times_used }} {{ Str::plural('time', $coupon->times_used) }}.
                Limit must be ≥ {{ $coupon->times_used }}.
            </div>
        @endif
        @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Expiry Date</label>
        <input type="text" name="expires_at"
               value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}"
               class="form-control flatpickr-date @error('expires_at') is-invalid @enderror"
               placeholder="No expiry" autocomplete="off">
        @if($coupon->is_expired)
            <div class="form-text text-danger">This coupon has expired.</div>
        @endif
        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Active toggle --}}
<div class="mb-4">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
               {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="is_active">Active</label>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <iconify-icon icon="solar:diskette-broken" class="align-middle me-1"></iconify-icon>
        Save Changes
    </button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

</form>
</div>
</div>
</div>
</div>

@endsection

@section('script')
@vite(['node_modules/flatpickr/dist/flatpickr.min.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.flatpickr-date', {
        dateFormat: 'Y-m-d',
        allowInput: true,
    });

    const typeSelect  = document.getElementById('coupon-type');
    const valuePrefix = document.getElementById('value-prefix');

    function updatePrefix() {
        valuePrefix.textContent = typeSelect.value === 'percent' ? '%' : '€';
    }
    typeSelect.addEventListener('change', updatePrefix);
});
</script>
@endsection
