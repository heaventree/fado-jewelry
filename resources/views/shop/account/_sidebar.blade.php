<div class="sidebar-account sidebar-content-wrap sticky-top">
    <div class="account-author">
        <div class="author_avatar">
            <div class="image">
                @php $avatarUrl = $user->avatar ? Storage::url($user->avatar) : '/images/avatar/avatar-4.jpg'; @endphp
                <img class="lazyload imgDash" src="{{ $avatarUrl }}" data-src="{{ $avatarUrl }}"
                    alt="Avatar">
            </div>
            <form action="{{ route('shop.account.avatar.upload') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                @csrf
                <input type="file" name="avatar" accept="image/*" style="display: none;" id="fileInputDash"
                       onchange="document.getElementById('avatar-form').submit();">
            </form>
            <div class="btn-change_img box-icon changeImgDash" onclick="document.getElementById('fileInputDash').click();">
                <i class="icon icon-camera"></i>
            </div>
        </div>
        <h4 class="author_name">{{ $user->name }}</h4>
        <p class="author_email h6">{{ $user->email }}</p>
    </div>
    <ul class="my-account-nav">
        <li>
            @if($activeNav === 'dashboard')
                <p class="my-account-nav_item h5 active">
                    <i class="icon icon-circle-four"></i>
                    Dashboard
                </p>
            @else
                <a href="{{ route('shop.account.index') }}" class="my-account-nav_item h5">
                    <i class="icon icon-circle-four"></i>
                    Dashboard
                </a>
            @endif
        </li>
        <li>
            @if($activeNav === 'orders')
                <p class="my-account-nav_item h5 active">
                    <i class="icon icon-box-arrow-down"></i>
                    Orders
                </p>
            @else
                <a href="{{ route('shop.account.orders') }}" class="my-account-nav_item h5">
                    <i class="icon icon-box-arrow-down"></i>
                    Orders
                </a>
            @endif
        </li>
        <li>
            @if($activeNav === 'addresses')
                <p class="my-account-nav_item h5 active">
                    <i class="icon icon-address-book"></i>
                    My address
                </p>
            @else
                <a href="{{ route('shop.account.addresses') }}" class="my-account-nav_item h5">
                    <i class="icon icon-address-book"></i>
                    My address
                </a>
            @endif
        </li>
        <li>
            @if($activeNav === 'settings')
                <p class="my-account-nav_item h5 active">
                    <i class="icon icon-setting"></i>
                    Setting
                </p>
            @else
                <a href="{{ route('shop.account.profile') }}" class="my-account-nav_item h5">
                    <i class="icon icon-setting"></i>
                    Setting
                </a>
            @endif
        </li>
        <li>
            <a href="{{ route('logout') }}" class="my-account-nav_item h5"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="icon icon-sign-out"></i>
                Log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</div>
