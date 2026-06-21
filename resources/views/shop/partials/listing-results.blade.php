{{-- Shop results partial — toolbar (sort) + product grid + pagination.
     Shared by listing.blade.php (full page load) and ShopController's AJAX
     branch (filter/sort/page changes), so both render identical markup. --}}
<div id="shop-results">
    <div class="tf-shop-control">
        <div class="tf-control-filter d-xl-none">
            <button type="button" id="filterShop" class="tf-btn-filter">
                <span class="icon icon-filter"></span><span class="text">Filter</span>
            </button>
        </div>
        <div class="tf-control-sorting">
            <p class="h6 d-none d-lg-block">Sort by:</p>
            <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                <div class="btn-select">
                    <span class="text-sort-value">
                        @switch($filters['sort'])
                            @case('price_asc') Price, low to high @break
                            @case('price_desc') Price, high to low @break
                            @case('name_asc') Alphabetically, A-Z @break
                            @default Newest @break
                        @endswitch
                    </span>
                    <span class="icon icon-caret-down"></span>
                </div>
                <div class="dropdown-menu">
                    @foreach(['newest' => 'Newest', 'name_asc' => 'Alphabetically, A-Z', 'price_asc' => 'Price, low to high', 'price_desc' => 'Price, high to low'] as $value => $label)
                        <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['sort', 'page']), ['sort' => $value])) }}"
                           class="select-item ajax-sort-link @if($filters['sort'] === $value) active @endif" data-sort-value="{{ $value }}">
                            <span class="text-value-item">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-control-shop gridLayout-wrapper">
        <div class="meta-filter-shop">
            <div class="count-text">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</div>
        </div>
        <div class="wrapper-shop tf-grid-layout tf-col-3" id="gridLayout">
            @forelse($products as $product)
                @php
                    $img  = $product->primaryImage?->path;
                    $img2 = $product->images->skip(1)->first()?->path;
                    $variantForSale = $product->variants->first(fn ($v) => $v->isOnSale());
                    $from = $product->variants->min('price_eur');
                @endphp
                <div class="card-product grid">
                    <div class="card-product_wrapper">
                        <a href="{{ route('shop.product', $product) }}" class="product-img">
                            @if($img)
                                <img class="lazyload img-product" src="{{ asset($img) }}" data-src="{{ asset($img) }}" alt="{{ $product->name }}">
                                @if($img2)
                                <img class="lazyload img-hover" src="{{ asset($img2) }}" data-src="{{ asset($img2) }}" alt="{{ $product->name }}">
                                @endif
                            @else
                                <img class="lazyload img-product" src="/images/ochaka/products/jewelry/product-5.jpg" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
                            @endif
                        </a>
                        <ul class="product-action_list">
                            <li>
                                <a href="{{ route('shop.product', $product) }}" class="hover-tooltip tooltip-left box-icon">
                                    <span class="icon icon-shopping-cart-simple"></span>
                                    <span class="tooltip">Add to cart</span>
                                </a>
                            </li>
                            <li class="wishlist">
                                <a href="{{ route('shop.wishlist.toggle') }}"
                                   class="hover-tooltip tooltip-left box-icon"
                                   onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: JSON.stringify({product_id: {{ $product->id }}})}).then(() => location.reload())">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                </a>
                            </li>
                        </ul>
                        @if($product->is_bestseller)
                        <ul class="product-badge_list">
                            <li class="product-badge_item h6 hot">Bestseller</li>
                        </ul>
                        @elseif($variantForSale)
                        <ul class="product-badge_list">
                            <li class="product-badge_item flash-sale">Sale</li>
                        </ul>
                        @endif
                        <div class="product-action_bot">
                            <a href="{{ route('shop.product', $product) }}" class="tf-btn btn-white animate-btn animate-dark">
                                Quick View
                                <i class="icon icon-view"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-product_info">
                        <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
                        <div class="price-wrap">
                            @if($variantForSale)
                                <span class="price-old h6 fw-normal">€{{ number_format((float)$variantForSale->price_eur, 2) }}</span>
                                <span class="price-new h6">€{{ number_format((float)$variantForSale->sale_price_eur, 2) }}</span>
                            @elseif($from)
                                <span class="price-new h6">From €{{ number_format((float)$from, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="h5">No products match these filters.</p>
            @endforelse
        </div>
        @if($products->lastPage() > 1)
        <div class="wd-full wg-pagination">
            @if(!$products->onFirstPage())
                <a href="{{ $products->previousPageUrl() }}" class="pagination-item h6 direct ajax-page-link"><i class="icon icon-caret-left"></i></a>
            @endif
            @for($page = 1; $page <= $products->lastPage(); $page++)
                @if($page === $products->currentPage())
                    <span class="pagination-item h6 active">{{ $page }}</span>
                @else
                    <a href="{{ $products->url($page) }}" class="pagination-item h6 ajax-page-link">{{ $page }}</a>
                @endif
            @endfor
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pagination-item h6 direct ajax-page-link"><i class="icon icon-caret-right"></i></a>
            @endif
        </div>
        @endif
    </div>
</div>
