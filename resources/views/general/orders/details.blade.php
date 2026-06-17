@extends('layouts.vertical', ['title' => 'Order ' . $order->order_number])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Header bar ───────────────────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">
            {{ $order->order_number }}
            <span class="badge bg-{{ $order->status_colour }}-subtle text-{{ $order->status_colour }} fs-13 ms-2">
                {{ $order->status_label }}
            </span>
        </h4>
        <p class="text-muted mb-0 fs-13">
            Placed {{ $order->created_at->format('d M Y \a\t H:i') }}
            @if($order->is_guest) · Guest order @endif
        </p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
        Back to Orders
    </a>
</div>

{{-- ── Status timeline ──────────────────────────────────────────────────────── --}}
@php
    $statusKeys  = array_keys(\App\Models\Order::STATUSES);
    $currentIdx  = array_search($order->status, $statusKeys);
    $isTerminal  = in_array($order->status, ['cancelled', 'refunded']);
    $flowKeys    = ['pending', 'processing', 'shipped', 'delivered'];
@endphp

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-center">

            {{-- Progress steps --}}
            <div class="col-xl-8">
                <div class="d-flex align-items-start gap-0">
                    @foreach($flowKeys as $i => $key)
                    @php
                        $cfg       = \App\Models\Order::STATUSES[$key];
                        $isDone    = !$isTerminal && (array_search($key, $statusKeys) <= $currentIdx);
                        $isCurrent = $order->status === $key;
                    @endphp
                    <div class="flex-fill text-center position-relative">
                        {{-- Connector line --}}
                        @if($i > 0)
                        <div class="position-absolute top-50 start-0 translate-middle-y"
                             style="height:2px;width:50%;background:{{ $isDone ? 'var(--bs-success)' : '#dee2e6' }}"></div>
                        @endif
                        @if($i < count($flowKeys) - 1)
                        <div class="position-absolute top-50 end-0 translate-middle-y"
                             style="height:2px;width:50%;background:{{ $isDone && !$isCurrent ? 'var(--bs-success)' : '#dee2e6' }}"></div>
                        @endif

                        <div class="position-relative d-inline-flex align-items-center justify-content-center rounded-circle mb-2
                            {{ $isCurrent ? 'bg-'.$cfg['colour'].' text-white' : ($isDone ? 'bg-success text-white' : 'bg-light text-muted') }}"
                             style="width:42px;height:42px;z-index:1">
                            <iconify-icon icon="{{ $isCurrent ? $cfg['icon'] : ($isDone ? 'solar:check-circle-broken' : $cfg['icon']) }}"
                                          class="fs-20"></iconify-icon>
                        </div>
                        <p class="mb-0 fs-12 fw-{{ $isCurrent ? 'semibold' : 'normal' }}
                                   text-{{ $isCurrent ? $cfg['colour'] : ($isDone ? 'success' : 'muted') }}">
                            {{ $cfg['label'] }}
                        </p>
                    </div>
                    @endforeach

                    @if($isTerminal)
                    <div class="flex-fill text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2
                                    bg-{{ $order->status_colour }} text-white"
                             style="width:42px;height:42px">
                            <iconify-icon icon="{{ \App\Models\Order::STATUSES[$order->status]['icon'] }}"
                                          class="fs-20"></iconify-icon>
                        </div>
                        <p class="mb-0 fs-12 fw-semibold text-{{ $order->status_colour }}">
                            {{ $order->status_label }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status update form --}}
            <div class="col-xl-4">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST"
                      class="d-flex align-items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm">
                        @foreach($statuses as $key => $cfg)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                {{ $cfg['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                        Update Status
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="row g-3">

    {{-- ── Left: items + totals ────────────────────────────────────────────── --}}
    <div class="col-xl-8">

        {{-- Items --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    Order Items
                    <span class="text-muted fw-normal fs-13">({{ $order->items->count() }})</span>
                </h4>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th style="width:60px" class="text-center">Size</th>
                            <th style="width:60px" class="text-center">Qty</th>
                            <th style="width:100px" class="text-end">Unit</th>
                            <th style="width:110px" class="text-end">Line total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded bg-light avatar-sm d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0">
                                        @if($item->product?->primaryImage)
                                            <img src="{{ $item->product->primaryImage->url }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="img-fluid" style="object-fit:cover;width:40px;height:40px">
                                        @else
                                            <iconify-icon icon="solar:gallery-broken" class="fs-18 text-muted"></iconify-icon>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->product)
                                            <a href="{{ route('admin.products.edit', $item->product) }}"
                                               class="text-dark fw-medium">{{ $item->product->name }}</a>
                                        @else
                                            <span class="text-muted fst-italic">Product deleted</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->variant)
                                    <span class="text-dark">{{ $item->variant->label }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->size)
                                    <span class="badge bg-light text-dark">US {{ $item->size->us_size }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center fw-medium">{{ $item->quantity }}</td>
                            <td class="text-end">
                                {{ $order->currency_symbol }}{{ number_format((float)$item->unit_price, 2) }}
                            </td>
                            <td class="text-end fw-semibold">
                                {{ $order->currency_symbol }}{{ number_format($item->line_total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No items recorded.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Totals --}}
            <div class="card-footer bg-light-subtle">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted">Subtotal</td>
                                <td class="text-end fw-medium">
                                    {{ $order->currency_symbol }}{{ number_format((float)$order->subtotal, 2) }}
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-semibold">Total</td>
                                <td class="text-end fw-bold fs-15">
                                    {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                                    <span class="text-muted fw-normal fs-12">({{ $order->currency_code }})</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Right: customer + shipping ──────────────────────────────────────── --}}
    <div class="col-xl-4">

        {{-- Customer --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Customer</h4></div>
            <div class="card-body">
                @if($order->user)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <span class="fw-bold text-primary fs-16">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">{{ $order->user->name }}</p>
                            <p class="text-muted fs-12 mb-0">{{ $order->user->email }}</p>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-light text-muted">Guest order</span>
                    </div>
                @endif

                @php $addr = $order->shipping_address ?? []; @endphp

                @if(!empty($addr['name']))
                    <p class="mb-1 fs-13"><span class="text-muted">Name:</span> {{ $addr['name'] }}</p>
                @endif
                @if(!empty($addr['email']))
                    <p class="mb-1 fs-13"><span class="text-muted">Email:</span> {{ $addr['email'] }}</p>
                @endif
                @if(!empty($addr['phone']))
                    <p class="mb-1 fs-13"><span class="text-muted">Phone:</span> {{ $addr['phone'] }}</p>
                @endif
            </div>
        </div>

        {{-- Shipping address --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Shipping Address</h4></div>
            <div class="card-body">
                @php $addr = $order->shipping_address ?? []; @endphp
                @if(empty($addr))
                    <p class="text-muted mb-0 fs-13">No address recorded.</p>
                @else
                    <address class="mb-0 fs-13 lh-lg">
                        @if(!empty($addr['name'])){{ $addr['name'] }}<br>@endif
                        @if(!empty($addr['address_1'])){{ $addr['address_1'] }}<br>@endif
                        @if(!empty($addr['address_2'])){{ $addr['address_2'] }}<br>@endif
                        @if(!empty($addr['city'])){{ $addr['city'] }}@endif
                        @if(!empty($addr['county'])), {{ $addr['county'] }}@endif
                        @if(!empty($addr['postcode'])) {{ $addr['postcode'] }}@endif
                        @if(!empty($addr['country']))<br>{{ $addr['country'] }}@endif
                    </address>
                @endif
            </div>
        </div>

        {{-- Order meta --}}
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Order Details</h4></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted ps-3">Order #</td>
                        <td class="fw-medium pe-3">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Placed</td>
                        <td class="pe-3">{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Last updated</td>
                        <td class="pe-3">{{ $order->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Currency</td>
                        <td class="pe-3">{{ $order->currency_code }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Status</td>
                        <td class="pe-3">
                            <span class="badge bg-{{ $order->status_colour }}-subtle text-{{ $order->status_colour }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
