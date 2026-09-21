@extends('layouts.app')

@section('title', 'Media Details - ' . $media->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('media.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Media</a>
        @if(auth()->user()->isPresident())
        <form action="{{ route('media.transition', $media) }}" method="POST" class="inline-flex space-x-2">
            @csrf
            @if(in_array($media->status, ['submitted', 'under_review']))
            <button type="submit" name="status" value="approved" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm">
                Approve Poster
            </button>
            <button type="submit" name="status" value="rejected" class="h-9 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-sm">
                Reject
            </button>
            @elseif($media->status === 'approved')
            <button type="submit" name="status" value="published" class="h-9 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg shadow-sm">
                Publish to Public Site 🌐
            </button>
            @endif
        </form>
        @endif
    </div>

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $media->title }}</h2>
                <p class="text-xs text-slate-500 mt-1">Submitted By: {{ $media->submitter?->name }} &bull; Category: <span class="uppercase font-semibold text-teal-700">{{ $media->media_type }}</span></p>
            </div>
            <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ 
                match($media->status) {
                    'approved', 'published' => 'bg-emerald-100 text-emerald-800',
                    'submitted', 'under_review' => 'bg-amber-100 text-amber-800',
                    'rejected' => 'bg-rose-100 text-rose-800',
                    default => 'bg-slate-100 text-slate-700'
                }
            }}">
                {{ str_replace('_', ' ', $media->status) }}
            </span>
        </div>

        @if($media->file_path)
        <div class="rounded-xl overflow-hidden border border-slate-200 max-h-96 flex items-center justify-center bg-slate-900">
            <img src="{{ asset('storage/' . $media->file_path) }}" alt="Poster" class="max-h-96 object-contain">
        </div>
        @endif

        <div class="space-y-2 text-xs text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-200">
            <p><strong>Event Date:</strong> {{ $media->event_date ? $media->event_date->format('M d, Y') : 'N/A' }}</p>
            <p><strong>Event Time:</strong> {{ $media->event_time ?? 'N/A' }}</p>
            <p><strong>Venue:</strong> {{ $media->venue ?? 'N/A' }}</p>
        </div>

        <div class="space-y-1">
            <h3 class="text-xs font-bold uppercase text-slate-400">Description</h3>
            <p class="text-sm text-slate-800 leading-relaxed">{{ $media->description ?? 'No description provided.' }}</p>
        </div>
    </div>
</div>
@endsection
