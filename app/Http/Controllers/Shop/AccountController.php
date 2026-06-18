<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $orderCount = Order::where('user_id', $user->id)->count();

        return view('shop.account.index', compact('user', 'recentOrders', 'orderCount'));
    }

    public function orders(): View
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('shop.account.orders', compact('user', 'orders'));
    }

    public function orderShow(Order $order): View
    {
        $user = Auth::user();

        abort_unless($order->user_id === $user->id, 403);

        $order->load([
            'items.product.primaryImage',
            'items.variant.metal',
            'items.variant.gemstone',
            'items.size',
        ]);

        return view('shop.account.order-detail', compact('user', 'order'));
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('shop.account.profile', compact('user'));
    }

    public function profileUpdate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:200', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return back()->with('profile_updated', true);
    }

    public function passwordUpdate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('password_updated', true);
    }
}
