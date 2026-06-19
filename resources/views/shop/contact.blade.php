@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'Contact Us — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Contact ' . Setting::get('store_name', 'FADÓ Jewellery') . ' — send a message or book a personal jewellery consultation.')
@section('canonical', route('shop.contact'))

@section('content')

{{-- Page Title — Ochaka s-page-title --}}
<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Contact Us</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Contact Us</h6></li>
            </ul>
        </div>
    </div>
</section>

{{-- Contact Us — Ochaka s-contact-us flat-spacing --}}
<section class="s-contact-us flat-spacing">

    <div class="container">
        <div class="row">

            {{-- Left — store info --}}
            <div class="col-xxl-5 offset-xxl-1 col-lg-6">
                <div class="left-col mb-lg-0">
                    <h3 class="title fw-normal">Visit Our Store</h3>

                    @if(session('success') || session('consultation_sent'))
                    <div class="alert-msg-success mb_20">
                        <p class="h6">{{ session('success', 'Your message has been sent — thank you! We\'ll be in touch shortly.') }}</p>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert-msg-error mb_20">
                        <p class="h6">{{ session('error') }}</p>
                    </div>
                    @endif

                    <ul class="store-info-list">
                        @if(Setting::get('store_address'))
                        <li>
                            <p class="h6 text-black fw-medium">Address:</p>
                            <p class="text-main">{!! nl2br(e(Setting::get('store_address'))) !!}</p>
                        </li>
                        @endif
                        @if(Setting::get('store_email'))
                        <li>
                            <p class="h6 text-black fw-medium">Email:</p>
                            <a href="mailto:{{ Setting::get('store_email') }}" class="link text-main">{{ Setting::get('store_email') }}</a>
                        </li>
                        @endif
                        @if(Setting::get('store_phone'))
                        <li>
                            <p class="h6 text-black fw-medium">Phone:</p>
                            <a href="tel:{{ preg_replace('/\s+/', '', Setting::get('store_phone')) }}" class="link text-main">{{ Setting::get('store_phone') }}</a>
                        </li>
                        @endif
                        <li>
                            <p class="h6 text-black fw-medium">Opening Hours:</p>
                            <p class="text-main">
                                Mon – Sat: 9am to 5:30pm<br>
                                Sunday: Closed
                            </p>
                        </li>
                    </ul>

                    @php
                        $fb = Setting::get('facebook_url');
                        $ig = Setting::get('instagram_url');
                        $tw = Setting::get('twitter_url');
                    @endphp
                    @if($fb || $ig || $tw)
                    <ul class="tf-social-icon">
                        @if($fb)
                        <li><a href="{{ $fb }}" target="_blank" rel="noopener" class="social-facebook"><span class="icon"><i class="icon-fb"></i></span></a></li>
                        @endif
                        @if($ig)
                        <li><a href="{{ $ig }}" target="_blank" rel="noopener" class="social-instagram"><span class="icon"><i class="icon-instagram-logo"></i></span></a></li>
                        @endif
                        @if($tw)
                        <li><a href="{{ $tw }}" target="_blank" rel="noopener" class="social-x"><span class="icon"><i class="icon-x"></i></span></a></li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>

            {{-- Right — contact form --}}
            <div class="col-xl-5 col-lg-6" id="contact">
                <div class="right-col">
                    <h3 class="title fw-normal">Get In Touch</h3>
                    <p class="sub-title text-main-4">
                        Have a question about a piece, need help with an order, or want to book a consultation? We'd love to hear from you.
                    </p>

                    @if($errors->any())
                    <div class="alert-msg-error mb_20">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)<li class="h6">{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form class="form-contact style-border" method="POST" action="{{ route('shop.contact.store') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="contact">
                        <div class="form-content">
                            <div class="cols tf-grid-layout sm-col-2">
                                <fieldset>
                                    <input type="text" name="name" placeholder="Name *" value="{{ old('name') }}" required>
                                </fieldset>
                                <fieldset>
                                    <input type="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                                </fieldset>
                            </div>
                            <fieldset>
                                <input type="tel" name="phone" placeholder="Phone (optional)" value="{{ old('phone') }}">
                            </fieldset>
                            <textarea name="message" placeholder="Message *" style="height:180px;" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="form_message text-center"></div>
                        <button type="submit" class="tf-btn btn-fill animate-btn w-100">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Consultation Form — only shown if enabled --}}
@if(Setting::get('consultation_enabled', '1'))
<section class="flat-spacing bg-rose-white" id="consultation">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="text-center mb_30">
                    <h2 class="title">Book a Consultation</h2>
                    <p class="sub-title text-main-4">
                        {{ Setting::get('consultation_intro_text', 'Book a private consultation with our jewellery specialists — in-store, by phone, or video call.') }}
                    </p>
                </div>

                <form class="form-contact style-border" method="POST" action="{{ route('shop.contact.store') }}">
                    @csrf
                    <input type="hidden" name="form_type" value="consultation">
                    <div class="form-content">
                        <div class="cols tf-grid-layout sm-col-2">
                            <fieldset>
                                <input type="text" name="name" placeholder="Name *" value="{{ old('name') }}" required>
                            </fieldset>
                            <fieldset>
                                <input type="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                            </fieldset>
                        </div>
                        <fieldset>
                            <input type="tel" name="phone" placeholder="Phone (optional)" value="{{ old('phone') }}">
                        </fieldset>
                        <textarea name="message" placeholder="What are you looking for? *" style="height:180px;" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="form_message text-center"></div>
                    <button type="submit" class="tf-btn btn-fill animate-btn w-100">
                        Request Consultation
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
