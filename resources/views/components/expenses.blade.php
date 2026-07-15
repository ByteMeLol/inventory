
    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- PAGE HEADER AREA -->
        <div class="mb-8 pb-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Expenses & Outlays</h1>
                <p class="text-xs text-slate-500 mt-1.5">Track tool procurement invoices, supplier payables, and operational overhead metrics.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button class="bg-white border border-slate-200 text-slate-600 px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-file-export mr-1.5 text-slate-400"></i> Export Logs
                </button>
                <!-- FIXED MODAL TRIGGER -->
                <button type="button" onclick="toggleModal('expense-modal', true)"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition shadow-sm shadow-slate-900/10">
                    <i class="fa-solid fa-plus mr-1.5 text-slate-300"></i> Record Expense
                </button>
            </div>
        </div>

        <!-- 📊 ALIGNED METRICS LAYER -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Monthly Outlays Scoped -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Expenses(this month)</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($currentMonthExpenses->sum('amount'), 2) }}</span>
                        <span class="text-xs font-medium text-slate-400">TZS gross</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>

            <!-- Card 2: Pending Supplier Accounts -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Cleared Expenses(this month)</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($currentMonthClearedExpenses, 2) }}</span>
                        {{-- <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded ml-1 tracking-wider uppercase">Due</span> --}}
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>

            <!-- Card 3: Procured Lines Month -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Cleared Expenses(last month)</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($lastMonthClearedExpenses, 2) }}</span>
                        <span class="text-xs font-medium text-slate-400">cleared</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-dolly"></i>
                </div>
            </div>
        </div>

        <!-- 📋 EXPENSE LEDGER DATA CONTAINER -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Expense Register</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Outlays isolated to the current tenant company context.</p>
                </div>
                <!-- Search bar -->
                <div class="relative max-w-xs w-full">
                    <input type="text" placeholder="Search supplier, reference or category..." 
                           class="w-full bg-white border border-slate-200 rounded-lg py-1.5 pl-8 pr-3 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-slate-400 transition-colors shadow-inner">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-[10px] text-slate-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4">Reference ID</th>
                            <th class="p-4">Recipient / Vendor</th>
                            <th class="p-4">Expense Category</th>
                            <th class="p-4">Posting Date</th>
                            <th class="p-4 text-center">Amount</th>
                            <th class="p-4">Settlement</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        <!-- Expense item 1 -->
                        @foreach ( $expenses as $expense )
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="p-4 font-mono font-bold text-slate-900">{{ $expense->reference }}</td>
                            <td class="p-4">
                                <span class="text-slate-900 block font-bold">{{ $expense->expense_name }}</span>
                                <span class="text-[10px] text-slate-400 block font-normal">PO: {{ $expense->po_number }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 border border-slate-200 bg-slate-50 text-slate-600 rounded text-[10px]">{{ $expense->category }}</span>
                            </td>
                            <td class="p-4 text-slate-500 font-mono font-semibold">{{ $expense->expense_date }}</td>
                            <td class="p-4 text-center text-slate-900 font-mono font-bold">TZS{{ number_format($expense->amount, 2) }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200">Cleared</span>
                            </td>
                            <td class="p-4 text-right">
                                <button class="text-slate-400 hover:text-slate-900 p-1 transition-colors" title="View Document"><i class="fa-solid fa-paperclip text-xs"></i></button>
                            </td>
                        </tr>
                        @endforeach ()
                            
                       
                        
                        
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination controls -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                <span>Showing 3 records for active ledger context</span>
                <div class="flex items-center space-x-1">
                    <button class="px-2.5 py-1 border border-slate-200 rounded bg-white hover:bg-slate-50 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-2.5 py-1 border border-slate-200 rounded bg-white hover:bg-slate-50 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- 📥 RECORD EXPENSE OVERLAY MODAL -->
        <div id="expense-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Dim Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('expense-modal', false)"></div>

            <!-- Modal Box Content -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xl w-full max-w-md overflow-hidden relative z-10 transform transition-all">
                
                <!-- Header Component -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Record Corporate Outlay</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Log vendor balances, reference pointers, and expense classes.</p>
                    </div>
                    <button type="button" onclick="toggleModal('expense-modal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Form Lines -->
                <form action="/expenses" method="POST" class="p-5 space-y-4">
                    @csrf

                    <!-- Vendor Name -->
                    <div class="space-y-1">
                        <label for="vendor_name" class="block text-[11px] font-semibold text-slate-700">Expenses Name</label>
                        <input type="text" name="vendor_name" id="vendor_name" required placeholder="e.g. Continental Tool Casting Ltd" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all">
                    </div>

                    <!-- Category Allocation Wrapper -->
                    <div class="space-y-1">
                        <label for="category" class="block text-[11px] font-semibold text-slate-700">Expense Allocation Category</label>
                        <div class="relative">
                            <select name="category" id="category" required 
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 pl-2.5 pr-8 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-900 appearance-none cursor-pointer transition-all">
                                <option value="">Select an outlays category context...</option>
                                <option value="inventory">Inventory Procurement</option>
                                <option value="shipping">Shipping & Duties</option>
                                <option value="facility">Facility Overhead</option>
                                <option value="tools">Tooling Assets & Maintenance</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Mapping Fields -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="reference_id" class="block text-[11px] font-semibold text-slate-700">Reference / PO ID</label>
                            <input type="text" name="reference_id" id="reference_id" required placeholder="#EXP-2026-XXX" 
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono" readonly>
                        </div>
                        <div class="space-y-1">
                            <label for="posting_date" class="block text-[11px] font-semibold text-slate-700">Posting Value Date</label>
                            <input type="date" name="posting_date" id="posting_date" required value="2026-06-23"
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono">
                        </div>
                    </div>

                    <!-- Amount configuration box -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="amount" class="block text-[11px] font-semibold text-slate-700">Gross Amount ($)</label>
                            <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00" 
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono">
                        </div>
                        <div class="space-y-1">
                            <label for="settlement" class="block text-[11px] font-semibold text-slate-700">Settlement Status</label>
                            <div class="relative">
                                <select name="settlement" id="settlement" required 
                                        class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 pl-2.5 pr-8 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-900 appearance-none cursor-pointer transition-all">
                                    <option value="cleared">Cleared / Paid</option>
                                    <option value="awaiting">Awaiting Invoice</option>
                                    <option value="dispute">Disputed Hold</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Bar Element -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                        <button type="button" onclick="toggleModal('expense-modal', false)" 
                                class="bg-white border border-slate-200 text-slate-600 px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all shadow-sm">
                            Commit Expense Log
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
        //here is a code to formulate referncce expenses id
        const referenceInput = document.getElementById('reference_id');
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const randomNumber = Math.floor(Math.random() * 1000);
        const referenceValue = `EXP-${year}-${randomNumber}`;
        referenceInput.value = referenceValue;
    </script>
