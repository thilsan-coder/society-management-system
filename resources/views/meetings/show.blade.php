@extends('layouts.app')

@section('title', 'Meeting Details - ' . $meeting->title)

@section('content')
<div class="space-y-6">
    <!-- Meeting Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-bold text-slate-900">{{ $meeting->title }}</h2>
                <span class="px-2.5 py-0.5 text-xs font-bold uppercase rounded-full {{ 
                    match($meeting->status) {
                        'approved', 'published' => 'bg-emerald-100 text-emerald-800',
                        'submitted', 'under_review' => 'bg-amber-100 text-amber-800',
                        'rejected' => 'bg-rose-100 text-rose-800',
                        default => 'bg-slate-100 text-slate-700'
                    }
                }}">
                    {{ str_replace('_', ' ', $meeting->status) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Date: {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('l, M d, Y') }} &bull; Time: {{ $meeting->start_time ?? 'N/A' }} - {{ $meeting->end_time ?? 'N/A' }} &bull; Venue: {{ $meeting->venue ?? 'N/A' }}</p>
        </div>

        <!-- Workflow Transitions -->
        <div class="flex items-center space-x-2">
            @if(auth()->user()->isPresident())
            <form action="{{ route('meetings.transition', $meeting) }}" method="POST" class="inline-flex space-x-2">
                @csrf
                @if(in_array($meeting->status, ['submitted', 'under_review']))
                <button type="submit" name="status" value="approved" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm">
                    Approve
                </button>
                <button type="submit" name="status" value="rejected" class="h-9 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-sm">
                    Reject
                </button>
                @elseif($meeting->status === 'approved')
                <button type="submit" name="status" value="published" class="h-9 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg shadow-sm">
                    Publish Publicly 🌐
                </button>
                @endif
            </form>
            @endif
        </div>
    </div>

    @if($meeting->status === 'rejected' && $meeting->rejection_reason)
    <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs rounded-r-lg">
        <span class="font-bold">President Rejection Note:</span> {{ $meeting->rejection_reason }}
    </div>
    @endif

    <!-- Content Breakdown Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Discussed Topics</h3>
            <p class="text-sm text-slate-800 whitespace-pre-line">{{ $meeting->discussed_topics ?? 'No topics specified.' }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Decisions & Resolutions</h3>
            <p class="text-sm text-slate-800 whitespace-pre-line">{{ $meeting->decisions_resolutions ?? 'No decisions recorded.' }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Action Items</h3>
            <p class="text-sm text-slate-800 whitespace-pre-line">{{ $meeting->action_items ?? 'No action items recorded.' }}</p>
        </div>
    </div>

    <!-- Secretary Attendance Manager -->
    @can('update', $meeting)
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Record Member Attendance & Absence Fine</h3>
                <p class="text-xs text-slate-500">Mark members present or absent. Absent members automatically receive a meeting absence fine.</p>
            </div>
        </div>

        <form action="{{ route('meetings.attendance', $meeting) }}" method="POST" class="space-y-4">
            @csrf

            <div class="flex items-center space-x-3 max-w-xs">
                <label class="text-xs font-bold text-slate-700 uppercase">Absence Fine Amount (Rs):</label>
                <input type="number" name="absence_fine_amount" value="100" min="0" step="10" required
                    class="h-9 px-3 border border-slate-300 rounded-lg text-sm font-bold w-28">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($allMembers as $mem)
                @php
                    $att = $meeting->attendances->firstWhere('member_id', $mem->id);
                    $currentStatus = $att ? $att->status : 'present';
                @endphp
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-900">{{ $mem->user?->name }}</p>
                        <p class="text-[10px] text-slate-400 font-mono">{{ $mem->member_number }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="inline-flex items-center text-xs">
                            <input type="radio" name="attendance[{{ $mem->id }}]" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }} class="text-teal-600">
                            <span class="ml-1 text-emerald-700 font-semibold">Present</span>
                        </label>
                        <label class="inline-flex items-center text-xs">
                            <input type="radio" name="attendance[{{ $mem->id }}]" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }} class="text-rose-600">
                            <span class="ml-1 text-rose-700 font-semibold">Absent</span>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-end pt-3">
                <button type="submit" class="h-10 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow">
                    Save Attendance & Apply Absence Fines
                </button>
            </div>
        </form>
    </div>
    @endcan
</div>
@endsection
