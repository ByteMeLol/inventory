    @props(['company', 'settings', 'inventoryCategories', 'inventoryUnits'])

    <div class="mx-auto max-w-6xl px-4 py-8">
        <!-- PAGE HEADER AREA -->
        <div class="mb-7 flex flex-col gap-5 border-b border-[#d8e2da] pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#78907e]">{{ __('Workspace settings') }}</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-[-0.04em] text-[#17231b]">{{ __('System configuration') }}</h1>
                <p class="mt-2 text-sm text-[#718077]">{{ __('Configure catalog, financial, and company defaults for :company.', ['company' => $company->name]) }}</p>
            </div>
            <div>
                <button type="submit" form="settings-form" class="bg-[#173b26] px-5 py-3 text-xs font-semibold text-white transition hover:bg-[#285b38]">
                    <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                    <span>{{ __('Save Settings') }}</span>
                </button>
            </div>
        </div>

        <!-- LANGUAGE SWITCHER CARD IN SETTINGS -->
        <section class="mb-7 border border-[#dfe6e0] bg-white p-6 rounded-xl shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#78907e]">{{ __('Language Preference') }}</p>
                    <h2 class="mt-1 text-lg font-semibold tracking-tight text-[#1b2b20]">{{ __('Application Language') }}</h2>
                    <p class="mt-1 text-xs text-[#8a978e]">{{ __('Switch system interface language between English and Kiswahili.') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('language.switch', 'en') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-semibold transition {{ app()->getLocale() === 'en' ? 'bg-[#173b26] text-white shadow-sm' : 'border border-slate-200 bg-slate-50 text-slate-700 hover:bg-white' }}">
                        <span>🇬🇧</span>
                        <span>English</span>
                    </a>
                    <a href="{{ route('language.switch', 'sw') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-semibold transition {{ app()->getLocale() === 'sw' ? 'bg-[#173b26] text-white shadow-sm' : 'border border-slate-200 bg-slate-50 text-slate-700 hover:bg-white' }}">
                        <span>🇹🇿</span>
                        <span>Kiswahili</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="mb-7 border border-[#dfe6e0] bg-white p-6">
            <div class="mb-6 flex items-end justify-between border-b border-[#e8ede9] pb-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#78907e]">{{ __('Catalog setup') }}</p>
                    <h2 class="mt-1 text-xl font-semibold tracking-tight text-[#1b2b20]">{{ __('Categories and units') }}</h2>
                    <p class="mt-1 text-xs text-[#8a978e]">{{ __('Shared by the SKU registration form and scoped to this company.') }}</p>
                </div>
                <span class="hidden text-xs text-[#8a978e] sm:block">{{ $inventoryCategories->count() }} {{ __('categories') }} · {{ $inventoryUnits->count() }} {{ __('units') }}</span>
            </div>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">{{ __('Product categories') }}</h3>
                    <form method="POST" action="{{ route('inventory.categories.store') }}" class="mt-3 grid grid-cols-[1fr_110px_auto] gap-2">
                        @csrf
                        <input name="name" required placeholder="{{ __('Category name') }}" class="min-w-0 border border-[#dfe6e0] bg-[#fbfcfb] px-3 py-3 text-xs outline-none focus:border-[#8db59a]">
                        <input name="code" required placeholder="{{ __('Code') }}" class="border border-[#dfe6e0] bg-[#fbfcfb] px-3 py-3 text-xs uppercase outline-none focus:border-[#8db59a]">
                        <button class="bg-[#173b26] px-4 py-3 text-xs font-semibold text-white hover:bg-[#285b38]">{{ __('Add') }}</button>
                    </form>
                    <div class="mt-4 divide-y divide-[#edf1ee] border-y border-[#edf1ee]">
                        @forelse($inventoryCategories as $category)
                            <div class="flex items-center justify-between py-3 text-xs"><span class="font-medium text-[#35493a]">{{ $category->name }}</span><span class="font-mono text-[10px] text-[#8a978e]">{{ $category->code }}</span></div>
                        @empty
                            <p class="py-4 text-xs text-[#8a978e]">{{ __('No categories configured.') }}</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">{{ __('Measurement units') }}</h3>
                    <form method="POST" action="{{ route('inventory.units.store') }}" class="mt-3 grid grid-cols-[1fr_80px_90px_auto] gap-2">
                        @csrf
                        <input name="name" required placeholder="{{ __('Unit name') }}" class="min-w-0 border border-[#dfe6e0] bg-[#fbfcfb] px-3 py-3 text-xs outline-none focus:border-[#8db59a]">
                        <input name="symbol" required placeholder="{{ __('Symbol') }}" class="border border-[#dfe6e0] bg-[#fbfcfb] px-3 py-3 text-xs outline-none focus:border-[#8db59a]">
                        <input name="conversion_factor" required value="1" type="number" step="0.000001" min="0.000001" class="border border-[#dfe6e0] bg-[#fbfcfb] px-3 py-3 text-xs outline-none focus:border-[#8db59a]">
                        <button class="bg-[#173b26] px-4 py-3 text-xs font-semibold text-white hover:bg-[#285b38]">{{ __('Add') }}</button>
                    </form>
                    <div class="mt-4 divide-y divide-[#edf1ee] border-y border-[#edf1ee]">
                        @forelse($inventoryUnits as $unit)
                            <div class="flex items-center justify-between py-3 text-xs"><span class="font-medium text-[#35493a]">{{ $unit->name }}</span><span class="font-mono text-[10px] text-[#8a978e]">{{ $unit->symbol }} · {{ $unit->conversion_factor }}</span></div>
                        @empty
                            <p class="py-4 text-xs text-[#8a978e]">{{ __('No units configured.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

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
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ __('Accounting & Ledger Defaults') }}</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Base Operating Currency') }}</label>
                                <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="TZS" @selected(($settings['currency'] ?? 'TZS') === 'TZS')>TZS (TSh) — {{ __('Tanzanian Shilling') }}</option>
                                    <option value="USD" @selected(($settings['currency'] ?? '') === 'USD')>USD ($) — {{ __('United States Dollar') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Industrial VAT / Tax Standard') }}</label>
                                <div class="relative flex items-center">
                                    <input type="number" name="tax_rate" step="0.1" value="{{ $settings['tax_rate'] ?? 18.0 }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all pr-8">
                                    <span class="absolute right-3 text-slate-400 font-mono text-[11px] font-bold">%</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">{{ __('Example for Tanzania: VAT is typically 18%.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Box 2: Inventory Rules & Alert Triggers -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ __('Inventory Threshold Formulas') }}</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Global Safety Low-Stock Limit') }}</label>
                                <div class="relative flex items-center">
                                    <input type="number" name="low_stock_limit" value="{{ $settings['low_stock_limit'] ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all pr-12">
                                    <span class="absolute right-3 text-slate-400 text-[10px] font-bold uppercase tracking-wider">{{ __('Units') }}</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">{{ __('Tanzania example: flag stock at 5 units or below.') }}</p>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Critical Out-of-Stock Locking') }}</label>
                                <select name="out_of_stock_policy" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="lock" @selected(($settings['out_of_stock_policy'] ?? 'lock') === 'lock')>{{ __('Restrict sales when stock is 0') }}</option>
                                    <option value="allow" @selected(($settings['out_of_stock_policy'] ?? '') === 'allow')>{{ __('Allow back-orders / negative counts') }}</option>
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">{{ __('Use lock mode for stricter Tanzanian retail control.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Box 3b: Local Business Defaults -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ __('Tanzania Business Defaults') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Timezone') }}</label>
                                <select name="timezone" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                                    <option value="Africa/Dar_es_Salaam" @selected(($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'Africa/Dar_es_Salaam')>Africa/Dar_es_Salaam</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Country') }}</label>
                                <input type="text" value="Tanzania" class="w-full bg-slate-100 border border-slate-200 rounded-lg p-2 font-medium text-slate-500" disabled>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Branch Name') }}</label>
                                <input type="text" name="branch_name" value="{{ $settings['branch_name'] ?? 'Dar es Salaam HQ' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Branch Code') }}</label>
                                <input type="text" name="branch_code" value="{{ $settings['branch_code'] ?? 'TZ-DSM-001' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium text-slate-800 focus:bg-white focus:border-slate-400 focus:outline-hidden transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Company Phone') }}</label>
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
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Node Architecture') }}</h3>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            
                            <div class="space-y-3 text-xs">
                                <div>
                                    <span class="text-slate-400 block font-bold uppercase tracking-wider text-[9px]">{{ __('Active Branch Identity') }}</span>
                                        <span class="text-slate-900 font-mono font-bold mt-0.5 block">{{ $settings['branch_code'] ?? 'TZ-DSM-001' }} ({{ $settings['branch_name'] ?? 'Dar es Salaam HQ' }})</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-bold uppercase tracking-wider text-[9px]">{{ __('Database Cluster Sync') }}</span>
                                        <span class="text-slate-600 font-medium mt-0.5 block">{{ __('Company-scoped via the active session tenant') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 mt-6">
                            <button type="button" class="w-full group p-2.5 rounded-lg border border-slate-200 bg-slate-50/40 hover:bg-white hover:border-slate-400 transition-all text-left flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-700 group-hover:text-slate-900 uppercase tracking-wide">{{ __('Test Node Ping') }}</span>
                                <i class="fa-solid fa-network-wired text-[10px] text-slate-400 group-hover:text-slate-900"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Box 4: System Diagnostics Clearance -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Instance Verification') }}</h3>
                        </div>
                        <div class="text-[11px] text-slate-500 space-y-2">
                            <p>{{ __('You are managing settings for :company.', ['company' => $company->name]) }}</p>
                            <p>{{ __('Current currency: :currency.', ['currency' => $settings['currency'] ?? 'TZS']) }}</p>
                            <p class="bg-slate-50 border border-slate-200 p-2 rounded font-mono text-[10px] text-slate-600 leading-normal">
                                {{ __('Tenant Scoped:') }}<br>
                                {{ __('Company ID :id', ['id' => $company->id]) }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
