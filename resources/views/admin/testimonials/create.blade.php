@extends('layouts.vertical', ['title' => 'Add Testimonial'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Add Testimonial</h4>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.testimonials.store') }}" method="POST">
            @csrf
            @include('admin.testimonials._form')
            <button type="submit" class="btn btn-primary">Create Testimonial</button>
        </form>
    </div>
</div>

@endsection
