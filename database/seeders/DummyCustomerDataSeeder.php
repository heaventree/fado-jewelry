<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds customer accounts, orders/order items, consultations, coupons and
 * wishlists so the admin panel (orders, customers, consultation inbox,
 * coupons) and the customer account area have real data to test against.
 * Skipped per-record via updateOrCreate/firstOrCreate so re-running is safe.
 */
class DummyCustomerDataSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Aoife Murphy',    'email' => 'aoife.murphy@example.com'],
            ['name' => 'Sean Kavanagh',   'email' => 'sean.kavanagh@example.com'],
            ['name' => 'Niamh Byrne',     'email' => 'niamh.byrne@example.com'],
            ['name' => 'Cian O\'Sullivan','email' => 'cian.osullivan@example.com'],
            ['name' => 'Roisin Walsh',    'email' => 'roisin.walsh@example.com'],
        ];

        $users = collect($customers)->map(function ($c) {
            return User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name'              => $c['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        });

        $products = Product::with('variants', 'sizes')->where('is_active', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('DummyCustomerDataSeeder skipped order seeding: no products found. Run DummyProductsSeeder first.');
            return;
        }

        // ── Orders + order items ────────────────────────────────────────────
        $statuses = array_keys(Order::STATUSES);
        $irishTowns = [
            ['city' => 'Dublin', 'county' => 'Dublin'],
            ['city' => 'Cork', 'county' => 'Cork'],
            ['city' => 'Galway', 'county' => 'Galway'],
            ['city' => 'Limerick', 'county' => 'Limerick'],
            ['city' => 'Waterford', 'county' => 'Waterford'],
        ];

        if (Order::count() === 0) {
            $orderNum = 0;

            foreach ($users as $i => $user) {
                $ordersForUser = 2;

                for ($o = 0; $o < $ordersForUser; $o++) {
                    $town = $irishTowns[$orderNum % count($irishTowns)];
                    $itemCount = rand(1, 3);
                    $picked = $products->random(min($itemCount, $products->count()));

                    $subtotal = 0;
                    $lineData = [];

                    foreach ($picked as $product) {
                        $variant = $product->variants->first();
                        if (!$variant) {
                            continue;
                        }
                        $qty       = rand(1, 2);
                        $unitPrice = $variant->isOnSale() ? (float) $variant->sale_price_eur : (float) $variant->price_eur;
                        $subtotal += $unitPrice * $qty;

                        $lineData[] = [
                            'product_id' => $product->id,
                            'variant_id' => $variant->id,
                            'size_id'    => $product->sizes->first()?->id,
                            'quantity'   => $qty,
                            'unit_price' => $unitPrice,
                        ];
                    }

                    if (empty($lineData)) {
                        continue;
                    }

                    $order = Order::create([
                        'user_id'          => $user->id,
                        'status'           => $statuses[$orderNum % count($statuses)],
                        'subtotal'         => round($subtotal, 2),
                        'total'            => round($subtotal, 2),
                        'currency_code'    => 'EUR',
                        'shipping_address' => [
                            'name'    => $user->name,
                            'email'   => $user->email,
                            'address' => rand(1, 99) . ' Main Street',
                            'city'    => $town['city'],
                            'county'  => $town['county'],
                            'country' => 'Ireland',
                            'postcode'=> '',
                        ],
                    ]);

                    foreach ($lineData as $line) {
                        OrderItem::create($line + ['order_id' => $order->id]);
                    }

                    $orderNum++;
                }
            }

            // A couple of guest orders (no user_id) to exercise the guest-checkout path
            for ($g = 0; $g < 2; $g++) {
                $product = $products->random();
                $variant = $product->variants->first();
                if (!$variant) {
                    continue;
                }

                $unitPrice = $variant->isOnSale() ? (float) $variant->sale_price_eur : (float) $variant->price_eur;

                $order = Order::create([
                    'user_id'          => null,
                    'status'           => 'pending',
                    'subtotal'         => $unitPrice,
                    'total'            => $unitPrice,
                    'currency_code'    => 'EUR',
                    'shipping_address' => [
                        'name'    => 'Guest Customer',
                        'email'   => 'guest' . $g . '@example.com',
                        'address' => '12 Patrick Street',
                        'city'    => 'Cork',
                        'county'  => 'Cork',
                        'country' => 'Ireland',
                        'postcode'=> '',
                    ],
                ]);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'size_id'    => $product->sizes->first()?->id,
                    'quantity'   => 1,
                    'unit_price' => $unitPrice,
                ]);
            }
        }

        // ── Wishlists ────────────────────────────────────────────────────────
        foreach ($users as $i => $user) {
            $picks = $products->random(min(3, $products->count()));
            foreach ($picks as $product) {
                Wishlist::firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'variant_id' => $product->variants->first()?->id,
                ]);
            }
        }

        // ── Consultations ────────────────────────────────────────────────────
        $consultations = [
            ['name' => 'Mary Kelly', 'email' => 'mary.kelly@example.com', 'phone' => '+353 87 123 4567', 'message' => 'I\'m interested in a custom Claddagh engagement ring — could we book a consultation?', 'preferred_contact' => 'phone', 'read' => true],
            ['name' => 'Tom Daly', 'email' => 'tom.daly@example.com', 'phone' => null, 'message' => 'Looking for advice on ring sizing for a surprise proposal. What sizes do you recommend bringing in?', 'preferred_contact' => 'email', 'read' => true],
            ['name' => 'Sarah Quinn', 'email' => 'sarah.quinn@example.com', 'phone' => '+353 86 987 6543', 'message' => 'Could you tell me more about The Jewellery Garden collection for a wedding gift?', 'preferred_contact' => 'phone', 'read' => false],
            ['name' => 'James Brennan', 'email' => 'james.brennan@example.com', 'phone' => null, 'message' => 'Do you offer engraving on the Trinity Knot range?', 'preferred_contact' => 'email', 'read' => false],
        ];

        foreach ($consultations as $c) {
            Consultation::firstOrCreate(
                ['email' => $c['email'], 'message' => $c['message']],
                [
                    'name'              => $c['name'],
                    'phone'             => $c['phone'],
                    'preferred_contact' => $c['preferred_contact'],
                    'read_at'           => $c['read'] ? now()->subDays(2) : null,
                ]
            );
        }

        // ── Coupons ──────────────────────────────────────────────────────────
        $coupons = [
            ['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'minimum_order' => null, 'usage_limit' => null, 'expires_at' => null],
            ['code' => 'FADO20',    'type' => 'percent', 'value' => 20, 'minimum_order' => 150, 'usage_limit' => 100, 'expires_at' => now()->addMonths(3)],
            ['code' => 'SAVE15EUR', 'type' => 'fixed',   'value' => 15, 'minimum_order' => 75,  'usage_limit' => 200, 'expires_at' => now()->addMonths(6)],
            ['code' => 'EXPIRED5',  'type' => 'fixed',   'value' => 5,  'minimum_order' => null, 'usage_limit' => null, 'expires_at' => now()->subDays(10)],
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(['code' => $c['code']], $c + ['times_used' => 0, 'is_active' => true]);
        }

        $this->command->info('Dummy customers, orders, wishlists, consultations and coupons seeded.');
    }
}
