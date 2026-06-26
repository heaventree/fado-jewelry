@php
    $img  = $product->primaryImage?->path;
    $img2 = $product->images->skip(1)->first()?->path;
    $variantForSale = $product->variants->first(fn ($v) => $v->isOnSale());
    $from = $product->variants->min('price_eur');
    $showSale = $showSale ?? false;
    $isWishlisted = isset($wishlistedIds) ? in_array($product->id, $wishlistedIds) : app(\App\Services\WishlistService::class)->has($product->id);
@endphp
<div class="swiper-slide">
    <div class="card-product">
        <div class="card-product_wrapper d-flex">
            <a href="{{ route('shop.product', $product) }}" class="product-img">
                @if($img)
                    <img class="lazyload img-product" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset($img) }}" alt="{{ $product->name }}">
                    @if($img2)
                    <img class="lazyload img-hover" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="{{ asset($img2) }}" alt="{{ $product->name }}">
                    @endif
                @else
                    <img class="lazyload img-product" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="/images/ochaka/products/jewelry/product-5.jpg" alt="{{ $product->name }}">
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
                       class="hover-tooltip tooltip-left box-icon @if($isWishlisted) is-wishlisted @endif"
                       onclick="event.preventDefault(); fadoToggleWishlist(this, {{ $product->id }})">
                        <span class="icon icon-heart"></span>
                        <span class="tooltip">{{ $isWishlisted ? 'Saved to Wishlist' : 'Add to Wishlist' }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('shop.product', $product) }}" class="hover-tooltip tooltip-left box-icon">
                        <span class="icon icon-view"></span>
                        <span class="tooltip">Quick view</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-product_info">
            <a href="{{ route('shop.product', $product) }}" class="name-product h4 link">{{ $product->name }}</a>
            <div class="price-wrap mb-0">
                @if($showSale && $variantForSale)
                    <span class="price-old h6 fw-normal">€{{ number_format((float)$variantForSale->price_eur, 2) }}</span>
                    <span class="price-new h6">€{{ number_format((float)$variantForSale->sale_price_eur, 2) }}</span>
                @elseif($from)
                    <span class="price-new h6">From €{{ number_format((float)$from, 2) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
