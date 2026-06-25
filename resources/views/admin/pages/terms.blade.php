@extends('layouts.vertical', ['title' => 'Terms & Conditions'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <iconify-icon icon="solar:check-circle-broken" class="align-middle me-1"></iconify-icon>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="fw-semibold mb-1">
            <iconify-icon icon="solar:document-bold-duotone" class="text-primary me-1"></iconify-icon>
            Terms & Conditions
        </h4>
        <p class="text-muted mb-0 fs-13">Edit the Terms & Conditions page content. HTML is supported.</p>
    </div>
</div>

<form action="{{ route('admin.pages.terms.update') }}" method="POST">
@csrf

<div class="card mb-4">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Terms & Conditions</label>
            <textarea name="terms_conditions" class="form-control" rows="15">{{ $settings['terms_conditions'] }}</textarea>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
