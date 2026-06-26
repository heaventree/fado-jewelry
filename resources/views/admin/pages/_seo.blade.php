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
                <label class="form-label fw-semibold">Page Title</label>
                <input type="text" name="{{ $prefix }}_meta_title" class="form-control"
                       value="{{ $settings[$prefix . '_meta_title'] ?? '' }}" maxlength="120"
                       placeholder="Custom title for this page (leave blank for default)">
                <div class="form-text">Overrides the default meta title for this page. Keep under 60 characters.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Meta Description</label>
                <textarea name="{{ $prefix }}_meta_description" class="form-control" rows="3"
                          maxlength="300"
                          placeholder="Custom description for this page (leave blank for default)">{{ $settings[$prefix . '_meta_description'] ?? '' }}</textarea>
                <div class="form-text">Overrides the default meta description. Keep under 160 characters.</div>
            </div>
            @if($hasOgImage ?? false)
            <div class="mb-0">
                <label class="form-label fw-semibold">OG Share Image</label>
                <input type="file" name="{{ $prefix }}_og_image_file" class="form-control" accept="image/*">
                @if(!empty($settings[$prefix . '_og_image']))
                    <img src="{{ asset('storage/' . $settings[$prefix . '_og_image']) }}" alt="OG Image" class="mt-2 rounded" style="max-height:120px">
                @endif
                <div class="form-text">Image shown when this page is shared on social media. Recommended: 1200x630px.</div>
            </div>
            @endif
        </div>
    </div>
</div>
