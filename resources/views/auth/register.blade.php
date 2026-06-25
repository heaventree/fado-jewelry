@extends('shop.layouts.app')

@section('title', 'Register — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Register</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Register</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Login -->
        <section class="flat-spacing">
            <div class="container">
                <div class="s-log">
                    <div class="col-left">
                        <h1 class="heading">Register</h1>
                        <form class="form-login" method="POST" action="{{ route('register') }}">
                            @csrf
                            @if ($errors->any())
                                <div class="mb-16">
                                    @foreach ($errors->all() as $error)
                                        <p class="h6 text-danger">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <div class="list-ver">
                                <fieldset>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name *" required>
                                </fieldset>
                                <fieldset>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address *" required>
                                </fieldset>
                                <fieldset class="password-wrapper">
                                    <input class="password-field" type="password" name="password" placeholder="Password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                                <fieldset class="password-wrapper">
                                    <input class="password-field" type="password" name="password_confirmation" placeholder="Confirm password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                            </div>
                            <button type="submit" class="tf-btn animate-btn w-100">
                                Register
                            </button>
                        </form>
                    </div>
                    <div class="col-right">
                        <h1 class="heading">Have An Account</h1>
                        <p class="h6 text-sub">
                            Welcome back, log in to your account to enhance your shopping experience, receive coupons, and the best discount codes.
                        </p>
                        <a href="{{ route('login') }}" class="btn_log tf-btn animate-btn">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Login -->
@endsection
