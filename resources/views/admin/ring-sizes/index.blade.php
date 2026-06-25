@extends('layouts.vertical', ['title' => 'Ring Sizes'])
@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Ring Sizes (US)</h4>
        <p class="text-muted mb-0 fs-13">Ring sizes are managed via the seeder. The sizes currently available are listed below.</p>
    </div>
</div>
<div class="card"><div class="card-body">
    <div class="d-flex flex-wrap gap-2">
        @foreach($sizes as $size)
        <span class="badge bg-primary-subtle text-primary fs-14 px-3 py-2">{{ number_format($size, $size == (int)$size ? 0 : 1) }}</span>
        @endforeach
    </div>
</div></div>
@endsection
