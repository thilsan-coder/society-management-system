@extends('public.layout')

@section('title', 'Media Gallery - Bonds of Friendship Association - STR')

@section('content')
<div x-data="{ lightboxImg: null, lightboxTitle: '' }" class="py-12 space-y-12">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-14 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Public Archives</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Media <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Gallery</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Official collection of published activity posters, event photos, and association announcements.
            </p>
        </div>
    </section>

    <!-- Filter Tabs -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800 flex flex-wrap gap-2 justify-center items-center shadow-lg text-xs font-bold uppercase tracking-wider">
            <a href="{{ route('public.media') }}" class="px-5 py-2.5 rounded-xl transition-all {{ !request('media_type') ? 'bg-teal-500 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                All Media
            </a>
            <a href="{{ route('public.media', ['media_type' => 'poster']) }}" class="px-5 py-2.5 rounded-xl transition-all {{ request('media_type') == 'poster' ? 'bg-teal-500 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Posters
            </a>
            <a href="{{ route('public.media', ['media_type' => 'activity']) }}" class="px-5 py-2.5 rounded-xl transition-all {{ request('media_type') == 'activity' ? 'bg-teal-500 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Activities
            </a>
            <a href="{{ route('public.media', ['media_type' => 'announcement']) }}" class="px-5 py-2.5 rounded-xl transition-all {{ request('media_type') == 'announcement' ? 'bg-teal-500 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Announcements
            </a>
        </div>
    </section>

    <!-- Media Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($mediaItems as $media)
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 overflow-hidden flex flex-col justify-between hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 shadow-xl group">
                @if($media->file_path)
                <div class="h-60 bg-slate-950 overflow-hidden relative cursor-pointer" @click="lightboxImg = '{{ asset('storage/' . $media->file_path) }}'; lightboxTitle = '{{ addslashes($media->title) }}'">
                    <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                        🔍 View Image
                    </div>
                </div>
                @else
                <div class="h-60 bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 flex flex-col items-center justify-center p-6 text-center border-b border-slate-800 space-y-2">
                    <span class="text-4xl">🖼️</span>
                    <h4 class="text-white font-black text-sm">{{ $media->title }}</h4>
                </div>
                @endif

                <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                {{ $media->media_type }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $media->event_date ? $media->event_date->format('M d, Y') : $media->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <h3 class="font-bold text-white text-base mt-2 group-hover:text-teal-400 transition-colors">{{ $media->title }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">{{ $media->description }}</p>
                    </div>

                    @if($media->venue)
                    <div class="pt-3 border-t border-slate-800/80 text-[10px] text-slate-400 font-medium">
                        📍 Venue: {{ $media->venue }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 text-slate-500 text-xs italic bg-slate-900 rounded-3xl border border-slate-800">
                No published media items available in this category.
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $mediaItems->links() }}
        </div>
    </section>

    <!-- Alpine Lightbox Modal -->
    <template x-if="lightboxImg">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md" x-cloak>
            <div @click.away="lightboxImg = null" class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center space-y-3">
                <div class="w-full flex items-center justify-between text-white bg-slate-900 px-5 py-3 rounded-2xl border border-slate-800">
                    <span x-text="lightboxTitle" class="font-bold text-sm uppercase"></span>
                    <button @click="lightboxImg = null" class="text-slate-400 hover:text-white font-bold text-sm">✕ Close</button>
                </div>
                <img :src="lightboxImg" class="max-h-[75vh] w-auto object-contain rounded-2xl shadow-2xl border border-slate-800">
            </div>
        </div>
    </template>
</div>
@endsection
