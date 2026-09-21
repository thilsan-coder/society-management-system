@extends('layouts.app')

@section('title', 'Schedule Meeting')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <h2 class="text-lg font-bold text-slate-900">Schedule New Society Meeting</h2>
        <a href="{{ route('meetings.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Meetings</a>
    </div>

    <form action="{{ route('meetings.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meeting Title / Topic *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Executive Committee Monthly Review"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meeting Date *</label>
                <input type="date" name="meeting_date" value="{{ old('meeting_date', now()->toDateString()) }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Venue / Location</label>
                <input type="text" name="venue" value="{{ old('venue', 'Society Main Hall') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time', '18:00') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time', '20:00') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Discussed Topics / Agenda</label>
            <textarea name="discussed_topics" rows="3" class="w-full p-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('discussed_topics') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Decisions & Resolutions</label>
            <textarea name="decisions_resolutions" rows="3" class="w-full p-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('decisions_resolutions') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Action Items & Tasks</label>
            <textarea name="action_items" rows="3" class="w-full p-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('action_items') }}</textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-4">
            <button type="submit" name="action" value="draft" class="h-10 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-sm">
                Save as Draft
            </button>
            <button type="submit" name="action" value="submit" class="h-10 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow">
                Submit for President Review
            </button>
        </div>
    </form>
</div>
@endsection
