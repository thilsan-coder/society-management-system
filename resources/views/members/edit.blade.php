@extends('layouts.app')

@section('title', 'Edit Member - ' . $member->member_number)

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <h2 class="text-lg font-bold text-slate-900">Edit Member Profile</h2>
        <a href="{{ route('members.show', $member) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">← Back to Profile</a>
    </div>

    <form action="{{ route('members.update', $member) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Member Number *</label>
                <input type="text" name="member_number" value="{{ old('member_number', $member->member_number) }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm font-mono font-bold focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Committee Role *</label>
                <select name="role" required class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                    <option value="member" {{ old('role', $member->user?->role) === 'member' ? 'selected' : '' }}>Normal Member</option>
                    <option value="president" {{ old('role', $member->user?->role) === 'president' ? 'selected' : '' }}>President</option>
                    <option value="secretary" {{ old('role', $member->user?->role) === 'secretary' ? 'selected' : '' }}>Secretary</option>
                    <option value="treasurer" {{ old('role', $member->user?->role) === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                    <option value="media" {{ old('role', $member->user?->role) === 'media' ? 'selected' : '' }}>Media Team</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $member->user?->name) }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $member->user?->email) }}" required
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $member->user?->phone) }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Society Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', $member->join_date ? $member->join_date->format('Y-m-d') : '') }}"
                    class="w-full h-10 px-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Address</label>
            <textarea name="address" rows="2" class="w-full p-3 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('address', $member->address) }}</textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-4">
            <a href="{{ route('members.show', $member) }}" class="h-10 px-4 flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800">Cancel</a>
            <button type="submit" class="h-10 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-sm shadow">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
