@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')

{{-- ── Greeting ─────────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-semibold mb-1">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }} 👋</h4>
        <p class="text-muted mb-0 fs-13">{{ now()->format('l, j F Y') }} — Here's what's happening at FADÓ.</p>
    </div>
    @if($unreadConsults > 0)
        <a href="{{ route('admin.consultations.index', ['filter' => 'unread']) }}"
           class="btn btn-danger btn-sm">
            <iconify-icon icon="solar:bell-bold-duotone" class="align-middle me-1"></iconify-icon>
            {{ $unreadConsults }} unread {{ Str::plural('enquiry', $unreadConsults) }}
        </a>
    @endif
</div>

{{-- ── Headline stat cards ──────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">

    <div class="col-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Active Products</p>
                        <h2 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">in the catalogue</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:t-shirt-bold-duotone"
                                      class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2 bg-light bg-opacity-50 border-top-0">
                <a href="{{ route('admin.products.index') }}" class="text-primary fw-semibold fs-12">
                    View all products <iconify-icon icon="solar:arrow-right-broken" class="align-middle"></iconify-icon>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Total Orders</p>
                        <h2 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h2>
                        <p class="fs-12 mt-1 mb-0">
                            @if($ordersThisMonth > 0)
                                <span class="text-success fw-medium">+{{ $ordersThisMonth }}</span>
                                <span class="text-muted"> this month</span>
                            @else
                                <span class="text-muted">0 this month</span>
                            @endif
                        </p>
                    </div>
                    <div class="avatar-md bg-info bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:bag-smile-bold-duotone"
                                      class="fs-32 text-info avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2 bg-light bg-opacity-50 border-top-0">
                <a href="{{ route('admin.orders.index') }}" class="text-primary fw-semibold fs-12">
                    View all orders <iconify-icon icon="solar:arrow-right-broken" class="align-middle"></iconify-icon>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Revenue (EUR)</p>
                        <h2 class="fw-bold mb-0">€{{ number_format((float)$revenueEur, 0) }}</h2>
                        <p class="text-muted fs-12 mt-1 mb-0">excl. cancelled / refunded</p>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:euro-bold-duotone"
                                      class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2 bg-light bg-opacity-50 border-top-0">
                <a href="{{ route('admin.orders.index') }}" class="text-primary fw-semibold fs-12">
                    View orders <iconify-icon icon="solar:arrow-right-broken" class="align-middle"></iconify-icon>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card h-100 {{ $unreadConsults > 0 ? 'border border-danger' : '' }}">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted fs-13 mb-1">Unread Enquiries</p>
                        <h2 class="fw-bold mb-0 {{ $unreadConsults > 0 ? 'text-danger' : '' }}">
                            {{ number_format($unreadConsults) }}
                        </h2>
                        <p class="text-muted fs-12 mt-1 mb-0">
                            {{ $pendingOrders }} {{ Str::plural('order', $pendingOrders) }} pending
                        </p>
                    </div>
                    <div class="avatar-md {{ $unreadConsults > 0 ? 'bg-danger' : 'bg-warning' }} bg-opacity-10 rounded flex-shrink-0">
                        <iconify-icon icon="solar:calendar-mark-bold-duotone"
                                      class="fs-32 {{ $unreadConsults > 0 ? 'text-danger' : 'text-warning' }} avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2 bg-light bg-opacity-50 border-top-0">
                <a href="{{ route('admin.consultations.index') }}" class="text-primary fw-semibold fs-12">
                    View enquiries <iconify-icon icon="solar:arrow-right-broken" class="align-middle"></iconify-icon>
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ── Order status breakdown ───────────────────────────────────────────────── --}}
@if($totalOrders > 0)
<div class="row g-3 mb-3">
    @foreach(\App\Models\Order::STATUSES as $key => $cfg)
    @php $count = $ordersByStatus[$key] ?? 0; @endphp
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="{{ $cfg['icon'] }}"
                                  class="fs-20 text-{{ $cfg['colour'] }} flex-shrink-0"></iconify-icon>
                    <div class="overflow-hidden">
                        <p class="text-muted fs-11 mb-0 text-truncate">{{ $cfg['label'] }}</p>
                        <p class="fw-bold fs-16 mb-0">{{ number_format($count) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ── Recent orders + Recent enquiries ───────────────────────────────────────── --}}
<div class="row g-3 mb-3">

    {{-- Recent Orders --}}
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Recent Orders</h4>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th class="text-center" style="width:50px">Items</th>
                            <th class="text-end" style="width:100px">Total</th>
                            <th style="width:100px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="fw-semibold text-dark">{{ $order->order_number }}</a>
                                <p class="text-muted fs-12 mb-0">{{ $order->created_at->format('d M') }}</p>
                            </td>
                            <td>
                                <p class="mb-0 fw-medium">{{ $order->customer_name }}</p>
                                @if($order->is_guest)
                                    <span class="badge bg-light text-muted fs-11">Guest</span>
                                @endif
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3 fst-italic">No orders yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Enquiries --}}
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">
                    Unread Enquiries
                    @if($unreadConsults > 0)
                        <span class="badge bg-danger ms-1">{{ $unreadConsults }}</span>
                    @endif
                </h4>
                <a href="{{ route('admin.consultations.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentConsultations as $enquiry)
                    <a href="{{ route('admin.consultations.show', $enquiry) }}"
                       class="d-flex align-items-start gap-3 p-3 border-bottom text-dark text-decoration-none
                              {{ $loop->last ? 'border-0' : '' }} hover-bg-light">
                        <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center
                                    justify-content-center flex-shrink-0">
                            <span class="fw-bold text-primary fs-13">
                                {{ strtoupper(substr($enquiry->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <p class="fw-semibold mb-0 text-truncate">{{ $enquiry->name }}</p>
                                <span class="text-muted fs-11 text-nowrap flex-shrink-0">
                                    {{ $enquiry->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            @if($enquiry->message)
                                <p class="text-muted fs-12 mb-0 text-truncate">{{ $enquiry->message }}</p>
                            @else
                                <p class="text-muted fs-12 mb-0 fst-italic">
                                    Prefers {{ $enquiry->preferred_contact_label }} contact
                                </p>
                            @endif
                        </div>
                        <span class="badge bg-danger rounded-pill flex-shrink-0" style="width:8px;height:8px;padding:0"
                              title="Unread"></span>
                    </a>
                @empty
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                        <iconify-icon icon="solar:inbox-bold-duotone" class="fs-48 mb-2 text-success"></iconify-icon>
                        <p class="mb-0 fw-medium">All caught up!</p>
                        <p class="fs-12 mb-0">No unread consultation enquiries.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── Top products ─────────────────────────────────────────────────────────── --}}
@if($topProducts->isNotEmpty())
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Top Products by Units Sold</h4>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">All products</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th style="width:30px">#</th>
                            <th>Product</th>
                            <th class="text-center" style="width:120px">Units sold</th>
                            <th class="text-end" style="width:140px">Revenue (EUR)</th>
                            <th style="width:80px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($topProducts as $i => $product)
                        <tr>
                            <td class="text-muted fw-semibold">{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $product->name }}</td>
                            <td class="text-center">{{ number_format($product->units_sold) }}</td>
                            <td class="text-end fw-semibold">€{{ number_format((float)$product->revenue, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-light btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
