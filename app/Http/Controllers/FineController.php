<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Member;
use App\Services\FineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Fine::class);

        $user = Auth::user();
        $query = Fine::with(['member.user', 'creator']);

        if ($user->isMember() && $user->member) {
            $query->where('member_id', $user->member->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($qm) use ($search) {
                      $qm->where('member_number', 'like', "%{$search}%")
                         ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%{$search}%"));
                  });
            });
        }

        if ($request->filled('fine_type')) {
            $query->where('fine_type', $request->fine_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fines = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        $allMembers = Member::with('user')->get();

        return view('fines.index', compact('fines', 'allMembers'));
    }

    public function storeSpotFine(Request $request, FineService $fineService)
    {
        $this->authorize('create', Fine::class);

        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $fineService->createSpotFine($member, $validated['reason'], (float) $validated['amount'], $request->user());

        return redirect()->route('fines.index')->with('success', "Spot fine issued successfully for Member {$member->member_number}.");
    }

    public function storeDefaultFine(Request $request, FineService $fineService)
    {
        $this->authorize('create', Fine::class);

        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $fineService->createDefaultFine($member, $validated['reason'], (float) $validated['amount'], $validated['description'] ?? null, $request->user());

        return redirect()->route('fines.index')->with('success', "Default fine issued successfully for Member {$member->member_number}.");
    }

    public function doubleSpotFine(Fine $fine, FineService $fineService, Request $request)
    {
        $this->authorize('double', $fine);

        $fineService->doubleSpotFine($fine, $request->user());

        return redirect()->route('fines.index')->with('success', "Spot fine remaining balance doubled successfully.");
    }
}
