@extends('layouts.vertical', ['title' => 'Add FAQ'])

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Add FAQ</h4>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            @include('admin.faqs._form')
            <button type="submit" class="btn btn-primary">Create FAQ</button>
        </form>
    </div>
</div>

@endsection
