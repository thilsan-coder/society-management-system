@extends('layouts.app')

@section('title', 'Society Expenses Ledger')

@section('content')
<div x-data="{ expenseModal: false }" class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('expenses.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category, description..."
                class="h-10 px-3 border border-slate-300 rounded-xl text-xs shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 w-full md:w-64 placeholder-slate-400">
            <button type="submit" class="h-10 px-4 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-all shadow-sm">
                Filter
            </button>
        </form>

        @if(auth()->user()->hasRole(['president', 'treasurer']))
        <button @click="expenseModal = true" type="button" class="h-10 px-4 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs shadow-md shadow-rose-600/10 flex items-center transition-all whitespace-nowrap">
            + Record Expense
        </button>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Expense Date</th>
                        <th class="px-6 py-3.5">Description</th>
                        <th class="px-6 py-3.5">Payment Method</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $expense->category }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            {{ $expense->expense_date ? $expense->expense_date->format('d M Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-700">
                            {{ $expense->description ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs uppercase font-semibold text-slate-600">
                            {{ $expense->payment_method }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-rose-600">
                            Rs {{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ $expense->recorder?->name }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No expense records logged yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Create Expense Modal -->
    <div x-show="expenseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Record Society Expense</h3>
                <button @click="expenseModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Category *</label>
                    <input type="text" name="category" required placeholder="e.g. Maintenance, Utilities, Printing" class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amount (Rs) *</label>
                    <input type="number" name="amount" step="0.01" min="1" required class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs font-bold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Date *</label>
                    <input type="date" name="expense_date" value="{{ now()->toDateString() }}" required class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Method *</label>
                    <select name="payment_method" required class="custom-select w-full h-10 px-3 border border-slate-300 rounded-xl text-xs bg-white text-slate-800">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"></textarea>
                </div>
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button @click="expenseModal = false" type="button" class="h-9 px-4 text-xs font-semibold text-slate-500">Cancel</button>
                    <button type="submit" class="h-9 px-5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-md">Record Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
