@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', $page . ' — ' . Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">{{ $page }}</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">{{ $page }}</h6></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        <div class="text-center py-5">
            <p class="h6 text-up letter-space-2 text-main mb-20">Coming Soon</p>
            <h2 class="h2 fw-normal mb-16">{{ $page }}</h2>
            <p class="h6 text-main mb-32">This page is being prepared. Check back soon.</p>
            <a href="{{ route('shop.home') }}" class="tf-btn animate-btn">← Back to Home</a>
        </div>
    </div>
</section>

@endsection
