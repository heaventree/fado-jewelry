@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Profile — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Account Settings</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('shop.account.index') }}" class="h6 link">My Account</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">Settings</h6></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 d-none d-xl-block">
                @include('shop.account.partials.nav')
            </div>
            <div class="col-xl-9">
                <div class="my-account-content">

                    {{-- Profile details --}}
                    <form class="form-change_pass" method="POST" action="{{ route('shop.account.profile.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-40">
                            <h2 class="account-title type-semibold">Personal Details</h2>

                            @if(session('profile_updated'))
                            <div class="tf-notice-wrap mb-16">
                                <p class="h6"><i class="icon icon-check-circle me-8"></i> Profile updated successfully.</p>
                            </div>
                            @endif

                            @if($errors->has('name') || $errors->has('email'))
                            <div class="tf-notice-wrap mb-16" style="border-color:#dc3545">
                                <ul class="mb-0 ps-16">
                                    @error('name')<li class="h6 text-danger">{{ $message }}</li>@enderror
                                    @error('email')<li class="h6 text-danger">{{ $message }}</li>@enderror
                                </ul>
                            </div>
                            @endif

                            <div class="form_content">
                                <div class="cols tf-grid-layout sm-col-2">
                                    <fieldset>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                               placeholder="Full Name *" required autocomplete="name">
                                        @error('name')<p class="h6 text-danger mt-4">{{ $message }}</p>@enderror
                                    </fieldset>
                                    <fieldset>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                               placeholder="Email Address *" required autocomplete="email">
                                        @error('email')<p class="h6 text-danger mt-4">{{ $message }}</p>@enderror
                                    </fieldset>
                                </div>
                            </div>

                            <button type="submit" class="tf-btn animate-btn">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    {{-- Change password --}}
                    <form class="form-change_pass" method="POST" action="{{ route('shop.account.password.update') }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <h2 class="account-title type-semibold">Change Password</h2>

                            @if(session('password_updated'))
                            <div class="tf-notice-wrap mb-16">
                                <p class="h6"><i class="icon icon-check-circle me-8"></i> Password changed successfully.</p>
                            </div>
                            @endif

                            @if($errors->has('current_password') || $errors->has('password'))
                            <div class="tf-notice-wrap mb-16" style="border-color:#dc3545">
                                <ul class="mb-0 ps-16">
                                    @error('current_password')<li class="h6 text-danger">{{ $message }}</li>@enderror
                                    @error('password')<li class="h6 text-danger">{{ $message }}</li>@enderror
                                </ul>
                            </div>
                            @endif

                            <div class="form_content site-change">
                                <fieldset class="password-wrapper">
                                    <input class="password-field" type="password" name="current_password"
                                           placeholder="Current password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                                <fieldset class="password-wrapper">
                                    <input class="password-field" type="password" name="password"
                                           placeholder="New password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                                <fieldset class="password-wrapper">
                                    <input class="password-field" type="password" name="password_confirmation"
                                           placeholder="Confirm new password *" required>
                                    <span class="toggle-pass icon-show-password"></span>
                                </fieldset>
                            </div>
                            <p class="h6 text-main mb-20">Minimum 8 characters, must include upper &amp; lowercase letters and a number.</p>
                            <button type="submit" class="btn-submit_form tf-btn animate-btn fw-bold">
                                Update Password
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
