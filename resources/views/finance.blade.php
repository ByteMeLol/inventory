<x-layout>
    <x-slot:title>{{ __('Finance') }}</x-slot:title>
    <x-success_alert />

    <div class="mx-auto max-w-7xl px-4 py-8" x-data="{ showTransactionForm: false, showCategoryForm: false }">
        <!-- Header -->
        <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-xs">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Finance Ledger') }}</h1>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                            {{ $transactions->total() }} {{ __('entries') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Sales receipts, purchases, expenses, and manual adjustments in one auditable ledger.') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" @click="showCategoryForm = true" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                    <i class="fa-solid fa-folder-plus text-slate-400"></i>
                    <span>{{ __('Finance category') }}</span>
                </button>
                <button type="button" @click="showTransactionForm = true" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                    <i class="fa-solid fa-plus text-slate-300"></i>
                    <span>{{ __('Post transaction') }}</span>
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Income receipts') }}</p>
                    <p class="mt-2 text-xl font-bold text-slate-900">TZS {{ number_format($transactions->where('type', 'income')->sum('amount'), 2) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Including sales and POS payments') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-arrow-down-left text-base"></i>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Expenses') }}</p>
                    <p class="mt-2 text-xl font-bold text-slate-900">TZS {{ number_format($transactions->where('type', 'expense')->sum('amount'), 2) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Purchases and operating costs') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-arrow-up-right text-base"></i>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Visible transactions') }}</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $transactions->total() }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Current company scope') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-6 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h3 class="text-sm font-bold text-blue-900">{{ __('Automatic vs manual entries') }}</h3>
                    </div>
                    <p class="text-xs leading-relaxed text-blue-800 mt-2">{{ __('Sales, purchases, and expenses post here automatically. Use :action for rent, salaries, bank charges, loans, tax, or owner contributions.', ['action' => __('Post transaction')]) }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-blue-100/80 text-[11px] text-blue-700">
                    <i class="fa-solid fa-shield-check mr-1"></i> {{ __('Tamper-evident chronological audit trail') }}
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs lg:col-span-3">
                <div class="mb-4 flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Finance categories') }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Groups used by manual and automatic entries.') }}</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ $categories->count() }} {{ __('categories') }}</span>
                </div>
                <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 max-h-48 overflow-y-auto">
                    @forelse($categories as $category)
                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold text-slate-800">{{ $category->name }}</span>
                                <span class="font-mono text-[10px] text-slate-400">{{ $category->code }}</span>
                            </div>
                            <span class="mt-1 block text-[10px] capitalize text-slate-500">{{ __($category->type) }}</span>
                        </div>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">{{ __('No finance categories yet. Use “Finance category”.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/50 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Transaction ledger') }}</h2>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('Every posted receipt and payment for this company.') }}</p>
                </div>
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ __('Live ledger') }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-xs">
                    <thead class="border-b border-slate-100 bg-slate-50/75 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-6 py-3.5">{{ __('Date') }}</th>
                            <th class="px-6 py-3.5">{{ __('Reference') }}</th>
                            <th class="px-6 py-3.5">{{ __('Category') }}</th>
                            <th class="px-6 py-3.5">{{ __('Payment method') }}</th>
                            <th class="px-6 py-3.5">{{ __('Type') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($transactions as $transaction)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $transaction->reference }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $transaction->category->name }}</td>
                                <td class="px-6 py-4">
                                    @if($transaction->payment_method)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold capitalize text-slate-700">{{ __(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold capitalize">
                                        {{ __($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold {{ $transaction->type === 'income' ? 'text-emerald-700' : 'text-slate-900' }}">TZS {{ number_format($transaction->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fa-solid fa-receipt mb-2 text-2xl text-slate-300 block"></i>
                                    <p class="text-sm font-semibold text-slate-700">{{ __('No transactions yet') }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ __('Sales and purchases will appear here automatically.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="border-t border-slate-100 p-4 bg-slate-50/50">{{ $transactions->links() }}</div>
            @endif
        </div>

        <!-- Add Category Modal -->
        <div x-show="showCategoryForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" @keydown.escape.window="showCategoryForm = false">
            <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-100" style="width: min(34rem, calc(100vw - 2rem));" @click.outside="showCategoryForm = false">
                <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Create finance category') }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Use categories to group manual income and costs.') }}</p>
                    </div>
                    <button type="button" @click="showCategoryForm = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('finance.categories.store') }}" class="space-y-4 text-xs font-medium">
                    @csrf
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Category name') }}</label>
                        <input name="name" required placeholder="{{ __('e.g. Bank charges') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Code') }}</label>
                            <input name="code" required placeholder="BANK_FEES" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 uppercase outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Type') }}</label>
                            <select name="type" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                                <option value="income">{{ __('Income') }}</option>
                                <option value="expense">{{ __('Expense') }}</option>
                                <option value="asset">{{ __('Asset') }}</option>
                                <option value="liability">{{ __('Liability') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 mt-2">
                        <button type="button" @click="showCategoryForm = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50 transition">{{ __('Cancel') }}</button>
                        <button class="rounded-xl bg-slate-900 px-5 py-2 font-semibold text-white hover:bg-slate-800 transition shadow-xs">{{ __('Create category') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Post Transaction Modal -->
        <div x-show="showTransactionForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" @keydown.escape.window="showTransactionForm = false">
            <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-100" style="width: min(42rem, calc(100vw - 2rem));" @click.outside="showTransactionForm = false">
                <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Post manual transaction') }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Use this for finance entries not created by Sales, Purchases, or Expenses.') }}</p>
                    </div>
                    <button type="button" @click="showTransactionForm = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('finance.transactions.store') }}" class="space-y-4 text-xs font-medium">
                    @csrf
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Finance category') }}</label>
                        <select name="finance_category_id" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} · {{ __(ucfirst($category->type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Transaction type') }}</label>
                            <select name="type" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                                <option value="expense">{{ __('Expense') }}</option>
                                <option value="income">{{ __('Income') }}</option>
                                <option value="asset">{{ __('Asset') }}</option>
                                <option value="liability">{{ __('Liability') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Amount (TZS)') }}</label>
                            <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono font-bold">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Date') }}</label>
                            <input type="date" name="transaction_date" required value="{{ now()->toDateString() }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Reference') }}</label>
                            <input name="reference" required placeholder="e.g. RENT-OCT-2026" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" placeholder="{{ __('What was this transaction for?') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition resize-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 mt-2">
                        <button type="button" @click="showTransactionForm = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50 transition">{{ __('Cancel') }}</button>
                        <button class="rounded-xl bg-slate-900 px-5 py-2 font-semibold text-white hover:bg-slate-800 transition shadow-xs">{{ __('Post transaction') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
