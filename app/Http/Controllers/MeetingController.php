<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Meeting;
use App\Models\Member;
use App\Services\AuditLogService;
use App\Services\FineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Meeting::class);

        $query = Meeting::with('creator');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $meetings = $query->orderBy('meeting_date', 'desc')->paginate(5)->withQueryString();

        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        $this->authorize('create', Meeting::class);

        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Meeting::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meeting_date' => ['required', 'date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'venue' => ['nullable', 'string', 'max:255'],
            'meeting_notes' => ['nullable', 'string'],
            'discussed_topics' => ['nullable', 'string'],
            'decisions_resolutions' => ['nullable', 'string'],
            'action_items' => ['nullable', 'string'],
            'action' => ['required', 'in:draft,submit'],
        ]);

        $status = ($validated['action'] === 'submit') ? 'submitted' : 'draft';

        $meeting = Meeting::create([
            'title' => $validated['title'],
            'meeting_date' => $validated['meeting_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'venue' => $validated['venue'] ?? null,
            'meeting_notes' => $validated['meeting_notes'] ?? null,
            'discussed_topics' => $validated['discussed_topics'] ?? null,
            'decisions_resolutions' => $validated['decisions_resolutions'] ?? null,
            'action_items' => $validated['action_items'] ?? null,
            'status' => $status,
            'created_by' => $request->user()->id,
        ]);

        AuditLogService::log($request->user(), "Created Meeting '{$meeting->title}' with status '{$status}'", Meeting::class, $meeting->id);

        return redirect()->route('meetings.show', $meeting)->with('success', "Meeting '{$meeting->title}' created successfully.");
    }

    public function show(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load(['creator', 'attendances.member.user', 'fines.member.user']);
        $allMembers = Member::with('user')->get();

        return view('meetings.show', compact('meeting', 'allMembers'));
    }

    public function recordAttendance(Request $request, Meeting $meeting, FineService $fineService)
    {
        $this->authorize('update', $meeting);

        $validated = $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'in:present,absent'],
            'absence_fine_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated, $meeting, $request, $fineService) {
            $absenceFineAmount = (float) ($validated['absence_fine_amount'] ?? 100.00);

            foreach ($validated['attendance'] as $memberId => $status) {
                Attendance::updateOrCreate(
                    [
                        'meeting_id' => $meeting->id,
                        'member_id' => $memberId,
                    ],
                    [
                        'status' => $status,
                    ]
                );

                if ($status === 'absent' && $absenceFineAmount > 0) {
                    $member = Member::find($memberId);
                    if ($member) {
                        $fineService->createAbsenceFine($member, $meeting, $absenceFineAmount, $request->user());
                    }
                }
            }

            AuditLogService::log($request->user(), "Recorded attendance for Meeting #{$meeting->id}", Meeting::class, $meeting->id);

            return redirect()->route('meetings.show', $meeting)->with('success', "Attendance and absence fines recorded successfully.");
        });
    }

    public function transitionStatus(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,approved,rejected,published'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], ['approved', 'rejected', 'published'])) {
            $this->authorize('approve', $meeting);
        } else {
            $this->authorize('update', $meeting);
        }

        $meeting->status = $validated['status'];
        if ($validated['status'] === 'rejected') {
            $meeting->rejection_reason = $validated['rejection_reason'] ?? 'Needs revision.';
        }
        $meeting->save();

        AuditLogService::log($request->user(), "Transitioned Meeting #{$meeting->id} status to '{$validated['status']}'", Meeting::class, $meeting->id);

        return redirect()->route('meetings.show', $meeting)->with('success', "Meeting status updated to {$validated['status']}.");
    }
}
