<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Headline stats ─────────────────────────────────────────────────────

        $totalProducts     = Product::where('is_active', true)->count();
        $totalOrders       = Order::count();
        $pendingOrders     = Order::where('status', 'pending')->count();
        $unreadConsults    = Consultation::whereNull('read_at')->count();

        // EUR revenue from non-cancelled, non-refunded orders
        $revenueEur = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->where('currency_code', 'EUR')
            ->sum('total');

        // Orders placed this calendar month
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ── Recent orders (5) ──────────────────────────────────────────────────

        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Recent unread consultations (5) ───────────────────────────────────

        $recentConsultations = Consultation::whereNull('read_at')
            ->latest()
            ->limit(5)
            ->get();

        // ── Orders by status (for mini breakdown) ─────────────────────────────

        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Top 5 products by units sold ──────────────────────────────────────

        $topProducts = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.id', 'products.name', 'products.slug',
                     DB::raw('SUM(order_items.quantity) as units_sold'),
                     DB::raw('SUM(order_items.quantity * order_items.unit_price) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        $statuses = Order::STATUSES;

        return view('dashboards.index', compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'unreadConsults',
            'revenueEur',
            'ordersThisMonth',
            'recentOrders',
            'recentConsultations',
            'ordersByStatus',
            'topProducts',
            'statuses',
        ));
    }
}
