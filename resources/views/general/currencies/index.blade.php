@extends('layouts.vertical', ['title' => 'Currency Management'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3">

    {{-- ── Exchange rates table ─────────────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Exchange Rates</h4>
                    <p class="text-muted fs-12 mb-0 mt-1">
                        EUR is the base currency — all prices are stored in EUR and converted at these rates.
                    </p>
                </div>
                <span class="badge bg-light text-dark fs-12">
                    <iconify-icon icon="solar:clock-circle-broken" class="align-middle me-1"></iconify-icon>
                    Rates updated manually
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th style="width:80px">Code</th>
                            <th>Currency</th>
                            <th style="width:120px" class="text-center">Status</th>
                            <th style="width:200px">
                                Rate
                                <span class="text-muted fw-normal fs-11">(1 EUR = X)</span>
                            </th>
                            <th>Auto-detects for regions</th>
                            <th style="width:160px">Last updated</th>
                            <th style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($currencies as $currency)
                        <tr>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fs-13 fw-bold">
                                    {{ $currency->code }}
                                </span>
                            </td>
                            <td class="fw-medium">{{ $currency->name }}</td>
                            <td class="text-center">
                                @if($currency->is_default)
                                    <span class="badge bg-success-subtle text-success">Default (base)</span>
                                @else
                                    <span class="badge bg-light text-dark">Active</span>
                                @endif
                            </td>
                            <td>
                                @if($currency->is_default)
                                    {{-- Default currency — rate is always 1, not editable --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control form-control-sm"
                                               value="1.000000" disabled style="width:120px">
                                        <span class="text-muted fs-12">fixed</span>
                                    </div>
                                @else
                                    {{-- Inline rate update form --}}
                                    <form action="{{ route('admin.currencies.update-rate', $currency) }}"
                                          method="POST" class="d-flex align-items-center gap-2"
                                          id="rate-form-{{ $currency->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number"
                                               name="rate"
                                               class="form-control form-control-sm @error('rate') is-invalid @enderror"
                                               value="{{ number_format((float)$currency->rate, 6, '.', '') }}"
                                               step="0.000001"
                                               min="0.000001"
                                               max="99999"
                                               style="width:130px"
                                               id="rate-input-{{ $currency->id }}"
                                               data-original="{{ number_format((float)$currency->rate, 6, '.', '') }}"
                                               oninput="markDirty({{ $currency->id }})">
                                        <button type="submit"
                                                class="btn btn-sm btn-success d-none"
                                                id="rate-save-{{ $currency->id }}"
                                                title="Save rate">
                                            <iconify-icon icon="solar:check-circle-broken" class="align-middle fs-16"></iconify-icon>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary d-none"
                                                id="rate-cancel-{{ $currency->id }}"
                                                onclick="cancelRate({{ $currency->id }})"
                                                title="Discard">
                                            <iconify-icon icon="solar:close-circle-broken" class="align-middle fs-16"></iconify-icon>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if(is_array($currency->region_codes) && count($currency->region_codes))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($currency->region_codes, 0, 8) as $rc)
                                            <span class="badge bg-light text-dark fs-11">{{ $rc }}</span>
                                        @endforeach
                                        @if(count($currency->region_codes) > 8)
                                            <span class="badge bg-light text-muted fs-11">
                                                +{{ count($currency->region_codes) - 8 }} more
                                            </span>
                                        @endif
                                    </div>
                                @elseif(is_null($currency->region_codes))
                                    <span class="text-muted fs-12 fst-italic">All other regions (catch-all)</span>
                                @else
                                    <span class="text-muted fs-12">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted fs-12">
                                    {{ $currency->updated_at->format('d M Y') }}<br>
                                    <span class="fs-11">{{ $currency->updated_at->format('H:i') }}</span>
                                </span>
                            </td>
                            <td>
                                @if($currency->is_default)
                                    <span class="text-muted fs-12">Cannot delete base</span>
                                @else
                                    <form action="{{ route('admin.currencies.destroy', $currency) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete {{ $currency->code }} ({{ $currency->name }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-soft-danger btn-sm" title="Delete currency">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Conversion preview ───────────────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h4 class="card-title mb-0">Conversion Preview</h4></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="preview-eur">Enter a price in EUR</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" id="preview-eur" class="form-control"
                               value="100" step="0.01" min="0"
                               oninput="updatePreview()">
                    </div>
                </div>
                <table class="table table-sm mb-0">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Currency</th>
                            <th class="text-end">Converted price</th>
                        </tr>
                    </thead>
                    <tbody id="preview-body">
                        @foreach($currencies as $currency)
                        <tr>
                            <td>
                                <span class="badge bg-primary-subtle text-primary me-1">{{ $currency->code }}</span>
                                {{ $currency->name }}
                            </td>
                            <td class="text-end fw-medium" id="preview-{{ $currency->code }}"
                                data-rate="{{ (float) $currency->rate }}">
                                {{ $currency->code === 'EUR' ? '€' : ($currency->code === 'USD' ? '$' : $currency->code . ' ') }}{{ number_format(100 * (float)$currency->rate, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Add new currency ────────────────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h4 class="card-title mb-0">Add New Currency</h4></div>
            <div class="card-body">
                <form action="{{ route('admin.currencies.store') }}" method="POST">
                @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="new-code">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="new-code" name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="GBP" maxlength="3"
                                   style="text-transform:uppercase">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="new-name">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="new-name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="British Pound">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="new-rate">
                                Rate (1 EUR = X) <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="new-rate" name="rate"
                                   class="form-control @error('rate') is-invalid @enderror"
                                   value="{{ old('rate') }}"
                                   placeholder="0.860000" step="0.000001" min="0.000001">
                            @error('rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="new-regions">
                                Region codes
                                <span class="text-muted fw-normal fs-12">(ISO 3166-1 alpha-2, comma-separated)</span>
                            </label>
                            <input type="text" id="new-regions" name="region_codes"
                                   class="form-control @error('region_codes') is-invalid @enderror"
                                   value="{{ old('region_codes') }}"
                                   placeholder="GB, IM, JE, GG">
                            <div class="form-text">
                                Visitors from these countries will see this currency by default.
                                Leave blank for a manual-only currency.
                            </div>
                            @error('region_codes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1 fs-16"></iconify-icon>
                                Add Currency
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── How rates work info box ──────────────────────────────────────────── --}}
    <div class="col-12">
        <div class="alert alert-light border mb-0">
            <div class="row g-3 align-items-start">
                <div class="col-auto">
                    <iconify-icon icon="solar:info-circle-broken" class="fs-24 text-primary align-middle"></iconify-icon>
                </div>
                <div class="col">
                    <h5 class="mb-1">How currency rates work on FADÓ</h5>
                    <ul class="mb-0 fs-13 text-muted">
                        <li>All product prices are entered and stored in <strong>EUR</strong>.</li>
                        <li>When a visitor from a USD region lands on the site, displayed prices are multiplied by the USD rate shown above.</li>
                        <li>Rates are <strong>manual</strong> — update them here whenever exchange rates change. There is no live feed.</li>
                        <li>The default currency (EUR) always has a rate of 1.000000 and cannot be changed.</li>
                        <li>Region detection uses the visitor's IP address (via ip-api.com). Visitors can also manually switch currency on the storefront.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('script-bottom')
<script>
(function () {
    // ── Inline rate editing ───────────────────────────────────────────────────
    window.markDirty = function (id) {
        const input    = document.getElementById('rate-input-' + id);
        const saveBtn  = document.getElementById('rate-save-' + id);
        const cancelBtn = document.getElementById('rate-cancel-' + id);
        const isDirty  = input.value !== input.dataset.original;
        saveBtn.classList.toggle('d-none', !isDirty);
        cancelBtn.classList.toggle('d-none', !isDirty);
    };

    window.cancelRate = function (id) {
        const input    = document.getElementById('rate-input-' + id);
        const saveBtn  = document.getElementById('rate-save-' + id);
        const cancelBtn = document.getElementById('rate-cancel-' + id);
        input.value = input.dataset.original;
        saveBtn.classList.add('d-none');
        cancelBtn.classList.add('d-none');
    };

    // ── Conversion preview ────────────────────────────────────────────────────
    window.updatePreview = function () {
        const eur = parseFloat(document.getElementById('preview-eur').value) || 0;

        document.querySelectorAll('#preview-body td[data-rate]').forEach(function (cell) {
            const rate     = parseFloat(cell.dataset.rate);
            const code     = cell.id.replace('preview-', '');
            const symbol   = code === 'EUR' ? '€' : (code === 'USD' ? '$' : code + ' ');
            const amount   = (eur * rate).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            cell.textContent = symbol + amount;
        });
    };

    // ── Force uppercase on currency code input ────────────────────────────────
    document.getElementById('new-code').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
}());
</script>
@endsection
