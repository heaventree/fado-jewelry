<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . ltrim($request->getPathInfo(), '/');

        // ── 1. OpenCart index.php pattern ─────────────────────────────────────
        // /index.php?route=product/product&product_id=N
        if ($path === '/index.php') {
            $route     = $request->query('route', '');
            $productId = (int) $request->query('product_id', 0);

            if ($route === 'product/product' && $productId > 0) {
                $product = Product::where('opencart_id', $productId)
                    ->where('is_active', true)
                    ->select('slug')
                    ->first();

                if ($product) {
                    return redirect()->route('shop.product', $product->slug, 301);
                }
            }

            // Other OpenCart index.php routes → homepage
            if (str_starts_with($route, 'product/category')) {
                return redirect()->route('shop.jewellery', [], 301);
            }
        }

        // ── 2. Redirects table lookup (cached for 1 hour) ─────────────────────
        $redirect = Cache::remember('redirect:' . $path, 3600, function () use ($path) {
            return DB::table('redirects')->where('from_path', $path)->first();
        });

        if ($redirect) {
            return redirect($redirect->to_path, $redirect->status_code);
        }

        return $next($request);
    }
}
