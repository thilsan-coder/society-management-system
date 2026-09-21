@extends('layouts.app')

@section('title', 'Secretary Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Meetings</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $total_meetings }}</h3>
            </div>
            <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Draft Reports</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $draft_reports }}</h3>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Submitted for Review</p>
                <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $submitted_reports }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Actions & Recent Meetings -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Secretary Controls</h3>
            <a href="{{ route('meetings.create') }}" class="w-full flex items-center justify-between p-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                <span>Create New Meeting</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('reports.index') }}" class="w-full flex items-center justify-between p-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium text-sm border border-slate-200">
                <span>Society Monthly Reports</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">Recent Meetings & Attendance Status</h3>
            <div class="space-y-3">
                @forelse($recent_meetings as $meeting)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <a href="{{ route('meetings.show', $meeting) }}" class="font-bold text-slate-900 hover:text-teal-600">{{ $meeting->title }}</a>
                        <p class="text-[11px] text-slate-500 mt-0.5">Date: {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') }} &bull; Venue: {{ $meeting->venue ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-slate-200 text-slate-700">{{ $meeting->status }}</span>
                        <a href="{{ route('meetings.show', $meeting) }}" class="text-teal-600 font-semibold hover:underline">View ↗</a>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No meetings created yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
