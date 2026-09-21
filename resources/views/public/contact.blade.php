@extends('public.layout')

@section('title', 'Contact Us - Bonds of Friendship Association - STR')

@section('content')
<div x-data="{ submitted: false, submitForm() { this.submitted = true; } }" class="py-12 space-y-12">
    <!-- Header Banner -->
    <section class="relative hero-gradient py-14 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-widest shadow-lg">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Get In Touch</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                Contact <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-200">Administration</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Have questions or need information? Reach out to the Bonds of Friendship Association - STR administration.
            </p>
        </div>
    </section>

    <!-- Split Grid Layout -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left Column: Official Contact Info Card -->
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 p-8 space-y-8 shadow-xl relative overflow-hidden">
                <div class="flex items-center space-x-4 border-b border-slate-800 pb-6">
                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-16 object-contain rounded-full bg-white p-1 border border-teal-500/30 shadow-lg">
                    <div>
                        <h3 class="text-lg font-black text-white uppercase tracking-wide">Bonds of Friendship</h3>
                        <p class="text-xs font-extrabold text-teal-400 uppercase tracking-widest mt-0.5">Association - STR</p>
                    </div>
                </div>

                <div class="space-y-6 text-xs text-slate-300">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-bold text-base shrink-0">📍</div>
                        <div>
                            <span class="block text-slate-400 font-bold uppercase text-[10px] tracking-wider">Official Venue</span>
                            <p class="text-white font-medium text-sm mt-0.5">Society Main Assembly Hall</p>
                            <p class="text-slate-400 text-xs">STR Association Headquarters</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-bold text-base shrink-0">✉️</div>
                        <div>
                            <span class="block text-slate-400 font-bold uppercase text-[10px] tracking-wider">General Email</span>
                            <p class="text-white font-medium text-sm mt-0.5">contact@str-association.org</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-bold text-base shrink-0">🕒</div>
                        <div>
                            <span class="block text-slate-400 font-bold uppercase text-[10px] tracking-wider">Administration Hours</span>
                            <p class="text-white font-medium text-sm mt-0.5">Monthly Assemblies & Scheduled Meetings</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-[11px] text-slate-400 space-y-1">
                    <span class="text-teal-400 font-bold uppercase tracking-wider block">Member Account Access</span>
                    <p>Registered members requiring account balance or fine details must log in to the private member portal.</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Interactive Contact Form -->
        <div class="lg:col-span-7">
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 p-8 sm:p-10 space-y-6 shadow-xl">
                <div>
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight">Send a Message</h3>
                    <p class="text-xs text-slate-400 mt-1">Submit your general inquiry directly to the executive committee.</p>
                </div>

                <!-- Success Alert -->
                <div x-show="submitted" x-cloak class="p-4 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold space-y-1">
                    <div class="flex items-center space-x-2">
                        <span>✅</span>
                        <span>Inquiry Submitted Successfully!</span>
                    </div>
                    <p class="text-slate-300 text-[11px] font-normal">Thank you for contacting Bonds of Friendship Association - STR. The executive administration will review your message.</p>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-5" x-show="!submitted">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Your Full Name</label>
                            <input type="text" required placeholder="e.g. John Doe" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-3 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
                            <input type="email" required placeholder="name@example.com" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-3 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Subject</label>
                        <input type="text" required placeholder="e.g. General Inquiry / Event Information" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-3 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Message</label>
                        <textarea required rows="5" placeholder="Write your message here..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-4 py-3 text-xs focus:border-teal-500 focus:outline-none placeholder-slate-500"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-teal-500 hover:bg-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all">
                        Send Inquiry ➔
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
