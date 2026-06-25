@php $u = $user ?? null; @endphp

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $u?->name) }}" required maxlength="255">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
        <input type="text" name="username" class="form-control" value="{{ old('username', $u?->username) }}" required maxlength="100">
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $u?->email) }}" required maxlength="255">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select" required>
            @php $currentRole = old('role', $u?->roles->first()?->name ?? 'staff'); @endphp
            <option value="super_admin" @selected($currentRole === 'super_admin')>Super Admin</option>
            <option value="store_admin" @selected($currentRole === 'store_admin')>Store Admin</option>
            <option value="staff" @selected($currentRole === 'staff')>Staff</option>
        </select>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Password @if(!$u)<span class="text-danger">*</span>@endif</label>
        <input type="password" name="password" class="form-control" {{ $u ? '' : 'required' }} minlength="8" autocomplete="new-password">
        @if($u)<small class="text-muted">Leave blank to keep current password.</small>@endif
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Confirm Password @if(!$u)<span class="text-danger">*</span>@endif</label>
        <input type="password" name="password_confirmation" class="form-control" {{ $u ? '' : 'required' }} autocomplete="new-password">
    </div>
</div>
