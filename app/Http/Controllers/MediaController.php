<?php

namespace App\Http\Controllers;

use App\Models\PosterAndMedia;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PosterAndMedia::class);

        $query = PosterAndMedia::with(['submitter', 'reviewer']);

        if (!auth()->user()->hasRole(['president', 'media'])) {
            $query->whereIn('status', ['approved', 'published']);
        }

        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        $mediaItems = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        return view('media.index', compact('mediaItems'));
    }

    public function create()
    {
        $this->authorize('create', PosterAndMedia::class);

        return view('media.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', PosterAndMedia::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'media_type' => ['required', 'in:poster,photo,video,announcement,activity'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable'],
            'venue' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'action' => ['required', 'in:draft,submit'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('posters', 'public');
        }

        $status = ($validated['action'] === 'submit') ? 'submitted' : 'draft';

        $media = PosterAndMedia::create([
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'event_date' => $validated['event_date'] ?? null,
            'event_time' => $validated['event_time'] ?? null,
            'venue' => $validated['venue'] ?? null,
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'status' => $status,
            'submitted_by' => $request->user()->id,
        ]);

        AuditLogService::log($request->user(), "Created Media '{$media->title}' with status '{$status}'", PosterAndMedia::class, $media->id);

        return redirect()->route('media.show', $media)->with('success', "Media content '{$media->title}' created.");
    }

    public function show(PosterAndMedia $media)
    {
        $this->authorize('view', $media);

        $media->load(['submitter', 'reviewer']);

        return view('media.show', compact('media'));
    }

    public function transitionStatus(Request $request, PosterAndMedia $media)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,approved,rejected,published'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], ['approved', 'rejected', 'published'])) {
            $this->authorize('approve', $media);
            $media->reviewed_by = $request->user()->id;
        } else {
            $this->authorize('update', $media);
        }

        $media->status = $validated['status'];
        if ($validated['status'] === 'rejected') {
            $media->rejection_reason = $validated['rejection_reason'] ?? 'Revision requested.';
        }
        $media->save();

        AuditLogService::log($request->user(), "Updated Media #{$media->id} status to '{$validated['status']}'", PosterAndMedia::class, $media->id);

        return redirect()->route('media.show', $media)->with('success', "Media status updated to {$validated['status']}.");
    }
}
