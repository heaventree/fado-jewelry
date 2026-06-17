@extends('layouts.vertical', ['title' => 'Low Stock Alerts'])

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
        <h4 class="fw-semibold mb-1">Low Stock Alerts</h4>
        <p class="text-muted mb-0 fs-13">
            Variants with {{ $threshold }} units or fewer remaining.
            @if($variants->isEmpty())
                <span class="text-success fw-medium">All stock levels are healthy.</span>
            @endif
        </p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
        <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
        All Stock
    </a>
</div>

{{-- ── Summary cards ────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card {{ $outOfStock->count() > 0 ? 'border border-danger' : '' }}">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Out of Stock</p>
                        <h2 class="fw-bold mb-0 {{ $outOfStock->count() > 0 ? 'text-danger' : '' }}">
                            {{ $outOfStock->count() }}
                        </h2>
                    </div>
                    <div class="avatar-md {{ $outOfStock->count() > 0 ? 'bg-danger' : 'bg-light' }} bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:close-circle-bold-duotone"
                                      class="fs-32 {{ $outOfStock->count() > 0 ? 'text-danger' : 'text-muted' }} avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card {{ $lowStock->count() > 0 ? 'border border-warning' : '' }}">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Running Low</p>
                        <h2 class="fw-bold mb-0 {{ $lowStock->count() > 0 ? 'text-warning' : '' }}">
                            {{ $lowStock->count() }}
                        </h2>
                        <p class="text-muted fs-12 mt-1 mb-0">1–{{ $threshold }} units left</p>
                    </div>
                    <div class="avatar-md {{ $lowStock->count() > 0 ? 'bg-warning' : 'bg-light' }} bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone"
                                      class="fs-32 {{ $lowStock->count() > 0 ? 'text-warning' : 'text-muted' }} avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Total Alerts</p>
                        <h2 class="fw-bold mb-0">{{ $variants->count() }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">variants need attention</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:bell-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3 h-100">
                <div class="flex-grow-1">
                    <p class="text-muted fs-13 mb-2">Alert threshold</p>
                    <p class="mb-0 fs-13">Showing variants with <strong>≤ {{ $threshold }} units</strong>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Out of stock ─────────────────────────────────────────────────────────── --}}
@if($outOfStock->isNotEmpty())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center gap-2">
        <iconify-icon icon="solar:close-circle-bold-duotone" class="text-danger fs-20"></iconify-icon>
        <h4 class="card-title mb-0 text-danger">Out of Stock ({{ $outOfStock->count() }})</h4>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-danger bg-opacity-10">
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th style="width:100px">SKU</th>
                    <th class="text-end" style="width:90px">Price (€)</th>
                    <th style="width:150px">Update Stock</th>
                    <th style="width:80px">Save</th>
                </tr>
            </thead>
            <tbody>
            @foreach($outOfStock as $variant)
                <tr>
                    <td>
                        <a href="{{ route('admin.products.edit', $variant->product) }}"
                           class="fw-medium text-dark">{{ $variant->product->name }}</a>
                    </td>
                    <td>
                        <span class="text-dark">{{ $variant->metal->name }}</span>
                        @if($variant->gemstone)
                            <span class="text-muted"> / {{ $variant->gemstone->name }}</span>
                        @endif
                    </td>
                    <td>
                        @if($variant->sku)
                            <code class="fs-12">{{ $variant->sku }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">€{{ number_format((float)$variant->price_eur, 2) }}</td>
                    <td>
                        <form action="{{ route('admin.inventory.update-stock', $variant) }}"
                              method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="stock" value="0" min="0" max="9999"
                                   class="form-control form-control-sm" style="width:80px">
                            <button type="submit" class="btn btn-sm btn-danger">
                                <iconify-icon icon="solar:diskette-broken" class="fs-15"></iconify-icon>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $variant->product) }}"
                           class="btn btn-light btn-sm" title="Edit product">
                            <iconify-icon icon="solar:pen-2-broken" class="fs-15"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Low stock ────────────────────────────────────────────────────────────── --}}
@if($lowStock->isNotEmpty())
<div class="card mb-3">
    <div class="card-header d-flex align-items-center gap-2">
        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-warning fs-20"></iconify-icon>
        <h4 class="card-title mb-0 text-warning">Running Low ({{ $lowStock->count() }})</h4>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-warning bg-opacity-10">
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th style="width:100px">SKU</th>
                    <th class="text-end" style="width:90px">Price (€)</th>
                    <th class="text-center" style="width:90px">Units left</th>
                    <th style="width:150px">Update Stock</th>
                    <th style="width:80px">Edit</th>
                </tr>
            </thead>
            <tbody>
            @foreach($lowStock as $variant)
                <tr>
                    <td>
                        <a href="{{ route('admin.products.edit', $variant->product) }}"
                           class="fw-medium text-dark">{{ $variant->product->name }}</a>
                    </td>
                    <td>
                        <span class="text-dark">{{ $variant->metal->name }}</span>
                        @if($variant->gemstone)
                            <span class="text-muted"> / {{ $variant->gemstone->name }}</span>
                        @endif
                    </td>
                    <td>
                        @if($variant->sku)
                            <code class="fs-12">{{ $variant->sku }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">€{{ number_format((float)$variant->price_eur, 2) }}</td>
                    <td class="text-center">
                        <span class="badge bg-warning text-dark fw-bold fs-14">{{ $variant->stock }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.inventory.update-stock', $variant) }}"
                              method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="stock" value="{{ $variant->stock }}" min="0" max="9999"
                                   class="form-control form-control-sm" style="width:80px">
                            <button type="submit" class="btn btn-sm btn-warning">
                                <iconify-icon icon="solar:diskette-broken" class="fs-15"></iconify-icon>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $variant->product) }}"
                           class="btn btn-light btn-sm" title="Edit product">
                            <iconify-icon icon="solar:pen-2-broken" class="fs-15"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($variants->isEmpty())
<div class="card">
    <div class="card-body py-5 text-center">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-48 text-success mb-3 d-block"></iconify-icon>
        <h5 class="fw-semibold">All stock levels are healthy</h5>
        <p class="text-muted mb-3">No variants are at or below the {{ $threshold }}-unit threshold.</p>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">View all stock</a>
    </div>
</div>
@endif

@endsection
