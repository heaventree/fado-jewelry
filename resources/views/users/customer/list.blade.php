@extends('layouts.vertical', ['title' => 'Customers'])

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
                        <p class="text-muted fs-13 mb-1">Total Customers</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['total']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">registered accounts</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"
                                      class="fs-32 text-primary avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Have Ordered</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['with_orders']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">customers with orders</p>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:bag-smile-bold-duotone"
                                      class="fs-32 text-success avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Total Orders</p>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['orders_total']) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">across all accounts</p>
                    </div>
                    <div class="avatar-md bg-info bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:box-bold-duotone"
                                      class="fs-32 text-info avatar-title"></iconify-icon>
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
                        <p class="text-muted fs-13 mb-1">Revenue (EUR)</p>
                        <h2 class="fw-bold mb-0">€{{ number_format((float)$stats['revenue_eur'], 0) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">excl. cancelled/refunded</p>
                    </div>
                    <div class="avatar-md bg-warning bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:euro-bold-duotone"
                                      class="fs-32 text-warning avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Customer table ───────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">All Customers</h4>

        <form class="d-flex gap-2 flex-wrap" method="GET" action="{{ route('admin.customers.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Name or email…" style="width:230px">
            <button class="btn btn-sm btn-outline-secondary">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th class="text-center" style="width:80px">Orders</th>
                    <th class="text-end" style="width:130px">Total spent</th>
                    <th style="width:130px">Joined</th>
                    <th style="width:70px">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center
                                        justify-content-center flex-shrink-0">
                                <span class="fw-bold text-primary fs-13">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="fw-medium text-dark">{{ $customer->name }}</a>
                                @if($customer->email_verified_at)
                                    <iconify-icon icon="solar:verified-check-bold-duotone"
                                                  class="text-success fs-14 align-middle ms-1"
                                                  title="Verified"></iconify-icon>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">{{ $customer->email }}</td>
                    <td class="text-center">
                        <span class="fw-semibold {{ $customer->orders_count > 0 ? 'text-dark' : 'text-muted' }}">
                            {{ $customer->orders_count }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold">
                        @if($customer->orders_sum_total)
                            €{{ number_format((float)$customer->orders_sum_total, 2) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-dark">{{ $customer->created_at->format('d M Y') }}</span>
                        <p class="mb-0 text-muted fs-12">{{ $customer->created_at->diffForHumans() }}</p>
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="btn btn-light btn-sm" title="View customer">
                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No customers found.
                        @if(request('search'))
                            <a href="{{ route('admin.customers.index') }}">Clear search</a>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="card-footer border-top">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection
