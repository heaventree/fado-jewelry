@extends('shop.layouts.app')

@php $storeName = \App\Models\Setting::get('store_name', 'FADÓ Jewellery'); @endphp

@section('title', ($page ?? 'Page') . ' — ' . $storeName)
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">{{ $page ?? 'Page' }}</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">{{ $page ?? 'Page' }}</h6>
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
                        @if(($page ?? '') === 'Privacy Policy')
                        <h2 class="account-title type-semibold mb-4">Privacy Policy</h2>
                        <div class="h6 text-main">
                            {!! \App\Models\Setting::get('privacy_policy', '<p class="mb-3"><strong>' . e($storeName) . '</strong> is committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal information when you visit our website or make a purchase.</p>
<h5 class="fw-semibold mt-4 mb-2">Information We Collect</h5>
<p class="mb-3">When you place an order or create an account, we collect your name, email address, shipping address, phone number, and payment details. We also collect browsing data via cookies to improve your experience.</p>
<h5 class="fw-semibold mt-4 mb-2">How We Use Your Information</h5>
<p class="mb-3">We use your information to process orders, deliver products, send order confirmations, respond to enquiries, and (with your consent) send marketing communications. We never sell your data to third parties.</p>
<h5 class="fw-semibold mt-4 mb-2">Cookies</h5>
<p class="mb-3">Our website uses cookies for essential functions (shopping cart, login sessions) and analytics. You can disable non-essential cookies in your browser settings.</p>
<h5 class="fw-semibold mt-4 mb-2">Data Retention</h5>
<p class="mb-3">We retain order data for 7 years as required by Irish law. Account data is kept until you request deletion.</p>
<h5 class="fw-semibold mt-4 mb-2">Your Rights</h5>
<p class="mb-3">Under GDPR, you have the right to access, correct, delete, or export your personal data. Contact us to exercise these rights.</p>
<h5 class="fw-semibold mt-4 mb-2">Security</h5>
<p class="mb-3">All transactions are encrypted via SSL. We never store your full card details on our servers.</p>') !!}
                        </div>
                        @else
                        <div class="text-center">
                            <h2 class="account-title type-semibold mb-4">Coming Soon</h2>
                            <p class="h6 text-main mb-4">This page is currently being prepared. Please check back soon.</p>
                            <a href="{{ route('shop.home') }}" class="tf-btn animate-btn">
                                Back to home
                                <i class="icon icon-arrow-right"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /Content -->
@endsection
