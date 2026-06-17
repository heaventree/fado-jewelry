@extends('layouts.vertical', ['title' => 'Coupons'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Header ───────────────────────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Coupons</h4>
        <p class="text-muted mb-0 fs-13">Manage discount codes for the shop.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add Coupon
    </a>
</div>

{{-- ── Stat cards ───────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Total Coupons</p>
                        <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:leaf-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Active</p>
                        <h2 class="fw-bold mb-0 text-success">{{ $stats['active'] }}</h2>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Expired</p>
                        <h2 class="fw-bold mb-0 {{ $stats['expired'] > 0 ? 'text-danger' : '' }}">{{ $stats['expired'] }}</h2>
                    </div>
                    <div class="avatar-md bg-danger bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-danger avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Used Up</p>
                        <h2 class="fw-bold mb-0 {{ $stats['used_up'] > 0 ? 'text-warning' : '' }}">{{ $stats['used_up'] }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">reached usage limit</p>
                    </div>
                    <div class="avatar-md bg-warning bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:close-circle-bold-duotone" class="fs-32 text-warning avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Table ─────────────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">All Coupons</h4>

        <form class="d-flex gap-2 flex-wrap align-items-center" method="GET"
              action="{{ route('admin.coupons.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search code…" style="width:180px">
            <select name="status" class="form-select form-select-sm" style="width:140px"
                    onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary">Search</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min. Order</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th class="text-center" style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <code class="fs-13 fw-semibold">{{ $coupon->code }}</code>
                    </td>
                    <td>
                        <span class="badge {{ $coupon->type === 'percent' ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success' }}">
                            {{ $coupon->discount_label }}
                            {{ $coupon->type === 'percent' ? 'off' : 'off (fixed)' }}
                        </span>
                    </td>
                    <td>
                        @if($coupon->minimum_order)
                            €{{ number_format((float) $coupon->minimum_order, 2) }}
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </td>
                    <td>
                        {{ $coupon->times_used }}
                        @if($coupon->usage_limit)
                            / {{ $coupon->usage_limit }}
                            @if($coupon->is_used_up)
                                <span class="badge bg-warning-subtle text-warning ms-1">Used up</span>
                            @endif
                        @else
                            <span class="text-muted fs-12">/ unlimited</span>
                        @endif
                    </td>
                    <td>
                        @if($coupon->expires_at)
                            <span class="{{ $coupon->is_expired ? 'text-danger' : 'text-dark' }}">
                                {{ $coupon->expires_at->format('d M Y') }}
                            </span>
                            @if($coupon->is_expired)
                                <span class="badge bg-danger-subtle text-danger ms-1">Expired</span>
                            @endif
                        @else
                            <span class="text-muted">No expiry</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="badge border-0 {{ $coupon->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                                    title="Click to {{ $coupon->is_active ? 'disable' : 'enable' }}"
                                    style="cursor:pointer">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                           class="btn btn-sm btn-outline-primary me-1" title="Edit">
                            <iconify-icon icon="solar:pen-2-broken" class="fs-15"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-trash-broken" class="fs-15"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No coupons yet.
                        <a href="{{ route('admin.coupons.create') }}">Create one</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
    <div class="card-footer border-top">
        {{ $coupons->links() }}
    </div>
    @endif
</div>

@endsection
