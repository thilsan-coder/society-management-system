@extends('layouts.app')

@section('title', 'Monthly Santha Ledger (Rs 300/mo)')

@section('content')
<div class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('santhas.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search member, number..."
                class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-full md:w-64">

            <select name="status" class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">All Statuses</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Fully Paid</option>
            </select>

            <button type="submit" class="h-10 px-4 bg-slate-800 text-white font-medium rounded-lg text-sm hover:bg-slate-900 transition-colors shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('santhas.index') }}" class="h-10 px-3 flex items-center text-xs font-semibold text-slate-500 hover:text-slate-700">Reset</a>
            @endif
        </form>

        <div class="text-xs text-slate-500 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 font-semibold">
            Rule: Rs 300/mo. 3-Month CycleGrace Period (Mar, Jun, Sep, Dec). Late fine applies Month 4+.
        </div>
    </div>

    <!-- Table (5 per page) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Member Number</th>
                        <th class="px-6 py-3.5">Member Name</th>
                        <th class="px-6 py-3.5">Santha Period</th>
                        <th class="px-6 py-3.5">Due Date</th>
                        <th class="px-6 py-3.5">Target Amount</th>
                        <th class="px-6 py-3.5">Paid Amount</th>
                        <th class="px-6 py-3.5">Remaining</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($santhas as $santha)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-teal-700">
                            {{ $santha->member?->member_number }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $santha->member?->user?->name }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ date('F Y', mktime(0, 0, 0, $santha->month, 1, $santha->year)) }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $santha->due_date ? $santha->due_date->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-900 font-bold">
                            Rs {{ number_format($santha->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 font-mono text-emerald-600 font-bold">
                            Rs {{ number_format($santha->paid_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 font-mono text-rose-600 font-bold">
                            Rs {{ number_format($santha->remaining, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-full {{ $santha->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ str_replace('_', ' ', $santha->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No Santha records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $santhas->links() }}
        </div>
    </div>
</div>
@endsection
