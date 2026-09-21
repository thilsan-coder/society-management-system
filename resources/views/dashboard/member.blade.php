@extends('layouts.app')

@section('title', 'Member Private Portal')

@section('content')
<div class="space-y-6">
    <!-- Member Profile Header Card -->
    <div class="bg-gradient-to-r from-teal-800 to-teal-600 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="h-16 w-16 rounded-full bg-white text-teal-800 font-extrabold text-xl flex items-center justify-center shadow-md">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
                <div class="flex items-center space-x-2 mt-1 text-teal-100 text-xs font-mono">
                    <span>Member No: {{ $member?->member_number ?? 'N/A' }}</span>
                    <span>&bull;</span>
                    <span class="capitalize">Role: {{ auth()->user()->role }}</span>
                </div>
            </div>
        </div>

        <div class="mt-4 md:mt-0 text-right bg-white/10 px-4 py-3 rounded-xl backdrop-blur-sm border border-white/20">
            <p class="text-[10px] text-teal-100 uppercase tracking-wider font-semibold">Total Outstanding Balance</p>
            <p class="text-2xl font-extrabold text-amber-300 mt-0.5">Rs {{ number_format($total_outstanding, 2) }}</p>
        </div>
    </div>

    <!-- Personal Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Santha Paid</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">Rs {{ number_format($santha_paid, 2) }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Santha Outstanding</p>
            <h3 class="text-xl font-bold text-rose-600 mt-1">Rs {{ number_format($santha_outstanding, 2) }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fines Paid</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">Rs {{ number_format($fine_paid, 2) }}</h3>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fines Outstanding</p>
            <h3 class="text-xl font-bold text-rose-600 mt-1">Rs {{ number_format($fine_outstanding, 2) }}</h3>
        </div>
    </div>

    <!-- Payment History Table & Meeting Attendance History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Payment History -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">My Payment Receipts</h3>
            <div class="space-y-3">
                @forelse($recent_payments as $payment)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $payment->receipt_number }}</p>
                        <p class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }} &bull; Method: {{ strtoupper($payment->payment_method) }}</p>
                    </div>
                    <div class="text-right space-y-1">
                        <span class="font-extrabold text-emerald-600">Rs {{ number_format($payment->total_amount, 2) }}</span>
                        <div>
                            <a href="{{ route('payments.pdf', $payment) }}" class="text-[10px] bg-teal-50 text-teal-700 px-2 py-0.5 rounded border border-teal-200 font-bold hover:bg-teal-100">Download PDF 📄</a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No payments recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- My Attendance History -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">My Meeting Attendance Record</h3>
            <div class="space-y-3">
                @forelse($recent_attendances as $att)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $att->meeting->title }}</p>
                        <p class="text-[11px] text-slate-500">Date: {{ \Carbon\Carbon::parse($att->meeting->meeting_date)->format('d M Y') }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $att->status === 'present' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $att->status }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No attendance records logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
