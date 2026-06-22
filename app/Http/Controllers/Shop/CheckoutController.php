<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

        $items          = $this->cart->items();
        $subtotalEur    = $this->cart->subtotalEur();
        $currency       = $this->currency->current();
        $user           = Auth::user();
        $savedAddresses = $user ? $user->addresses()->orderByDesc('is_default')->get() : collect();
        $paymentMethods = $this->paymentMethods();

        $shippingRates = [
            'IE'        => (float) Setting::get('shipping_rate_ireland', 5.95),
            'INTL'      => (float) Setting::get('shipping_rate_international', 12.95),
            'threshold' => (float) Setting::get('free_shipping_threshold', 75),
        ];
        $shippingNotice = Setting::get('shipping_notice');

        return view('shop.checkout', compact(
            'items', 'subtotalEur', 'currency', 'user', 'savedAddresses', 'paymentMethods', 'shippingRates', 'shippingNotice'
        ));
    }

    public function place(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.cart')->with('info', 'Your bag is empty.');
        }

        $availableMethods = array_keys($this->paymentMethods(true));

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'line1'          => ['required', 'string', 'max:255'],
            'line2'          => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'county'         => ['nullable', 'string', 'max:100'],
            'country'        => ['required', 'string', 'size:2'],
            'postcode'       => ['required', 'string', 'max:20'],
            'payment_method' => ['required', Rule::in($availableMethods)],
        ]);

        $items       = $this->cart->items();
        $subtotalEur = $this->cart->subtotalEur();
        $currency    = $this->currency->current();

        $threshold     = (float) Setting::get('free_shipping_threshold', 75);
        $shippingEur   = $subtotalEur >= $threshold
            ? 0.0
            : (strtoupper($data['country']) === 'IE'
                ? (float) Setting::get('shipping_rate_ireland', 5.95)
                : (float) Setting::get('shipping_rate_international', 12.95));

        $order = DB::transaction(function () use ($data, $items, $subtotalEur, $shippingEur, $currency): Order {
            $order = Order::create([
                'user_id'          => Auth::id(),
                'status'           => 'pending',
                'subtotal'         => $subtotalEur,
                'shipping_total'   => $shippingEur,
                'total'            => $subtotalEur + $shippingEur,
                'currency_code'    => $currency->code,
                'payment_method'   => $data['payment_method'],
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

        if ($request->boolean('save_address') && Auth::check()) {
            $user = Auth::user();
            $exists = $user->addresses()
                ->where('line1', $data['line1'])
                ->where('city', $data['city'])
                ->where('postcode', $data['postcode'])
                ->exists();

            if (! $exists) {
                $user->addresses()->create([
                    'label'    => 'Shipping',
                    'name'     => $data['name'],
                    'line1'    => $data['line1'],
                    'line2'    => $data['line2'] ?? null,
                    'city'     => $data['city'],
                    'county'   => $data['county'] ?? null,
                    'postcode' => $data['postcode'],
                    'country'  => strtoupper($data['country']),
                    'phone'    => $data['phone'] ?? null,
                ]);
            }
        }

        $this->cart->clear();

        // Store order ID in session so the guest can view their confirmation
        session(['last_order_id' => $order->id]);

        return redirect()->route('shop.order.confirmation', $order)
            ->with('order_placed', true);
    }

    /**
     * Real, backend-defined payment methods only — never hardcoded in the view.
     * 'cod' ("order on request") is genuinely functional: no payment processing
     * happens, the admin follows up by phone/payment link, gated by the
     * `cod_enabled` setting (admin-facing copy: "Enable order-on-request checkout").
     * 'stripe' has config fields in Settings but NO working charge integration —
     * no Stripe SDK is installed and no processing code exists anywhere in this
     * app. It is only ever returned here for display as a disabled/"coming soon"
     * option when keys are configured, and $selectable (default) excludes it
     * entirely so it can never be chosen at checkout.
     */
    private function paymentMethods(bool $selectable = false): array
    {
        $methods = [];

        if (Setting::get('cod_enabled', '1')) {
            $methods['cod'] = [
                'label'       => 'Order on request',
                'description' => Setting::get('payment_method_label') ?: 'Our team will contact you to arrange payment.',
                'functional'  => true,
            ];
        }

        if (! $selectable && Setting::get('stripe_publishable_key')) {
            $methods['stripe'] = [
                'label'       => 'Card (Stripe)',
                'description' => 'Card payments are not yet available — coming soon.',
                'functional'  => false,
            ];
        }

        return $methods;
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
