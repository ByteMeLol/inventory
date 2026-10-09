<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\FinancialTransaction;
use App\Services\FinancePostingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanceController extends Controller
{
    public function index()
    {
        $companyId = $this->companyId();

        return view('finance', [
            'transactions' => FinancialTransaction::with(['category', 'source'])->where('company_id', $companyId)->latest('transaction_date')->paginate(20),
            'categories' => FinanceCategory::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('finance_categories')->where(fn ($q) => $q->where('company_id', $companyId))],
            'type' => ['required', Rule::in(['income', 'expense', 'asset', 'liability'])],
        ]);
        FinanceCategory::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', 'Finance category created.');
    }

    public function store(Request $request, FinancePostingService $finance)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'finance_category_id' => ['required', Rule::exists('finance_categories', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'type' => ['required', Rule::in(['income', 'expense', 'asset', 'liability'])],
            'amount' => ['required', 'numeric', 'gt:0'],
            'transaction_date' => ['required', 'date'],
            'reference' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        $transaction = FinancialTransaction::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', "Transaction {$transaction->reference} posted.");
    }

    private function companyId(): int
    {
        $companyId = session('company_id') ?: auth()->user()?->company_id;
        abort_unless($companyId, 403, 'No active company scope found.');

        return (int) $companyId;
    }
}
