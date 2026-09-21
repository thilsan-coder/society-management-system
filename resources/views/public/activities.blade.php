@extends('public.layout')

@section('title', 'Activities & Events - Bonds of Friendship Association - STR')

@section('content')
<div x-data="{ lightboxImg: null }" class="py-12 space-y-12">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-14 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Community Programs</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Activities & <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Events</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Explore social activities, cultural celebrations, assembly meetings, and community initiatives organized by the association.
            </p>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('public.activities') }}" class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col sm:flex-row gap-4 justify-between items-center shadow-lg">
            <div class="w-full sm:w-96 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activities or events..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-2.5 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
            </div>

            <div class="w-full sm:w-auto flex gap-3">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                    Search
                </button>
                @if(request('search'))
                <a href="{{ route('public.activities') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Activities Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($activities as $act)
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 overflow-hidden flex flex-col justify-between hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 shadow-xl group">
                @if($act->file_path)
                <div class="h-56 overflow-hidden bg-slate-950 relative cursor-pointer" @click="lightboxImg = '{{ asset('storage/' . $act->file_path) }}'">
                    <img src="{{ asset('storage/' . $act->file_path) }}" alt="{{ $act->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                        🔍 Click to Enlarge
                    </div>
                </div>
                @else
                <div class="h-56 bg-gradient-to-br from-teal-950 via-slate-900 to-slate-950 flex flex-col items-center justify-center p-6 text-center border-b border-slate-800 space-y-2">
                    <span class="text-3xl">📅</span>
                    <h4 class="text-white font-black text-base">{{ $act->title }}</h4>
                </div>
                @endif

                <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-wider rounded bg-teal-500/10 text-teal-400 border border-teal-500/20">
                            {{ $act->media_type }}
                        </span>
                        <h3 class="text-lg font-bold text-white group-hover:text-teal-400 transition-colors leading-snug">{{ $act->title }}</h3>
                        <p class="text-slate-400 text-xs leading-relaxed line-clamp-3">{{ $act->description }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-800/80 text-[11px] text-slate-400 flex items-center justify-between font-medium">
                        <span>📅 {{ $act->event_date ? $act->event_date->format('M d, Y') : 'Date TBD' }}</span>
                        <span>📍 {{ $act->venue ?? 'Assembly Venue' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 text-slate-500 text-xs italic bg-slate-900 rounded-3xl border border-slate-800">
                No published activities or events available at this time.
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    </section>

    <!-- Alpine Lightbox Modal -->
    <template x-if="lightboxImg">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md" x-cloak>
            <div @click.away="lightboxImg = null" class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center">
                <button @click="lightboxImg = null" class="absolute -top-10 right-0 text-white font-bold text-lg bg-slate-900 px-3 py-1 rounded-lg border border-slate-700">✕ Close</button>
                <img :src="lightboxImg" class="max-h-[85vh] w-auto object-contain rounded-2xl shadow-2xl border border-slate-800">
            </div>
        </div>
    </template>
</div>
@endsection
