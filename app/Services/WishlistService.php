<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Dual-mode wishlist service.
 *
 * - Authenticated users: persisted in the wishlists DB table.
 * - Guests: stored in session under fado_wishlist as [product_id => variant_id|null].
 *
 * On first authenticated page-view after guest session, call mergeGuestToDb()
 * to pull session items into the user's DB wishlist.
 */
class WishlistService
{
    private const SESSION_KEY = 'fado_wishlist';
    private const COUNT_KEY   = 'wishlist_count';

    // ── Public API ─────────────────────────────────────────────────────────────

    /**
     * Toggle a product in/out of the wishlist.
     * Returns true if added, false if removed.
     */
    public function toggle(int $productId, ?int $variantId = null): bool
    {
        if (Auth::check()) {
            return $this->toggleDb($productId, $variantId);
        }

        return $this->toggleSession($productId, $variantId);
    }

    /** Check whether a product (optionally specific variant) is wishlisted. */
    public function has(int $productId, ?int $variantId = null): bool
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
        }

        return array_key_exists($productId, $this->rawSession());
    }

    /** Get all wishlisted product IDs in one query (avoids N+1 in loops). */
    public function wishlistedIds(): array
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->all();
        }

        return array_keys($this->rawSession());
    }

    /**
     * Return hydrated wishlist items.
     *
     * @return Collection<int, array{product: Product, variant: ?\App\Models\ProductVariant, wishlisted_at: ?\Carbon\Carbon}>
     */
    public function items(): Collection
    {
        if (Auth::check()) {
            return $this->dbItems();
        }

        return $this->sessionItems();
    }

    /** Total number of wishlisted products. */
    public function count(): int
    {
        $count = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->count()
            : count($this->rawSession());

        Session::put(self::COUNT_KEY, $count);

        return $count;
    }

    /**
     * Merge guest session wishlist into the authenticated user's DB wishlist.
     * Call this after login / registration.
     */
    public function mergeGuestToDb(): void
    {
        if (! Auth::check()) {
            return;
        }

        foreach ($this->rawSession() as $productId => $variantId) {
            Wishlist::firstOrCreate([
                'user_id'    => Auth::id(),
                'product_id' => (int) $productId,
                'variant_id' => $variantId ?: null,
            ]);
        }

        Session::forget(self::SESSION_KEY);
        $this->syncCount();
    }

    // ── DB helpers (auth users) ────────────────────────────────────────────────

    private function toggleDb(int $productId, ?int $variantId): bool
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->syncCount();
            return false;
        }

        Wishlist::create([
            'user_id'    => Auth::id(),
            'product_id' => $productId,
            'variant_id' => $variantId,
        ]);

        $this->syncCount();
        return true;
    }

    private function dbItems(): Collection
    {
        return Wishlist::with(['product.images', 'product.variants.metal', 'variant.metal', 'variant.gemstone'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(fn (Wishlist $w) => [
                'product'      => $w->product,
                'variant'      => $w->variant,
                'wishlist_id'  => $w->id,
                'wishlisted_at'=> $w->created_at,
            ])
            ->filter(fn ($row) => $row['product'] !== null)
            ->values();
    }

    // ── Session helpers (guests) ───────────────────────────────────────────────

    private function toggleSession(int $productId, ?int $variantId): bool
    {
        $raw = $this->rawSession();

        if (array_key_exists($productId, $raw)) {
            unset($raw[$productId]);
            $this->saveSession($raw);
            return false;
        }

        $raw[$productId] = $variantId;
        $this->saveSession($raw);
        return true;
    }

    private function sessionItems(): Collection
    {
        $raw = $this->rawSession();

        if (empty($raw)) {
            return collect();
        }

        $productIds = array_keys($raw);

        return Product::with(['images', 'variants.metal', 'variants.gemstone'])
            ->where('is_active', true)
            ->whereIn('id', $productIds)
            ->get()
            ->map(fn (Product $p) => [
                'product'      => $p,
                'variant'      => $p->variants->firstWhere('id', $raw[$p->id] ?? null),
                'wishlist_id'  => null,
                'wishlisted_at'=> null,
            ])
            ->values();
    }

    // ── Internal ───────────────────────────────────────────────────────────────

    /** Raw session data: [product_id (int key) => variant_id|null] */
    private function rawSession(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    private function saveSession(array $raw): void
    {
        Session::put(self::SESSION_KEY, $raw);
        Session::put(self::COUNT_KEY, count($raw));
    }

    private function syncCount(): void
    {
        $count = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->count()
            : count($this->rawSession());

        Session::put(self::COUNT_KEY, $count);
    }
}
