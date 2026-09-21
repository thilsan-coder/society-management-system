<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report->title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { border-bottom: 2px solid #00a896; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { float: left; width: 50px; height: 50px; }
        .title-container { float: left; margin-left: 15px; }
        .title { font-size: 16px; font-weight: bold; color: #00a896; }
        .subtitle { font-size: 11px; font-weight: bold; color: #555; }
        .clear { clear: both; }
        .section-title { font-size: 12px; font-weight: bold; color: #00a896; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-top: 15px; margin-bottom: 10px; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .report-table td { padding: 6px; border-bottom: 1px solid #eee; }
        .report-table tr:nth-child(even) { background-color: #fcfcfc; }
        .amount { text-align: right; font-family: monospace; font-weight: bold; }
        .footer { text-align: center; font-size: 9px; color: #888; margin-top: 30px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        <div class="title-container">
            <div class="title">Bonds of Friendship Association - STR</div>
            <div class="subtitle">{{ $report->title }}</div>
            <div style="font-size: 9px; color: #888;">Generated from Central Financial Database &bull; Status: {{ strtoupper($report->status) }}</div>
        </div>
        <div class="clear"></div>
    </div>

    @php $data = $report->summary_json; @endphp

    <div class="section-title">1. Monthly Santha Collection Section</div>
    <table class="report-table">
        <tr><td>Total Active Members:</td><td class="amount">{{ $data['total_members'] ?? 0 }}</td></tr>
        <tr><td>Expected Monthly Santha (Rs 300 / member):</td><td class="amount">Rs {{ number_format($data['expected_santha'] ?? 0, 2) }}</td></tr>
        <tr><td>Actual Santha Collected:</td><td class="amount" style="color: #00a896;">Rs {{ number_format($data['santha_collected'] ?? 0, 2) }}</td></tr>
        <tr><td>Santha Outstanding Balance:</td><td class="amount" style="color: #d90429;">Rs {{ number_format($data['santha_outstanding'] ?? 0, 2) }}</td></tr>
    </table>

    <div class="section-title">2. Fines Breakdown Section</div>
    <table class="report-table">
        <tr><td>Total Fines Issued:</td><td class="amount">Rs {{ number_format($data['total_fines_issued'] ?? 0, 2) }}</td></tr>
        <tr><td>Spot Fines Collected:</td><td class="amount">Rs {{ number_format($data['spot_fine_collected'] ?? 0, 2) }}</td></tr>
        <tr><td>Default Fines Collected:</td><td class="amount">Rs {{ number_format($data['default_fine_collected'] ?? 0, 2) }}</td></tr>
        <tr><td>Meeting Absence Fines Collected:</td><td class="amount">Rs {{ number_format($data['absence_fine_collected'] ?? 0, 2) }}</td></tr>
        <tr><td>Late Santha Fines Collected:</td><td class="amount">Rs {{ number_format($data['late_santha_fine_collected'] ?? 0, 2) }}</td></tr>
        <tr><td>Total Fines Outstanding:</td><td class="amount" style="color: #d90429;">Rs {{ number_format($data['fine_outstanding'] ?? 0, 2) }}</td></tr>
    </table>

    <div class="section-title">3. Society Overall Financial Account</div>
    <table class="report-table">
        <tr><td>Total Income (Santha + Fines):</td><td class="amount" style="color: #00a896;">Rs {{ number_format($data['total_income'] ?? 0, 2) }}</td></tr>
        <tr><td>Total Ledger Expenses:</td><td class="amount" style="color: #d90429;">Rs {{ number_format($data['total_expenses'] ?? 0, 2) }}</td></tr>
        <tr><td><strong>Current Net Society Balance:</strong></td><td class="amount" style="font-size: 13px; color: #00a896;"><strong>Rs {{ number_format($data['current_society_balance'] ?? 0, 2) }}</strong></td></tr>
        <tr><td>Total System Outstanding Dues:</td><td class="amount" style="color: #f77f00;">Rs {{ number_format($data['total_outstanding_amount'] ?? 0, 2) }}</td></tr>
    </table>

    <div class="footer">
        Submitted By: {{ $report->submitter?->name ?? 'Treasurer' }} &bull; Reviewed By: {{ $report->reviewer?->name ?? 'President' }}<br>
        Bonds of Friendship Association - STR Central Management System
    </div>

</body>
</html>
