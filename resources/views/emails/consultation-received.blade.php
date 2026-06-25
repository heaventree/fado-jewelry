<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">

<h2 style="color: #044705;">New Consultation Request</h2>

<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <tr>
        <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee; width: 120px;">Name</td>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $data['name'] ?? '—' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Email</td>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $data['email'] ?? '—' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Phone</td>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $data['phone'] ?? '—' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Message</td>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $data['message'] ?? '—' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; font-weight: bold;">Received</td>
        <td style="padding: 8px;">{{ now()->format('d M Y H:i') }}</td>
    </tr>
</table>

<p style="color: #999; font-size: 12px;">This is an internal notification from {{ \App\Models\Setting::get('store_name', 'FADÓ Jewellery') }}.</p>

</body>
</html>
