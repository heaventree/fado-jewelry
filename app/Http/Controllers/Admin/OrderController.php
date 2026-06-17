<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])
            ->latest();

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search by order number, customer name, or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                // Numeric search = order ID
                if (is_numeric($search)) {
                    $q->where('id', (int) $search);
                }
                $q->orWhereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                )->orWhereJsonContains('shipping_address->name', $search)
                 ->orWhereJsonContains('shipping_address->email', $search);
            });
        }

        $orders = $query->paginate(25)->withQueryString();

        // Stat counts for the summary cards
        $stats = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'total'      => Order::count(),
        ];

        $statuses = Order::STATUSES;

        return view('general.orders.list', compact('orders', 'stats', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product.primaryImage', 'items.variant.metal', 'items.variant.gemstone', 'items.size']);

        $statuses = Order::STATUSES;

        return view('general.orders.details', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Order::STATUSES))],
        ]);

        $old = $order->status_label;
        $order->update(['status' => $request->input('status')]);
        $new = $order->fresh()->status_label;

        return back()->with('success', "Order {$order->order_number} status changed from {$old} → {$new}.");
    }
}
