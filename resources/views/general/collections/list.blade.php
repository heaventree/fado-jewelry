@extends('layouts.vertical', ['title' => 'Collections'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <h4 class="card-title flex-grow-1 mb-0">All Collections</h4>

                <form class="d-flex gap-2" method="GET" action="{{ route('admin.collections.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Search…" style="width:200px">
                    <button class="btn btn-sm btn-outline-secondary">Search</button>
                </form>

                <a href="{{ route('admin.collections.create') }}" class="btn btn-sm btn-primary">
                    <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1 fs-16"></iconify-icon>
                    Add Collection
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th style="width:64px">Banner</th>
                            <th>Collection</th>
                            <th>Banner Headline</th>
                            <th style="width:100px" class="text-center">Products</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($collections as $collection)
                        <tr>
                            <td>
                                <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center overflow-hidden">
                                    @if($collection->banner_image)
                                        <img src="{{ Storage::url($collection->banner_image) }}"
                                             alt="{{ $collection->name }}"
                                             class="avatar-md object-fit-cover">
                                        <x-file-size :path="$collection->banner_image" />
                                    @else
                                        <iconify-icon icon="solar:gallery-broken" class="fs-22 text-muted"></iconify-icon>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.collections.edit', $collection) }}"
                                   class="text-dark fw-medium fs-15">{{ $collection->name }}</a>
                                <p class="text-muted mb-0 mt-1 fs-12">/{{ $collection->slug }}</p>
                            </td>
                            <td>
                                @if($collection->banner_title)
                                    <span class="text-muted fs-13">{{ Str::limit($collection->banner_title, 60) }}</span>
                                @else
                                    <span class="text-muted fs-12 fst-italic">No headline set</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-medium">{{ $collection->products_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.collections.edit', $collection) }}"
                                       class="btn btn-soft-primary btn-sm" title="Edit">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-16"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST"
                                          onsubmit="return confirm('Delete collection &quot;{{ addslashes($collection->name) }}&quot;? Products will be detached but not deleted.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-soft-danger btn-sm" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No collections yet.
                                <a href="{{ route('admin.collections.create') }}">Add the first one.</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($collections->hasPages())
            <div class="card-footer border-top">
                {{ $collections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
