@extends('public.layout')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-16 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-black text-slate-900 uppercase">Posters & Activity Media Gallery</h1>
        <p class="text-xs text-slate-500 font-medium">Published Public Content</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mediaItems as $media)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            @if($media->file_path)
            <img src="{{ asset('storage/' . $media->file_path) }}" class="h-48 w-full object-cover">
            @else
            <div class="h-48 bg-slate-800 flex items-center justify-center text-white font-bold p-4 text-center">
                {{ $media->title }}
            </div>
            @endif
            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded bg-teal-100 text-teal-800">{{ $media->media_type }}</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-1">{{ $media->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $media->description }}</p>
                </div>
                <div class="text-[10px] text-slate-400 font-medium pt-2 border-t border-slate-100">
                    Event Date: {{ $media->event_date ? $media->event_date->format('M d, Y') : 'N/A' }}
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-slate-400 text-xs italic">
            No published media content available.
        </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $mediaItems->links() }}
    </div>
</div>
@endsection
