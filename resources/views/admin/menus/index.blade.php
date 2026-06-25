@extends('layouts.vertical', ['title' => 'Menus'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Menus</h4>
        <p class="text-muted mb-0 fs-13">Manage navigation menus for header and footer.</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Create Menu
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Name</th>
                    <th style="width:150px">Location</th>
                    <th style="width:80px">Items</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($menus as $menu)
                <tr>
                    <td class="fw-medium">{{ $menu->name }}</td>
                    <td>
                        @if($menu->location)
                            <span class="badge bg-primary-subtle text-primary">{{ str_replace('_', ' ', $menu->location) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $menu->items_count }}</td>
                    <td>
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this menu and all its items?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No menus yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
