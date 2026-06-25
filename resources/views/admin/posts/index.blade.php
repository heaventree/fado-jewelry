@extends('layouts.vertical', ['title' => 'Blog Posts'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Blog Posts</h4>
        <p class="text-muted mb-0 fs-13">Manage blog posts for the front-end.</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add Post
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Title</th>
                    <th>Excerpt</th>
                    <th style="width:140px">Status</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td class="fw-medium">{{ $post->title }}</td>
                    <td class="text-muted">{{ Str::limit($post->excerpt, 50) }}</td>
                    <td>
                        @if($post->published_at)
                            <span class="badge bg-success-subtle text-success">{{ $post->published_at->format('d M Y') }}</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this post?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No blog posts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
