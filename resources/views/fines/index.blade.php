@extends('layouts.app')

@section('title', 'Fines Ledger & Penalty Management')

@section('content')
<div x-data="{ spotModal: false, defaultModal: false }" class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('fines.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reason, member..."
                class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-full md:w-64">

            <select name="fine_type" class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">All Fine Categories</option>
                <option value="spot" {{ request('fine_type') === 'spot' ? 'selected' : '' }}>Spot Fine</option>
                <option value="default" {{ request('fine_type') === 'default' ? 'selected' : '' }}>Default Fine</option>
                <option value="late_santha" {{ request('fine_type') === 'late_santha' ? 'selected' : '' }}>Late Santha Fine</option>
                <option value="absence" {{ request('fine_type') === 'absence' ? 'selected' : '' }}>Meeting Absence Fine</option>
            </select>

            <button type="submit" class="h-10 px-4 bg-slate-800 text-white font-medium rounded-lg text-sm hover:bg-slate-900 transition-colors shadow-sm">
                Filter
            </button>
        </form>

        @if(auth()->user()->hasRole(['president', 'treasurer']))
        <div class="flex items-center space-x-2">
            <button @click="spotModal = true" type="button" class="h-10 px-4 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg text-sm shadow transition-colors whitespace-nowrap">
                + Issue Spot Fine
            </button>
            <button @click="defaultModal = true" type="button" class="h-10 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-sm shadow transition-colors whitespace-nowrap">
                + Issue Default Fine
            </button>
        </div>
        @endif
    </div>

    <!-- Table (5 per page) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Member No & Name</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Reason</th>
                        <th class="px-6 py-3.5">Original</th>
                        <th class="px-6 py-3.5">Paid</th>
                        <th class="px-6 py-3.5">Remaining</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Doubling Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($fines as $fine)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $fine->member?->user?->name }}</p>
                            <p class="text-xs font-mono font-bold text-teal-700">{{ $fine->member?->member_number }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full {{ 
                                match($fine->fine_type) {
                                    'spot' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                    'default' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                    'late_santha' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                    'absence' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                }
                            }}">
                                {{ str_replace('_', ' ', $fine->fine_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-700 max-w-xs truncate">
                            {{ $fine->reason }}
                            @if($fine->doubling_count > 0)
                            <span class="ml-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                Doubled x{{ $fine->doubling_count }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600">Rs {{ number_format($fine->original_amount, 2) }}</td>
                        <td class="px-6 py-4 font-mono text-emerald-600 font-bold">Rs {{ number_format($fine->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 font-mono text-rose-600 font-bold">Rs {{ number_format($fine->remaining_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-full {{ $fine->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ str_replace('_', ' ', $fine->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($fine->fine_type === 'spot' && $fine->status !== 'paid' && auth()->user()->hasRole(['president', 'treasurer']))
                            <form action="{{ route('fines.double', $fine) }}" method="POST" onsubmit="return confirm('Double remaining unpaid balance (Rs {{ number_format($fine->remaining_amount, 2) }} -> Rs {{ number_format($fine->remaining_amount * 2, 2) }})?');">
                                @csrf
                                <button type="submit" class="h-8 px-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 rounded font-bold text-xs transition-colors">
                                    Double Unpaid Balance ✖2
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-slate-400 italic">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No fine records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $fines->links() }}
        </div>
    </div>

    <!-- Spot Fine Modal -->
    <div x-show="spotModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Issue Spot Fine</h3>
                <button @click="spotModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form action="{{ route('fines.spot') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Select Member *</label>
                    <select name="member_id" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm bg-white">
                        <option value="">-- Choose Member --</option>
                        @foreach($allMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->user?->name }} ({{ $m->member_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reason *</label>
                    <input type="text" name="reason" required placeholder="e.g. Late Arrival to Assembly" class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Spot Fine Amount (Rs) *</label>
                    <input type="number" name="amount" value="50" min="1" step="1" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm font-bold">
                    <p class="text-[10px] text-amber-600 mt-1 font-medium">Note: Only unpaid remaining balance doubles during penalty cycles.</p>
                </div>
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button @click="spotModal = false" type="button" class="h-9 px-4 text-xs font-semibold text-slate-500">Cancel</button>
                    <button type="submit" class="h-9 px-5 bg-amber-600 text-white font-bold text-xs rounded-lg shadow">Issue Spot Fine</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Default Fine Modal -->
    <div x-show="defaultModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Issue Default Fine</h3>
                <button @click="defaultModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form action="{{ route('fines.default') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Select Member *</label>
                    <select name="member_id" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm bg-white">
                        <option value="">-- Choose Member --</option>
                        @foreach($allMembers as $m)
                        <option value="{{ $m->id }}">{{ $m->user?->name }} ({{ $m->member_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reason / Category *</label>
                    <input type="text" name="reason" required placeholder="e.g. Failure to perform duty" class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Default Fine Amount (Rs) *</label>
                    <input type="number" name="amount" value="100" min="1" step="1" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm font-bold">
                    <p class="text-[10px] text-slate-400 mt-1">Note: Default fines do NOT double automatically.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes / Description</label>
                    <textarea name="description" rows="2" class="w-full p-2 border border-slate-300 rounded-lg text-xs"></textarea>
                </div>
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button @click="defaultModal = false" type="button" class="h-9 px-4 text-xs font-semibold text-slate-500">Cancel</button>
                    <button type="submit" class="h-9 px-5 bg-rose-600 text-white font-bold text-xs rounded-lg shadow">Issue Default Fine</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
