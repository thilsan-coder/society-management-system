@extends('layouts.app')

@section('title', 'President Governance Hub')

@section('content')
<div class="space-y-6">
    <!-- Top Compact Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Members</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $total_members }}</h3>
                <p class="text-[11px] text-teal-600 font-medium mt-1">{{ $committee_members }} Committee Members</p>
            </div>
            <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Approvals</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $pending_approvals }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Requires President Review</p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Collections</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">Rs {{ number_format($total_collections, 2) }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Actual DB Receipts</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Outstanding</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1">Rs {{ number_format($total_santha_outstanding + $total_fine_outstanding, 2) }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Santhas + Fines Due</p>
            </div>
            <div class="p-3 bg-rose-50 text-rose-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Actions & Recent Audit Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Governance Actions -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">President Quick Controls</h3>
            <div class="space-y-3">
                <a href="{{ route('members.create') }}" class="w-full flex items-center justify-between p-3 bg-teal-50 text-teal-800 rounded-lg hover:bg-teal-100 transition-colors font-medium text-sm border border-teal-200">
                    <span>Create New Member & Account</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </a>

                <a href="{{ route('approvals.index') }}" class="w-full flex items-center justify-between p-3 bg-amber-50 text-amber-800 rounded-lg hover:bg-amber-100 transition-colors font-medium text-sm border border-amber-200">
                    <span>Review Pending Submissions</span>
                    <span class="px-2 py-0.5 bg-amber-600 text-white rounded-full text-xs font-bold">{{ $pending_approvals }}</span>
                </a>

                <a href="{{ route('audit-logs.index') }}" class="w-full flex items-center justify-between p-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium text-sm border border-slate-200">
                    <span>View System Audit History</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Recent Audit Logs -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Recent System Audit Logs</h3>
                <a href="{{ route('audit-logs.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800">View All ↗</a>
            </div>
            <div class="space-y-3">
                @forelse($recent_logs as $log)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $log->action }}</p>
                        <p class="text-[10px] text-slate-500">User: {{ $log->user?->name ?? 'System' }} &bull; IP: {{ $log->ip_address }}</p>
                    </div>
                    <span class="text-[10px] text-slate-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No audit logs recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
