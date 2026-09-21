<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Fine;
use App\Models\Member;
use App\Models\MonthlyReport;
use App\Models\Payment;
use App\Models\Santha;
use App\Services\AuditLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', MonthlyReport::class);

        $query = MonthlyReport::with(['submitter', 'reviewer']);

        if (!auth()->user()->hasRole(['president', 'secretary', 'treasurer'])) {
            $query->whereIn('status', ['approved', 'published']);
        }

        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        $reports = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(5)->withQueryString();

        return view('reports.index', compact('reports'));
    }

    public function generateFinancialReport(Request $request)
    {
        $this->authorize('create', MonthlyReport::class);

        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2020', 'max:2030'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $year = (int) $validated['year'];
        $month = (int) $validated['month'];

        $totalMembers = Member::count();
        $expectedSantha = $totalMembers * 300.00;

        $monthSanthas = Santha::where('year', $year)->where('month', $month)->get();
        $santhaCollected = (float) $monthSanthas->sum('paid_amount');
        $santhaOutstanding = (float) $monthSanthas->sum(fn($s) => $s->amount - $s->paid_amount);

        $monthFines = Fine::whereYear('fine_date', $year)->whereMonth('fine_date', $month)->get();
        $totalFinesIssued = (float) $monthFines->sum('original_amount');
        $fineCollected = (float) $monthFines->sum('paid_amount');
        $fineOutstanding = (float) $monthFines->sum('remaining_amount');

        $spotFineCollected = (float) $monthFines->where('fine_type', 'spot')->sum('paid_amount');
        $defaultFineCollected = (float) $monthFines->where('fine_type', 'default')->sum('paid_amount');
        $absenceFineCollected = (float) $monthFines->where('fine_type', 'absence')->sum('paid_amount');
        $lateSanthaFineCollected = (float) $monthFines->where('fine_type', 'late_santha')->sum('paid_amount');

        $totalIncome = (float) Payment::whereYear('payment_date', $year)->whereMonth('payment_date', $month)->sum('total_amount');
        $totalExpenses = (float) Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $month)->sum('amount');
        $currentSocietyBalance = (float) Payment::sum('total_amount') - (float) Expense::sum('amount');
        $totalSystemOutstanding = (float) Santha::whereIn('status', ['unpaid', 'partially_paid'])->get()->sum(fn($s) => $s->amount - $s->paid_amount)
            + (float) Fine::whereIn('status', ['unpaid', 'partially_paid'])->sum('remaining_amount');

        $summaryJson = [
            'total_members' => $totalMembers,
            'expected_santha' => $expectedSantha,
            'santha_collected' => $santhaCollected,
            'santha_outstanding' => $santhaOutstanding,
            'total_fines_issued' => $totalFinesIssued,
            'fine_collected' => $fineCollected,
            'fine_outstanding' => $fineOutstanding,
            'spot_fine_collected' => $spotFineCollected,
            'default_fine_collected' => $defaultFineCollected,
            'absence_fine_collected' => $absenceFineCollected,
            'late_santha_fine_collected' => $lateSanthaFineCollected,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'current_society_balance' => $currentSocietyBalance,
            'total_outstanding_amount' => $totalSystemOutstanding,
        ];

        $monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
        $title = "Monthly Financial Report - {$monthName} {$year}";

        $report = MonthlyReport::updateOrCreate(
            [
                'year' => $year,
                'month' => $month,
                'report_type' => 'financial',
            ],
            [
                'title' => $title,
                'summary_json' => $summaryJson,
                'status' => 'draft',
                'submitted_by' => $request->user()->id,
            ]
        );

        AuditLogService::log($request->user(), "Generated Monthly Financial Report for {$monthName} {$year}", MonthlyReport::class, $report->id);

        return redirect()->route('reports.show', $report)->with('success', "Monthly Financial Report generated.");
    }

    public function show(MonthlyReport $report)
    {
        $this->authorize('view', $report);

        $report->load(['submitter', 'reviewer']);

        return view('reports.show', compact('report'));
    }

    public function transitionStatus(Request $request, MonthlyReport $report)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,approved,rejected,published'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], ['approved', 'rejected', 'published'])) {
            $this->authorize('approve', $report);
            $report->reviewed_by = $request->user()->id;
        } else {
            $this->authorize('update', $report);
        }

        $report->status = $validated['status'];
        if ($validated['status'] === 'rejected') {
            $report->rejection_reason = $validated['rejection_reason'] ?? 'Revision requested.';
        }
        $report->save();

        AuditLogService::log($request->user(), "Updated Monthly Report #{$report->id} status to '{$validated['status']}'", MonthlyReport::class, $report->id);

        return redirect()->route('reports.show', $report)->with('success', "Report status updated to {$validated['status']}.");
    }

    public function pdf(MonthlyReport $report)
    {
        $this->authorize('view', $report);

        $report->load(['submitter', 'reviewer']);

        $pdf = Pdf::loadView('pdf.report', compact('report'));
        return $pdf->download("Financial-Report-{$report->year}-{$report->month}.pdf");
    }
}
