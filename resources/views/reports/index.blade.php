@extends('layouts.app')

@section('title', 'Monthly Financial & Secretary Reports')

@section('content')
<div x-data="{ reportModal: false }" class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('reports.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-3">
            <select name="report_type" class="custom-select h-10 px-3 border border-slate-300 rounded-xl text-xs bg-white text-slate-700">
                <option value="">All Report Types</option>
                <option value="financial" {{ request('report_type') === 'financial' ? 'selected' : '' }}>Financial Report</option>
                <option value="secretary" {{ request('report_type') === 'secretary' ? 'selected' : '' }}>Secretary Report</option>
            </select>
            <button type="submit" class="h-10 px-4 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-all shadow-sm">
                Filter
            </button>
        </form>

        @if(auth()->user()->hasRole(['president', 'treasurer']))
        <button @click="reportModal = true" type="button" class="h-10 px-4 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-xl text-xs shadow-md shadow-teal-600/10 flex items-center transition-all whitespace-nowrap">
            + Generate Monthly Financial Report
        </button>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Report Title</th>
                        <th class="px-6 py-3.5">Type</th>
                        <th class="px-6 py-3.5">Period</th>
                        <th class="px-6 py-3.5">Workflow Status</th>
                        <th class="px-6 py-3.5">Submitted By</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            <a href="{{ route('reports.show', $report) }}" class="hover:text-teal-600">{{ $report->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold uppercase text-slate-700">
                            {{ $report->report_type }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            {{ date('F Y', mktime(0, 0, 0, $report->month, 1, $report->year)) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full {{ 
                                match($report->status) {
                                    'approved', 'published' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                    'submitted', 'under_review' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                    'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                }
                            }}">
                                {{ str_replace('_', ' ', $report->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ $report->submitter?->name }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('reports.show', $report) }}" class="h-8 px-3 inline-flex items-center bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg font-bold text-xs transition-colors">
                                View Details ➔
                            </a>
                            <a href="{{ route('reports.pdf', $report) }}" class="h-8 px-3 inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg font-bold text-xs transition-colors">
                                PDF 📄
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No monthly reports generated yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $reports->links() }}
        </div>
    </div>

    <!-- Generate Report Modal -->
    <div x-show="reportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Generate Monthly Financial Report</h3>
                <button @click="reportModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form action="{{ route('reports.generate') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Year *</label>
                        <select name="year" required class="custom-select w-full h-10 px-3 border border-slate-300 rounded-xl text-xs bg-white text-slate-800">
                            @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Month *</label>
                        <select name="month" required class="custom-select w-full h-10 px-3 border border-slate-300 rounded-xl text-xs bg-white text-slate-800">
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 italic">Report calculations are dynamically compiled from actual DB transactions.</p>
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button @click="reportModal = false" type="button" class="h-9 px-4 text-xs font-semibold text-slate-500">Cancel</button>
                    <button type="submit" class="h-9 px-5 bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs rounded-xl shadow-md">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
