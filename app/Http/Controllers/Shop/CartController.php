<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService     $cart,
        private CurrencyService $currency,
    ) {}

    public function show(): View
    {
        $items    = $this->cart->items();
        $currency = $this->currency->current();

        return view('shop.cart', [
            'items'       => $items,
            'subtotalEur' => $this->cart->subtotalEur(),
            'currency'    => $currency,
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'size_id'    => ['nullable', 'integer', 'exists:product_sizes,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $variant = ProductVariant::findOrFail($data['variant_id']);

        $this->cart->add(
            productId: (int) $data['product_id'],
            variantId: (int) $data['variant_id'],
            sizeId:    isset($data['size_id']) ? (int) $data['size_id'] : null,
            quantity:  (int) $data['quantity'],
            priceEur:  (float) $variant->price_eur,
        );

        return back()->with('cart_success', 'Added to your bag!');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key'      => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $this->cart->update($data['key'], (int) $data['quantity']);

        return back()->with('cart_updated', 'Bag updated.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $this->cart->remove($data['key']);

        return back()->with('cart_updated', 'Item removed from bag.');
    }
}
