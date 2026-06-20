@php
    $img  = $product->primaryImage?->path;
    $img2 = $product->images->skip(1)->first()?->path;
    $variantForSale = $product->variants->first(fn ($v) => $v->isOnSale());
    $from = $product->variants->min('price_eur');
    $showSale = $showSale ?? false;
@endphp
<div class="swiper-slide">
    <div class="card-product">
        <div class="card-product_wrapper d-flex">
            <a href="{{ route('shop.product', $product) }}" class="product-img">
                @if($img)
                    <img class="lazyload img-product" src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" data-src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt="{{ $product->name }}">
                    @if($img2)
                    <img class="lazyload img-hover" src="{{ \Illuminate\Support\Facades\Storage::url($img2) }}" data-src="{{ \Illuminate\Support\Facades\Storage::url($img2) }}" alt="{{ $product->name }}">
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
                    <a href="{{ route('shop.wishlist.toggle', $product->id) }}"
                       class="hover-tooltip tooltip-left box-icon"
                       onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>location.reload())">
                        <span class="icon icon-heart"></span>
                        <span class="tooltip">Add to Wishlist</span>
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
