@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Profile — ' . Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<div style="background:var(--fado-deep-green); padding:40px 0 36px">
    <div class="container">
        <nav style="margin-bottom:8px">
            <ol class="d-flex gap-2 list-unstyled mb-0" style="font-size:.75rem">
                <li><a href="{{ route('shop.account.index') }}" style="color:rgba(255,255,255,.6); text-decoration:none">My Account</a></li>
                <li style="color:rgba(255,255,255,.4)">/</li>
                <li style="color:rgba(255,255,255,.9); font-weight:600">Profile</li>
            </ol>
        </nav>
        <h1 style="font-family:Georgia,serif; font-size:1.75rem; font-weight:400; color:#fff; margin:0">My Profile</h1>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:48px 0 80px; min-height:60vh">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('shop.account.partials.nav')
            </div>
            <div class="col-lg-9">

                {{-- ── Profile details form ────────────────────────────────────── --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px; margin-bottom:20px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:6px; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Personal Details
                    </h2>

                    @if(session('profile_updated'))
                    <div style="background:var(--fado-pale-mint); border:1.5px solid var(--fado-green-mid); border-radius:3px;
                                padding:12px 18px; margin-bottom:20px; font-size:.875rem; color:var(--fado-deep-green);
                                display:flex; align-items:center; gap:10px; margin-top:16px">
                        <i class="icon icon-check-circle" style="color:var(--fado-green-mid)"></i>
                        Profile updated successfully.
                    </div>
                    @endif

                    @if($errors->has('name') || $errors->has('email'))
                    <div style="background:#fff3f3; border:1px solid #f5c6c6; border-radius:3px;
                                padding:12px 18px; margin-bottom:20px; margin-top:16px">
                        <ul style="margin:0; padding-left:20px; color:#dc3545; font-size:.8125rem">
                            @error('name')<li>{{ $message }}</li>@enderror
                            @error('email')<li>{{ $message }}</li>@enderror
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('shop.account.profile.update') }}" style="margin-top:20px">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', [
                                    'name'     => 'name',
                                    'label'    => 'Full Name',
                                    'value'    => old('name', $user->name),
                                    'type'     => 'text',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', [
                                    'name'     => 'email',
                                    'label'    => 'Email Address',
                                    'value'    => old('email', $user->email),
                                    'type'     => 'email',
                                    'required' => true,
                                ])
                            </div>
                        </div>
                        <button type="submit"
                                style="padding:12px 28px; background:var(--fado-deep-green); color:#fff;
                                       border:none; border-radius:2px; font-size:.9rem; font-weight:600;
                                       cursor:pointer; transition:background .2s"
                                onmouseover="this.style.background='var(--fado-green-mid)'"
                                onmouseout="this.style.background='var(--fado-deep-green)'">
                            Save Changes
                        </button>
                    </form>
                </div>

                {{-- ── Change password form ────────────────────────────────────── --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px">
                    <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:0; padding-bottom:16px;
                               border-bottom:1px solid var(--fado-cream)">
                        Change Password
                    </h2>

                    @if(session('password_updated'))
                    <div style="background:var(--fado-pale-mint); border:1.5px solid var(--fado-green-mid); border-radius:3px;
                                padding:12px 18px; margin-bottom:0; font-size:.875rem; color:var(--fado-deep-green);
                                display:flex; align-items:center; gap:10px; margin-top:20px">
                        <i class="icon icon-check-circle" style="color:var(--fado-green-mid)"></i>
                        Password changed successfully.
                    </div>
                    @endif

                    @if($errors->has('current_password') || $errors->has('password'))
                    <div style="background:#fff3f3; border:1px solid #f5c6c6; border-radius:3px;
                                padding:12px 18px; margin-top:20px">
                        <ul style="margin:0; padding-left:20px; color:#dc3545; font-size:.8125rem">
                            @error('current_password')<li>{{ $message }}</li>@enderror
                            @error('password')<li>{{ $message }}</li>@enderror
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('shop.account.password.update') }}" style="margin-top:20px">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                @include('shop.partials.checkout-field', [
                                    'name'     => 'current_password',
                                    'label'    => 'Current Password',
                                    'value'    => '',
                                    'type'     => 'password',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-sm-4">
                                @include('shop.partials.checkout-field', [
                                    'name'     => 'password',
                                    'label'    => 'New Password',
                                    'value'    => '',
                                    'type'     => 'password',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-sm-4">
                                @include('shop.partials.checkout-field', [
                                    'name'     => 'password_confirmation',
                                    'label'    => 'Confirm New Password',
                                    'value'    => '',
                                    'type'     => 'password',
                                    'required' => true,
                                ])
                            </div>
                        </div>
                        <p style="font-size:.75rem; color:#888; margin-bottom:16px">
                            Minimum 8 characters, must include upper &amp; lowercase letters and a number.
                        </p>
                        <button type="submit"
                                style="padding:12px 28px; background:var(--fado-deep-green); color:#fff;
                                       border:none; border-radius:2px; font-size:.9rem; font-weight:600;
                                       cursor:pointer; transition:background .2s"
                                onmouseover="this.style.background='var(--fado-green-mid)'"
                                onmouseout="this.style.background='var(--fado-deep-green)'">
                            Update Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
