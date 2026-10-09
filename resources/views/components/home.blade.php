
    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- Welcome Banner Profile Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-500 shadow-sm">
                    <i class="fa-solid fa-user-gear text-base"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold tracking-tight text-slate-900">Good day, {{ auth()->user()->first_name }}</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Welcome back to your active management instance.</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 self-start sm:self-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-slate-50 text-slate-600 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                    Node: 2B-HQ
                </span>
            </div>
        </div>

        <!-- Primary Layout Distribution Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Grid Pane: Routing Links & Meta Elements -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Assigned Operations Portal -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned Workspace Routing</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="/sales" class="group p-4 rounded-lg border border-slate-200/80 bg-slate-50/20 hover:bg-white hover:border-slate-400 hover:shadow-sm transition-all flex items-start space-x-3.5">
                            <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 group-hover:text-slate-900 flex items-center justify-center text-sm bg-white transition-colors shadow-sm">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider group-hover:text-slate-900">Sales Orders</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Process counter transactions and logistics shipments.</p>
                            </div>
                        </a>

                        <a href="/expenses" class="group p-4 rounded-lg border border-slate-200/80 bg-slate-50/20 hover:bg-white hover:border-slate-400 hover:shadow-sm transition-all flex items-start space-x-3.5">
                            <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 group-hover:text-slate-900 flex items-center justify-center text-sm bg-white transition-colors shadow-sm">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider group-hover:text-slate-900">Expenses & Outlays</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Track factory outlays and supplier vendor payables.</p>
                            </div>
                        </a>

                        <a href="/purchases" class="group p-4 rounded-lg border border-slate-200/80 bg-slate-50/20 hover:bg-white hover:border-slate-400 hover:shadow-sm transition-all flex items-start space-x-3.5">
                            <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 group-hover:text-slate-900 flex items-center justify-center text-sm bg-white transition-colors shadow-sm">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            </div>
                            <div><h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Purchases & Suppliers</h4><p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Record purchase orders, suppliers, and goods received.</p></div>
                        </a>

                        <a href="/finance" class="group p-4 rounded-lg border border-slate-200/80 bg-slate-50/20 hover:bg-white hover:border-slate-400 hover:shadow-sm transition-all flex items-start space-x-3.5">
                            <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 group-hover:text-slate-900 flex items-center justify-center text-sm bg-white transition-colors shadow-sm">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div><h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Finance Ledger</h4><p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Track categorized income, expenses, assets, and liabilities.</p></div>
                        </a>

                        <a href="/inventory" class="group p-4 rounded-lg border border-slate-200/80 bg-slate-50/20 hover:bg-white hover:border-slate-400 hover:shadow-sm transition-all flex items-start space-x-3.5">
                            <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 group-hover:text-slate-900 flex items-center justify-center text-sm bg-white transition-colors shadow-sm">
                                <i class="fa-solid fa-warehouse"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider group-hover:text-slate-900">Stock Inventory</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Cross-check hardware quantities and line item tallies.</p>
                            </div>
                        </a>

                        @if(auth()->user()->role_id == 1)
                            <a href="/dashboard" class="group p-4 rounded-lg bg-slate-900 hover:bg-slate-800 text-white transition-all flex items-start space-x-3.5 shadow-sm shadow-slate-900/10">
                                <div class="w-9 h-9 rounded-lg bg-white/10 text-white flex items-center justify-center text-sm transition-colors shadow-inner">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">System Insights</h4>
                                    <p class="text-[11px] text-slate-300 mt-1 leading-relaxed">Review workspace reports and global tenant indices.</p>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Bottom Information Blocks -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white border border-slate-200 p-4 rounded-xl flex items-center space-x-3.5 shadow-sm">
                        <div class="text-slate-400 text-sm"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div class="text-xs">
                            <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">Session Status</span>
                            <span class="text-slate-800 font-semibold mt-0.5 block">Online and verified</span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-4 rounded-xl flex items-center space-x-3.5 shadow-sm">
                        <div class="text-slate-400 text-sm"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="text-xs">
                            <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">Security Scope</span>
                            <span class="text-slate-800 font-semibold mt-0.5 block">Access level authorization clearance</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Grid Pane: Bulletins & Notices -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm h-full flex flex-col justify-between">
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Internal Bulletins</h3>
                            <i class="fa-solid fa-circle-nodes text-slate-300 text-xs"></i>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="p-4 bg-slate-50 border border-slate-200/60 rounded-xl">
                                <span class="text-[9px] font-mono font-bold text-slate-400 block mb-1">June 2026 Audit Window</span>
                                <h5 class="text-xs font-bold text-slate-900 mb-0.5">Bi-Annual Stock Audit</h5>
                                <p class="text-[11px] text-slate-500 leading-relaxed">Ensure all stored industrial parts and SKU lines match back-end database figures precisely.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-6 text-center">
                        <span class="text-[10px] font-mono text-slate-400 block">Authenticated Instance</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
