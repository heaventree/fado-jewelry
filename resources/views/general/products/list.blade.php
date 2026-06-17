@extends('layouts.vertical', ['title' => 'Products'])

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
                <h4 class="card-title flex-grow-1 mb-0">All Products</h4>

                <form class="d-flex gap-2" method="GET" action="{{ route('admin.products.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Search products…" style="width:220px">
                    <select name="status" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                        <option value="">All status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Search</button>
                </form>

                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                    <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1 fs-16"></iconify-icon>
                    Add Product
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th style="width:56px">Image</th>
                            <th>Product</th>
                            <th>Variants</th>
                            <th>Price from</th>
                            <th>Categories</th>
                            <th style="width:80px">Status</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center overflow-hidden">
                                    @if($product->primaryImage)
                                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="avatar-md object-fit-cover">
                                    @else
                                        <iconify-icon icon="solar:gallery-broken" class="fs-24 text-muted"></iconify-icon>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-dark fw-medium fs-15">{{ $product->name }}</a>
                                <p class="text-muted mb-0 mt-1 fs-12">/{{ $product->slug }}</p>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">{{ $product->variants->count() }}</span>
                                <span class="text-muted fs-12"> variant{{ $product->variants->count() === 1 ? '' : 's' }}</span>
                            </td>
                            <td>
                                @if($product->lowest_price)
                                    <span class="text-dark fw-medium">€{{ number_format($product->lowest_price, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @foreach($product->categories->take(2) as $cat)
                                    <span class="badge bg-light text-dark me-1">{{ $cat->name }}</span>
                                @endforeach
                                @if($product->categories->count() > 2)
                                    <span class="text-muted fs-12">+{{ $product->categories->count() - 2 }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-soft-primary btn-sm" title="Edit">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-16"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
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
                            <td colspan="7" class="text-center py-4 text-muted">
                                No products found.
                                <a href="{{ route('admin.products.create') }}">Add the first product.</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="card-footer border-top">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
