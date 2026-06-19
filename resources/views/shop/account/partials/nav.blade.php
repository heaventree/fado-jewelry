@php
    $currentRoute = Route::currentRouteName();
    $navItems = [
        ['route' => 'shop.account.index',   'icon' => 'icon-circle-four',    'label' => 'Dashboard'],
        ['route' => 'shop.account.orders',  'icon' => 'icon-box-arrow-down', 'label' => 'Orders'],
        ['route' => 'shop.wishlist',        'icon' => 'icon-heart',          'label' => 'Wishlist'],
        ['route' => 'shop.account.profile', 'icon' => 'icon-setting',        'label' => 'Settings'],
    ];
@endphp

<div class="sidebar-account sidebar-content-wrap sticky-top">
    <div class="account-author">
        <div class="author_avatar">
            <div class="image">
                <img class="lazyload imgDash" src="/images/ochaka/avatar/avatar-4.jpg" data-src="/images/ochaka/avatar/avatar-4.jpg" alt="Avatar">
            </div>
        </div>
        <h4 class="author_name">{{ auth()->user()?->name }}</h4>
        <p class="author_email h6">{{ auth()->user()?->email }}</p>
    </div>
    <ul class="my-account-nav">
        @foreach($navItems as $item)
        @php $active = $currentRoute === $item['route']; @endphp
        <li>
            @if($active)
            <p class="my-account-nav_item h5 active">
                <i class="icon {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
                @if($item['route'] === 'shop.wishlist' && session('wishlist_count', 0) > 0)
                    <span class="ms-auto badge-count h6">{{ session('wishlist_count') }}</span>
                @endif
            </p>
            @else
            <a href="{{ route($item['route']) }}" class="my-account-nav_item h5">
                <i class="icon {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
                @if($item['route'] === 'shop.wishlist' && session('wishlist_count', 0) > 0)
                    <span class="ms-auto badge-count h6">{{ session('wishlist_count') }}</span>
                @endif
            </a>
            @endif
        </li>
        @endforeach
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="my-account-nav_item h5 w-100 text-start border-0 bg-transparent p-0">
                    <i class="icon icon-sign-out"></i>
                    Log out
                </button>
            </form>
        </li>
    </ul>
</div>
