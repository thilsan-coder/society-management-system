@extends('public.layout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-16 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-black text-slate-900 uppercase">Executive Committee Roster</h1>
        <p class="text-xs text-slate-500 font-medium">Official Leadership of Bonds of Friendship Association - STR</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($committee as $m)
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3 hover:border-teal-500 transition-colors">
            <div class="h-16 w-16 mx-auto rounded-full bg-teal-600 text-white font-extrabold text-xl flex items-center justify-center shadow-md">
                {{ strtoupper(substr($m->user?->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">{{ $m->user?->name }}</h3>
                <span class="inline-block px-3 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-teal-50 text-teal-700 border border-teal-200 mt-1">
                    {{ $m->user?->role }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
