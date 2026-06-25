@extends('layouts.vertical', ['title' => 'Edit Colour'])
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3"><h4 class="fw-semibold mb-0">Edit Colour</h4><a href="{{ route('admin.colours.index') }}" class="btn btn-outline-secondary btn-sm">Back</a></div>
<div class="card"><div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('admin.colours.update', $colour) }}" method="POST">@csrf @method('PUT')
        <div class="row g-3 mb-3">
            <div class="col-md-8"><label class="form-label fw-semibold">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $colour->name) }}" required maxlength="100"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Hex Code</label><input type="text" name="hex_code" class="form-control" value="{{ old('hex_code', $colour->hex_code) }}" maxlength="7" placeholder="#FF0000"></div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div></div>
@endsection
