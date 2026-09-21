<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Santha;
use App\Services\PaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $user = Auth::user();
        $query = Payment::with(['member.user', 'treasurer']);

        if ($user->isMember() && $user->member) {
            $query->where('member_id', $user->member->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($qm) use ($search) {
                      $qm->where('member_number', 'like', "%{$search}%")
                         ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%{$search}%"));
                  });
            });
        }

        $payments = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();
        $allMembers = Member::with('user')->get();

        return view('payments.index', compact('payments', 'allMembers'));
    }

    public function getUnpaidItems(Member $member)
    {
        $this->authorize('create', Payment::class);

        $unpaidSanthas = Santha::where('member_id', $member->id)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'label' => "Santha - " . date('F Y', mktime(0, 0, 0, $s->month, 1, $s->year)) . " (Due: Rs " . number_format($s->remaining, 2) . ")",
                'remaining' => $s->remaining,
            ]);

        $unpaidFines = Fine::where('member_id', $member->id)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->orderBy('id', 'asc')
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'label' => strtoupper($f->fine_type) . " Fine - {$f->reason} (Due: Rs " . number_format($f->remaining_amount, 2) . ")",
                'remaining' => $f->remaining_amount,
            ]);

        return response()->json([
            'santhas' => $unpaidSanthas,
            'fines' => $unpaidFines,
        ]);
    }

    public function store(Request $request, PaymentService $paymentService)
    {
        $this->authorize('create', Payment::class);

        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'santha_allocations' => ['nullable', 'array'],
            'fine_allocations' => ['nullable', 'array'],
            'payment_method' => ['required', 'string'],
            'reference_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $member = Member::findOrFail($validated['member_id']);

        $santhaAllocations = array_filter($validated['santha_allocations'] ?? [], fn($val) => (float) $val > 0);
        $fineAllocations = array_filter($validated['fine_allocations'] ?? [], fn($val) => (float) $val > 0);

        try {
            $payment = $paymentService->recordExplicitPayment(
                $member,
                $santhaAllocations,
                $fineAllocations,
                $validated['payment_method'],
                $validated['reference_number'] ?? null,
                $validated['notes'] ?? null,
                $request->user()
            );

            return redirect()->route('payments.show', $payment)->with('success', "Payment {$payment->receipt_number} recorded successfully.");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['member.user', 'treasurer', 'items.payable']);

        return view('payments.show', compact('payment'));
    }

    public function pdf(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['member.user', 'treasurer', 'items.payable']);

        $pdf = Pdf::loadView('pdf.receipt', compact('payment'));
        return $pdf->download("Receipt-{$payment->receipt_number}.pdf");
    }
}
