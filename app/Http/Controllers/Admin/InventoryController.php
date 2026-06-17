<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    const LOW_STOCK_THRESHOLD = 3;

    public function index(Request $request): View
    {
        $threshold = self::LOW_STOCK_THRESHOLD;

        $query = ProductVariant::with(['product', 'metal', 'gemstone'])
            ->whereHas('product', fn ($q) => $q->where('is_active', true));

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhereHas('product', fn ($pq) =>
                      $pq->where('name', 'like', "%{$search}%")
                  );
            });
        }

        $stockFilter = $request->input('stock');
        if ($stockFilter === 'out') {
            $query->where('stock', 0);
        } elseif ($stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', $threshold);
        } elseif ($stockFilter === 'ok') {
            $query->where('stock', '>', $threshold);
        }

        $variants = $query->orderByRaw('stock ASC, product_id ASC')->paginate(40)->withQueryString();

        $stats = [
            'total_variants' => ProductVariant::count(),
            'total_stock'    => ProductVariant::sum('stock'),
            'out_of_stock'   => ProductVariant::where('stock', 0)->count(),
            'low_stock'      => ProductVariant::where('stock', '>', 0)->where('stock', '<=', $threshold)->count(),
        ];

        return view('general.inventory.warehouse', compact('variants', 'stats', 'threshold'));
    }

    public function lowStock(): View
    {
        $threshold = self::LOW_STOCK_THRESHOLD;

        $variants = ProductVariant::with(['product', 'metal', 'gemstone'])
            ->whereHas('product', fn ($q) => $q->where('is_active', true))
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->get();

        $outOfStock = $variants->where('stock', 0);
        $lowStock   = $variants->where('stock', '>', 0);

        return view('general.inventory.received-orders', compact(
            'variants', 'outOfStock', 'lowStock', 'threshold'
        ));
    }

    public function updateStock(Request $request, ProductVariant $variant): RedirectResponse
    {
        $request->validate([
            'stock' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $variant->update(['stock' => $request->integer('stock')]);

        return back()->with('success', "Stock updated for {$variant->product->name} ({$variant->label}).");
    }

    public function bulkUpdateStock(Request $request): RedirectResponse
    {
        $request->validate([
            'stocks'          => ['required', 'array'],
            'stocks.*.id'     => ['required', 'integer', 'exists:product_variants,id'],
            'stocks.*.stock'  => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $updated = 0;
        foreach ($request->input('stocks') as $row) {
            ProductVariant::where('id', $row['id'])->update(['stock' => $row['stock']]);
            $updated++;
        }

        return back()->with('success', "Updated stock for {$updated} " . ($updated === 1 ? 'variant' : 'variants') . '.');
    }
}
