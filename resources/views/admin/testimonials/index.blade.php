@extends('layouts.vertical', ['title' => 'Testimonials'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Testimonials</h4>
        <p class="text-muted mb-0 fs-13">Manage customer reviews shown on the homepage.</p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add Testimonial
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Name</th>
                    <th>Review (preview)</th>
                    <th style="width:80px">Rating</th>
                    <th style="width:80px">Order</th>
                    <th style="width:80px">Active</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($testimonials as $t)
                <tr>
                    <td>
                        <span class="fw-semibold">{{ $t->name }}</span>
                        @if($t->location)<p class="text-muted fs-12 mb-0">{{ $t->location }}</p>@endif
                    </td>
                    <td>{{ Str::limit($t->body, 80) }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="icon-star {{ $i <= $t->rating ? 'text-warning' : 'text-muted' }}" style="font-size:12px"></i>
                        @endfor
                    </td>
                    <td>{{ $t->sort_order }}</td>
                    <td>
                        <span class="badge bg-{{ $t->active ? 'success' : 'secondary' }}-subtle text-{{ $t->active ? 'success' : 'secondary' }}">
                            {{ $t->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this testimonial?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No testimonials yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
