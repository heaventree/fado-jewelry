@extends('layouts.vertical', ['title' => 'Metals'])
@section('content')
@if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
@if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <h4 class="fw-semibold mb-0">Metals</h4>
    <a href="{{ route('admin.metals.create') }}" class="btn btn-primary btn-sm"><iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>Add Metal</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover">
            <thead class="bg-light-subtle"><tr><th>Name</th><th style="width:100px">Variants</th><th style="width:120px">Actions</th></tr></thead>
            <tbody>
            @forelse($metals as $m)
            <tr>
                <td class="fw-semibold">{{ $m->name }}</td>
                <td>{{ $m->variants_count }}</td>
                <td>
                    <a href="{{ route('admin.metals.edit', $m) }}" class="btn btn-light btn-sm me-1"><iconify-icon icon="solar:pen-broken" class="fs-16"></iconify-icon></a>
                    <form action="{{ route('admin.metals.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this metal?')">@csrf @method('DELETE')<button class="btn btn-light btn-sm text-danger"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-16"></iconify-icon></button></form>
                </td>
            </tr>
            @empty<tr><td colspan="3" class="text-center py-4 text-muted">No metals.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
