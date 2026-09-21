@extends('public.layout')

@section('title', 'Executive Committee - Bonds of Friendship Association - STR')

@section('content')
<div class="py-12 space-y-16">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-16 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Governance & Leadership</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Executive <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Committee</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Meet the elected executive officers responsible for leading, managing, and maintaining the governance of Bonds of Friendship Association - STR.
            </p>
        </div>
    </section>

    <!-- Committee Grid Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($committee as $m)
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 p-8 text-center space-y-6 hover:border-teal-500/50 hover:-translate-y-1 transition-all duration-300 shadow-xl group relative overflow-hidden">
                <!-- Background Accent Glow -->
                <div class="absolute -top-12 -right-12 w-24 h-24 rounded-full bg-teal-500/5 blur-xl group-hover:bg-teal-500/10 transition-colors"></div>

                <!-- Avatar Circle -->
                <div class="relative z-10">
                    <div class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-teal-400 via-teal-600 to-slate-900 text-slate-950 font-black text-3xl flex items-center justify-center shadow-xl shadow-teal-500/20 group-hover:scale-105 transition-transform duration-300 border-2 border-teal-400/40">
                        {{ strtoupper(substr($m->user?->name ?? 'M', 0, 2)) }}
                    </div>
                </div>

                <!-- Officer Name & Role -->
                <div class="space-y-2 relative z-10">
                    <h3 class="text-xl font-bold text-white group-hover:text-teal-400 transition-colors">{{ $m->user?->name }}</h3>
                    <div class="inline-block">
                        <span class="px-4 py-1 text-xs font-black uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/30 shadow-inner">
                            {{ $m->user?->role }}
                        </span>
                    </div>
                </div>

                <!-- Executive Role Description snippet -->
                <div class="pt-4 border-t border-slate-800 text-slate-400 text-xs leading-relaxed relative z-10">
                    @if(strtolower($m->user?->role) === 'president')
                        Responsible for overall society governance, presiding over monthly assemblies, and approving official expenditures, reports, and media content.
                    @elseif(strtolower($m->user?->role) === 'secretary')
                        Manages administrative affairs, official meeting schedules, agenda preparation, and accurate record-keeping of assembly proceedings.
                    @elseif(strtolower($m->user?->role) === 'treasurer')
                        Oversees financial operations, monthly Santha collection records, fine balance tracking, payment receipt issuance, and monthly financial reporting.
                    @elseif(strtolower($m->user?->role) === 'media')
                        Handles public announcements, activity event posters, media publication workflows, and public portal updates.
                    @else
                        Executive committee member contributing to general society governance, committee deliberations, and community initiatives.
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 text-slate-500 text-xs italic bg-slate-900 rounded-3xl border border-slate-800">
                No committee members currently listed in the database system.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Governance Note -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 text-xs text-slate-400 space-y-2">
            <span class="text-teal-400 font-bold uppercase tracking-wider block">Official Governance Note</span>
            <p>
                All executive decisions, financial expenditures, and public publications are subject to the society's formal multi-stage approval workflow and audited by the President.
            </p>
        </div>
    </section>
</div>
@endsection
