<div>
    <label for="field_{{ $name }}"
           style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                  text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:6px">
        {{ $label }}
        @if($required ?? false)<span style="color:#dc3545"> *</span>@endif
    </label>
    <input type="{{ $type ?? 'text' }}"
           id="field_{{ $name }}"
           name="{{ $name }}"
           value="{{ $value ?? '' }}"
           {{ ($required ?? false) ? 'required' : '' }}
           autocomplete="{{ $name === 'email' ? 'email' : ($name === 'name' ? 'name' : ($name === 'phone' ? 'tel' : 'on')) }}"
           style="width:100%; padding:11px 14px;
                  border:1px solid {{ $errors->has($name) ? '#dc3545' : 'var(--fado-warm-grey)' }};
                  border-radius:3px; font-size:.9375rem; color:var(--fado-deep-green);
                  background:#fff; outline:none; transition:border-color .2s"
           onfocus="this.style.borderColor='var(--fado-green-mid)'"
           onblur="this.style.borderColor='{{ $errors->has($name) ? '#dc3545' : 'var(--fado-warm-grey)' }}'">
    @error($name)
    <p style="font-size:.75rem; color:#dc3545; margin-top:4px; margin-bottom:0">{{ $message }}</p>
    @enderror
</div>
