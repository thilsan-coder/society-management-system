@extends('layouts.app')

@section('title', 'Treasurer Financial Management')

@section('content')
<div class="space-y-6">
    <!-- Top Financial Overview Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Income Collected</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">Rs {{ number_format($total_income, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Santha + Fines Collected</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Society Expenses</p>
            <h3 class="text-2xl font-bold text-rose-600 mt-1">Rs {{ number_format($total_expenses, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Total Ledger Expenses</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Net Society Balance</p>
            <h3 class="text-2xl font-bold {{ $net_balance >= 0 ? 'text-teal-600' : 'text-red-600' }} mt-1">Rs {{ number_format($net_balance, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Income minus Expenses</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Outstanding Dues</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">Rs {{ number_format($total_santha_outstanding + $total_fine_outstanding, 2) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Santha & Fine Balances</p>
        </div>
    </div>

    <!-- Quick Financial Actions & Members Dues -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Treasurer Quick Controls</h3>
            <a href="{{ route('payments.index') }}" class="w-full flex items-center justify-between p-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                <span>Record Payment & Receipt</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('fines.index') }}" class="w-full flex items-center justify-between p-3 bg-amber-50 text-amber-800 rounded-lg hover:bg-amber-100 transition-colors font-medium text-sm border border-amber-200">
                <span>Issue Spot / Default Fine</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </a>
            <a href="{{ route('expenses.index') }}" class="w-full flex items-center justify-between p-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium text-sm border border-slate-200">
                <span>Record Society Expense</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </a>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">Members with Outstanding Dues</h3>
            <div class="space-y-3">
                @forelse($members_with_due as $member)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $member->user?->name }} <span class="text-slate-400 font-mono">({{ $member->member_number }})</span></p>
                        <p class="text-[11px] text-slate-500">Santha Due: Rs {{ number_format($member->total_santha_outstanding, 2) }} | Fine Due: Rs {{ number_format($member->total_fine_outstanding, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-rose-600">Rs {{ number_format($member->total_outstanding, 2) }}</span>
                        <a href="{{ route('members.show', $member) }}" class="block text-[11px] text-teal-600 font-semibold hover:underline">View Account ↗</a>
                    </div>
                </div>
                @empty
                <p class="text-xs text-emerald-600 font-medium italic">All member accounts are fully settled!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
