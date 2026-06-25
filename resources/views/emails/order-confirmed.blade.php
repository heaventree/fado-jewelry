@php
    $storeName = \App\Models\Setting::get('store_name', 'FADÓ Jewellery');
    $storeEmail = \App\Models\Setting::get('store_email', 'info@fadojewellery.ie');
    $addr = $order->shipping_address;
@endphp
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">

<h2 style="color: #044705;">Thank you for your order!</h2>

<p>Hi {{ $order->user?->name ?? $addr['name'] ?? 'there' }},</p>

<p>Your order <strong>#{{ $order->order_number }}</strong> has been confirmed and is being prepared.</p>

<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
        <tr style="background: #f5f5f5;">
            <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Item</th>
            <th style="text-align: center; padding: 8px; border-bottom: 1px solid #ddd;">Qty</th>
            <th style="text-align: right; padding: 8px; border-bottom: 1px solid #ddd;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $item->product?->name ?? 'Product' }}</td>
            <td style="text-align: center; padding: 8px; border-bottom: 1px solid #eee;">{{ $item->quantity }}</td>
            <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;">€{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="padding: 8px; text-align: right; font-weight: bold;">Total:</td>
            <td style="padding: 8px; text-align: right; font-weight: bold;">€{{ number_format((float) $order->total, 2) }}</td>
        </tr>
    </tfoot>
</table>

@if($addr)
<p><strong>Delivery address:</strong><br>
{{ $addr['name'] ?? '' }}<br>
{{ $addr['line1'] ?? '' }}
@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif<br>
{{ $addr['city'] ?? '' }}, {{ $addr['county'] ?? '' }} {{ $addr['postcode'] ?? '' }}<br>
{{ $addr['country'] ?? '' }}
</p>
@endif

<p>If you have any questions about your order, please contact us at <a href="mailto:{{ $storeEmail }}">{{ $storeEmail }}</a>.</p>

<p>Thank you,<br>{{ $storeName }}</p>

</body>
</html>
