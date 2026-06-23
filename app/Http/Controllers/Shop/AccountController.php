<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index(): View
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->id)
            ->withCount('items')
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
            ->withCount('items')
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

    public function addresses(): View
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();

        return view('shop.account.addresses', compact('user', 'addresses'));
    }

    public function addressStore(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'label'    => ['required', 'string', 'max:60'],
            'name'     => ['required', 'string', 'max:120'],
            'line1'    => ['required', 'string', 'max:200'],
            'line2'    => ['nullable', 'string', 'max:200'],
            'city'     => ['required', 'string', 'max:100'],
            'county'   => ['nullable', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'country'  => ['required', 'string', 'size:2'],
            'phone'    => ['nullable', 'string', 'max:30'],
        ]);

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $user->addresses()->create($data);

        return back()->with('address_saved', 'Address added successfully.');
    }

    public function addressUpdate(Request $request, Address $address): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($address->user_id === $user->id, 403);

        $data = $request->validate([
            'label'    => ['required', 'string', 'max:60'],
            'name'     => ['required', 'string', 'max:120'],
            'line1'    => ['required', 'string', 'max:200'],
            'line2'    => ['nullable', 'string', 'max:200'],
            'city'     => ['required', 'string', 'max:100'],
            'county'   => ['nullable', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'country'  => ['required', 'string', 'size:2'],
            'phone'    => ['nullable', 'string', 'max:30'],
        ]);

        if ($request->boolean('is_default')) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $address->update($data);

        return back()->with('address_saved', 'Address updated successfully.');
    }

    public function addressDestroy(Address $address): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($address->user_id === $user->id, 403);

        $address->delete();

        return back()->with('address_saved', 'Address deleted.');
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('shop.account.profile', compact('user'));
    }

    public function profileUpdate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'first_name' => ['required', 'string', 'max:60'],
            'last_name'  => ['required', 'string', 'max:60'],
            'email'      => ['required', 'email', 'max:200', 'unique:users,email,' . $user->id],
        ];

        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password']         = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
        }

        $request->validate($rules);

        $user->update([
            'name'  => trim($request->first_name . ' ' . $request->last_name),
            'email' => $request->email,
        ]);

        if ($request->filled('current_password')) {
            $user->update(['password' => Hash::make($request->password)]);
            return back()->with('password_updated', true);
        }

        return back()->with('profile_updated', true);
    }

    public function avatarUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('profile_updated', true);
    }

    public function avatarDelete(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

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
