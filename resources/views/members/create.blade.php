@extends('layouts.app')

@section('title', 'Create New Member (President Authority)')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Create New Member & Account</h2>
            <p class="text-xs text-slate-500">Only President is authorized to issue unique Member Numbers and create accounts.</p>
        </div>
        <a href="{{ route('members.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Directory</a>
    </div>

    <form action="{{ route('members.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unique Member Number *</label>
                <input type="text" name="member_number" value="{{ old('member_number', 'MEM-' . rand(100, 999)) }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                <p class="text-[10px] text-slate-400 mt-1">Single source of truth in members table.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Committee Role *</label>
                <select name="role" required class="custom-select w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white text-slate-800">
                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Normal Member</option>
                    <option value="president" {{ old('role') === 'president' ? 'selected' : '' }}>President</option>
                    <option value="secretary" {{ old('role') === 'secretary' ? 'selected' : '' }}>Secretary</option>
                    <option value="treasurer" {{ old('role') === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                    <option value="media" {{ old('role') === 'media' ? 'selected' : '' }}>Media Team</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Initial Password *</label>
                <input type="password" name="password" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Society Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', now()->toDateString()) }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Emergency Contact</label>
                <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Address</label>
            <textarea name="address" rows="2" class="w-full p-3 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">{{ old('address') }}</textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-4">
            <a href="{{ route('members.index') }}" class="h-10 px-4 flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</a>
            <button type="submit" class="h-10 px-6 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-xl text-xs shadow-md shadow-teal-600/10">
                Create Member & Issue Account
            </button>
        </div>
    </form>
</div>
@endsection
