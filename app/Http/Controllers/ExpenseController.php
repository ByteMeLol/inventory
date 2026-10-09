<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\FinancePostingService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    //
    public function store(Request $request, FinancePostingService $finance)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            // 'company_id'=>['required','exists:companies,id'],
            'vendor_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'string', 'max:255'],
            'posting_date' => ['required', 'date'],
            'amount' => ['required', 'numeric'],
            'settlement' => ['required', 'string', 'max:50'],
        ]);
        // dd($validatedData);
        $expense = Expense::create([
            'company_id' => session('company_id'),
            'expense_name' => $validatedData['vendor_name'],
            'category' => $validatedData['category'],
            'reference' => $validatedData['reference_id'],
            'expense_date' => $validatedData['posting_date'],
            'amount' => $validatedData['amount'],
            'status' => $validatedData['settlement'],
        ]);
        $finance->post((int) session('company_id'), 'OPERATING_EXPENSES', 'Operating expenses', 'expense', 'expense', $validatedData['amount'], $validatedData['reference_id'], $expense, $validatedData['category']);

        return redirect()->back()->with('success', 'Expense created successfully.');
    }

    public function index()
    {
        $expenses = Expense::all();

        // sum of expenses for curren month with settlement status cleared
        $currentMonthClearedExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->where('expense_date', '<=', now()->endOfMonth())->where('status', 'cleared')->sum('amount');
        // dd($currentMonthClearedExpenses);
        $currentMonthExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->where('expense_date', '<=', now()->endOfMonth());

        return view('expenses', compact('expenses', 'currentMonthExpenses', 'currentMonthClearedExpenses'));
    }
}
