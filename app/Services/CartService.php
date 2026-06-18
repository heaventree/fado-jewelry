<?php

namespace App\Services;

use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Session-based cart service.
 *
 * Session key: fado_cart
 * Each entry: [ product_id, variant_id, size_id|null, quantity, price_eur ]
 * Entry key : "{variant_id}_{size_id}"   (size_id = 0 when null)
 */
class CartService
{
    private const CART_KEY  = 'fado_cart';
    private const COUNT_KEY = 'cart_count';

    // ── Mutations ──────────────────────────────────────────────────────────────

    public function add(
        int    $productId,
        int    $variantId,
        ?int   $sizeId,
        int    $quantity,
        float  $priceEur,
    ): void {
        $raw = $this->raw();
        $key = $this->makeKey($variantId, $sizeId);

        if (isset($raw[$key])) {
            $raw[$key]['quantity'] = min(10, $raw[$key]['quantity'] + $quantity);
        } else {
            $raw[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'size_id'    => $sizeId,
                'quantity'   => min(10, $quantity),
                'price_eur'  => $priceEur,
            ];
        }

        $this->save($raw);
    }

    public function update(string $key, int $quantity): void
    {
        $raw = $this->raw();

        if ($quantity <= 0) {
            unset($raw[$key]);
        } elseif (isset($raw[$key])) {
            $raw[$key]['quantity'] = min(10, $quantity);
        }

        $this->save($raw);
    }

    public function remove(string $key): void
    {
        $raw = $this->raw();
        unset($raw[$key]);
        $this->save($raw);
    }

    public function clear(): void
    {
        Session::forget(self::CART_KEY);
        Session::put(self::COUNT_KEY, 0);
    }

    // ── Reads ──────────────────────────────────────────────────────────────────

    /**
     * Return hydrated cart items (DB lookups for product/variant/size).
     * Silently drops items whose variant was deleted.
     *
     * @return Collection<int, array>
     */
    public function items(): Collection
    {
        $raw = $this->raw();

        if (empty($raw)) {
            return collect();
        }

        $variantIds = collect($raw)->pluck('variant_id')->unique()->values();
        $sizeIds    = collect($raw)->pluck('size_id')->filter()->unique()->values();

        $variants = ProductVariant::with(['product.images', 'metal', 'gemstone'])
            ->whereIn('id', $variantIds)
            ->get()
            ->keyBy('id');

        $sizes = $sizeIds->isNotEmpty()
            ? ProductSize::whereIn('id', $sizeIds)->get()->keyBy('id')
            : collect();

        return collect($raw)
            ->map(function (array $data, string $key) use ($variants, $sizes): ?array {
                $variant = $variants->get($data['variant_id']);

                if (! $variant || ! $variant->product) {
                    return null; // product/variant deleted
                }

                return [
                    'key'       => $key,
                    'product'   => $variant->product,
                    'variant'   => $variant,
                    'size'      => $data['size_id'] ? $sizes->get($data['size_id']) : null,
                    'quantity'  => $data['quantity'],
                    'price_eur' => (float) $data['price_eur'],
                    'line_eur'  => (float) $data['price_eur'] * $data['quantity'],
                ];
            })
            ->filter()
            ->values();
    }

    /** Total number of individual items (sum of quantities). */
    public function count(): int
    {
        return (int) collect($this->raw())->sum('quantity');
    }

    /** Subtotal in EUR (sum of line totals). */
    public function subtotalEur(): float
    {
        return (float) collect($this->raw())->sum(fn ($d) => $d['price_eur'] * $d['quantity']);
    }

    public function isEmpty(): bool
    {
        return empty($this->raw());
    }

    /** Raw session data keyed by cart key. */
    public function raw(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function makeKey(int $variantId, ?int $sizeId): string
    {
        return $variantId . '_' . ($sizeId ?? 0);
    }

    private function save(array $raw): void
    {
        Session::put(self::CART_KEY, $raw);
        Session::put(self::COUNT_KEY, (int) collect($raw)->sum('quantity'));
    }
}
