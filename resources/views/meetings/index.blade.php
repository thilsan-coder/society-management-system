@extends('layouts.app')

@section('title', 'Society Meetings & Minutes')

@section('content')
<div class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('meetings.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, venue..."
                class="h-10 px-3 border border-slate-300 rounded-xl text-xs shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 w-full md:w-64 placeholder-slate-400">

            <select name="status" class="custom-select h-10 px-3 border border-slate-300 rounded-xl text-xs shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white text-slate-700">
                <option value="">All Workflow States</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            </select>

            <button type="submit" class="h-10 px-4 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-all shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('meetings.index') }}" class="h-10 px-3 flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>

        @can('create', App\Models\Meeting::class)
        <a href="{{ route('meetings.create') }}" class="h-10 px-4 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-xl text-xs shadow-md shadow-teal-600/10 flex items-center transition-all whitespace-nowrap">
            + Schedule Meeting
        </a>
        @endcan
    </div>

    <!-- Standardized Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Meeting Title</th>
                        <th class="px-6 py-3.5">Meeting Date</th>
                        <th class="px-6 py-3.5">Time & Venue</th>
                        <th class="px-6 py-3.5">Workflow Status</th>
                        <th class="px-6 py-3.5">Organized By</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($meetings as $meeting)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            <a href="{{ route('meetings.show', $meeting) }}" class="hover:text-teal-600">{{ $meeting->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            <p>{{ $meeting->start_time ?? 'N/A' }} - {{ $meeting->end_time ?? 'N/A' }}</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $meeting->venue ?? 'Main Hall' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full {{ 
                                match($meeting->status) {
                                    'approved', 'published' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                    'submitted', 'under_review' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                    'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                }
                            }}">
                                {{ str_replace('_', ' ', $meeting->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            {{ $meeting->creator?->name }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('meetings.show', $meeting) }}" class="h-8 px-3 inline-flex items-center bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg font-bold text-xs transition-colors">
                                View Details ➔
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No meetings found matching your filter criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $meetings->links() }}
        </div>
    </div>
</div>
@endsection
