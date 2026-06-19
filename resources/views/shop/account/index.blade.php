@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'My Account — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">My Account</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><h6 class="current-page fw-normal">My account</h6></li>
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

                {{-- Quick stats --}}
                <div class="row g-3 mb-4">
                    @foreach([
                        ['icon' => 'icon-package',    'value' => $orderCount, 'label' => 'Total Orders'],
                        ['icon' => 'icon-heart',      'value' => session('wishlist_count', 0), 'label' => 'Wishlist Items'],
                        ['icon' => 'icon-user-circle','value' => $user->created_at->format('M Y'), 'label' => 'Member Since'],
                    ] as $stat)
                    <div class="col-sm-4">
                        <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                                    padding:20px 24px; display:flex; align-items:center; gap:16px">
                            <div style="width:44px; height:44px; background:var(--fado-cream); border-radius:50%;
                                        display:flex; align-items:center; justify-content:center; flex-shrink:0">
                                <i class="icon {{ $stat['icon'] }}" style="font-size:1.125rem; color:var(--fado-deep-green)"></i>
                            </div>
                            <div>
                                <p style="font-size:1.25rem; font-weight:700; color:var(--fado-deep-green); margin:0; line-height:1.1">
                                    {{ $stat['value'] }}
                                </p>
                                <p style="font-size:.75rem; color:#888; margin:0; margin-top:2px">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Recent orders --}}
                <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; overflow:hidden; margin-bottom:20px">
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px;
                                border-bottom:1px solid var(--fado-cream)">
                        <h2 style="font-family:Georgia,serif; font-size:1.125rem; font-weight:400;
                                   color:var(--fado-deep-green); margin:0">Recent Orders</h2>
                        <a href="{{ route('shop.account.orders') }}"
                           style="font-size:.8125rem; color:var(--fado-green-mid); text-decoration:none; font-weight:600">
                            View all →
                        </a>
                    </div>

                    @if($recentOrders->isEmpty())
                    <div style="padding:40px 24px; text-align:center">
                        <i class="icon icon-package" style="font-size:2.5rem; color:var(--fado-warm-grey); display:block; margin-bottom:12px; opacity:.5"></i>
                        <p style="color:#888; font-size:.875rem; margin-bottom:20px">You haven't placed any orders yet.</p>
                        <a href="{{ route('shop.jewellery') }}"
                           style="display:inline-block; padding:11px 28px; background:var(--fado-deep-green);
                                  color:#fff; text-decoration:none; border-radius:2px; font-size:.875rem; font-weight:600">
                            Browse Jewellery
                        </a>
                    </div>
                    @else
                    <table style="width:100%; border-collapse:collapse; font-size:.875rem">
                        <thead>
                            <tr style="background:var(--fado-cream)">
                                <th style="padding:10px 24px; text-align:left; font-size:.7rem; font-weight:700;
                                           letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Order</th>
                                <th style="padding:10px 16px; text-align:left; font-size:.7rem; font-weight:700;
                                           letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Date</th>
                                <th style="padding:10px 16px; text-align:left; font-size:.7rem; font-weight:700;
                                           letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Status</th>
                                <th style="padding:10px 24px; text-align:right; font-size:.7rem; font-weight:700;
                                           letter-spacing:.1em; text-transform:uppercase; color:var(--fado-warm-grey)">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr style="border-top:1px solid var(--fado-cream)">
                                <td style="padding:14px 24px">
                                    <a href="{{ route('shop.account.order', $order) }}"
                                       style="font-weight:600; color:var(--fado-deep-green); text-decoration:none; font-family:Georgia,serif">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="padding:14px 16px; color:#666">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td style="padding:14px 16px">
                                    @php
                                        $colours = ['pending'=>'#f59e0b','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#10b981','cancelled'=>'#ef4444','refunded'=>'#6b7280'];
                                        $colour  = $colours[$order->status] ?? '#6b7280';
                                    @endphp
                                    <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:.7rem;
                                                 font-weight:700; background:{{ $colour }}18; color:{{ $colour }}; letter-spacing:.03em">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td style="padding:14px 24px; text-align:right; font-weight:600; color:var(--fado-deep-green)">
                                    {{ $order->currency_symbol }}{{ number_format((float)$order->total, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                {{-- Quick links --}}
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('shop.wishlist') }}"
                           style="display:flex; align-items:center; gap:12px; padding:16px 20px;
                                  background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                                  text-decoration:none; color:var(--fado-deep-green); transition:box-shadow .2s"
                           onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'"
                           onmouseout="this.style.boxShadow='none'">
                            <i class="icon icon-heart" style="font-size:1.25rem; color:var(--fado-green-mid)"></i>
                            <div>
                                <p style="font-weight:600; font-size:.9rem; margin:0">My Wishlist</p>
                                <p style="font-size:.75rem; color:#888; margin:0">Saved pieces</p>
                            </div>
                            <i class="icon icon-caret-right" style="margin-left:auto; color:var(--fado-warm-grey)"></i>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('shop.account.profile') }}"
                           style="display:flex; align-items:center; gap:12px; padding:16px 20px;
                                  background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                                  text-decoration:none; color:var(--fado-deep-green); transition:box-shadow .2s"
                           onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'"
                           onmouseout="this.style.boxShadow='none'">
                            <i class="icon icon-user-circle" style="font-size:1.25rem; color:var(--fado-green-mid)"></i>
                            <div>
                                <p style="font-weight:600; font-size:.9rem; margin:0">Edit Profile</p>
                                <p style="font-size:.75rem; color:#888; margin:0">Name, email, password</p>
                            </div>
                            <i class="icon icon-caret-right" style="margin-left:auto; color:var(--fado-warm-grey)"></i>
                        </a>
                    </div>
                </div>

                </div>{{-- /my-account-content --}}
            </div>
        </div>
    </div>
</section>

@endsection

