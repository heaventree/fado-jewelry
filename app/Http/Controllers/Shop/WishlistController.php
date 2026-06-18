<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        private WishlistService $wishlist,
        private CurrencyService $currency,
    ) {}

    /** Wishlist page. */
    public function show(): View
    {
        $items    = $this->wishlist->items();
        $currency = $this->currency->current();

        return view('shop.wishlist', compact('items', 'currency'));
    }

    /**
     * Toggle a product in/out of the wishlist.
     * Accepts AJAX (returns JSON) or regular form POST (redirects back).
     */
    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $added = $this->wishlist->toggle(
            productId: (int) $data['product_id'],
            variantId: isset($data['variant_id']) ? (int) $data['variant_id'] : null,
        );

        $count = $this->wishlist->count();

        if ($request->expectsJson()) {
            return response()->json([
                'added'   => $added,
                'count'   => $count,
                'message' => $added ? 'Added to wishlist' : 'Removed from wishlist',
            ]);
        }

        return back()->with(
            $added ? 'wishlist_added' : 'wishlist_removed',
            $added ? 'Added to your wishlist.' : 'Removed from wishlist.'
        );
    }

    /** Remove a specific item (used on the wishlist page itself). */
    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $this->wishlist->toggle((int) $data['product_id']); // toggle off

        return back()->with('wishlist_removed', 'Removed from wishlist.');
    }
}
