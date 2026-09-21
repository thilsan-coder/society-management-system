<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Expense;
use App\Models\Fine;
use App\Models\Meeting;
use App\Models\Member;
use App\Models\MonthlyReport;
use App\Models\Payment;
use App\Models\PosterAndMedia;
use App\Models\Santha;
use App\Services\SanthaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(SanthaService $santhaService)
    {
        $user = Auth::user();

        // Ensure monthly santha dues and late fines are evaluated up to current date
        if ($user->hasRole(['president', 'treasurer']) || ($user->isMember() && $user->member)) {
            $santhaService->generateMonthlySanthasForAllMembers();
        }

        if ($user->isPresident()) {
            $data = [
                'total_members' => Member::count(),
                'committee_members' => Member::whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))->count(),
                'pending_approvals' => Meeting::where('status', 'submitted')->count() 
                    + MonthlyReport::where('status', 'submitted')->count() 
                    + PosterAndMedia::where('status', 'submitted')->count(),
                'total_collections' => (float) Payment::sum('total_amount'),
                'total_expenses' => (float) Expense::sum('amount'),
                'total_santha_outstanding' => (float) Santha::whereIn('status', ['unpaid', 'partially_paid'])->get()->sum(fn($s) => $s->amount - $s->paid_amount),
                'total_fine_outstanding' => (float) Fine::whereIn('status', ['unpaid', 'partially_paid'])->sum('remaining_amount'),
                'recent_logs' => AuditLog::with('user')->orderBy('id', 'desc')->take(5)->get(),
                'recent_meetings' => Meeting::orderBy('meeting_date', 'desc')->take(5)->get(),
            ];
            return view('dashboard.president', $data);
        }

        if ($user->isSecretary()) {
            $data = [
                'total_meetings' => Meeting::count(),
                'upcoming_meetings' => Meeting::where('meeting_date', '>=', now()->toDateString())->orderBy('meeting_date', 'asc')->take(5)->get(),
                'draft_reports' => MonthlyReport::where('report_type', 'secretary')->where('status', 'draft')->count(),
                'submitted_reports' => MonthlyReport::where('report_type', 'secretary')->where('status', 'submitted')->count(),
                'recent_meetings' => Meeting::orderBy('meeting_date', 'desc')->take(5)->get(),
            ];
            return view('dashboard.secretary', $data);
        }

        if ($user->isTreasurer()) {
            $totalSanthaCollected = (float) Santha::sum('paid_amount');
            $totalSanthaOutstanding = (float) Santha::whereIn('status', ['unpaid', 'partially_paid'])->get()->sum(fn($s) => $s->amount - $s->paid_amount);
            $totalFineCollected = (float) Fine::sum('paid_amount');
            $totalFineOutstanding = (float) Fine::whereIn('status', ['unpaid', 'partially_paid'])->sum('remaining_amount');
            $totalIncome = (float) Payment::sum('total_amount');
            $totalExpenses = (float) Expense::sum('amount');
            $netBalance = $totalIncome - $totalExpenses;

            $data = [
                'total_santha_collected' => $totalSanthaCollected,
                'total_santha_outstanding' => $totalSanthaOutstanding,
                'total_fine_collected' => $totalFineCollected,
                'total_fine_outstanding' => $totalFineOutstanding,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_balance' => $netBalance,
                'recent_payments' => Payment::with('member.user')->orderBy('id', 'desc')->take(5)->get(),
                'members_with_due' => Member::with('user')->get()->filter(fn($m) => $m->total_outstanding > 0)->take(5),
            ];
            return view('dashboard.treasurer', $data);
        }

        if ($user->isMedia()) {
            $data = [
                'draft_posters' => PosterAndMedia::where('status', 'draft')->count(),
                'submitted_posters' => PosterAndMedia::where('status', 'submitted')->count(),
                'approved_posters' => PosterAndMedia::where('status', 'approved')->count(),
                'published_posters' => PosterAndMedia::where('status', 'published')->count(),
                'recent_media' => PosterAndMedia::orderBy('id', 'desc')->take(5)->get(),
            ];
            return view('dashboard.media', $data);
        }

        // Normal Member
        $member = $user->member;
        if (!$member) {
            // Fallback if member record not created yet
            return view('dashboard.member', [
                'member' => null,
                'santha_paid' => 0,
                'santha_outstanding' => 0,
                'fine_paid' => 0,
                'fine_outstanding' => 0,
                'total_paid' => 0,
                'total_outstanding' => 0,
                'recent_payments' => collect(),
                'recent_attendances' => collect(),
            ]);
        }

        $data = [
            'member' => $member,
            'santha_paid' => $member->total_santha_paid,
            'santha_outstanding' => $member->total_santha_outstanding,
            'fine_paid' => $member->total_fine_paid,
            'fine_outstanding' => $member->total_fine_outstanding,
            'total_paid' => $member->total_paid,
            'total_outstanding' => $member->total_outstanding,
            'recent_payments' => $member->payments()->orderBy('id', 'desc')->take(5)->get(),
            'recent_attendances' => $member->attendances()->with('meeting')->orderBy('id', 'desc')->take(5)->get(),
        ];

        return view('dashboard.member', $data);
    }
}
