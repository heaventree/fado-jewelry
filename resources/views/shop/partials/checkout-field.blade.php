<fieldset class="{{ $errors->has($name) ? 'has-error' : '' }}">
    <input type="{{ $type ?? 'text' }}"
           id="field_{{ $name }}"
           name="{{ $name }}"
           value="{{ $value ?? '' }}"
           placeholder="{{ $label }}{{ ($required ?? false) ? ' *' : '' }}"
           {{ ($required ?? false) ? 'required' : '' }}
           autocomplete="{{ $name === 'email' ? 'email' : ($name === 'name' ? 'name' : ($name === 'phone' ? 'tel' : 'on')) }}">
    @error($name)
    <p class="h6 text-danger mt-4">{{ $message }}</p>
    @enderror
</fieldset>
