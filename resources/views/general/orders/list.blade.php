@extends('layouts.vertical', ['title' => 'Orders'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Stat cards ──────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Total Orders</h4>
                        <p class="fw-semibold fs-22 mb-0">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                        <iconify-icon icon="solar:bag-smile-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
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
                        <h4 class="card-title mb-1">Pending</h4>
                        <p class="fw-semibold fs-22 mb-0 text-warning">{{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="avatar-md bg-warning bg-opacity-10 rounded">
                        <iconify-icon icon="solar:clock-circle-broken" class="fs-32 text-warning avatar-title"></iconify-icon>
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
                        <h4 class="card-title mb-1">Processing</h4>
                        <p class="fw-semibold fs-22 mb-0 text-info">{{ number_format($stats['processing']) }}</p>
                    </div>
                    <div class="avatar-md bg-info bg-opacity-10 rounded">
                        <iconify-icon icon="solar:settings-broken" class="fs-32 text-info avatar-title"></iconify-icon>
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
                        <h4 class="card-title mb-1">Shipped</h4>
                        <p class="fw-semibold fs-22 mb-0 text-primary">{{ number_format($stats['shipped']) }}</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                        <iconify-icon icon="solar:box-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Orders table ─────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">All Orders</h4>

        <form class="d-flex gap-2 flex-wrap" method="GET" action="{{ route('admin.orders.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Order #, name or email…" style="width:220px">

            <select name="status" class="form-select form-select-sm" style="width:150px"
                    onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach($statuses as $key => $cfg)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                        {{ $cfg['label'] }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-sm btn-outline-secondary">Search</button>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th style="width:90px">Order</th>
                    <th>Customer</th>
                    <th style="width:140px">Date</th>
                    <th style="width:60px" class="text-center">Items</th>
                    <th style="width:110px" class="text-end">Total</th>
                    <th style="width:130px">Status</th>
                    <th style="width:80px">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="fw-semibold text-dark">{{ $order->order_number }}</a>
                    </td>
                    <td>
                        <p class="mb-0 fw-medium">{{ $order->customer_name }}</p>
                        <p class="mb-0 text-muted fs-12">{{ $order->customer_email }}</p>
                        @if($order->is_guest)
                            <span class="badge bg-light text-muted fs-11">Guest</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-dark">{{ $order->created_at->format('d M Y') }}</span>
                        <p class="mb-0 text-muted fs-12">{{ $order->created_at->format('H:i') }}</p>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium">{{ $order->items->count() }}</span>
                    </td>
                    <td class="text-end fw-semibold">
                        {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                    </td>
                    <td>
                        {{-- Inline status update --}}
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST"
                              class="d-flex align-items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    class="form-select form-select-sm border-{{ $order->status_colour }}"
                                    onchange="this.form.submit()"
                                    title="Change status">
                                @foreach($statuses as $key => $cfg)
                                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                        {{ $cfg['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="btn btn-light btn-sm" title="View order">
                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No orders found.
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.orders.index') }}">Clear filters</a>
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
