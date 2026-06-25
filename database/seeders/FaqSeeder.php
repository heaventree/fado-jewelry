<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'How do I create an account?', 'answer' => 'Click "Login" in the top right corner, then select "Register". You will need your name, email address and a password. Once registered you can save your wishlist, track orders and enjoy faster checkout.'],
            ['question' => 'What if I forget my password?', 'answer' => 'Click "Forgot your password?" on the login page and enter your email address. We will send you a link to reset your password.'],
            ['question' => 'Can I checkout as a guest?', 'answer' => 'Yes. You do not need an account to place an order. However, creating an account lets you track your orders and save your shipping details for next time.'],
            ['question' => 'How do I place an order?', 'answer' => 'Browse our collections, select your preferred metal and size, then add the item to your bag. When you are ready, proceed to checkout, fill in your shipping details and confirm payment.'],
            ['question' => 'Can I change or cancel my order?', 'answer' => 'Please contact us as soon as possible. If your order has not yet been dispatched we will do our best to make changes or cancel it for you.'],
            ['question' => 'What ring sizes do you offer?', 'answer' => 'We offer US ring sizes from 5 to 10. If you are unsure of your size, please contact us and we can help you find the right fit.'],
            ['question' => 'Do you offer free shipping?', 'answer' => 'Yes, we offer free shipping on orders over the threshold shown at checkout. Shipping rates for Ireland and international orders are calculated at checkout.'],
            ['question' => 'How long does delivery take?', 'answer' => 'Ireland deliveries typically arrive within 2-4 business days. International orders usually take 5-10 business days depending on the destination.'],
            ['question' => 'Do you ship internationally?', 'answer' => 'Yes, we ship to the UK, EU, US, Canada, Australia and more. International shipping rates are calculated at checkout.'],
            ['question' => 'What is your return policy?', 'answer' => 'We accept returns within 30 days of delivery for unworn items in their original packaging. Please contact us to arrange a return.'],
            ['question' => 'How do I get a refund?', 'answer' => 'Once we receive your returned item and inspect it, we will process your refund to the original payment method within 5-7 business days.'],
            ['question' => 'Can I book a consultation?', 'answer' => 'Yes! Visit our Contact page to book a one-to-one consultation with our team.'],
            ['question' => 'Are your pieces hallmarked?', 'answer' => 'Yes, all our gold and silver pieces are hallmarked at the Dublin Assay Office, guaranteeing the quality of the metal used.'],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['sort_order' => $i + 1])
            );
        }
    }
}
