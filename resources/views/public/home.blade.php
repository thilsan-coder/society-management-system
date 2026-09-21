@extends('public.layout')

@section('title', 'Bonds of Friendship Association - STR (Official Portal)')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)" class="space-y-24">

    <!-- Premium Hero Section -->
    <section class="relative hero-gradient pt-16 pb-24 overflow-hidden border-b border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-8"
                x-show="loaded"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0">

                <!-- Society Badge Ring -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-teal-500/5">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span>Official Association Portal</span>
                </div>

                <!-- Logo & Headline -->
                <div class="space-y-4">
                    <div class="inline-block p-3 rounded-full bg-slate-900/90 border border-teal-500/30 shadow-2xl shadow-teal-500/10">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 w-24 object-contain rounded-full bg-white p-1">
                    </div>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight uppercase leading-tight">
                        Bonds of Friendship <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Association</span>
                    </h1>
                    <p class="text-xs sm:text-sm font-extrabold uppercase tracking-widest text-teal-400">
                        STR &bull; Unity &bull; Fellowship &bull; Community Service
                    </p>
                </div>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                    Welcome to the official portal of Bonds of Friendship Association - STR. Fostering unity, mutual fellowship, organized governance, and community growth.
                </p>

                <!-- CTA Buttons -->
                <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('public.about') }}" class="px-7 py-3.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg shadow-teal-500/20 hover:scale-105 transition-all">
                        Discover Our Mission
                    </a>
                    <a href="{{ route('public.activities') }}" class="px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white border border-slate-700 rounded-xl text-xs font-extrabold uppercase tracking-wider hover:scale-105 transition-all">
                        View Activities & Events
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Executive Committee Highlights -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800 pb-6">
            <div>
                <span class="text-xs font-bold text-teal-400 uppercase tracking-widest">Leadership</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight mt-1">Executive Committee</h2>
            </div>
            <a href="{{ route('public.committee') }}" class="text-xs font-bold text-teal-400 hover:text-teal-300 uppercase tracking-wider transition-colors">
                View Full Roster ➔
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($committee as $m)
            <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-6 text-center space-y-4 hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 group shadow-lg">
                <div class="h-16 w-16 mx-auto rounded-full bg-gradient-to-br from-teal-500 to-teal-700 text-slate-950 font-black text-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($m->user?->name ?? 'M', 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-bold text-white text-base group-hover:text-teal-400 transition-colors">{{ $m->user?->name }}</h3>
                    <span class="inline-block px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20 mt-2">
                        {{ $m->user?->role }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-10 text-slate-500 text-xs italic bg-slate-900 rounded-xl border border-slate-800">
                Executive committee members list will appear here once configured.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Published Activities & Events Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800 pb-6">
            <div>
                <span class="text-xs font-bold text-teal-400 uppercase tracking-widest">Community</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight mt-1">Activities & Events</h2>
            </div>
            <a href="{{ route('public.activities') }}" class="text-xs font-bold text-teal-400 hover:text-teal-300 uppercase tracking-wider transition-colors">
                All Activities ➔
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($activities as $act)
            <div class="bg-slate-900/90 rounded-2xl border border-slate-800 overflow-hidden flex flex-col hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 shadow-xl group">
                @if($act->file_path)
                <div class="h-48 overflow-hidden bg-slate-950">
                    <img src="{{ asset('storage/' . $act->file_path) }}" alt="Event Poster" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                @else
                <div class="h-48 bg-gradient-to-br from-teal-900 to-slate-950 flex items-center justify-center p-6 text-center text-white font-black text-base border-b border-slate-800">
                    {{ $act->title }}
                </div>
                @endif
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-wider rounded bg-teal-500/10 text-teal-400 border border-teal-500/20">{{ $act->media_type }}</span>
                        <h3 class="text-base font-bold text-white mt-3 group-hover:text-teal-400 transition-colors">{{ $act->title }}</h3>
                        <p class="text-slate-400 text-xs mt-2 line-clamp-3 leading-relaxed">{{ $act->description }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80 text-[11px] text-slate-400 flex items-center justify-between font-medium">
                        <span>📅 {{ $act->event_date ? $act->event_date->format('M d, Y') : 'Date TBD' }}</span>
                        <span>📍 {{ $act->venue ?? 'Main Hall' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-slate-500 text-xs italic bg-slate-900 rounded-2xl border border-slate-800">
                No published activity posters available at this time.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Latest Announcements Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800 pb-6">
            <div>
                <span class="text-xs font-bold text-teal-400 uppercase tracking-widest">Updates</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight mt-1">Latest Announcements</h2>
            </div>
            <a href="{{ route('public.announcements') }}" class="text-xs font-bold text-teal-400 hover:text-teal-300 uppercase tracking-wider transition-colors">
                All Announcements ➔
            </a>
        </div>

        <div class="space-y-4">
            @forelse($announcements as $anc)
            <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-teal-500/50 transition-all duration-300">
                <div class="space-y-1 max-w-2xl">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-400">Announcement</span>
                    <h3 class="text-base font-bold text-white">{{ $anc->title }}</h3>
                    <p class="text-slate-400 text-xs line-clamp-2">{{ $anc->description }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs text-slate-400 font-medium block">{{ $anc->created_at->format('M d, Y') }}</span>
                    <a href="{{ route('public.announcements') }}" class="inline-block text-xs font-bold text-teal-400 hover:text-teal-300 mt-1">Read More ➔</a>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-500 text-xs italic bg-slate-900 rounded-xl border border-slate-800">
                No published announcements available at this time.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Bottom Call-To-Action Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 rounded-3xl p-8 sm:p-12 text-center space-y-6 border border-teal-500/30 shadow-2xl relative overflow-hidden">
            <h2 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-tight">Authorized Member Portal Access</h2>
            <p class="text-slate-300 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
                Log in to your private account to view your personal profile, Santha status, payment receipts, and meeting attendance records.
            </p>
            <div class="pt-2">
                <a href="{{ route('login') }}" class="px-8 py-3.5 bg-teal-400 hover:bg-teal-300 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-xl hover:scale-105 transition-all inline-block">
                    Sign In to Member Portal ➔
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
