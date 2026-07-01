{{--
    Shared "Image Quality" control — include in any admin form with a file
    upload. Applies to every image uploaded in that form submission.
--}}
<div class="mb-3">
    <label class="form-label" for="image_quality">Image Quality</label>
    <select id="image_quality" name="image_quality" class="form-select @error('image_quality') is-invalid @enderror">
        <option value="high" {{ old('image_quality') === 'high' ? 'selected' : '' }}>High quality (larger file)</option>
        <option value="balanced" {{ old('image_quality', 'balanced') === 'balanced' ? 'selected' : '' }}>Balanced (recommended)</option>
        <option value="small" {{ old('image_quality') === 'small' ? 'selected' : '' }}>Smaller file (more compression)</option>
    </select>
    <div class="form-text">Applies to any image uploaded above.</div>
    @error('image_quality')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
