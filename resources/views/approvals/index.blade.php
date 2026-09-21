@extends('layouts.app')

@section('title', 'President Central Approvals Queue')

@section('content')
<div class="space-y-8">
    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 text-xs text-amber-900 flex items-center justify-between">
        <div>
            <span class="font-bold">President Governance Queue:</span> Review submitted meetings, financial reports, and posters. Only President-approved items become eligible for public publication.
        </div>
    </div>

    <!-- Pending Meetings -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">1. Pending Meeting Submissions</h3>
        <div class="space-y-3">
            @forelse($pendingMeetings as $meeting)
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $meeting->title }}</h4>
                    <p class="text-slate-500 mt-0.5">Submitted By: {{ $meeting->creator?->name }} &bull; Date: {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') }} &bull; Venue: {{ $meeting->venue }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('meetings.show', $meeting) }}" class="h-8 px-3 inline-flex items-center bg-slate-200 text-slate-700 font-bold rounded">Review</a>
                    <form action="{{ route('meetings.transition', $meeting) }}" method="POST" class="inline-flex space-x-2">
                        @csrf
                        <button type="submit" name="status" value="approved" class="h-8 px-3 bg-emerald-600 text-white font-bold rounded">Approve</button>
                        <button type="submit" name="status" value="rejected" class="h-8 px-3 bg-rose-600 text-white font-bold rounded">Reject</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic">No pending meeting submissions.</p>
            @endforelse
        </div>
    </div>

    <!-- Pending Reports -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">2. Pending Monthly Financial Reports</h3>
        <div class="space-y-3">
            @forelse($pendingReports as $report)
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $report->title }}</h4>
                    <p class="text-slate-500 mt-0.5">Submitted By: {{ $report->submitter?->name }} &bull; Period: {{ date('F Y', mktime(0,0,0, $report->month, 1, $report->year)) }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('reports.show', $report) }}" class="h-8 px-3 inline-flex items-center bg-slate-200 text-slate-700 font-bold rounded">Review Data</a>
                    <form action="{{ route('reports.transition', $report) }}" method="POST" class="inline-flex space-x-2">
                        @csrf
                        <button type="submit" name="status" value="approved" class="h-8 px-3 bg-emerald-600 text-white font-bold rounded">Approve</button>
                        <button type="submit" name="status" value="rejected" class="h-8 px-3 bg-rose-600 text-white font-bold rounded">Reject</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic">No pending report submissions.</p>
            @endforelse
        </div>
    </div>

    <!-- Pending Media -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">3. Pending Poster & Activity Submissions</h3>
        <div class="space-y-3">
            @forelse($pendingMedia as $media)
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $media->title }}</h4>
                    <p class="text-slate-500 mt-0.5">Submitted By: {{ $media->submitter?->name }} &bull; Category: {{ strtoupper($media->media_type) }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('media.show', $media) }}" class="h-8 px-3 inline-flex items-center bg-slate-200 text-slate-700 font-bold rounded">Review Media</a>
                    <form action="{{ route('media.transition', $media) }}" method="POST" class="inline-flex space-x-2">
                        @csrf
                        <button type="submit" name="status" value="approved" class="h-8 px-3 bg-emerald-600 text-white font-bold rounded">Approve</button>
                        <button type="submit" name="status" value="rejected" class="h-8 px-3 bg-rose-600 text-white font-bold rounded">Reject</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic">No pending poster submissions.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
