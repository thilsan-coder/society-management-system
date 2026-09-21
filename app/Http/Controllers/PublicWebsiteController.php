<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Member;
use App\Models\PosterAndMedia;
use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    public function home()
    {
        // Fetch only President-approved/published content
        $activities = PosterAndMedia::where('status', 'published')
            ->whereIn('media_type', ['poster', 'activity'])
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $announcements = PosterAndMedia::where('status', 'published')
            ->where('media_type', 'announcement')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $committee = Member::with('user')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))
            ->get();

        $mediaGallery = PosterAndMedia::where('status', 'published')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        return view('public.home', compact('activities', 'announcements', 'committee', 'mediaGallery'));
    }

    public function about()
    {
        $committee = Member::with('user')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))
            ->get();

        return view('public.about', compact('committee'));
    }

    public function committee()
    {
        $committee = Member::with('user')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['president', 'secretary', 'treasurer', 'media']))
            ->get();

        return view('public.committee', compact('committee'));
    }

    public function members(Request $request)
    {
        // EXPOSE ONLY PUBLIC-APPROVED MEMBER DATA: Name and Role
        // Strictly NO financial, payment, fine, address, or phone leakage
        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn($q) => $q->where('role', $request->role));
        }

        $members = $query->orderBy('id', 'asc')->paginate(12)->withQueryString();

        return view('public.members', compact('members'));
    }

    public function announcements(Request $request)
    {
        $query = PosterAndMedia::where('status', 'published')
            ->where('media_type', 'announcement');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $announcements = $query->orderBy('id', 'desc')->paginate(6)->withQueryString();

        return view('public.announcements', compact('announcements'));
    }

    public function activities(Request $request)
    {
        $query = PosterAndMedia::where('status', 'published')
            ->whereIn('media_type', ['poster', 'activity']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $activities = $query->orderBy('id', 'desc')->paginate(6)->withQueryString();

        return view('public.activities', compact('activities'));
    }

    public function media(Request $request)
    {
        $query = PosterAndMedia::where('status', 'published');

        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        $mediaItems = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('public.media', compact('mediaItems'));
    }

    public function contact()
    {
        return view('public.contact');
    }
}
