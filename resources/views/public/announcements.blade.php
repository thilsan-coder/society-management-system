@extends('public.layout')

@section('title', 'Announcements & News - Bonds of Friendship Association - STR')

@section('content')
<div x-data="{ activeModal: null }" class="py-12 space-y-12">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-14 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Official News</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Society <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Announcements</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Stay informed with official published updates, notices, and press releases from the association leadership.
            </p>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('public.announcements') }}" class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col sm:flex-row gap-4 justify-between items-center shadow-lg">
            <div class="w-full sm:w-96 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search announcements..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-2.5 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
            </div>

            <div class="w-full sm:w-auto flex gap-3">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                    Search
                </button>
                @if(request('search'))
                <a href="{{ route('public.announcements') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Announcements List Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($announcements as $anc)
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 overflow-hidden flex flex-col justify-between hover:border-teal-500/50 transition-all duration-300 shadow-xl group">
                @if($anc->file_path)
                <div class="h-48 overflow-hidden bg-slate-950">
                    <img src="{{ asset('storage/' . $anc->file_path) }}" alt="{{ $anc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                @endif
                <div class="p-8 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                Announcement
                            </span>
                            <span class="text-xs text-slate-400 font-medium">📅 {{ $anc->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white group-hover:text-teal-400 transition-colors leading-snug">{{ $anc->title }}</h3>
                        <p class="text-slate-400 text-xs leading-relaxed line-clamp-3">{{ $anc->description }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <button @click="activeModal = {{ $anc->id }}" type="button" class="text-xs font-bold text-teal-400 hover:text-teal-300 uppercase tracking-wider flex items-center space-x-1">
                            <span>Read Full Announcement</span>
                            <span>➔</span>
                        </button>
                    </div>
                </div>

                <!-- Alpine.js Detail Modal -->
                <template x-if="activeModal === {{ $anc->id }}">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
                        <div @click.away="activeModal = null" class="bg-slate-900 border border-slate-800 rounded-3xl p-8 max-w-2xl w-full space-y-6 shadow-2xl relative">
                            <div class="flex items-start justify-between border-b border-slate-800 pb-4">
                                <div>
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20">Announcement</span>
                                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mt-2">{{ $anc->title }}</h3>
                                    <p class="text-xs text-slate-400 mt-1">Published on {{ $anc->created_at->format('F d, Y') }}</p>
                                </div>
                                <button @click="activeModal = null" class="p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700">✕</button>
                            </div>

                            @if($anc->file_path)
                            <div class="max-h-64 rounded-xl overflow-hidden bg-slate-950">
                                <img src="{{ asset('storage/' . $anc->file_path) }}" class="w-full h-full object-contain">
                            </div>
                            @endif

                            <div class="text-slate-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line max-h-60 overflow-y-auto pr-2">
                                {{ $anc->description }}
                            </div>

                            <div class="pt-4 border-t border-slate-800 flex justify-end">
                                <button @click="activeModal = null" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold uppercase">Close</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @empty
            <div class="col-span-2 text-center py-16 text-slate-500 text-xs italic bg-slate-900 rounded-3xl border border-slate-800">
                No published announcements available at this time.
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    </section>
</div>
@endsection
