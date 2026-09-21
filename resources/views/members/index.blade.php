@extends('layouts.app')

@section('title', 'Members Directory')

@section('content')
<div class="space-y-6">
    <!-- Height-Matched Toolbar (Search, Filter, Create) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('members.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, member no, phone..."
                class="h-10 px-3 border border-slate-300 rounded-xl text-xs shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 w-full md:w-64 placeholder-slate-400">

            <select name="role" class="custom-select h-10 px-3 border border-slate-300 rounded-xl text-xs shadow-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white text-slate-700">
                <option value="">All Committee Roles</option>
                <option value="president" {{ request('role') === 'president' ? 'selected' : '' }}>President</option>
                <option value="secretary" {{ request('role') === 'secretary' ? 'selected' : '' }}>Secretary</option>
                <option value="treasurer" {{ request('role') === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                <option value="media" {{ request('role') === 'media' ? 'selected' : '' }}>Media Team</option>
                <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Normal Member</option>
            </select>

            <button type="submit" class="h-10 px-4 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-all shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('members.index') }}" class="h-10 px-3 flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>

        @can('create', App\Models\Member::class)
        <a href="{{ route('members.create') }}" class="h-10 px-4 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-xl text-xs shadow-md shadow-teal-600/10 flex items-center transition-all whitespace-nowrap">
            + Create New Member
        </a>
        @endcan
    </div>

    <!-- Standardized Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Member No</th>
                        <th class="px-6 py-3.5">Name / Email</th>
                        <th class="px-6 py-3.5">Phone</th>
                        <th class="px-6 py-3.5">Committee Role</th>
                        <th class="px-6 py-3.5">Outstanding Dues</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-teal-700">
                            {{ $member->member_number }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $member->user?->name }}</p>
                            <p class="text-xs text-slate-400">{{ $member->user?->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            {{ $member->user?->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $member->user?->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold {{ $member->total_outstanding > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            Rs {{ number_format($member->total_outstanding, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" type="button" class="h-8 px-3 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 font-bold text-xs flex items-center space-x-1 border border-slate-200 transition-colors">
                                    <span>Actions</span>
                                    <span>▾</span>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="origin-top-right absolute right-0 mt-2 w-40 rounded-xl shadow-xl bg-white border border-slate-200 z-50 overflow-hidden divide-y divide-slate-100">
                                    <a href="{{ route('members.show', $member) }}" class="block px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 font-semibold">View Account</a>
                                    @can('update', $member)
                                    <a href="{{ route('members.edit', $member) }}" class="block px-4 py-2.5 text-xs text-teal-700 hover:bg-teal-50 font-semibold">Edit Member</a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No society members found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
