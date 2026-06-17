@extends('layouts.vertical', ['title' => 'Inventory'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Stat cards ───────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">

    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Total Variants</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['total_variants']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">metal × gemstone combinations</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Total Units in Stock</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['total_stock']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">across all variants</p>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:reorder-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card {{ $stats['low_stock'] > 0 ? 'border border-warning' : '' }}">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Low Stock</p>
                        <h2 class="fw-bold mb-0 {{ $stats['low_stock'] > 0 ? 'text-warning' : '' }}">
                            {{ number_format($stats['low_stock']) }}
                        </h2>
                        <p class="text-muted fs-12 mt-1 mb-0">≤ {{ $threshold }} units remaining</p>
                    </div>
                    <div class="avatar-md {{ $stats['low_stock'] > 0 ? 'bg-warning' : 'bg-light' }} bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone"
                                      class="fs-32 {{ $stats['low_stock'] > 0 ? 'text-warning' : 'text-muted' }} avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
            @if($stats['low_stock'] > 0)
            <div class="card-footer py-2 bg-warning bg-opacity-10 border-top-0">
                <a href="{{ route('admin.inventory.low-stock') }}" class="text-warning fw-semibold fs-12">
                    View low stock alerts <iconify-icon icon="solar:arrow-right-broken" class="align-middle"></iconify-icon>
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card {{ $stats['out_of_stock'] > 0 ? 'border border-danger' : '' }}">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Out of Stock</p>
                        <h2 class="fw-bold mb-0 {{ $stats['out_of_stock'] > 0 ? 'text-danger' : '' }}">
                            {{ number_format($stats['out_of_stock']) }}
                        </h2>
                        <p class="text-muted fs-12 mt-1 mb-0">variants at zero</p>
                    </div>
                    <div class="avatar-md {{ $stats['out_of_stock'] > 0 ? 'bg-danger' : 'bg-light' }} bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:close-circle-bold-duotone"
                                      class="fs-32 {{ $stats['out_of_stock'] > 0 ? 'text-danger' : 'text-muted' }} avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Inventory table ──────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">All Stock</h4>

        <form class="d-flex gap-2 flex-wrap align-items-center" method="GET"
              action="{{ route('admin.inventory.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Product name or SKU…" style="width:210px">

            <select name="stock" class="form-select form-select-sm" style="width:150px"
                    onchange="this.form.submit()">
                <option value="">All stock levels</option>
                <option value="out"  {{ request('stock') === 'out'  ? 'selected' : '' }}>Out of stock</option>
                <option value="low"  {{ request('stock') === 'low'  ? 'selected' : '' }}>Low stock (≤ {{ $threshold }})</option>
                <option value="ok"   {{ request('stock') === 'ok'   ? 'selected' : '' }}>OK (> {{ $threshold }})</option>
            </select>

            <button class="btn btn-sm btn-outline-secondary">Search</button>
            @if(request('search') || request('stock'))
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <form action="{{ route('admin.inventory.bulk-stock') }}" method="POST" id="bulk-stock-form">
        @csrf
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>Product</th>
                        <th>Variant (metal / gemstone)</th>
                        <th style="width:100px">SKU</th>
                        <th class="text-end" style="width:90px">Price (€)</th>
                        <th style="width:130px">Status</th>
                        <th style="width:140px">Stock</th>
                        <th style="width:70px">Save</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($variants as $variant)
                    @php
                        $stockLevel = $variant->stock === 0
                            ? 'out'
                            : ($variant->stock <= $threshold ? 'low' : 'ok');
                    @endphp
                    <input type="hidden" name="stocks[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                    <tr class="{{ $stockLevel === 'out' ? 'table-danger' : ($stockLevel === 'low' ? 'table-warning' : '') }} bg-opacity-25">
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
                            @if($stockLevel === 'out')
                                <span class="badge bg-danger-subtle text-danger">
                                    <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon>Out of stock
                                </span>
                            @elseif($stockLevel === 'low')
                                <span class="badge bg-warning-subtle text-warning">
                                    <iconify-icon icon="solar:danger-triangle-broken" class="me-1"></iconify-icon>Low stock
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success">
                                    <iconify-icon icon="solar:check-circle-broken" class="me-1"></iconify-icon>In stock
                                </span>
                            @endif
                        </td>
                        <td>
                            <input type="number"
                                   name="stocks[{{ $loop->index }}][stock]"
                                   value="{{ $variant->stock }}"
                                   min="0" max="9999"
                                   class="form-control form-control-sm stock-input"
                                   data-original="{{ $variant->stock }}"
                                   style="width:90px"
                                   oninput="markDirty(this)">
                        </td>
                        <td>
                            {{-- Per-row quick-save via individual PATCH form --}}
                            <form action="{{ route('admin.inventory.update-stock', $variant) }}"
                                  method="POST" class="d-inline row-save-form" style="display:none!important">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" class="row-stock-val" name="stock" value="{{ $variant->stock }}">
                                <button type="submit"
                                        class="btn btn-sm btn-success row-save-btn d-none"
                                        title="Save this row">
                                    <iconify-icon icon="solar:diskette-broken" class="fs-15"></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No variants found.
                            @if(request('search') || request('stock'))
                                <a href="{{ route('admin.inventory.index') }}">Clear filters</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($variants->isNotEmpty())
        <div class="card-footer d-flex align-items-center justify-content-between gap-3 flex-wrap border-top">
            <span class="text-muted fs-13" id="dirty-notice" style="display:none">
                <iconify-icon icon="solar:pen-2-broken" class="align-middle me-1 text-warning"></iconify-icon>
                Unsaved changes — use row <iconify-icon icon="solar:diskette-broken" class="align-middle"></iconify-icon>
                buttons or save all below.
            </span>
            <button type="submit" class="btn btn-primary ms-auto" id="save-all-btn" disabled>
                <iconify-icon icon="solar:diskette-broken" class="align-middle me-1"></iconify-icon>
                Save all changes
            </button>
        </div>
        @endif
    </form>

    @if($variants->hasPages())
    <div class="card-footer border-top">
        {{ $variants->links() }}
    </div>
    @endif
</div>

@endsection

@section('script')
<script>
function markDirty(input) {
    const original = parseInt(input.dataset.original, 10);
    const current  = parseInt(input.value, 10);
    const isDirty  = current !== original;
    const row      = input.closest('tr');
    const saveBtn  = row.querySelector('.row-save-btn');
    const rowForm  = row.querySelector('.row-save-form');

    // Sync hidden input in the per-row PATCH form
    row.querySelector('.row-stock-val').value = input.value;

    if (saveBtn) saveBtn.classList.toggle('d-none', !isDirty);
    if (rowForm) rowForm.style.display = isDirty ? '' : 'none!important';

    // Check if any input across the whole table is dirty
    const anyDirty = [...document.querySelectorAll('.stock-input')].some(el => {
        return parseInt(el.value, 10) !== parseInt(el.dataset.original, 10);
    });

    document.getElementById('dirty-notice').style.display = anyDirty ? '' : 'none';
    document.getElementById('save-all-btn').disabled = !anyDirty;
}
</script>
@endsection
