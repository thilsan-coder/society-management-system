<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MonthlyReport;
use App\Models\PosterAndMedia;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $this->middleware('role:president');

        $pendingMeetings = Meeting::with('creator')->whereIn('status', ['submitted', 'under_review'])->orderBy('id', 'desc')->get();
        $pendingReports = MonthlyReport::with('submitter')->whereIn('status', ['submitted', 'under_review'])->orderBy('id', 'desc')->get();
        $pendingMedia = PosterAndMedia::with('submitter')->whereIn('status', ['submitted', 'under_review'])->orderBy('id', 'desc')->get();

        return view('approvals.index', compact('pendingMeetings', 'pendingReports', 'pendingMedia'));
    }
}
