@extends('layouts.app')

@section('title', 'Security Audit Logs')

@section('content')
<div class="space-y-6">
    <!-- Height-Matched Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action, user..."
                class="h-10 px-3 border border-slate-300 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-full md:w-64">
            <button type="submit" class="h-10 px-4 bg-slate-800 text-white font-medium rounded-lg text-sm hover:bg-slate-900 transition-colors shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Table (5 per page) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Timestamp</th>
                        <th class="px-6 py-3.5">User</th>
                        <th class="px-6 py-3.5">Action Executed</th>
                        <th class="px-6 py-3.5">Target Model</th>
                        <th class="px-6 py-3.5">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors text-xs">
                        <td class="px-6 py-4 font-mono text-slate-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $log->user?->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 text-slate-800 font-medium">
                            {{ $log->action }}
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-500">
                            {{ $log->model_type ? class_basename($log->model_type) . " #{$log->model_id}" : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-400">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                            No security audit logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
