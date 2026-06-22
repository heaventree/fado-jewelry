@extends('shop.layouts.app')

@section('title', 'Account Settings — FADÓ Jewellery')
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">My Account</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Settings</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Account -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        @include('shop.account._sidebar', ['activeNav' => 'settings'])
                    </div>
                    <div class="col-xl-9">
                        <div class="my-account-content">
                            @if(session('profile_updated'))
                            <div class="alert alert-success mb-4">Profile updated successfully.</div>
                            @endif
                            @if(session('password_updated'))
                            <div class="alert alert-success mb-4">Password changed successfully.</div>
                            @endif
                            @if($errors->any())
                            <div class="mb-4">
                                @foreach($errors->all() as $error)
                                <p class="h6 text-danger">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                            <form class="form-change_pass" method="POST" action="{{ route('shop.account.profile.update') }}">
                                @csrf
                                @method('PATCH')
                                <div class="">
                                    <h2 class="account-title type-semibold">Account Setting</h2>
                                    <div class="form_content">
                                        <fieldset>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Full name *" required>
                                        </fieldset>
                                        <fieldset>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email *" required>
                                        </fieldset>
                                    </div>
                                    <button type="submit" class="btn-submit_form tf-btn animate-btn w-100 fw-bold">
                                        Save changes
                                    </button>
                                </div>
                            </form>
                            <form class="form-change_pass" method="POST" action="{{ route('shop.account.password.update') }}">
                                @csrf
                                @method('PATCH')
                                <div class="">
                                    <h2 class="account-title type-semibold">Change Password</h2>
                                    <div class="form_content site-change">
                                        <fieldset class="password-wrapper">
                                            <input class="password-field" type="password" name="current_password" placeholder="Current password *" required>
                                            <span class="toggle-pass icon-show-password"></span>
                                        </fieldset>
                                        <fieldset class="password-wrapper">
                                            <input class="password-field" type="password" name="password" placeholder="New password *" required>
                                            <span class="toggle-pass icon-show-password"></span>
                                        </fieldset>
                                        <fieldset class="password-wrapper">
                                            <input class="password-field" type="password" name="password_confirmation" placeholder="Confirm password *" required>
                                            <span class="toggle-pass icon-show-password"></span>
                                        </fieldset>
                                    </div>
                                    <button type="submit" class="btn-submit_form tf-btn animate-btn w-100 fw-bold">
                                        Save change
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection
