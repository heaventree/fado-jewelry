@extends('layouts.vertical', ['title' => 'Categories'])

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

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <h4 class="card-title flex-grow-1 mb-0">All Categories</h4>

                <form class="d-flex gap-2" method="GET" action="{{ route('admin.categories.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Search…" style="width:200px">
                    <button class="btn btn-sm btn-outline-secondary">Search</button>
                </form>

                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                    <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1 fs-16"></iconify-icon>
                    Add Category
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th style="width:64px">Banner</th>
                            <th>Category</th>
                            <th>Parent</th>
                            <th style="width:80px" class="text-center">Sort</th>
                            <th style="width:90px" class="text-center">Products</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center overflow-hidden">
                                    @if($category->banner_image)
                                        <img src="{{ Storage::url($category->banner_image) }}"
                                             alt="{{ $category->name }}"
                                             class="avatar-md object-fit-cover">
                                        <x-file-size :path="$category->banner_image" />
                                    @else
                                        <iconify-icon icon="solar:gallery-broken" class="fs-22 text-muted"></iconify-icon>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-dark fw-medium fs-15">{{ $category->name }}</a>
                                <p class="text-muted mb-0 mt-1 fs-12">/{{ $category->slug }}</p>
                                @if($category->banner_title)
                                    <p class="text-muted mb-0 fs-12 fst-italic">"{{ Str::limit($category->banner_title, 40) }}"</p>
                                @endif
                            </td>
                            <td>
                                @if($category->parent)
                                    <span class="badge bg-light text-dark">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-muted fs-12">Top level</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ $category->sort_order }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-medium">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="btn btn-soft-primary btn-sm" title="Edit">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-16"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Delete category \"{{ addslashes($category->name) }}\"?')">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                No categories yet.
                                <a href="{{ route('admin.categories.create') }}">Add the first one.</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="card-footer border-top">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
