@php
    $isStaff      = auth()->check() && auth()->user()->hasRole('staff') && ! auth()->user()->hasRole(['super_admin', 'store_admin']);
    $isAdminPlus  = auth()->check() && auth()->user()->hasRole(['super_admin', 'store_admin']);
    $isSuperAdmin = auth()->check() && auth()->user()->hasRole('super_admin');

    $inventoryLowStock   = \App\Models\ProductVariant::where('stock', '<=', \App\Http\Controllers\Admin\InventoryController::LOW_STOCK_THRESHOLD)->count();
    $consultationUnread  = \App\Models\Consultation::whereNull('read_at')->count();
@endphp

<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('second', [ 'dashboards' , 'index']) }}" class="logo-dark">
            <img src="/images/logo-sm.png" class="logo-sm" alt="FADÓ">
            <span class="logo-lg fw-bold fs-20" style="color:#044705;">FADÓ</span>
        </a>

        <a href="{{ route('second', [ 'dashboards' , 'index']) }}" class="logo-light">
            <img src="/images/logo-sm.png" class="logo-sm" alt="FADÓ">
            <span class="logo-lg fw-bold fs-20 text-white">FADÓ</span>
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">General</li>

            {{-- Dashboard: all admin roles --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', [ 'dashboards' , 'index']) }}">
                    <span class="nav-icon"><iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Dashboard </span>
                </a>
            </li>

            {{-- Products: super_admin + store_admin only --}}
            @if($isAdminPlus)
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon"><iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Products </span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.products.index') }}">All Products</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.products.create') }}">Add Product</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Attributes: directly below Products --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAttributes" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarAttributes">
                    <span class="nav-icon"><iconify-icon icon="solar:medal-ribbons-star-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Attributes </span>
                </a>
                <div class="collapse" id="sidebarAttributes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.metals.index') }}">Metals</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.gemstones.index') }}">Gemstones</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.ring-sizes.index') }}">Ring Sizes</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.colours.index') }}">Colours</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Category: super_admin + store_admin only --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCategory">
                    <span class="nav-icon"><iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Category </span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.categories.index') }}">All Categories</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.categories.create') }}">Add Category</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Collections: super_admin + store_admin only --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCollections" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCollections">
                    <span class="nav-icon"><iconify-icon icon="solar:bookmark-circle-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Collections </span>
                </a>
                <div class="collapse" id="sidebarCollections">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.collections.index') }}">All Collections</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.collections.create') }}">Add Collection</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            {{-- Inventory: all admin roles --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarInventory" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarInventory">
                    <span class="nav-icon"><iconify-icon icon="solar:box-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Inventory </span>
                    @if($inventoryLowStock > 0)
                        <span class="badge bg-warning badge-pill ms-auto">{{ $inventoryLowStock }}</span>
                    @endif
                </a>
                <div class="collapse" id="sidebarInventory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.inventory.index') }}">All Stock</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.inventory.low-stock') }}">
                                Low Stock Alerts
                                @if($inventoryLowStock > 0)
                                    <span class="badge bg-warning ms-1">{{ $inventoryLowStock }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Orders: all admin roles --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarOrders">
                    <span class="nav-icon"><iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Orders </span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.orders.index') }}">All Orders</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Coupons: super_admin + store_admin only --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCoupons" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCoupons">
                    <span class="nav-icon"><iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Coupons </span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.coupons.index') }}">All Coupons</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.coupons.create') }}">Add Coupon</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Consultations: all admin roles --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.consultations.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:calendar-mark-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Consultations </span>
                    @if($consultationUnread > 0)
                        <span class="badge bg-danger badge-pill ms-auto">{{ $consultationUnread }}</span>
                    @endif
                </a>
            </li>

            {{-- Invoices: all admin roles --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarInvoice" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarInvoice">
                    <span class="nav-icon"><iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Invoices </span>
                </a>
                <div class="collapse" id="sidebarInvoice">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.invoices.index') }}">All Invoices</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Currencies: super_admin + store_admin only --}}
            @if($isAdminPlus)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.currencies.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:dollar-minimalistic-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Currencies </span>
                </a>
            </li>

            <li class="menu-title mt-2">Navigation</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.menus.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:hamburger-menu-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Menus </span>
                </a>
            </li>

            <li class="menu-title mt-2">Content</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.posts.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Blog Posts </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.sliders.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:slider-vertical-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Sliders </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.testimonials.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:chat-round-dots-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Testimonials </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.faqs.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:question-circle-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> FAQs </span>
                </a>
            </li>

            <li class="menu-title mt-2">Pages</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pages.home') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:home-2-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Home Page </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pages.about') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:info-circle-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> About Us </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pages.contact') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Contact </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pages.terms') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:document-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Terms & Conditions </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pages.privacy') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Privacy Policy </span>
                </a>
            </li>

            {{-- Settings: super_admin + store_admin only --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:settings-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Settings </span>
                </a>
            </li>

            <li class="menu-title mt-2">Users</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Admin Users </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Customers </span>
                </a>
            </li>


            @endif {{-- end $isAdminPlus --}}

        </ul>
    </div>
</div>
