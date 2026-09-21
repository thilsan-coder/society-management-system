<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('member_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn($q) => $q->where('role', $request->role));
        }

        $members = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        $this->authorize('create', Member::class);

        return view('members.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Member::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['president', 'secretary', 'treasurer', 'media', 'member'])],
            'member_number' => ['required', 'string', 'max:50', 'unique:members,member_number'],
            'password' => ['required', 'string', 'min:6'],
            'join_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'emergency_contact' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => $validated['role'],
                'status' => 'active',
                'password' => Hash::make($validated['password']),
            ]);

            $member = Member::create([
                'user_id' => $user->id,
                'member_number' => $validated['member_number'],
                'join_date' => $validated['join_date'] ?? now()->toDateString(),
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            AuditLogService::log(
                $request->user(),
                "Created Member #{$member->member_number} ({$user->name}) with role '{$user->role}'",
                Member::class,
                $member->id
            );

            return redirect()->route('members.show', $member)->with('success', "Member {$member->member_number} created successfully.");
        });
    }

    public function show(Member $member)
    {
        $this->authorize('view', $member);

        $member->load([
            'user',
            'santhas' => fn($q) => $q->orderBy('year', 'desc')->orderBy('month', 'desc'),
            'fines' => fn($q) => $q->orderBy('id', 'desc'),
            'payments' => fn($q) => $q->orderBy('id', 'desc'),
            'attendances.meeting' => fn($q) => $q->orderBy('meeting_date', 'desc'),
        ]);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $this->authorize('update', $member);

        $member->load('user');
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorize('update', $member);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['president', 'secretary', 'treasurer', 'media', 'member'])],
            'member_number' => ['required', 'string', 'max:50', Rule::unique('members', 'member_number')->ignore($member->id)],
            'join_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'emergency_contact' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $member, $request) {
            $member->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => $validated['role'],
            ]);

            $member->update([
                'member_number' => $validated['member_number'],
                'join_date' => $validated['join_date'] ?? $member->join_date,
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            AuditLogService::log($request->user(), "Updated Member #{$member->member_number}", Member::class, $member->id);
        });

        return redirect()->route('members.show', $member)->with('success', "Member updated successfully.");
    }
}
