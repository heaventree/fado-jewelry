<div class="row g-3 mb-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Title *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required maxlength="300">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug ?? '') }}" maxlength="300" placeholder="Auto-generated from title">
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Excerpt</label>
        <textarea name="excerpt" class="form-control" rows="2" maxlength="1000">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Body *</label>
        <textarea name="body" class="form-control" rows="12" required>{{ old('body', $post->body ?? '') }}</textarea>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Featured Image</label>
        <input type="file" name="featured_image" class="form-control" accept="image/*">
        @if(!empty($post->featured_image))
        <div class="mt-2">
            <img src="{{ Storage::url($post->featured_image) }}" alt="Featured" class="rounded" style="max-height:120px">
            <x-file-size :path="$post->featured_image" />
        </div>
        @endif
    </div>
    <div class="col-md-2">
        @include('general.partials.image-quality-select')
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Author</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $post->author ?? '') }}" maxlength="100">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Publish Date</label>
        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
        <small class="text-muted">Leave blank = Draft</small>
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $post->sort_order ?? 0) }}" min="0">
    </div>
</div>
