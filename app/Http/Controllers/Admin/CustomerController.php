<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private const ADMIN_ROLES = ['super_admin', 'store_admin', 'staff'];

    public function index(Request $request): View
    {
        $query = User::withCount('orders')
            ->withSum('orders', 'total')
            ->whereDoesntHave('roles', fn ($q) => $q->whereIn('name', self::ADMIN_ROLES))
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(25)->withQueryString();

        $customerScope = User::whereDoesntHave('roles', fn ($q) => $q->whereIn('name', self::ADMIN_ROLES));

        $stats = [
            'total'         => (clone $customerScope)->count(),
            'with_orders'   => (clone $customerScope)->has('orders')->count(),
            'orders_total'  => Order::count(),
            'revenue_eur'   => Order::whereNotIn('status', ['cancelled', 'refunded'])
                                    ->where('currency_code', 'EUR')
                                    ->sum('total'),
        ];

        return view('users.customer.list', compact('customers', 'stats'));
    }

    public function show(User $customer): View
    {
        $customer->load('addresses')->loadCount('orders');

        $orders = $customer->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        $lifetime = $customer->orders()
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total');

        $lastOrder = $customer->orders()->latest()->first();

        $statuses = Order::STATUSES;

        return view('users.customer.details', compact(
            'customer',
            'orders',
            'lifetime',
            'lastOrder',
            'statuses',
        ));
    }
}
