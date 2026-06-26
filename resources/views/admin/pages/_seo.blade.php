@php $prefix = $seoPrefix ?? ''; @endphp

<div class="card mb-4">
    <div class="card-header" role="button" data-bs-toggle="collapse" data-bs-target="#seoSettings" aria-expanded="false">
        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <iconify-icon icon="solar:global-bold-duotone" class="text-primary fs-18"></iconify-icon>
            SEO Settings
            <iconify-icon icon="solar:alt-arrow-down-linear" class="ms-auto fs-16 text-muted"></iconify-icon>
        </h5>
    </div>
    <div class="collapse" id="seoSettings">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">SEO Title</label>
                <input type="text" name="{{ $prefix }}_meta_title" class="form-control"
                       value="{{ $settings[$prefix . '_meta_title'] ?? '' }}" maxlength="120"
                       placeholder="Custom title tag — leave blank to use default">
                <div class="form-text">Recommended: 50-60 characters</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Meta Description</label>
                <textarea name="{{ $prefix }}_meta_description" class="form-control seo-meta-desc" rows="3"
                          maxlength="300"
                          placeholder="Custom meta description — leave blank to use default">{{ $settings[$prefix . '_meta_description'] ?? '' }}</textarea>
                <div class="form-text">Recommended: 150-160 characters. <span class="seo-char-count text-muted">0</span>/160</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Focus Keyword</label>
                <input type="text" name="{{ $prefix }}_focus_keyword" class="form-control"
                       value="{{ $settings[$prefix . '_focus_keyword'] ?? '' }}"
                       placeholder="Primary keyword e.g. Irish Claddagh Rings">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Secondary Keywords</label>
                <input type="text" name="{{ $prefix }}_keywords" class="form-control"
                       value="{{ $settings[$prefix . '_keywords'] ?? '' }}"
                       placeholder="keyword1, keyword2, keyword3">
                <div class="form-text">Comma separated</div>
            </div>
            @if($hasOgImage ?? false)
            <div class="mb-3">
                <label class="form-label fw-semibold">OG Share Image</label>
                <input type="file" name="{{ $prefix }}_og_image_file" class="form-control" accept="image/*">
                @if(!empty($settings[$prefix . '_og_image']))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $settings[$prefix . '_og_image']) }}" alt="OG Image" class="rounded" style="max-height:120px">
                        <x-file-size :path="$settings[$prefix . '_og_image']" />
                    </div>
                @endif
                <div class="form-text">Recommended: 1200x630px</div>
            </div>
            @endif
            <div class="mb-3">
                <label class="form-label fw-semibold">Robots</label>
                <select name="{{ $prefix }}_robots" class="form-select">
                    @php $currentRobots = $settings[$prefix . '_robots'] ?? 'index, follow'; @endphp
                    <option value="index, follow" {{ $currentRobots === 'index, follow' ? 'selected' : '' }}>index, follow (default)</option>
                    <option value="noindex, follow" {{ $currentRobots === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                    <option value="index, nofollow" {{ $currentRobots === 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                    <option value="noindex, nofollow" {{ $currentRobots === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                </select>
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Canonical URL</label>
                <input type="url" name="{{ $prefix }}_canonical" class="form-control"
                       value="{{ $settings[$prefix . '_canonical'] ?? '' }}"
                       placeholder="https://yourdomain.com/page — leave blank for auto">
                <div class="form-text">Only set if this page has a canonical URL different from its own URL</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.seo-meta-desc').forEach(function(ta) {
        var counter = ta.closest('.mb-3').querySelector('.seo-char-count');
        if (!counter) return;
        function update() { counter.textContent = ta.value.length; }
        ta.addEventListener('input', update);
        update();
    });
});
</script>
