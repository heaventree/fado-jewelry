@extends('layouts.vertical', ['title' => 'Admin Users'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Admin Users</h4>
        <p class="text-muted mb-0 fs-13">Manage admin panel users and roles.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <iconify-icon icon="solar:add-circle-broken" class="align-middle me-1"></iconify-icon>
        Add Admin User
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th style="width:120px">Role</th>
                    <th style="width:120px">Joined</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="fw-medium">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge bg-{{ $role->name === 'super_admin' ? 'danger' : ($role->name === 'store_admin' ? 'primary' : 'info') }}-subtle text-{{ $role->name === 'super_admin' ? 'danger' : ($role->name === 'store_admin' ? 'primary' : 'info') }}">
                            {{ str_replace('_', ' ', $role->name) }}
                        </span>
                        @endforeach
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-light btn-sm me-1" title="Edit">
                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-16"></iconify-icon>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this admin user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger" title="Delete">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-16"></iconify-icon>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No admin users.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
