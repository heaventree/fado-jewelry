@extends('layouts.vertical', ['title' => 'Invoices'])

@section('content')

{{-- ── Header ───────────────────────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Invoices</h4>
        <p class="text-muted mb-0 fs-13">Printable invoices for all non-cancelled orders.</p>
    </div>
</div>

{{-- ── Stat cards ───────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Total Invoices</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['total']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">all active orders</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:bill-list-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Pending Payment</p>
                        <h2 class="fw-bold mb-0 {{ $stats['pending'] > 0 ? 'text-warning' : '' }}">
                            {{ number_format($stats['pending']) }}
                        </h2>
                        <p class="text-muted fs-12 mt-1 mb-0">awaiting payment</p>
                    </div>
                    <div class="avatar-md bg-warning bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-warning avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Shipped</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['shipped']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">in transit</p>
                    </div>
                    <div class="avatar-md bg-info bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-info avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Delivered</p>
                        <h2 class="fw-bold mb-0 text-success">{{ number_format($stats['delivered']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">fulfilled</p>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Table ─────────────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">All Invoices</h4>

        <form class="d-flex gap-2 flex-wrap align-items-center" method="GET"
              action="{{ route('admin.invoices.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Order # or customer…" style="width:200px">

            <select name="status" class="form-select form-select-sm" style="width:160px"
                    onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach($statuses as $key => $cfg)
                    @if(!in_array($key, ['cancelled','refunded']))
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                            {{ $cfg['label'] }}
                        </option>
                    @endif
                @endforeach
            </select>

            <button class="btn btn-sm btn-outline-secondary">Search</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                    <th class="text-center" style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.invoices.show', $order) }}"
                           class="fw-semibold text-dark">
                            INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </a>
                        <div class="text-muted fs-12">{{ $order->order_number }}</div>
                    </td>
                    <td>
                        <div class="fw-medium">{{ $order->customer_name }}</div>
                        <div class="text-muted fs-12">{{ $order->customer_email }}</div>
                    </td>
                    <td>
                        <div>{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-muted fs-12">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td>{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</td>
                    <td class="text-end fw-semibold">
                        {{ $order->currency_symbol }}{{ number_format((float) $order->total, 2) }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->status_colour }}-subtle text-{{ $order->status_colour }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.invoices.show', $order) }}"
                           class="btn btn-sm btn-outline-primary me-1" title="View invoice">
                            <iconify-icon icon="solar:eye-broken" class="fs-15"></iconify-icon>
                        </a>
                        <a href="{{ route('admin.invoices.show', $order) }}?print=1"
                           class="btn btn-sm btn-outline-secondary" title="Print invoice" target="_blank">
                            <iconify-icon icon="solar:printer-broken" class="fs-15"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No invoices found.
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.invoices.index') }}">Clear filters</a>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="card-footer border-top">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
