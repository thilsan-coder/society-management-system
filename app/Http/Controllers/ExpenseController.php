<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('recorder');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(5)->withQueryString();

        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'payment_method' => ['required', 'string'],
            'reference_number' => ['nullable', 'string'],
        ]);

        $expense = Expense::create([
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'] ?? null,
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'recorded_by' => $request->user()->id,
        ]);

        AuditLogService::log($request->user(), "Recorded Expense (Rs {$expense->amount} - {$expense->category})", Expense::class, $expense->id);

        return redirect()->route('expenses.index')->with('success', "Expense recorded successfully.");
    }
}
