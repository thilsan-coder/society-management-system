<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Member;
use App\Models\MonthlyReport;
use App\Models\PosterAndMedia;
use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    public function home()
    {
        $posters = PosterAndMedia::where('status', 'published')->orderBy('id', 'desc')->take(6)->get();
        $announcements = PosterAndMedia::where('status', 'published')->where('media_type', 'announcement')->orderBy('id', 'desc')->take(3)->get();
        $committee = Member::with('user')->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))->get();

        return view('public.home', compact('posters', 'announcements', 'committee'));
    }

    public function about()
    {
        $committee = Member::with('user')->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))->get();
        return view('public.about', compact('committee'));
    }

    public function committee()
    {
        $committee = Member::with('user')->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))->get();
        return view('public.committee', compact('committee'));
    }

    public function media()
    {
        $mediaItems = PosterAndMedia::where('status', 'published')->orderBy('id', 'desc')->paginate(5);
        return view('public.media', compact('mediaItems'));
    }

    public function reports()
    {
        $reports = MonthlyReport::where('status', 'published')->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(5);
        return view('public.reports', compact('reports'));
    }

    public function contact()
    {
        return view('public.contact');
    }
}
