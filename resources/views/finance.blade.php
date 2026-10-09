<x-layout>
    <x-slot:title>Finance</x-slot:title>
    <x-success_alert />

    <div class="mx-auto max-w-5xl px-4 py-8" x-data="{ showTransactionForm: false, showCategoryForm: false }">
        <div class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Finance workspace</p>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Money in and money out</h1>
                <p class="mt-1 max-w-2xl text-sm text-slate-500">Sales receipts, purchases, expenses, and manual adjustments in one auditable ledger.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="showCategoryForm = true" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50">
                    <i class="fa-solid fa-folder-plus text-slate-400"></i>
                    Finance category
                </button>
                <button type="button" @click="showTransactionForm = true" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-700">
                    <i class="fa-solid fa-plus"></i>
                    Post transaction
                </button>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Income receipts</p>
                <p class="mt-2 text-xl font-bold text-slate-900">TZS {{ number_format($transactions->where('type', 'income')->sum('amount'), 2) }}</p>
                <p class="mt-1 text-xs text-slate-500">Including sales and POS payments</p>
            </div>
            <div class="rounded-xl border border-rose-100 bg-rose-50/60 p-5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Expenses</p>
                <p class="mt-2 text-xl font-bold text-slate-900">TZS {{ number_format($transactions->where('type', 'expense')->sum('amount'), 2) }}</p>
                <p class="mt-1 text-xs text-slate-500">Purchases and operating costs</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Visible transactions</p>
                <p class="mt-2 text-xl font-bold text-slate-900">{{ $transactions->total() }}</p>
                <p class="mt-1 text-xs text-slate-500">Current company scope</p>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-5">
            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-5 lg:col-span-2">
                <div class="flex gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5 text-blue-500"></i>
                    <div>
                        <p class="text-xs font-bold text-blue-900">Automatic vs manual entries</p>
                        <p class="mt-1 text-xs leading-relaxed text-blue-800">Sales, purchases, and expenses post here automatically. Use <strong>Post transaction</strong> for rent, salaries, bank charges, loans, tax, or owner contributions.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-3">
                <div class="mb-4 flex items-center justify-between">
                    <div><h2 class="text-sm font-bold text-slate-900">Finance categories</h2><p class="mt-1 text-[11px] text-slate-500">Groups used by manual and automatic entries.</p></div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-500">{{ $categories->count() }} categories</span>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @forelse($categories as $category)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-2"><span class="text-xs font-semibold text-slate-800">{{ $category->name }}</span><span class="font-mono text-[9px] text-slate-400">{{ $category->code }}</span></div>
                            <span class="mt-1 block text-[10px] capitalize text-slate-500">{{ $category->type }}</span>
                        </div>
                    @empty
                        <div class="col-span-full rounded-lg border border-dashed border-slate-200 p-4 text-center text-xs text-slate-400">No finance categories yet. Use “Finance category”.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/70 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Transaction ledger</h2>
                    <p class="mt-1 text-xs text-slate-500">Every posted receipt and payment for this company.</p>
                </div>
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[10px] font-semibold text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Live ledger
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-xs">
                    <thead class="border-b border-slate-100 bg-white text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Reference</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Payment method</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $transaction)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td class="px-5 py-4 font-mono font-semibold text-slate-900">{{ $transaction->reference }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $transaction->category->name }}</td>
                                <td class="px-5 py-4">
                                    @if($transaction->payment_method)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold capitalize text-emerald-700">{{ str_replace('_', ' ', $transaction->payment_method) }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="{{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} rounded-full px-2.5 py-1 text-[10px] font-semibold capitalize">{{ $transaction->type }}</span>
                                </td>
                                <td class="px-5 py-4 text-right font-mono font-bold text-slate-900">TZS {{ number_format($transaction->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center"><i class="fa-solid fa-receipt mb-3 text-2xl text-slate-300"></i><p class="text-sm font-semibold text-slate-700">No transactions yet</p><p class="mt-1 text-xs text-slate-400">Sales and purchases will appear here automatically.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="border-t border-slate-100 px-5 py-3">{{ $transactions->links() }}</div>
            @endif
        </div>

        <div x-show="showCategoryForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" @keydown.escape.window="showCategoryForm = false">
            <div class="rounded-2xl bg-white p-6 shadow-2xl" style="width: min(34rem, calc(100vw - 2rem));" @click.outside="showCategoryForm = false">
                <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4"><div><p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Finance setup</p><h2 class="text-lg font-bold text-slate-900">Create finance category</h2><p class="mt-1 text-xs text-slate-500">Use categories to group manual income and costs.</p></div><button type="button" @click="showCategoryForm = false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button></div>
                <form method="POST" action="{{ route('finance.categories.store') }}" class="space-y-4">@csrf
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Category name</label><input name="name" required placeholder="e.g. Bank charges" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Code</label><input name="code" required placeholder="BANK_FEES" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm uppercase outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Type</label><select name="type" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="income">Income</option><option value="expense">Expense</option><option value="asset">Asset</option><option value="liability">Liability</option></select></div></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" @click="showCategoryForm = false" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white">Create category</button></div>
                </form>
            </div>
        </div>

        <div x-show="showTransactionForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" @keydown.escape.window="showTransactionForm = false">
            <div class="rounded-2xl bg-white p-6 shadow-2xl" style="width: min(42rem, calc(100vw - 2rem));" @click.outside="showTransactionForm = false">
                <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4"><div><p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Finance entry</p><h2 class="text-lg font-bold text-slate-900">Post manual transaction</h2><p class="mt-1 text-xs text-slate-500">Use this for finance entries not created by Sales, Purchases, or Expenses.</p></div><button type="button" @click="showTransactionForm = false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button></div>
                <form method="POST" action="{{ route('finance.transactions.store') }}" class="space-y-5">@csrf
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Finance category</label><select name="finance_category_id" required class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="">Select a category</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }} · {{ ucfirst($category->type) }}</option>@endforeach</select><p class="mt-1 text-[11px] text-slate-400">Create a category first if the list is empty.</p></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Transaction type</label><select name="type" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="expense">Expense</option><option value="income">Income</option><option value="asset">Asset</option><option value="liability">Liability</option></select></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Amount (TZS)</label><input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Date</label><input type="date" name="transaction_date" required value="{{ now()->toDateString() }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Reference</label><input name="reference" required placeholder="e.g. RENT-OCT-2026" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div></div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Description <span class="font-normal text-slate-400">(optional)</span></label><textarea name="description" rows="3" placeholder="What was this transaction for?" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></textarea></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-5"><button type="button" @click="showTransactionForm = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</button><button class="rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-700">Post transaction</button></div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
