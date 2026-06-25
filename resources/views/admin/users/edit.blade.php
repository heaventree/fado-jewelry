@extends('layouts.vertical', ['title' => 'Edit Admin User'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Edit Admin User</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.users._form', ['user' => $user])
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

@endsection
