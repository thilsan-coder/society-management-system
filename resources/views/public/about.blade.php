@extends('public.layout')

@section('title', 'About Us - Bonds of Friendship Association - STR')

@section('content')
<div class="py-12 space-y-16">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-16 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Organizational Profile</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                About Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Association</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Dedicated to fostering genuine fellowship, structured financial governance, and impactful community welfare across our society.
            </p>
        </div>
    </section>

    <!-- Main Overview & Vision Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <div class="lg:col-span-5 space-y-6">
            <div class="p-4 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl relative overflow-hidden group">
                <div class="aspect-square rounded-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950/40 p-8 flex flex-col items-center justify-center text-center space-y-6 border border-slate-800/80">
                    <div class="p-4 rounded-full bg-slate-950 border border-teal-500/40 shadow-xl shadow-teal-500/10">
                        <img src="{{ asset('images/logo.png') }}" alt="Society Emblem" class="h-28 w-28 object-contain rounded-full bg-white p-1">
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-wide">Bonds of Friendship</h3>
                        <p class="text-xs font-extrabold text-teal-400 uppercase tracking-widest mt-1">Association - STR</p>
                    </div>
                    <span class="px-4 py-1.5 rounded-full bg-slate-900 border border-slate-700 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                        Official Association Charter
                    </span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-3">
                <span class="text-xs font-extrabold text-teal-400 uppercase tracking-widest">Our Founding Ethos</span>
                <h2 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-tight">Unity, Mutual Support & Integrity</h2>
            </div>
            
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                <strong>Bonds of Friendship Association - STR</strong> operates as a structured non-profit society built upon mutual trust, camaraderie, and democratic decision-making. Established to unify members under a shared vision of brotherhood and social development, the association provides a reliable framework for collaborative growth and assistance.
            </p>

            <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                Through regular monthly assemblies, transparent financial contributions (Santha), and organized social events, we maintain strong organizational discipline while honoring individual contributions to community progress.
            </p>

            <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-1">
                    <span class="text-teal-400 font-extrabold text-lg">Structured Meetings</span>
                    <p class="text-slate-400 text-xs">Formal monthly governance assemblies with documented minutes and attendance tracking.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-1">
                    <span class="text-teal-400 font-extrabold text-lg">Financial Audit</span>
                    <p class="text-slate-400 text-xs">Full monthly financial reporting and ledger oversight managed by executive officers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Pillars Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="text-xs font-bold text-teal-400 uppercase tracking-widest">Core Values</span>
            <h2 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">Pillars of Our Society</h2>
            <p class="text-slate-400 text-xs">The foundational principles that guide every decision and activity within the association.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pillar 1 -->
            <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 space-y-4 hover:border-teal-500/40 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-black text-xl group-hover:scale-110 transition-transform">
                    🤝
                </div>
                <h3 class="text-base font-bold text-white group-hover:text-teal-400 transition-colors">Fellowship & Brotherhood</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Fostering lifelong friendships, mutual respect, and assistance among all registered members and families.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 space-y-4 hover:border-teal-500/40 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-black text-xl group-hover:scale-110 transition-transform">
                    ⚖️
                </div>
                <h3 class="text-base font-bold text-white group-hover:text-teal-400 transition-colors">Democratic Governance</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Operating through an elected executive committee with multi-tiered approval workflows and audit trail logging.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 space-y-4 hover:border-teal-500/40 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-black text-xl group-hover:scale-110 transition-transform">
                    📊
                </div>
                <h3 class="text-base font-bold text-white group-hover:text-teal-400 transition-colors">Financial Accountability</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Clear record-keeping of monthly Santha collections, fines, expenses, and published executive financial reports.
                </p>
            </div>

            <!-- Pillar 4 -->
            <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 space-y-4 hover:border-teal-500/40 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-black text-xl group-hover:scale-110 transition-transform">
                    🌟
                </div>
                <h3 class="text-base font-bold text-white group-hover:text-teal-400 transition-colors">Community Welfare</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Organizing social events, cultural celebrations, educational support, and community outreach activities.
                </p>
            </div>
        </div>
    </section>

    <!-- Leadership Spotlight Snippet -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-800 pb-6">
            <div>
                <span class="text-xs font-bold text-teal-400 uppercase tracking-widest">Executive Roster</span>
                <h2 class="text-2xl font-black text-white uppercase tracking-tight mt-1">Leadership Team</h2>
            </div>
            <a href="{{ route('public.committee') }}" class="px-5 py-2.5 bg-slate-900 border border-slate-700 hover:border-teal-500/50 text-teal-400 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                Full Committee Roster ➔
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($committee as $m)
            <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-6 text-center space-y-3 hover:border-teal-500/40 transition-all group">
                <div class="h-14 w-14 mx-auto rounded-full bg-gradient-to-br from-teal-500 to-teal-700 text-slate-950 font-black text-lg flex items-center justify-center shadow-lg">
                    {{ strtoupper(substr($m->user?->name ?? 'M', 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm group-hover:text-teal-400 transition-colors">{{ $m->user?->name }}</h3>
                    <span class="inline-block px-2.5 py-0.5 text-[9px] font-extrabold uppercase tracking-wider rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20 mt-1">
                        {{ $m->user?->role }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-8 text-slate-500 text-xs italic bg-slate-900 rounded-xl border border-slate-800">
                Committee members list available upon system setup.
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
