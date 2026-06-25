@extends('layouts.vertical', ['title' => 'Add Slide'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Add Slide</h4>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.sliders._form')
            <button type="submit" class="btn btn-primary">Create Slide</button>
        </form>
    </div>
</div>

@endsection
