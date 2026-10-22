
    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- PAGE HEADER AREA -->
        <div class="mb-8 pb-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold tracking-tight text-slate-900">Reports Engine</h1>
                <p class="text-xs text-slate-400 mt-0.5">Generate historical ledger exports, balance audits, and material sheets for a specific company.</p>
            </div>
        </div>

        <!-- 🎛️ FILTERS AND CONFIGURATION MATRICES -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm mb-6">
            <div class="border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Report Selection Parameters</h3>
            </div>
            
            <form action="/reports" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs font-medium">
                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Company</label>
                    <select name="company_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                        <option value="">Current company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) $selectedCompanyId === (string) $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Statement Class</label>
                    <select name="statement_class" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                        <option value="sales_summary" @selected($selectedStatementClass === 'sales_summary')>Sales Orders Ledger Summary</option>
                        <option value="expense_outlays" @selected($selectedStatementClass === 'expense_outlays')>Factory Overheads & Expenses</option>
                        <option value="inventory_valuation" @selected($selectedStatementClass === 'inventory_valuation')>Stock Item Valuation Ledger</option>
                    </select>
                </div>

                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Date Horizon Range</label>
                    <select name="date_horizon" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                        <option value="cur_month">Current Month (June 2026)</option>
                        <option value="q2">Second Quarter (Q2)</option>
                        <option value="ytd">Year to Date (YTD)</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg transition shadow-xs flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                        <span>Compile Ledger</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 📊 QUICK SHEET EXPORTERS AND HISTORY SPLIT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Side Ledger Output (2/3 width) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm lg:col-span-2 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Compiled Ledger Log Results</h3>
                    <span class="text-[10px] font-mono text-slate-400 border border-slate-200/60 bg-white px-2 py-0.5 rounded">{{ $selectedCompany->name ?? 'Active Scope' }}</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                                <th class="p-4">Report Class Pointer</th>
                                <th class="p-4">Compiled By</th>
                                <th class="p-4 text-center">Data Scope</th>
                                <th class="p-4 text-right">System Output</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                            @forelse ($statementRows->take(5) as $row)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="p-4 font-mono font-bold text-slate-900">{{ $row->pointer }}</td>
                                    <td class="p-4">{{ $row->compiled_by }}</td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-0.5 border border-slate-200 bg-slate-50 text-slate-500 rounded text-[10px] font-semibold">{{ $row->scope }}</span>
                                    </td>
                                    <td class="p-4 text-right text-emerald-600 font-bold">{{ $row->output }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-400">No reports found for the selected company.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Action Panel Box (1/3 width) -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">Immediate File Action</h3>
                    
                    <div class="space-y-2">
                        <a href="/reports?company_id={{ $selectedCompanyId }}&statement_class={{ $selectedStatementClass }}" class="w-full group p-3 rounded-lg border border-slate-200 bg-slate-50/20 hover:bg-white hover:border-slate-400 transition-all text-left flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-slate-400 group-hover:text-slate-900 text-xs w-4"><i class="fa-solid fa-file-csv"></i></div>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 uppercase tracking-wide text-[11px]">Export Active Raw CSV</span>
                            </div>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-slate-600"></i>
                        </a>

                        <a href="/reports?company_id={{ $selectedCompanyId }}&statement_class={{ $selectedStatementClass }}" class="w-full group p-3 rounded-lg border border-slate-200 bg-slate-50/20 hover:bg-white hover:border-slate-400 transition-all text-left flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-slate-400 group-hover:text-slate-900 text-xs w-4"><i class="fa-solid fa-print"></i></div>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 uppercase tracking-wide text-[11px]">Print Ledger Invoice</span>
                            </div>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-slate-600"></i>
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 text-center mt-6 flex items-center justify-between text-[10px] font-mono text-slate-400">
                    <span>Engine Layer: {{ $selectedCompany->name ?? 'Active' }}</span>
                    <span>v1.0.2</span>
                </div>
            </div>

        </div>

    </div>
