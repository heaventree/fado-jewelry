@extends('layouts.vertical', ['title' => 'Create Menu'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Create Menu</h4>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Location</label>
                    <select name="location" class="form-select">
                        <option value="">None</option>
                        <option value="header" @selected(old('location') === 'header')>Header</option>
                        <option value="footer_col_1" @selected(old('location') === 'footer_col_1')>Footer Column 1 (Shopping)</option>
                        <option value="footer_col_2" @selected(old('location') === 'footer_col_2')>Footer Column 2 (Information)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Menu</button>
        </form>
    </div>
</div>

@endsection
