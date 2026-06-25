@extends('shop.layouts.app')

@php $storeName = \App\Models\Setting::get('store_name', 'FADÓ Jewellery'); @endphp

@section('title', 'Terms & Conditions — ' . $storeName)
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Terms & Conditions</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Terms & Conditions</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Content -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h2 class="account-title type-semibold mb-4">Terms & Conditions</h2>
                        <div class="h6 text-main">
                            {!! \App\Models\Setting::get('terms_conditions', '<p>Our terms and conditions are currently being updated. Please contact us for details.</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Content -->
@endsection
