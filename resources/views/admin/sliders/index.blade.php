@extends('layouts.vertical', ['title' => 'Sliders'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Hero Sliders</h4>
        <p class="text-muted mb-0 fs-13">Manage homepage hero slider images.</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add Slide
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th style="width:80px">Image</th>
                    <th>Heading</th>
                    <th style="width:80px">Order</th>
                    <th style="width:80px">Active</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($sliders as $slider)
                <tr>
                    <td>
                        <img src="{{ Storage::url($slider->image) }}" alt="" class="rounded" style="width:60px;height:40px;object-fit:cover">
                        <x-file-size :path="$slider->image" />
                    </td>
                    <td>
                        <span class="fw-semibold">{{ $slider->heading }}</span>
                        @if($slider->subheading)
                            <p class="text-muted fs-12 mb-0">{{ Str::limit($slider->subheading, 50) }}</p>
                        @endif
                    </td>
                    <td>{{ $slider->sort_order }}</td>
                    <td>
                        <span class="badge bg-{{ $slider->active ? 'success' : 'secondary' }}-subtle text-{{ $slider->active ? 'success' : 'secondary' }}">
                            {{ $slider->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this slide?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No slides yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
