<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- PAGE HEADER AREA -->
    <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 shadow-xs">
                <i class="fa-solid fa-receipt text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Expenses & Outlays') }}</h1>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $expenses->count() }} {{ __('records') }}
                    </span>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Track operational overhead, supplier payables, and daily expenses.') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleModal('expense-modal', true)"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                <i class="fa-solid fa-plus text-slate-300"></i>
                <span>{{ __('Record Expense') }}</span>
            </button>
        </div>
    </div>

    <!-- 📊 ALIGNED METRICS LAYER -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Card 1: Monthly Outlays Scoped -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Expenses (This Month)') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($currentMonthExpenses->sum('amount'), 2) }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Card 2: Cleared This Month -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Cleared (This Month)') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($currentMonthClearedExpenses, 2) }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>

        <!-- Card 3: Cleared Last Month -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Cleared (Last Month)') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($lastMonthClearedExpenses, 2) }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- 📋 EXPENSE LEDGER DATA CONTAINER -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ __('Expense Register') }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('All operating expenses recorded for the active company.') }}</p>
            </div>
            <!-- Search bar -->
            <div class="relative max-w-xs w-full">
                <input type="text" placeholder="{{ __('Search supplier, reference or category...') }}" 
                       class="w-full bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-3 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                        <th class="px-6 py-3.5">{{ __('Reference ID') }}</th>
                        <th class="px-6 py-3.5">{{ __('Recipient / Vendor') }}</th>
                        <th class="px-6 py-3.5">{{ __('Expense Category') }}</th>
                        <th class="px-6 py-3.5">{{ __('Posting Date') }}</th>
                        <th class="px-6 py-3.5 text-center">{{ __('Amount') }}</th>
                        <th class="px-6 py-3.5 text-right">{{ __('Settlement') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                    @forelse ( $expenses as $expense )
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $expense->reference }}</td>
                        <td class="px-6 py-4">
                            <span class="text-slate-900 block font-semibold">{{ $expense->expense_name }}</span>
                            @if($expense->po_number)
                                <span class="text-[10px] text-slate-400 block font-normal mt-0.5 font-mono">PO: {{ $expense->po_number }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">{{ __($expense->category) }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono">{{ $expense->expense_date }}</td>
                        <td class="px-6 py-4 text-center text-slate-900 font-mono font-bold">TZS {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ __('Cleared') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-xs text-slate-400">
                            <i class="fa-solid fa-receipt text-slate-300 text-2xl block mb-2"></i>
                            {{ __('No expenses found for the current company.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination controls -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500 font-medium">
            <span>{{ __('Showing') }} {{ $expenses->count() }} {{ __('records') }}</span>
        </div>
    </div>

    <!-- 📥 RECORD EXPENSE OVERLAY MODAL -->
    <div id="expense-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Dim Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" onclick="toggleModal('expense-modal', false)"></div>

        <!-- Modal Box Content -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative z-10 transform transition-all p-6 sm:p-7">
            
            <!-- Header Component -->
            <div class="pb-4 border-b border-slate-100 flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Record Expense') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Log vendor balances, invoice references, and categories.') }}</p>
                </div>
                <button type="button" onclick="toggleModal('expense-modal', false)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Form Lines -->
            <form action="/expenses" method="POST" class="space-y-4 text-xs font-medium">
                @csrf

                <!-- Vendor Name -->
                <div>
                    <label for="vendor_name" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Vendor / Expense Name') }}</label>
                    <input type="text" name="vendor_name" id="vendor_name" required placeholder="{{ __('e.g. Continental Tool Casting Ltd') }}" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                </div>

                <!-- Category Allocation Wrapper -->
                <div>
                    <label for="category" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Category') }}</label>
                    <select name="category" id="category" required 
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        <option value="">{{ __('Select category...') }}</option>
                        <option value="inventory">{{ __('Inventory Procurement') }}</option>
                        <option value="shipping">{{ __('Shipping & Duties') }}</option>
                        <option value="facility">{{ __('Facility Overhead') }}</option>
                        <option value="tools">{{ __('Tooling Assets & Maintenance') }}</option>
                    </select>
                </div>

                <!-- Reference Mapping Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="reference_id" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Reference ID') }}</label>
                        <input type="text" name="reference_id" id="reference_id" required placeholder="#EXP-2026-XXX" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono" readonly>
                    </div>
                    <div>
                        <label for="posting_date" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Expense Date') }}</label>
                        <input type="date" name="posting_date" id="posting_date" required value="{{ now()->toDateString() }}"
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                </div>

                <!-- Amount configuration box -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Amount (TZS)') }}</label>
                        <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono font-bold">
                    </div>
                    <div>
                        <label for="settlement" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Status') }}</label>
                        <select name="settlement" id="settlement" required 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                            <option value="cleared">{{ __('Cleared / Paid') }}</option>
                            <option value="awaiting">{{ __('Awaiting Invoice') }}</option>
                            <option value="dispute">{{ __('Disputed Hold') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Actions Bar Element -->
                <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3 mt-4">
                    <button type="button" onclick="toggleModal('expense-modal', false)" 
                            class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold hover:bg-slate-50 transition">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" 
                            class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-xl font-semibold transition shadow-xs">
                        {{ __('Save Expense') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- NATIVE JAVASCRIPT UTILITY LOOPS -->
<script>
    function toggleModal(modalId, actionState) {
        const targetModal = document.getElementById(modalId);
        if (targetModal) {
            if (actionState) {
                targetModal.classList.remove('hidden');
            } else {
                targetModal.classList.add('hidden');
            }
        }
    }
    const referenceInput = document.getElementById('reference_id');
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const randomNumber = Math.floor(Math.random() * 1000);
    const referenceValue = `EXP-${year}-${randomNumber}`;
    if(referenceInput) {
        referenceInput.value = referenceValue;
    }
</script>
