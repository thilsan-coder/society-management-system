@extends('layouts.app')

@section('title', 'Media Team Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Draft Posters</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $draft_posters }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Submitted</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $submitted_posters }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Approved</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $approved_posters }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Published</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $published_posters }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Media Controls</h3>
            <a href="{{ route('media.create') }}" class="w-full flex items-center justify-between p-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                <span>Create Poster / Media</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('media.index') }}" class="w-full flex items-center justify-between p-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium text-sm border border-slate-200">
                <span>All Media Items</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">Recent Posters & Activities</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($recent_media as $media)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded bg-teal-100 text-teal-800">{{ $media->media_type }}</span>
                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded bg-slate-200 text-slate-700">{{ $media->status }}</span>
                    </div>
                    <p class="font-bold text-slate-900 truncate">{{ $media->title }}</p>
                    <a href="{{ route('media.show', $media) }}" class="inline-block text-teal-600 font-semibold hover:underline">View Content ↗</a>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No media items created yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
