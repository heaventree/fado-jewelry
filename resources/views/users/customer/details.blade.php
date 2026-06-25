@extends('layouts.vertical', ['title' => $customer->name])

@section('content')

{{-- ── Header bar ───────────────────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">{{ $customer->name }}</h4>
        <p class="text-muted mb-0 fs-13">
            Customer since {{ $customer->created_at->format('d M Y') }}
            @if($customer->email_verified_at)
                · <span class="text-success">
                    <iconify-icon icon="solar:verified-check-bold-duotone" class="align-middle me-1"></iconify-icon>Verified
                  </span>
            @endif
        </p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
        <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
        All Customers
    </a>
</div>

<div class="row g-3">

    {{-- ── Left column: profile + stats ───────────────────────────────────────── --}}
    <div class="col-xl-4">

        {{-- Profile card --}}
        <div class="card mb-3">
            <div class="card-body text-center pt-4">
                <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center
                            justify-content-center mx-auto mb-3" style="width:72px;height:72px">
                    <span class="fw-bold text-primary" style="font-size:28px">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </span>
                </div>
                <h4 class="fw-semibold mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-0">{{ $customer->email }}</p>
                @if($customer->email_verified_at)
                    <span class="badge bg-success-subtle text-success mt-2">
                        <iconify-icon icon="solar:verified-check-bold-duotone" class="me-1"></iconify-icon>
                        Verified account
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning mt-2">Email not verified</span>
                @endif
            </div>
            <div class="card-footer border-top">
                <a href="mailto:{{ $customer->email }}" class="btn btn-primary btn-sm w-100">
                    <iconify-icon icon="solar:letter-broken" class="align-middle me-1"></iconify-icon>
                    Email customer
                </a>
            </div>
        </div>

        {{-- Quick stats --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Account Summary</h4></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted ps-3">Total orders</td>
                        <td class="fw-semibold pe-3">{{ number_format($customer->orders_count) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Lifetime value</td>
                        <td class="fw-semibold pe-3">
                            @if($lifetime > 0)
                                €{{ number_format((float)$lifetime, 2) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Last order</td>
                        <td class="pe-3">
                            @if($lastOrder)
                                <a href="{{ route('admin.orders.show', $lastOrder) }}" class="fw-medium">
                                    {{ $lastOrder->order_number }}
                                </a>
                                <p class="text-muted fs-12 mb-0">{{ $lastOrder->created_at->format('d M Y') }}</p>
                            @else
                                <span class="text-muted">No orders yet</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Joined</td>
                        <td class="pe-3">{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Verified</td>
                        <td class="pe-3">
                            @if($customer->email_verified_at)
                                {{ $customer->email_verified_at->format('d M Y') }}
                            @else
                                <span class="text-warning">Not verified</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    {{-- ── Right column: order history ─────────────────────────────────────────── --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">
                    Order History
                    <span class="text-muted fw-normal fs-13">({{ $customer->orders_count }})</span>
                </h4>
                @if($lifetime > 0)
                    <span class="text-muted fs-13 fw-medium">
                        Lifetime: <span class="text-dark fw-semibold">€{{ number_format((float)$lifetime, 2) }}</span>
                    </span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Order</th>
                            <th style="width:120px">Date</th>
                            <th class="text-center" style="width:60px">Items</th>
                            <th class="text-end" style="width:110px">Total</th>
                            <th style="width:110px">Status</th>
                            <th style="width:70px">Action</th>
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
                                <span class="text-dark">{{ $order->created_at->format('d M Y') }}</span>
                                <p class="mb-0 text-muted fs-12">{{ $order->created_at->format('H:i') }}</p>
                            </td>
                            <td class="text-center">{{ $order->items->count() }}</td>
                            <td class="text-end fw-semibold">
                                {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_colour }}-subtle text-{{ $order->status_colour }}">
                                    {{ $order->status_label }}
                                </span>
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
                            <td colspan="6" class="text-center py-4 text-muted fst-italic">
                                This customer hasn't placed any orders yet.
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

        {{-- Saved Addresses --}}
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">
                    Saved Addresses
                    <span class="text-muted fw-normal fs-13">({{ $customer->addresses->count() }})</span>
                </h4>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Label</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>County</th>
                            <th>Postcode</th>
                            <th>Country</th>
                            <th>Phone</th>
                            <th style="width:70px">Default</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($customer->addresses as $address)
                        <tr>
                            <td class="fw-semibold">{{ $address->label ?? '—' }}</td>
                            <td>{{ $address->name }}</td>
                            <td>
                                {{ $address->line1 }}
                                @if($address->line2)<br><span class="text-muted fs-12">{{ $address->line2 }}</span>@endif
                            </td>
                            <td>{{ $address->city }}</td>
                            <td>{{ $address->county ?? '—' }}</td>
                            <td>{{ $address->postcode ?? '—' }}</td>
                            <td>{{ $address->country }}</td>
                            <td>{{ $address->phone ?? '—' }}</td>
                            <td class="text-center">
                                @if($address->is_default)
                                    <span class="badge bg-success-subtle text-success">Default</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted fst-italic">
                                No saved addresses.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
