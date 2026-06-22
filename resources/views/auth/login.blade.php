@extends('shop.layouts.app')

@section('title', 'Login — FADÓ Jewellery')
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Login</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Login</h6>
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
                        <h1 class="heading">Login</h1>
                        <form class="form-login" method="POST" action="{{ route('login') }}">
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
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address *" required>
                                </fieldset>
                                <fieldset class="password-wrapper mb-8">
                                    <input class="password-field" type="password" name="password" placeholder="Password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                                <div class="check-bottom">
                                    <div class="checkbox-wrap">
                                        <input id="remember" type="checkbox" name="remember" class="tf-check">
                                        <label for="remember" class="h6">Keep me signed in</label>
                                    </div>
                                    <h6>
                                        <a href="{{ route('password.request') }}" class="link">
                                            Forgot your password?
                                        </a>
                                    </h6>
                                </div>
                            </div>
                            <button id="btnLogin" type="submit" class="tf-btn animate-btn w-100">
                                Login
                            </button>
                        </form>
                    </div>
                    <div class="col-right">
                        <h1 class="heading">New Customer</h1>
                        <p class="h6 text-sub">
                            Create an account with FADÓ Jewellery to save your wishlist, track orders, and enjoy a faster checkout experience.
                        </p>
                        <a href="{{ route('register') }}" class="btn_log tf-btn animate-btn">
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Login -->
@endsection
