
    @props(['company', 'settings'])

    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- PAGE HEADER AREA -->
        <div class="mb-8 pb-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold tracking-tight text-slate-900">System Configuration</h1>
                <p class="text-xs text-slate-400 mt-0.5">Configure Tanzania-specific defaults for this company tenant.</p>
            </div>
            <div>
                <button type="submit" form="settings-form" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-1.5 px-4 rounded-lg transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </div>

        <!-- MAIN DUAL-COLUMN CONFIGURATION GRID -->
        <form id="settings-form" action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="country" value="Tanzania">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN: FINANCIAL & NODE VARIABLES (2/3 Width) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Box 1: Core Financial Parameters -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Accounting & Ledger Defaults</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Base Operating Currency</label>
                                <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="TZS" @selected(($settings['currency'] ?? 'TZS') === 'TZS')>TZS (TSh) — Tanzanian Shilling</option>
                                    <option value="USD" @selected(($settings['currency'] ?? '') === 'USD')>USD ($) — United States Dollar</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Industrial VAT / Tax Standard</label>
                                <div class="relative flex items-center">
                                    <input type="number" name="tax_rate" step="0.1" value="{{ $settings['tax_rate'] ?? 18.0 }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all pr-8">
                                    <span class="absolute right-3 text-slate-400 font-mono text-[11px] font-bold">%</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">Example for Tanzania: VAT is typically 18%.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Box 2: Inventory Rules & Alert Triggers -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Inventory Threshold Formulas</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Global Safety Low-Stock Limit</label>
                                <div class="relative flex items-center">
                                    <input type="number" name="low_stock_limit" value="{{ $settings['low_stock_limit'] ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all pr-12">
                                    <span class="absolute right-3 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Units</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">Tanzania example: flag stock at 5 units or below.</p>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Critical Out-of-Stock Locking</label>
                                <select name="out_of_stock_policy" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="lock" @selected(($settings['out_of_stock_policy'] ?? 'lock') === 'lock')>Restrict sales when stock is 0</option>
                                    <option value="allow" @selected(($settings['out_of_stock_policy'] ?? '') === 'allow')>Allow back-orders / negative counts</option>
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">Use lock mode for stricter Tanzanian retail control.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Box 3b: Local Business Defaults -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tanzania Business Defaults</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Timezone</label>
                                <select name="timezone" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="Africa/Dar_es_Salaam" @selected(($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'Africa/Dar_es_Salaam')>Africa/Dar_es_Salaam</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Country</label>
                                <input type="text" value="Tanzania" class="w-full bg-slate-100 border border-slate-200 rounded-lg p-2 font-medium text-slate-500" disabled>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Branch Name</label>
                                <input type="text" name="branch_name" value="{{ $settings['branch_name'] ?? 'Dar es Salaam HQ' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Branch Code</label>
                                <input type="text" name="branch_code" value="{{ $settings['branch_code'] ?? 'TZ-DSM-001' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Company Phone</label>
                                <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '+255 754 000 000' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all" placeholder="+255 7XX XXX XXX">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: NODE INFRASTRUCTURE & BACKUPS (1/3 Width) -->
                <div class="space-y-6">
                    
                    <!-- Box 3: Network Infrastructure Reference -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Node Architecture</h3>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            
                            <div class="space-y-3 text-xs">
                                <div>
                                    <span class="text-slate-400 block font-bold uppercase tracking-wider text-[9px]">Active Branch Identity</span>
                                        <span class="text-slate-900 font-mono font-bold mt-0.5 block">{{ $settings['branch_code'] ?? 'TZ-DSM-001' }} ({{ $settings['branch_name'] ?? 'Dar es Salaam HQ' }})</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-bold uppercase tracking-wider text-[9px]">Database Cluster Sync</span>
                                        <span class="text-slate-600 font-medium mt-0.5 block">Company-scoped via the active session tenant</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 mt-6">
                            <button type="button" class="w-full group p-2.5 rounded-lg border border-slate-200 bg-slate-50/40 hover:bg-white hover:border-slate-400 transition-all text-left flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-700 group-hover:text-slate-900 uppercase tracking-wide">Test Node Ping</span>
                                <i class="fa-solid fa-network-wired text-[10px] text-slate-400 group-hover:text-slate-900"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Box 4: System Diagnostics Clearance -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Instance Verification</h3>
                        </div>
                        <div class="text-[11px] text-slate-500 space-y-2">
                            <p>You are managing settings for <span class="font-bold text-slate-900">{{ $company->name }}</span>.</p>
                            <p>Current currency: <span class="font-bold text-slate-900">{{ $settings['currency'] ?? 'TZS' }}</span>.</p>
                            <p class="bg-slate-50 border border-slate-200 p-2 rounded font-mono text-[10px] text-slate-600 leading-normal">
                                Tenant Scoped:<br>
                                Company ID {{ $company->id }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
