@extends('layouts.vertical', ['title' => 'FAQs'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- FAQ Page Settings --}}
<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">FAQ Page Settings</h5></div>
    <div class="card-body">
        <form action="{{ route('admin.faqs.settings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Sidebar Banner Image</label>
                <input type="file" name="faq_banner_image_file" class="form-control" accept="image/*">
                @if($faqBannerImage)
                    <img src="{{ asset('storage/' . $faqBannerImage) }}" alt="FAQ Banner" class="mt-2 rounded" style="max-height:120px">
                @endif
            </div>
            <hr>
            <p class="fw-semibold text-muted fs-13 mb-2">SEO Settings</p>
            <div class="mb-3">
                <label class="form-label fw-semibold">Page Title</label>
                <input type="text" name="faq_meta_title" class="form-control" value="{{ $faqMetaTitle ?? '' }}" maxlength="120" placeholder="Custom title (leave blank for default)">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Meta Description</label>
                <textarea name="faq_meta_description" class="form-control" rows="3" maxlength="300" placeholder="Custom description (leave blank for default)">{{ $faqMetaDescription ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
                Save Settings
            </button>
        </form>
    </div>
</div>

{{-- FAQ Q&A Management --}}
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
