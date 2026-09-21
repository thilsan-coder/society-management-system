<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $payment->receipt_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { border-bottom: 2px solid #00a896; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { float: left; width: 60px; height: 60px; }
        .title-container { float: left; margin-left: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #00a896; text-transform: uppercase; }
        .subtitle { font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase; }
        .receipt-no { float: right; text-align: right; }
        .receipt-no-box { font-size: 14px; font-weight: bold; color: #00a896; background: #e6f7f5; padding: 6px 12px; border: 1px solid #00a896; border-radius: 4px; display: inline-block; }
        .clear { clear: both; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #f9f9f9; padding: 10px; border-radius: 4px; }
        .info-table td { padding: 6px; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #00a896; color: #fff; font-weight: bold; text-align: left; padding: 8px; font-size: 11px; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .items-table tfoot td { font-weight: bold; background: #f0fdfa; border-top: 2px solid #00a896; }
        .footer { text-align: center; font-size: 10px; color: #888; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        <div class="title-container">
            <div class="title">Bonds of Friendship</div>
            <div class="subtitle">Association - STR</div>
            <div style="font-size: 10px; color: #888;">Official Payment Receipt</div>
        </div>
        <div class="receipt-no">
            <div class="receipt-no-box">{{ $payment->receipt_number }}</div>
            <div style="font-size: 11px; color: #555; margin-top: 5px;">Date: {{ $payment->payment_date->format('d/m/Y') }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Member Details:</strong><br>
                Name: {{ $payment->member?->user?->name }}<br>
                Member No: <strong>{{ $payment->member?->member_number }}</strong><br>
                Email: {{ $payment->member?->user?->email }}
            </td>
            <td width="50%">
                <strong>Payment Information:</strong><br>
                Method: {{ strtoupper($payment->payment_method) }}<br>
                Reference: {{ $payment->reference_number ?? 'N/A' }}<br>
                Issued By: {{ $payment->treasurer?->name ?? 'Treasurer' }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th style="text-align: right;">Allocated Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment->items as $item)
            <tr>
                <td>
                    @if($item->payable_type === 'App\Models\Santha')
                        Monthly Santha: {{ date('F Y', mktime(0,0,0, $item->payable?->month, 1, $item->payable?->year)) }}
                    @elseif($item->payable_type === 'App\Models\Fine')
                        Fine ({{ strtoupper($item->payable?->fine_type) }}): {{ $item->payable?->reason }}
                    @else
                        {{ class_basename($item->payable_type) }} #{{ $item->payable_id }}
                    @endif
                </td>
                <td style="text-align: right; font-family: monospace;">Rs {{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL AMOUNT PAID</td>
                <td style="text-align: right; font-family: monospace; font-size: 14px; color: #00a896;">Rs {{ number_format($payment->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Thank you for your contribution to Bonds of Friendship Association - STR.<br>
        This is a computer-generated receipt. Central Database Verified.
    </div>

</body>
</html>
