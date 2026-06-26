@extends('layouts.vertical', ['title' => 'Robots.txt'])

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
            <iconify-icon icon="solar:bug-bold-duotone" class="text-primary me-1"></iconify-icon>
            Robots.txt
        </h4>
        <p class="text-muted mb-0 fs-13">Controls which pages search engine crawlers can access.</p>
    </div>
    <a href="{{ url('/robots.txt') }}" target="_blank" class="btn btn-outline-primary">
        <iconify-icon icon="solar:eye-bold-duotone" class="me-1"></iconify-icon>
        View Live
    </a>
</div>

<form action="{{ route('admin.pages.robots.update') }}" method="POST">
@csrf

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">Edit robots.txt</h5></div>
    <div class="card-body">
        <textarea name="robots_txt" class="form-control" rows="20"
                  style="font-family:monospace; font-size:13px; line-height:1.6">{{ $robotsTxt }}</textarea>
        <div class="form-text mt-2">
            Live URL: <a href="{{ url('/robots.txt') }}" target="_blank">{{ url('/robots.txt') }}</a>
        </div>
    </div>
</div>

<div class="alert alert-warning fs-13 mb-4">
    <iconify-icon icon="solar:danger-triangle-broken" class="align-middle me-1"></iconify-icon>
    <strong>Warning:</strong> Incorrect robots.txt can block search engines from indexing your site. Only edit if you know what you're doing.
</div>

<button type="submit" class="btn btn-primary">
    <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
    Save Changes
</button>

</form>

@endsection
