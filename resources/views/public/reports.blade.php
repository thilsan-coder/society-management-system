@extends('public.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-black text-slate-900 uppercase">Approved Public Society Reports</h1>
        <p class="text-xs text-slate-500 font-medium">President-Approved Official Summaries</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase">
                <tr>
                    <th class="px-6 py-3.5">Report Title</th>
                    <th class="px-6 py-3.5">Period</th>
                    <th class="px-6 py-3.5 text-right">PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($reports as $report)
                <tr>
                    <td class="px-6 py-4 font-bold text-slate-900">{{ $report->title }}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">{{ date('F Y', mktime(0,0,0, $report->month, 1, $report->year)) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('reports.pdf', $report) }}" class="px-3 py-1.5 bg-teal-50 text-teal-700 font-bold text-xs rounded border border-teal-200 hover:bg-teal-100">
                            Download PDF 📄
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-xs italic">
                        No published public reports available.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
