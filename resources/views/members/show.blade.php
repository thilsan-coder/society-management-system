@extends('layouts.app')

@section('title', 'Member Financial Account - ' . $member->member_number)

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="h-14 w-14 rounded-full bg-teal-600 text-white font-extrabold text-xl flex items-center justify-center shadow-md">
                {{ strtoupper(substr($member->user?->name ?? 'M', 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center space-x-3">
                    <h2 class="text-xl font-bold text-slate-900">{{ $member->user?->name }}</h2>
                    <span class="px-2.5 py-0.5 text-xs font-mono font-bold rounded bg-teal-50 text-teal-700 border border-teal-200">
                        {{ $member->member_number }}
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-slate-100 text-slate-700">
                        {{ $member->user?->role }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Email: {{ $member->user?->email }} &bull; Phone: {{ $member->user?->phone ?? 'N/A' }} &bull; Joined: {{ $member->join_date ? $member->join_date->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            @can('update', $member)
            <a href="{{ route('members.edit', $member) }}" class="h-9 px-4 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg flex items-center shadow-sm">
                Edit Profile & Role
            </a>
            @endcan
        </div>
    </div>

    <!-- Financial Account Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Santha Paid</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">Rs {{ number_format($member->total_santha_paid, 2) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Santha Due</p>
            <h3 class="text-xl font-bold text-rose-600 mt-1">Rs {{ number_format($member->total_santha_outstanding, 2) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fines Paid</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">Rs {{ number_format($member->total_fine_paid, 2) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fines Due</p>
            <h3 class="text-xl font-bold text-rose-600 mt-1">Rs {{ number_format($member->total_fine_outstanding, 2) }}</h3>
        </div>
    </div>

    <!-- Detailed Tabs for Santhas, Fines, Payments, Attendance -->
    <div x-data="{ tab: 'santhas' }" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex border-b border-slate-200 bg-slate-50">
            <button @click="tab = 'santhas'" :class="{ 'border-teal-600 text-teal-700 font-bold bg-white': tab === 'santhas', 'text-slate-500 hover:text-slate-700': tab !== 'santhas' }" class="px-6 py-3 text-xs uppercase tracking-wider font-semibold border-b-2 transition-colors">
                Monthly Santha (Rs 300)
            </button>
            <button @click="tab = 'fines'" :class="{ 'border-teal-600 text-teal-700 font-bold bg-white': tab === 'fines', 'text-slate-500 hover:text-slate-700': tab !== 'fines' }" class="px-6 py-3 text-xs uppercase tracking-wider font-semibold border-b-2 transition-colors">
                Fines Breakdown
            </button>
            <button @click="tab = 'payments'" :class="{ 'border-teal-600 text-teal-700 font-bold bg-white': tab === 'payments', 'text-slate-500 hover:text-slate-700': tab !== 'payments' }" class="px-6 py-3 text-xs uppercase tracking-wider font-semibold border-b-2 transition-colors">
                Payment History
            </button>
            <button @click="tab = 'attendance'" :class="{ 'border-teal-600 text-teal-700 font-bold bg-white': tab === 'attendance', 'text-slate-500 hover:text-slate-700': tab !== 'attendance' }" class="px-6 py-3 text-xs uppercase tracking-wider font-semibold border-b-2 transition-colors">
                Meeting Attendance
            </button>
        </div>

        <div class="p-6">
            <!-- Santha Tab -->
            <div x-show="tab === 'santhas'" class="space-y-4">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2">Cycle Period</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Paid</th>
                            <th class="px-4 py-2">Remaining</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($member->santhas as $santha)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ date('F Y', mktime(0,0,0, $santha->month, 1, $santha->year)) }}</td>
                            <td class="px-4 py-3 font-mono">Rs {{ number_format($santha->amount, 2) }}</td>
                            <td class="px-4 py-3 font-mono text-emerald-600">Rs {{ number_format($santha->paid_amount, 2) }}</td>
                            <td class="px-4 py-3 font-mono text-rose-600">Rs {{ number_format($santha->remaining, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $santha->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $santha->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-4 text-xs italic text-slate-400">No Santha records generated yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Fines Tab -->
            <div x-show="tab === 'fines'" class="space-y-4">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2">Fine Type</th>
                            <th class="px-4 py-2">Reason</th>
                            <th class="px-4 py-2">Original</th>
                            <th class="px-4 py-2">Paid</th>
                            <th class="px-4 py-2">Remaining</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($member->fines as $fine)
                        <tr>
                            <td class="px-4 py-3 uppercase font-bold text-xs text-amber-700">{{ $fine->fine_type }}</td>
                            <td class="px-4 py-3 text-slate-800">{{ $fine->reason }}</td>
                            <td class="px-4 py-3 font-mono">Rs {{ number_format($fine->original_amount, 2) }}</td>
                            <td class="px-4 py-3 font-mono text-emerald-600">Rs {{ number_format($fine->paid_amount, 2) }}</td>
                            <td class="px-4 py-3 font-mono text-rose-600 font-bold">Rs {{ number_format($fine->remaining_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $fine->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $fine->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-4 text-xs italic text-slate-400">No fines recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Payments Tab -->
            <div x-show="tab === 'payments'" class="space-y-4">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2">Receipt No</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Method</th>
                            <th class="px-4 py-2">Total Amount</th>
                            <th class="px-4 py-2 text-right">PDF</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($member->payments as $payment)
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-teal-700">{{ $payment->receipt_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 uppercase text-xs font-semibold">{{ $payment->payment_type }}</td>
                            <td class="px-4 py-3 uppercase text-xs">{{ $payment->payment_method }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-600">Rs {{ number_format($payment->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('payments.pdf', $payment) }}" class="px-2.5 py-1 bg-teal-50 text-teal-700 font-bold text-xs rounded border border-teal-200 hover:bg-teal-100">
                                    Download Receipt 📄
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-4 text-xs italic text-slate-400">No payment records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Attendance Tab -->
            <div x-show="tab === 'attendance'" class="space-y-4">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2">Meeting Title</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($member->attendances as $att)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $att->meeting->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($att->meeting->meeting_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full {{ $att->status === 'present' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $att->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-4 text-xs italic text-slate-400">No meeting attendance logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
