@extends('public.layout')

@section('title', 'Members Directory - Bonds of Friendship Association - STR')

@section('content')
<div class="py-12 space-y-12">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-14 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Public Roster</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Members <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Directory</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Official directory of registered members belonging to Bonds of Friendship Association - STR.
            </p>
        </div>
    </section>

    <!-- Search & Filter Controls Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('public.members') }}" class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col sm:flex-row gap-4 justify-between items-center shadow-lg">
            <div class="w-full sm:w-80 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search member by name..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-2.5 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
            </div>

            <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3 items-center">
                <select name="role" class="custom-select w-full sm:w-48 bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-2.5 text-xs focus:border-teal-500 focus:outline-none">
                    <option value="">All Roles</option>
                    <option value="president" {{ request('role') == 'president' ? 'selected' : '' }}>President</option>
                    <option value="secretary" {{ request('role') == 'secretary' ? 'selected' : '' }}>Secretary</option>
                    <option value="treasurer" {{ request('role') == 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                    <option value="media" {{ request('role') == 'media' ? 'selected' : '' }}>Media Officer</option>
                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                </select>

                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                    Filter
                </button>
                @if(request('search') || request('role'))
                <a href="{{ route('public.members') }}" class="w-full sm:w-auto text-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Public Members Grid (Name & Role Only) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($members as $m)
            <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-6 flex flex-col items-center text-center space-y-4 hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 group shadow-xl">
                <!-- Member Avatar Initials -->
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-slate-800 to-slate-950 border border-slate-700 group-hover:border-teal-400/60 text-teal-400 font-black text-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-all">
                    {{ strtoupper(substr($m->user?->name ?? 'M', 0, 2)) }}
                </div>

                <!-- Public Data Only: Name & Role -->
                <div class="space-y-1.5">
                    <h3 class="font-bold text-white text-base group-hover:text-teal-400 transition-colors">{{ $m->user?->name }}</h3>
                    <div>
                        <span class="inline-block px-3 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20">
                            {{ $m->user?->role ?? 'Member' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 text-slate-500 text-xs italic bg-slate-900 rounded-2xl border border-slate-800">
                No members found matching your search criteria.
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $members->links() }}
        </div>
    </section>
</div>
@endsection
