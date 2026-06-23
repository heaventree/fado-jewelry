@extends('shop.layouts.app')

@php
    $storeName  = \App\Models\Setting::get('store_name', 'FADÓ Jewellery');
    $storePhone = \App\Models\Setting::get('store_phone');
    $storeEmail = \App\Models\Setting::get('store_email', 'info@fadojewellery.ie');
    $storeAddr  = \App\Models\Setting::get('store_address');
    $fbUrl      = \App\Models\Setting::get('facebook_url');
    $igUrl      = \App\Models\Setting::get('instagram_url');
    $twUrl      = \App\Models\Setting::get('twitter_url');
    $consultationEnabled = \App\Models\Setting::get('consultation_enabled', '1');
    $consultationIntro   = \App\Models\Setting::get('consultation_intro_text', 'We would love to hear from you. Whether you have a question about a piece, need help choosing the perfect gift, or want to book a consultation — our team is here to help.');
@endphp

@section('title', 'Contact Us — ' . $storeName)
@section('meta_description', 'Get in touch with ' . $storeName . '. Contact us by phone, email or book a consultation.')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Contact Us</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Contact Us</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Contact Us -->
        <section class="s-contact-us flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-5 offset-xxl-1 col-lg-6">
                        <div class="left-col mb-lg-0">
                            <h3 class="title fw-normal">Get In Touch</h3>
                            <ul class="store-info-list">
                                @if($storeAddr)
                                <li>
                                    <p class="h6 text-black fw-medium">Address:</p>
                                    <p class="text-main">{{ $storeAddr }}</p>
                                </li>
                                @endif
                                <li>
                                    <p class="h6 text-black fw-medium">Email:</p>
                                    <a href="mailto:{{ $storeEmail }}" class="link text-main">{{ $storeEmail }}</a>
                                </li>
                                @if($storePhone)
                                <li>
                                    <p class="h6 text-black fw-medium">Phone:</p>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $storePhone) }}" class="link text-main">{{ $storePhone }}</a>
                                </li>
                                @endif
                            </ul>
                            <ul class="tf-social-icon">
                                @if($fbUrl)
                                <li>
                                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener" class="social-facebook">
                                        <span class="icon"><i class="icon-fb"></i></span>
                                    </a>
                                </li>
                                @endif
                                @if($igUrl)
                                <li>
                                    <a href="{{ $igUrl }}" target="_blank" rel="noopener" class="social-instagram">
                                        <span class="icon"><i class="icon-instagram-logo"></i></span>
                                    </a>
                                </li>
                                @endif
                                @if($twUrl)
                                <li>
                                    <a href="{{ $twUrl }}" target="_blank" rel="noopener" class="social-x">
                                        <span class="icon"><i class="icon-x"></i></span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <div class="right-col">
                            <h3 class="title fw-normal">{{ $consultationEnabled ? 'Book a Consultation' : 'Send a Message' }}</h3>
                            <p class="sub-title text-main-4">
                                {{ $consultationIntro }}
                            </p>
                            @if(session('consultation_sent'))
                            <div class="alert alert-success">Thank you for your message. We will be in touch shortly.</div>
                            @endif
                            @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if($errors->any())
                            <div class="mb-4">
                                @foreach($errors->all() as $error)
                                <p class="h6 text-danger">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                            <form class="form-contact style-border" method="POST" action="{{ route('shop.contact.store') }}">
                                @csrf
                                <div class="form-content">
                                    <div class="cols tf-grid-layout sm-col-2">
                                        <fieldset>
                                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name *" required>
                                        </fieldset>
                                        <fieldset>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email *" required>
                                        </fieldset>
                                    </div>
                                    <fieldset>
                                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone (optional)">
                                    </fieldset>
                                    <fieldset>
                                        <select name="preferred_contact" class="w-100">
                                            <option value="email" {{ old('preferred_contact') === 'phone' ? '' : 'selected' }}>Prefer email reply</option>
                                            <option value="phone" {{ old('preferred_contact') === 'phone' ? 'selected' : '' }}>Prefer phone call</option>
                                        </select>
                                    </fieldset>
                                    <textarea name="message" placeholder="Your message *" style="height: 229px;" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="form_message text-center"></div>
                                <button type="submit" class="tf-btn btn-fill animate-btn w-100">
                                    SEND
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Contact Us -->
@endsection
