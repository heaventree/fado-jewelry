@extends('layouts.vertical', ['title' => 'Edit Gemstone'])
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3"><h4 class="fw-semibold mb-0">Edit Gemstone</h4><a href="{{ route('admin.gemstones.index') }}" class="btn btn-outline-secondary btn-sm">Back</a></div>
<div class="card"><div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.gemstones.update', $gemstone) }}" method="POST">@csrf @method('PUT')
        <div class="mb-3"><label class="form-label fw-semibold">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $gemstone->name) }}" required maxlength="100"></div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div></div>
@endsection
