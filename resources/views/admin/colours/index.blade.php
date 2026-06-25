@extends('layouts.vertical', ['title' => 'Colours'])
@section('content')
@if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <h4 class="fw-semibold mb-0">Colours</h4>
    <a href="{{ route('admin.colours.create') }}" class="btn btn-primary btn-sm"><iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>Add Colour</a>
</div>
<div class="card"><div class="table-responsive">
    <table class="table align-middle mb-0 table-hover">
        <thead class="bg-light-subtle"><tr><th>Name</th><th style="width:80px">Colour</th><th style="width:120px">Actions</th></tr></thead>
        <tbody>
        @forelse($colours as $c)
        <tr>
            <td class="fw-semibold">{{ $c->name }}</td>
            <td>@if($c->hex_code)<span style="display:inline-block;width:24px;height:24px;border-radius:4px;background:{{ $c->hex_code }};border:1px solid #ddd"></span>@else —@endif</td>
            <td>
                <a href="{{ route('admin.colours.edit', $c) }}" class="btn btn-light btn-sm me-1"><iconify-icon icon="solar:pen-broken" class="fs-16"></iconify-icon></a>
                <form action="{{ route('admin.colours.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-light btn-sm text-danger"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-16"></iconify-icon></button></form>
            </td>
        </tr>
        @empty<tr><td colspan="3" class="text-center py-4 text-muted">No colours.</td></tr>@endforelse
        </tbody>
    </table>
</div></div>
@endsection
