@extends('layouts.app')

@section('title', 'Payments & Official Receipts')

@section('content')
<div x-data="paymentModalData()" class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('payments.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search receipt no, member..."
                class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-full md:w-64">

            <button type="submit" class="h-10 px-4 bg-slate-800 text-white font-medium rounded-lg text-sm hover:bg-slate-900 transition-colors shadow-sm">
                Filter
            </button>
        </form>

        @can('create', App\Models\Payment::class)
        <button @click="openModal()" type="button" class="h-10 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow flex items-center transition-colors whitespace-nowrap">
            + Record Payment & Issue Receipt
        </button>
        @endcan
    </div>

    <!-- Table (5 per page) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Receipt Number</th>
                        <th class="px-6 py-3.5">Member Name & No</th>
                        <th class="px-6 py-3.5">Payment Date</th>
                        <th class="px-6 py-3.5">Type & Method</th>
                        <th class="px-6 py-3.5">Total Amount</th>
                        <th class="px-6 py-3.5">Recorded By</th>
                        <th class="px-6 py-3.5 text-right">PDF Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-teal-700">
                            <a href="{{ route('payments.show', $payment) }}" class="hover:underline">{{ $payment->receipt_number }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $payment->member?->user?->name }}</p>
                            <p class="text-xs font-mono text-slate-400">{{ $payment->member?->member_number }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <span class="px-2 py-0.5 font-bold uppercase rounded bg-slate-100 text-slate-700">{{ $payment->payment_type }}</span>
                            <span class="ml-1 uppercase text-slate-500 font-semibold">{{ $payment->payment_method }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-emerald-600 font-bold text-base">
                            Rs {{ number_format($payment->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $payment->treasurer?->name }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('payments.pdf', $payment) }}" class="h-8 px-3 inline-flex items-center bg-teal-50 hover:bg-teal-100 text-teal-700 rounded font-bold text-xs border border-teal-200">
                                Download PDF 📄
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No payment receipts recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Explicit Payment Allocation Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full shadow-2xl space-y-6 border border-slate-200 my-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Record Explicit Payment & Issue Receipt</h3>
                    <p class="text-xs text-slate-500">Select member and explicitly enter allocations for specific Santhas/Fines.</p>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
            </div>

            <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Select Member *</label>
                        <select x-model="selectedMemberId" @change="fetchUnpaidItems()" name="member_id" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm bg-white">
                            <option value="">-- Select Member --</option>
                            @foreach($allMembers as $m)
                            <option value="{{ $m->id }}">{{ $m->user?->name }} ({{ $m->member_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Method *</label>
                        <select name="payment_method" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm bg-white">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online / UPI</option>
                            <option value="check">Check</option>
                        </select>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div x-show="loading" class="text-center py-4 text-xs font-semibold text-teal-600">
                    Fetching member unpaid dues...
                </div>

                <!-- Explicit Allocations Breakdown -->
                <div x-show="!loading && selectedMemberId" class="space-y-4">
                    <!-- Unpaid Santhas -->
                    <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-200 pb-2">1. Monthly Santha Allocations</h4>
                        <template x-if="santhas.length === 0">
                            <p class="text-xs text-slate-400 italic">No unpaid Santhas found for this member.</p>
                        </template>
                        <template x-for="s in santhas" :key="s.id">
                            <div class="flex items-center justify-between bg-white p-2.5 rounded-lg border border-slate-200 text-xs">
                                <span class="font-semibold text-slate-800" x-text="s.label"></span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-slate-400">Rs</span>
                                    <input type="number" :name="'santha_allocations[' + s.id + ']'" step="0.01" min="0" :max="s.remaining" placeholder="0.00"
                                        class="h-8 w-28 px-2 border border-slate-300 rounded text-xs font-mono font-bold text-emerald-700">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Unpaid Fines -->
                    <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-800 border-b border-slate-200 pb-2">2. Fine Allocations</h4>
                        <template x-if="fines.length === 0">
                            <p class="text-xs text-slate-400 italic">No unpaid Fines found for this member.</p>
                        </template>
                        <template x-for="f in fines" :key="f.id">
                            <div class="flex items-center justify-between bg-white p-2.5 rounded-lg border border-slate-200 text-xs">
                                <span class="font-semibold text-slate-800" x-text="f.label"></span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-slate-400">Rs</span>
                                    <input type="number" :name="'fine_allocations[' + f.id + ']'" step="0.01" min="0" :max="f.remaining" placeholder="0.00"
                                        class="h-8 w-28 px-2 border border-slate-300 rounded text-xs font-mono font-bold text-rose-700">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reference No / Txn ID</label>
                            <input type="text" name="reference_number" placeholder="Optional" class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes</label>
                            <input type="text" name="notes" placeholder="Optional notes" class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                    <button @click="showModal = false" type="button" class="h-10 px-4 text-xs font-semibold text-slate-500">Cancel</button>
                    <button type="submit" :disabled="!selectedMemberId" class="h-10 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow disabled:opacity-50">
                        Record Explicit Payment & Generate Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function paymentModalData() {
        return {
            showModal: false,
            selectedMemberId: '',
            loading: false,
            santhas: [],
            fines: [],
            openModal() {
                this.showModal = true;
            },
            fetchUnpaidItems() {
                if (!this.selectedMemberId) return;
                this.loading = true;
                fetch(`/payments/unpaid-items/${this.selectedMemberId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.santhas = data.santhas;
                        this.fines = data.fines;
                        this.loading = false;
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
@endsection
