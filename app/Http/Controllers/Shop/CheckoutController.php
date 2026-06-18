<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService     $cart,
        private CurrencyService $currency,
    ) {}

    public function show(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.cart')->with('info', 'Your bag is empty.');
        }

        $items       = $this->cart->items();
        $subtotalEur = $this->cart->subtotalEur();
        $currency    = $this->currency->current();
        $user        = Auth::user();

        return view('shop.checkout', compact('items', 'subtotalEur', 'currency', 'user'));
    }

    public function place(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.cart')->with('info', 'Your bag is empty.');
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'line1'    => ['required', 'string', 'max:255'],
            'line2'    => ['nullable', 'string', 'max:255'],
            'city'     => ['required', 'string', 'max:100'],
            'county'   => ['nullable', 'string', 'max:100'],
            'country'  => ['required', 'string', 'size:2'],
            'postcode' => ['required', 'string', 'max:20'],
        ]);

        $items       = $this->cart->items();
        $subtotalEur = $this->cart->subtotalEur();
        $currency    = $this->currency->current();

        $order = DB::transaction(function () use ($data, $items, $subtotalEur, $currency): Order {
            $order = Order::create([
                'user_id'          => Auth::id(),
                'status'           => 'pending',
                'subtotal'         => $subtotalEur,
                'total'            => $subtotalEur, // shipping calculated later
                'currency_code'    => $currency->code,
                'shipping_address' => [
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'phone'    => $data['phone'] ?? null,
                    'line1'    => $data['line1'],
                    'line2'    => $data['line2'] ?? null,
                    'city'     => $data['city'],
                    'county'   => $data['county'] ?? null,
                    'country'  => strtoupper($data['country']),
                    'postcode' => $data['postcode'],
                ],
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'variant_id' => $item['variant']->id,
                    'size_id'    => $item['size']?->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price_eur'],
                ]);
            }

            return $order;
        });

        $this->cart->clear();

        // Store order ID in session so the guest can view their confirmation
        session(['last_order_id' => $order->id]);

        return redirect()->route('shop.order.confirmation', $order)
            ->with('order_placed', true);
    }

    public function confirmation(Order $order): View|RedirectResponse
    {
        // Logged-in user must own the order, or it must be the last-placed order this session
        $isOwner  = Auth::check() && Auth::id() === $order->user_id;
        $isGuest  = session('last_order_id') === $order->id;

        if (! $isOwner && ! $isGuest) {
            abort(403);
        }

        $order->load(['items.product.images', 'items.variant.metal', 'items.variant.gemstone', 'items.size']);

        return view('shop.order-confirmation', compact('order'));
    }
}
