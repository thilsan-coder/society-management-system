@extends('public.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16 space-y-8">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center space-x-4 border-b border-slate-100 pb-4">
            <img src="{{ asset('images/logo.png') }}" class="h-16 w-16 object-contain rounded-full border border-slate-200 p-1">
            <div>
                <h1 class="text-2xl font-black text-slate-900 uppercase">About Our Society</h1>
                <p class="text-xs font-bold text-teal-600 uppercase tracking-widest">Bonds of Friendship Association - STR</p>
            </div>
        </div>

        <div class="space-y-4 text-sm text-slate-700 leading-relaxed">
            <p>
                <strong>Bonds of Friendship Association - STR</strong> is an official society dedicated to promoting mutual support, social fellowship, community growth, and organized governance among all members.
            </p>
            <p>
                Our core objectives include:
            </p>
            <ul class="list-disc list-inside space-y-1 text-xs text-slate-600 pl-2">
                <li>Building strong, lasting bonds of friendship and brotherhood among society members.</li>
                <li>Conducting monthly meetings, discussions, and decision-making for community welfare.</li>
                <li>Maintaining transparent financial accountability through monthly Santha and structured accounts.</li>
                <li>Organizing social, cultural, and community activities.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
