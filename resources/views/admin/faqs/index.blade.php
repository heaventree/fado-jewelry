@extends('layouts.vertical', ['title' => 'FAQs'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">FAQs</h4>
        <p class="text-muted mb-0 fs-13">Manage frequently asked questions.</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add FAQ
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Question</th>
                    <th style="width:80px">Order</th>
                    <th style="width:80px">Active</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($faqs as $faq)
                <tr>
                    <td>{{ Str::limit($faq->question, 80) }}</td>
                    <td>{{ $faq->sort_order }}</td>
                    <td>
                        <span class="badge bg-{{ $faq->active ? 'success' : 'secondary' }}-subtle text-{{ $faq->active ? 'success' : 'secondary' }}">
                            {{ $faq->active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this FAQ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No FAQs yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
