@php
    $currentRoute = Route::currentRouteName();
    $links = [
        ['route' => 'shop.account.index',   'icon' => 'icon-house',       'label' => 'Overview'],
        ['route' => 'shop.account.orders',  'icon' => 'icon-package',     'label' => 'My Orders'],
        ['route' => 'shop.wishlist',        'icon' => 'icon-heart',       'label' => 'Wishlist'],
        ['route' => 'shop.account.profile', 'icon' => 'icon-user-circle', 'label' => 'Profile'],
    ];
@endphp

<nav style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; overflow:hidden">
    @foreach($links as $link)
    @php
        $active = $currentRoute === $link['route'];
    @endphp
    <a href="{{ route($link['route']) }}"
       style="display:flex; align-items:center; gap:10px; padding:14px 20px;
              font-size:.9rem; text-decoration:none; border-bottom:1px solid var(--fado-cream);
              {{ $active
                  ? 'background:var(--fado-cream); color:var(--fado-deep-green); font-weight:600; border-left:3px solid var(--fado-deep-green);'
                  : 'color:#555; font-weight:400; border-left:3px solid transparent;'
              }}
              transition:all .15s"
       @unless($active) onmouseover="this.style.background='var(--fado-near-white)'; this.style.color='var(--fado-deep-green)'"
       onmouseout="this.style.background='transparent'; this.style.color='#555'" @endunless>
        <i class="icon {{ $link['icon'] }}" style="font-size:1rem; flex-shrink:0"></i>
        {{ $link['label'] }}
        @if($link['route'] === 'shop.wishlist' && session('wishlist_count', 0) > 0)
            <span style="margin-left:auto; font-size:.7rem; background:var(--fado-gold); color:#fff;
                         border-radius:10px; padding:1px 7px; font-weight:700">
                {{ session('wishlist_count') }}
            </span>
        @endif
    </a>
    @endforeach
    <form method="POST" action="{{ route('logout') }}"
          style="border-left:3px solid transparent">
        @csrf
        <button type="submit"
                style="display:flex; align-items:center; gap:10px; padding:14px 20px; width:100%;
                       background:none; border:none; font-size:.9rem; color:#888; cursor:pointer;
                       transition:color .15s; text-align:left"
                onmouseover="this.style.color='#dc3545'"
                onmouseout="this.style.color='#888'">
            <i class="icon icon-sign-out" style="font-size:1rem"></i>
            Sign Out
        </button>
    </form>
</nav>
