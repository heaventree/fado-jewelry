<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
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

        $stats = [
            'total'     => Order::whereNotIn('status', ['cancelled', 'refunded'])->count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        $statuses = Order::STATUSES;

        return view('general.invoice.list', compact('orders', 'stats', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product.primaryImage', 'items.variant.metal', 'items.variant.gemstone', 'items.size']);

        return view('general.invoice.details', compact('order'));
    }
}
