@extends('layouts.app')

@section('title', 'Payment Receipt - ' . $payment->receipt_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Payments</a>
        <a href="{{ route('payments.pdf', $payment) }}" class="h-10 px-5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow flex items-center">
            Download PDF Receipt 📄
        </a>
    </div>

    <!-- Official Printable Receipt Container -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-md space-y-6">
        <!-- Receipt Header with Official Logo -->
        <div class="flex flex-col sm:flex-row items-center justify-between border-b-2 border-teal-600 pb-6 gap-4">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain rounded-full border border-slate-200 p-1">
                <div>
                    <h1 class="text-xl font-black text-slate-900 uppercase">Bonds of Friendship</h1>
                    <p class="text-xs font-bold text-teal-600 uppercase tracking-widest">Association - STR</p>
                    <p class="text-[10px] text-slate-400 mt-1">Official Payment Receipt & Confirmation</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-teal-50 text-teal-800 border border-teal-200 font-mono font-extrabold text-sm rounded-md shadow-sm">
                    {{ $payment->receipt_number }}
                </span>
                <p class="text-xs text-slate-500 mt-1 font-semibold">Date: {{ $payment->payment_date->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Member & Payment Info Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Member Details</p>
                <p class="text-sm font-bold text-slate-900 mt-1">{{ $payment->member?->user?->name }}</p>
                <p class="font-mono text-teal-700 font-bold mt-0.5">Member No: {{ $payment->member?->member_number }}</p>
                <p class="text-slate-500 mt-0.5">Email: {{ $payment->member?->user?->email }}</p>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Transaction Summary</p>
                <p class="text-xs font-bold text-slate-800 uppercase mt-1">Method: {{ strtoupper($payment->payment_method) }}</p>
                <p class="text-slate-500 mt-0.5">Reference: {{ $payment->reference_number ?? 'N/A' }}</p>
                <p class="text-slate-500 mt-0.5">Issued By Treasurer: {{ $payment->treasurer?->name ?? 'System' }}</p>
            </div>
        </div>

        <!-- Items Breakdown Table -->
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Allocated Payment Breakdown</h3>
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-600 font-semibold uppercase">
                    <tr>
                        <th class="px-4 py-2.5">Item Description</th>
                        <th class="px-4 py-2.5 text-right">Allocated Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($payment->items as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">
                            @if($item->payable_type === 'App\Models\Santha')
                                Monthly Santha: {{ date('F Y', mktime(0,0,0, $item->payable?->month, 1, $item->payable?->year)) }}
                            @elseif($item->payable_type === 'App\Models\Fine')
                                Fine ({{ strtoupper($item->payable?->fine_type) }}): {{ $item->payable?->reason }}
                            @else
                                {{ class_basename($item->payable_type) }} #{{ $item->payable_id }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">
                            Rs {{ number_format($item->amount, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-teal-50 text-slate-900 border-t-2 border-teal-600 font-bold">
                    <tr>
                        <td class="px-4 py-3 uppercase text-xs">Total Amount Paid</td>
                        <td class="px-4 py-3 text-right font-mono text-base text-emerald-700">
                            Rs {{ number_format($payment->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="pt-6 border-t border-slate-200 text-center text-[11px] text-slate-400 space-y-1">
            <p class="font-semibold text-slate-600">Thank you for your prompt contribution to Bonds of Friendship Association - STR.</p>
            <p>This receipt is computer-generated and officially verified centrally.</p>
        </div>
    </div>
</div>
@endsection
