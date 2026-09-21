<?php

namespace App\Http\Controllers;

use App\Models\Santha;
use App\Services\SanthaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanthaController extends Controller
{
    public function index(Request $request, SanthaService $santhaService)
    {
        $user = Auth::user();
        $santhaService->generateMonthlySanthasForAllMembers();

        $query = Santha::with('member.user');

        if ($user->isMember() && $user->member) {
            $query->where('member_id', $user->member->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('member_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $santhas = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(5)->withQueryString();

        return view('santhas.index', compact('santhas'));
    }
}
