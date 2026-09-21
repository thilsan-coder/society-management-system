@extends('layouts.app')

@section('title', 'Monthly Report Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Reports</a>
        <div class="flex items-center space-x-3">
            <a href="{{ route('reports.pdf', $report) }}" class="h-9 px-4 bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm flex items-center hover:bg-slate-800 transition-all">
                Download PDF 📄
            </a>

            @if(in_array($report->status, ['draft', 'rejected']) && auth()->user()->hasRole(['president', 'treasurer', 'secretary']))
            <form action="{{ route('reports.transition', $report) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="submitted">
                <button type="submit" class="h-9 px-4 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Submit for President Review
                </button>
            </form>
            @endif

            @if(auth()->user()->isPresident())
            <form action="{{ route('reports.transition', $report) }}" method="POST" class="inline-flex space-x-2">
                @csrf
                @if(in_array($report->status, ['submitted', 'under_review']))
                <button type="submit" name="status" value="approved" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Approve Report
                </button>
                <button type="submit" name="status" value="rejected" class="h-9 px-4 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Reject
                </button>
                @elseif($report->status === 'approved')
                <button type="submit" name="status" value="published" class="h-9 px-4 bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                    Publish Publicly 🌐
                </button>
                @endif
            </form>
            @endif
        </div>
    </div>

    <!-- Main Report Body Card -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-md space-y-6">
        <div class="border-b border-slate-200 pb-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $report->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">Submitted By: {{ $report->submitter?->name }} &bull; Reviewed By: {{ $report->reviewer?->name ?? 'Pending' }}</p>
            </div>
            <span class="px-3 py-1 text-[10px] font-extrabold uppercase rounded-full {{ 
                match($report->status) {
                    'approved', 'published' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                    'submitted', 'under_review' => 'bg-amber-100 text-amber-800 border border-amber-200',
                    'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                    default => 'bg-slate-100 text-slate-700 border border-slate-200'
                }
            }}">
                {{ str_replace('_', ' ', $report->status) }}
            </span>
        </div>

        @php $data = $report->summary_json; @endphp

        <!-- 1. Santha Section -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-2">1. Monthly Santha Collection Overview</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Total Members</p>
                    <p class="text-lg font-bold text-slate-900 mt-1">{{ $data['total_members'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Expected Santha</p>
                    <p class="text-lg font-bold text-slate-900 mt-1">Rs {{ number_format($data['expected_santha'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                    <p class="text-emerald-800 font-semibold">Santha Collected</p>
                    <p class="text-lg font-bold text-emerald-700 mt-1">Rs {{ number_format($data['santha_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200">
                    <p class="text-rose-800 font-semibold">Santha Outstanding</p>
                    <p class="text-lg font-bold text-rose-700 mt-1">Rs {{ number_format($data['santha_outstanding'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- 2. Fine Section -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800 border-b border-slate-100 pb-2">2. Fines Breakdown Section</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Total Fines Issued</p>
                    <p class="text-base font-bold text-slate-900 mt-1">Rs {{ number_format($data['total_fines_issued'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Spot Fine Collected</p>
                    <p class="text-base font-bold text-emerald-600 mt-1">Rs {{ number_format($data['spot_fine_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Default Fine Collected</p>
                    <p class="text-base font-bold text-emerald-600 mt-1">Rs {{ number_format($data['default_fine_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Absence Fine Collected</p>
                    <p class="text-base font-bold text-emerald-600 mt-1">Rs {{ number_format($data['absence_fine_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-slate-500 font-semibold">Late Santha Fine Collected</p>
                    <p class="text-base font-bold text-emerald-600 mt-1">Rs {{ number_format($data['late_santha_fine_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200">
                    <p class="text-rose-800 font-semibold">Fine Outstanding</p>
                    <p class="text-base font-bold text-rose-700 mt-1">Rs {{ number_format($data['fine_outstanding'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- 3. Overall Account -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-2">3. Overall Society Account</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                    <p class="text-emerald-800 font-semibold">Total Income</p>
                    <p class="text-xl font-extrabold text-emerald-700 mt-1">Rs {{ number_format($data['total_income'] ?? 0, 2) }}</p>
                </div>
                <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200">
                    <p class="text-rose-800 font-semibold">Total Expenses</p>
                    <p class="text-xl font-extrabold text-rose-700 mt-1">Rs {{ number_format($data['total_expenses'] ?? 0, 2) }}</p>
                </div>
                <div class="p-4 bg-teal-50 rounded-2xl border border-teal-300">
                    <p class="text-teal-900 font-semibold">Current Society Balance</p>
                    <p class="text-xl font-extrabold text-teal-800 mt-1">Rs {{ number_format($data['current_society_balance'] ?? 0, 2) }}</p>
                </div>
                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
                    <p class="text-amber-800 font-semibold">Total System Dues</p>
                    <p class="text-xl font-extrabold text-amber-700 mt-1">Rs {{ number_format($data['total_outstanding_amount'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
