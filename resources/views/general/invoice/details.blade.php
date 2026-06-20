@extends('layouts.vertical', ['title' => 'Invoice INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT)])

@section('css')
<style>
@media print {
    /* Hide all admin chrome */
    #topbar-custom, .leftside-menu, .app-menu, .navbar-custom,
    .page-title-box, .footer, .no-print, .btn, .alert {
        display: none !important;
    }
    body, .content-page, .content {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }
    .invoice-wrapper {
        max-width: 100% !important;
        padding: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body { padding: 0 !important; }
    a { color: inherit !important; text-decoration: none !important; }
    @page { margin: 15mm; size: A4 portrait; }
}
</style>
@endsection

@section('content')

{{-- ── Toolbar (screen only) ────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-3 no-print">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
            <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
            All Invoices
        </a>
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
            <iconify-icon icon="solar:cart-large-broken" class="align-middle me-1"></iconify-icon>
            View Order {{ $order->order_number }}
        </a>
    </div>
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:printer-broken" class="align-middle me-1"></iconify-icon>
        Print / Save PDF
    </button>
</div>

{{-- ── Invoice document ─────────────────────────────────────────────────────── --}}
<div class="invoice-wrapper mx-auto" style="max-width:820px">
<div class="card">
<div class="card-body p-4 p-md-5">

    {{-- Header --}}
    <div class="row align-items-start mb-4">
        <div class="col-7">
            <h2 class="fw-bold text-dark mb-1" style="font-size:1.6rem; letter-spacing:.5px">{{ \App\Models\Setting::get('store_name', 'FADÓ') }}</h2>
            <p class="text-muted fs-13 mb-0">{{ \App\Models\Setting::get('store_tagline', 'Fine Irish Jewellery') }}</p>
            <p class="text-muted fs-12 mt-2 mb-0">
                {{ \App\Models\Setting::get('store_email', 'info@fadojewellery.ie') }}<br>
                {{ \Illuminate\Support\Str::after(url('/'), '://') }}
            </p>
        </div>
        <div class="col-5 text-end">
            <h3 class="fw-bold text-primary mb-1">INVOICE</h3>
            <p class="fw-semibold mb-0">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p class="text-muted fs-13 mb-0">{{ $order->order_number }}</p>
            <p class="text-muted fs-13 mb-0 mt-1">{{ $order->created_at->format('d F Y') }}</p>
            <span class="badge bg-{{ $order->status_colour }}-subtle text-{{ $order->status_colour }} mt-2">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <hr class="my-4">

    {{-- Bill to / Ship to --}}
    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-uppercase text-muted fw-semibold fs-11 mb-2" style="letter-spacing:.8px">Bill To</h6>
            <p class="fw-semibold mb-1">{{ $order->customer_name }}</p>
            <p class="text-muted fs-13 mb-0">{{ $order->customer_email }}</p>
        </div>
        @if($order->shipping_address)
        <div class="col-6">
            <h6 class="text-uppercase text-muted fw-semibold fs-11 mb-2" style="letter-spacing:.8px">Ship To</h6>
            @php $addr = $order->shipping_address; @endphp
            <p class="fw-semibold mb-1">{{ $addr['name'] ?? $order->customer_name }}</p>
            <p class="text-muted fs-13 mb-0">
                @if(!empty($addr['address_line_1'])){{ $addr['address_line_1'] }}<br>@endif
                @if(!empty($addr['address_line_2'])){{ $addr['address_line_2'] }}<br>@endif
                @if(!empty($addr['city'])){{ $addr['city'] }}@endif
                @if(!empty($addr['county'])), {{ $addr['county'] }}@endif
                @if(!empty($addr['postcode'])) {{ $addr['postcode'] }}@endif
                @if(!empty($addr['country']))<br>{{ $addr['country'] }}@endif
                @if(!empty($addr['phone']))<br>{{ $addr['phone'] }}@endif
            </p>
        </div>
        @endif
    </div>

    {{-- Line items --}}
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f8f9fa">
                <tr>
                    <th class="py-2">Description</th>
                    <th class="text-center py-2" style="width:70px">Qty</th>
                    <th class="text-end py-2" style="width:110px">Unit Price</th>
                    <th class="text-end py-2" style="width:110px">Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="fw-medium">{{ $item->product->name ?? '(deleted product)' }}</div>
                        @if($item->variant)
                            <div class="text-muted fs-12">
                                {{ $item->variant->metal->name ?? '' }}
                                @if($item->variant->gemstone)
                                    / {{ $item->variant->gemstone->name }}
                                @endif
                                @if($item->variant->sku)
                                    &nbsp;·&nbsp; SKU: {{ $item->variant->sku }}
                                @endif
                            </div>
                        @endif
                        @if($item->size)
                            <div class="text-muted fs-12">Ring size: US {{ $item->size->us_size }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">
                        {{ $order->currency_symbol }}{{ number_format((float) $item->unit_price, 2) }}
                    </td>
                    <td class="text-end fw-semibold">
                        {{ $order->currency_symbol }}{{ number_format($item->line_total, 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totals --}}
    <div class="row justify-content-end mb-4">
        <div class="col-auto" style="min-width:280px">
            <table class="table table-borderless table-sm mb-0" style="font-size:.875rem">
                <tbody>
                    <tr>
                        <td class="text-muted">Subtotal</td>
                        <td class="text-end fw-medium">
                            {{ $order->currency_symbol }}{{ number_format((float) $order->subtotal, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Shipping</td>
                        <td class="text-end fw-medium text-muted">—</td>
                    </tr>
                    <tr class="border-top">
                        <td class="fw-bold fs-15 pt-2">Total</td>
                        <td class="text-end fw-bold fs-15 pt-2 text-primary">
                            {{ $order->currency_symbol }}{{ number_format((float) $order->total, 2) }}
                            <span class="fs-12 fw-normal text-muted">{{ $order->currency_code }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer note --}}
    <hr class="my-4">
    <div class="row">
        <div class="col-12 text-center text-muted fs-12">
            <p class="mb-1">Thank you for your order. If you have any questions, please contact us at {{ \App\Models\Setting::get('store_email', 'info@fadojewellery.ie') }}</p>
            <p class="mb-0">{{ \App\Models\Setting::get('store_name', 'FADÓ Jewellery') }} — {{ \App\Models\Setting::get('store_tagline', 'Fine Irish Jewellery') }} &nbsp;·&nbsp; {{ \Illuminate\Support\Str::after(url('/'), '://') }}</p>
        </div>
    </div>

</div>
</div>
</div>

@if(request('print') === '1')
<script>window.addEventListener('load', () => window.print());</script>
@endif

@endsection
