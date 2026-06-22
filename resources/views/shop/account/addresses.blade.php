@extends('shop.layouts.app')

@section('title', 'My Address — FADÓ Jewellery')
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
                            <h6 class="current-page fw-normal">My address</h6>
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
                        @include('shop.account._sidebar', ['activeNav' => 'addresses'])
                    </div>
                    <div class="col-xl-9">
                        <div class="my-account-content">
                            <h2 class="account-title type-semibold">My Address</h2>
                            <div class="account-my_address">
                                @forelse($addresses as $addr)
                                <div class="account-address-item">
                                    <div class="address-item_content">
                                        <h4 class="address-title">{{ $loop->first ? 'Default' : 'Address' }}</h4>
                                        <div class="address-info">
                                            <h5 class="fw-semibold">{{ $addr['name'] ?? '' }}</h5>
                                            <p class="h6">{{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif</p>
                                            <p class="h6">{{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }}@endif {{ $addr['postcode'] ?? '' }}, {{ $addr['country'] ?? '' }}</p>
                                        </div>
                                        @if(!empty($addr['phone']))
                                        <div class="address-info">
                                            <h5 class="fw-semibold">Phone</h5>
                                            <p class="h6">{{ $addr['phone'] }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="h6 text-main">No saved addresses yet. Your shipping address will appear here after you place an order.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection
