@extends('layouts.vertical', ['title' => 'Gemstones'])
@section('content')
@if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
@if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <h4 class="fw-semibold mb-0">Gemstones</h4>
    <a href="{{ route('admin.gemstones.create') }}" class="btn btn-primary btn-sm"><iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>Add Gemstone</a>
</div>
<div class="card"><div class="table-responsive">
    <table class="table align-middle mb-0 table-hover">
        <thead class="bg-light-subtle"><tr><th>Name</th><th style="width:100px">Variants</th><th style="width:120px">Actions</th></tr></thead>
        <tbody>
        @forelse($gemstones as $g)
        <tr>
            <td class="fw-semibold">{{ $g->name }}</td><td>{{ $g->variants_count }}</td>
            <td>
                <a href="{{ route('admin.gemstones.edit', $g) }}" class="btn btn-light btn-sm me-1"><iconify-icon icon="solar:pen-broken" class="fs-16"></iconify-icon></a>
                <form action="{{ route('admin.gemstones.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-light btn-sm text-danger"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-16"></iconify-icon></button></form>
            </td>
        </tr>
        @empty<tr><td colspan="3" class="text-center py-4 text-muted">No gemstones.</td></tr>@endforelse
        </tbody>
    </table>
</div></div>
@endsection
