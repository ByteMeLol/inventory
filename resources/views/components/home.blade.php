<div class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- Welcome Banner Profile Header -->
    <div class="mb-8 rounded-2xl bg-white border border-slate-200/80 p-6 sm:p-7 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-xs">
                <i class="fa-solid fa-layer-group text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">{{ __('Good day, :name', ['name' => auth()->user()->first_name]) }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        {{ __('Live') }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ __('Here is what is happening across your inventory and business operations today.') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 self-start sm:self-center">
            <div class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200/80">
                <i class="fa-regular fa-building mr-2 text-slate-400"></i>
                <span>{{ session('company_name') ?? auth()->user()->company->name ?? __('Active Workspace') }}</span>
            </div>
        </div>
    </div>

    <!-- Primary Layout Distribution Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Grid Pane: Routing Links & Meta Elements -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Assigned Operations Portal -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Quick Modules') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Navigate directly to core daily workflows') }}</p>
                    </div>
                    <i class="fa-solid fa-compass text-slate-300 text-sm"></i>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="/sales" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-emerald-300 hover:shadow-sm hover:bg-emerald-50/20 transition-all flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-cash-register"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-emerald-700 transition-colors">{{ __('Sales Orders') }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Create invoices, counter sales, and customer fulfillment.') }}</p>
                        </div>
                    </a>

                    <a href="/inventory" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-blue-300 hover:shadow-sm hover:bg-blue-50/20 transition-all flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-blue-700 transition-colors">{{ __('Inventory Stock') }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Catalog management, stock levels, and low quantity alerts.') }}</p>
                        </div>
                    </a>

                    <a href="/purchases" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-violet-300 hover:shadow-sm hover:bg-violet-50/20 transition-all flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-violet-700 transition-colors">{{ __('Purchases & Suppliers') }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Record supplier orders, receiving goods, and vendor bills.') }}</p>
                        </div>
                    </a>

                    <a href="/finance" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-amber-300 hover:shadow-sm hover:bg-amber-50/20 transition-all flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-amber-700 transition-colors">{{ __('Finance Ledger') }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Track real-time cash flow, income, expenses, and accounts.') }}</p>
                        </div>
                    </a>

                    <a href="/expenses" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-rose-300 hover:shadow-sm hover:bg-rose-50/20 transition-all flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-rose-700 transition-colors">{{ __('Expenses & Outlays') }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Log day-to-day operating overhead and supplier payables.') }}</p>
                        </div>
                    </a>

                    @if(auth()->user()->role_id == 1)
                        <a href="/dashboard" class="group p-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white transition-all flex items-start space-x-3.5 shadow-sm shadow-slate-900/10">
                            <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">{{ __('Executive Insights') }}</h4>
                                <p class="text-xs text-slate-300 mt-1 leading-relaxed">{{ __('Review high-level executive analytics and performance metrics.') }}</p>
                            </div>
                        </a>
                    @else
                        <a href="/customers" class="group p-4 rounded-xl border border-slate-200/80 bg-white hover:border-indigo-300 hover:shadow-sm hover:bg-indigo-50/20 transition-all flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">{{ __('Customers') }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('View customer profiles, contacts, and delivery destinations.') }}</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Bottom Information Blocks -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center space-x-3.5 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500">
                        <i class="fa-solid fa-user-shield text-sm"></i>
                    </div>
                    <div class="text-xs">
                        <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">{{ __('Active Role') }}</span>
                        <span class="text-slate-800 font-semibold mt-0.5 block">{{ __(str_replace('_', ' ', auth()->user()->role->name ?? 'User')) }}</span>
                    </div>
                </div>
                <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center space-x-3.5 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </div>
                    <div class="text-xs">
                        <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">{{ __('System Status') }}</span>
                        <span class="text-slate-800 font-semibold mt-0.5 block">{{ __('Connected & synchronized') }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Grid Pane: Bulletins & Notices -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between h-full">
                <div>
                    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Operational Tips') }}</h3>
                        <i class="fa-regular fa-lightbulb text-amber-500 text-sm"></i>
                    </div>
                    
                    <div class="space-y-3.5">
                        <div class="p-4 bg-slate-50/70 border border-slate-200/60 rounded-xl">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full inline-block mb-1.5">{{ __('Stock Management') }}</span>
                            <h5 class="text-xs font-bold text-slate-900 mb-1">{{ __('Regular Stock Counts') }}</h5>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ __('Review your low stock alerts regularly in the inventory module to prevent unexpected shortages.') }}</p>
                        </div>

                        <div class="p-4 bg-slate-50/70 border border-slate-200/60 rounded-xl">
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full inline-block mb-1.5">{{ __('Finance') }}</span>
                            <h5 class="text-xs font-bold text-slate-900 mb-1">{{ __('Automated Ledgers') }}</h5>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ __('All confirmed sales and recorded purchases post directly into the finance ledger for simplified accounting.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 mt-6 flex items-center justify-between text-xs text-slate-400">
                    <span>{{ __('App Version') }} 2.4</span>
                    <span class="font-medium text-slate-600">{{ __('Tanzania Edition') }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
