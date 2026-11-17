<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- PAGE HEADER AREA -->
    <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-chart-pie text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Reports & Statements') }}</h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Generate balance audits, sales summaries, and inventory valuation reports.') }}</p>
            </div>
        </div>
    </div>

    <!-- 🎛️ FILTERS AND CONFIGURATION MATRICES -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs mb-8">
        <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Report Filters') }}</h3>
            <i class="fa-solid fa-sliders text-slate-300 text-xs"></i>
        </div>
        
        <form action="/reports" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs font-medium">
            <div>
                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Company') }}</label>
                <select name="company_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 focus:bg-white focus:border-slate-400 transition-all outline-none">
                    <option value="">{{ __('Current company') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @selected((string) $selectedCompanyId === (string) $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Report Type') }}</label>
                <select name="statement_class" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 focus:bg-white focus:border-slate-400 transition-all outline-none">
                    <option value="sales_summary" @selected($selectedStatementClass === 'sales_summary')>{{ __('Sales Orders Ledger Summary') }}</option>
                    <option value="expense_outlays" @selected($selectedStatementClass === 'expense_outlays')>{{ __('Factory Overheads & Expenses') }}</option>
                    <option value="inventory_valuation" @selected($selectedStatementClass === 'inventory_valuation')>{{ __('Stock Item Valuation Ledger') }}</option>
                </select>
            </div>

            <div>
                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Date Range') }}</label>
                <select name="date_horizon" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 focus:bg-white focus:border-slate-400 transition-all outline-none">
                    <option value="cur_month">{{ __('Current Month') }}</option>
                    <option value="q2">{{ __('Second Quarter (Q2)') }}</option>
                    <option value="ytd">{{ __('Year to Date (YTD)') }}</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-4 rounded-xl transition shadow-xs flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-arrows-rotate text-xs text-slate-300"></i>
                    <span>{{ __('Generate Report') }}</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 📊 QUICK SHEET EXPORTERS AND HISTORY SPLIT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side Ledger Output (2/3 width) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs lg:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">{{ __('Generated Report Summary') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Showing calculated output for selected parameters') }}</p>
                </div>
                <span class="text-xs font-semibold text-slate-600 border border-slate-200 bg-white px-2.5 py-1 rounded-lg">{{ $selectedCompany->name ?? __('Active Scope') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                            <th class="px-6 py-3.5">{{ __('Report Reference') }}</th>
                            <th class="px-6 py-3.5">{{ __('Compiled By') }}</th>
                            <th class="px-6 py-3.5 text-center">{{ __('Scope') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Total Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse ($statementRows->take(5) as $row)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $row->pointer }}</td>
                                <td class="px-6 py-4 text-slate-800">{{ $row->compiled_by }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">{{ $row->scope }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-emerald-600 font-bold font-mono">{{ $row->output }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-xs text-slate-400">
                                    <i class="fa-solid fa-chart-simple text-slate-300 text-2xl block mb-2"></i>
                                    {{ __('No reports found for the selected company.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Action Panel Box (1/3 width) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <h3 class="font-bold text-slate-900 text-sm">{{ __('Quick Exports') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Export current dataset directly') }}</p>
                </div>
                
                <div class="space-y-3">
                    <a href="/reports?company_id={{ $selectedCompanyId }}&statement_class={{ $selectedStatementClass }}" class="w-full group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-slate-300 hover:shadow-xs transition-all text-left flex items-center justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-file-csv"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900 block">{{ __('Export CSV') }}</span>
                                <span class="text-[11px] text-slate-400">{{ __('Comma separated spreadsheet') }}</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-down text-xs text-slate-300 group-hover:text-slate-600"></i>
                    </a>

                    <a href="/reports?company_id={{ $selectedCompanyId }}&statement_class={{ $selectedStatementClass }}" class="w-full group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-slate-300 hover:shadow-xs transition-all text-left flex items-center justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-print"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900 block">{{ __('Print Report') }}</span>
                                <span class="text-[11px] text-slate-400">{{ __('Printer friendly layout') }}</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-300 group-hover:text-slate-600"></i>
                    </a>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 text-center mt-6 flex items-center justify-between text-xs text-slate-400">
                <span>{{ $selectedCompany->name ?? __('Active Workspace') }}</span>
                <span class="font-medium text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i>{{ __('Verified') }}</span>
            </div>
        </div>

    </div>

</div>
