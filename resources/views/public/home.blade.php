@extends('public.layout')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 text-white py-20 px-4">
    <div class="max-w-5xl mx-auto text-center space-y-6">
        <div class="inline-block p-3 bg-white rounded-full shadow-lg mb-2">
            <img src="{{ asset('images/logo.png') }}" class="h-20 w-20 object-contain">
        </div>
        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight">Bonds of Friendship Association</h1>
        <p class="text-teal-300 font-bold uppercase tracking-widest text-sm sm:text-base">STR &bull; Unity &bull; Fellowship &bull; Service</p>
        <p class="text-slate-300 max-w-2xl mx-auto text-sm leading-relaxed">
            Welcome to the official public portal of Bonds of Friendship Association - STR. Fostering unity, friendship, and organized society management.
        </p>
        <div class="pt-4 flex items-center justify-center space-x-4">
            <a href="{{ route('public.about') }}" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow-lg transition-all">
                Learn About Our Society
            </a>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-lg text-sm transition-all">
                Member Portal Login ➔
            </a>
        </div>
    </div>
</div>

<!-- Published Posters & Activities Section -->
<div class="max-w-7xl mx-auto px-4 py-16 space-y-8">
    <div class="text-center space-y-2">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Official Activity Posters & Announcements</h2>
        <p class="text-xs text-slate-500 font-medium">President-Approved Public Society Updates</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posters as $poster)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            @if($poster->file_path)
            <img src="{{ asset('storage/' . $poster->file_path) }}" class="h-48 w-full object-cover">
            @else
            <div class="h-48 bg-gradient-to-br from-teal-700 to-slate-800 flex items-center justify-center text-white font-bold text-lg p-4 text-center">
                {{ $poster->title }}
            </div>
            @endif
            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <span class="px-2.5 py-0.5 text-[9px] font-extrabold uppercase rounded bg-teal-100 text-teal-800">{{ $poster->media_type }}</span>
                    <h3 class="text-base font-bold text-slate-900 mt-2">{{ $poster->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-3">{{ $poster->description }}</p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                    Event Date: {{ $poster->event_date ? $poster->event_date->format('M d, Y') : 'N/A' }}
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-slate-400 text-xs italic bg-white rounded-xl border border-slate-200">
            No published public posters at this time.
        </div>
        @endforelse
    </div>
</div>
@endsection
