<div class="inventory-page mx-auto w-full max-w-7xl px-4 py-8"
     x-data="inventoryOperations()"
     x-init="loadOperations()"
     @open-modal.window="if ($event.detail === 'add-item') showModal = true"
     @close-modal.window="showModal = false">
            
    <!-- Page Header Area -->
    <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Inventory overview') }}</h1>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $inventory->total() }} {{ __('SKUs') }}
                    </span>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Catalog, stock health, traceability, and warehouse operations in one view.') }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" @click="showStocktake = true" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                <i class="fa-solid fa-clipboard-check text-slate-400"></i>
                <span>{{ __('Start stocktake') }}</span>
            </button>
            <button @click="$dispatch('open-modal', 'add-item')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                <i class="fa-solid fa-plus text-slate-300"></i>
                <span>{{ __('Register New SKU') }}</span>
            </button>
        </div>
    </div>

    <!-- 3 Core Inventory Metrics -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <!-- Total Catalog Items -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Total catalog items') }}</span>
                <span class="mt-2 block text-2xl font-bold tracking-tight text-slate-900">{{ $inventory->count() }} <span class="text-xs font-normal text-slate-400">SKUs</span></span>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <i class="fa-solid fa-layer-group text-base"></i>
            </div>
        </div>

        <!-- Critical Low Stock -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Critical low stock') }}</span>
                <span id="low_stock" class="mt-2 block text-2xl font-bold tracking-tight {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-400">{{ __('lines') }}</span></span>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
            </div>
        </div>

        <!-- Total Capital Valuation -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Total capital valuation') }}</span>
                <span class="mt-2 block text-2xl font-bold tracking-tight text-slate-900" x-text="formatMoney(totalValue)">TZS 0.00</span>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <i class="fa-solid fa-scale-balanced text-base"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
        <form method="GET" action="/inventory" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative w-full md:flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Filter by SKU code or part description...') }}" class="w-full bg-slate-50/70 border border-slate-200 rounded-xl py-2 pl-9 pr-4 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all">
            </div>
            <div class="w-full md:w-56">
                <select name="category" class="w-full bg-slate-50/70 border border-slate-200 rounded-xl py-2 px-3 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition-all">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($inventoryCategories as $category)
                        <option value="{{ $category->name }}" @selected(request('category') === $category->name)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs py-2 px-5 rounded-xl transition shadow-xs flex items-center justify-center gap-2">
                <i class="fa-solid fa-filter text-[11px] text-slate-300"></i>
                <span>{{ __('Apply Filters') }}</span>
            </button>
            @if(request('search') || request('category'))
                <a href="/inventory" class="text-xs font-semibold text-slate-500 hover:text-slate-800 px-2 py-2">{{ __('Reset') }}</a>
            @endif
        </form>
    </div>

    <!-- Catalog Table Container -->
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">{{ __('SKU catalog') }}</h2>
                <p class="mt-0.5 text-xs text-slate-500">{{ __('Manage products, pricing, and available stock.') }}</p>
            </div>
            <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-600">{{ $inventory->total() }} {{ __('records') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                        <th class="px-6 py-3.5 w-36">{{ __('SKU Code') }}</th>
                        <th class="px-6 py-3.5">{{ __('Material Part Description') }}</th>
                        <th class="px-6 py-3.5">{{ __('Category') }}</th>
                        <th class="px-6 py-3.5">{{ __('Unit') }}</th>
                        <th class="px-6 py-3.5 text-right">{{ __('Unit Price') }}</th>
                        <th class="px-6 py-3.5 text-center w-32">{{ __('Available Stock') }}</th>
                        <th class="px-6 py-3.5 text-center w-32">{{ __('Safety Minimum') }}</th>
                        <th class="px-6 py-3.5 text-right w-40">{{ __('Status Matrix') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                    @forelse ($inventory as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $item->sku }}</td>
                            <td class="px-6 py-4 text-slate-900 font-semibold">{{ $item->description }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->category }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->unit?->symbol ?? '—' }}</td>
                            <td class="px-6 py-4 text-right font-mono text-slate-700">TZS {{ number_format($item->unit_price ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-center font-bold {{ $item->stock_count <= $item->safety_threshold ? 'text-rose-600' : 'text-slate-900' }}">{{ $item->stock_count }}</td>
                            <td class="px-6 py-4 text-center font-mono text-slate-400">{{ $item->safety_threshold }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider
                                    {{ $item->stock_count <= $item->safety_threshold
                                        ? 'text-rose-700 bg-rose-50 border border-rose-100'
                                        : 'text-emerald-700 bg-emerald-50 border border-emerald-100' }}">
                                    {{ $item->stock_count <= $item->safety_threshold ? __('Low Stock') : __('Available') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-xs text-slate-400">
                                <i class="fa-solid fa-boxes-stacked text-slate-300 text-2xl block mb-2"></i>
                                {{ __('No SKU records found for the current company.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inventory->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $inventory->links() }}
            </div>
        @endif
    </div>

    <!-- Registration Modal -->
    <div 
        x-show="showModal" 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
        style="display: none;"
    >
        <div 
            x-show="showModal" 
            x-transition.opacity 
            @click="showModal = false"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"
        ></div>

        <div 
            x-show="showModal" 
            x-transition.scale.95
            class="relative w-full max-w-lg transform rounded-2xl bg-white p-6 sm:p-7 text-left shadow-2xl border border-slate-100 transition-all z-10"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Register New SKU Resource') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Append a new item record to your current company inventory.') }}</p>
                </div>
                <button @click="showModal = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="/inventory" method="POST" class="space-y-4 text-xs font-medium">
                @csrf
                
                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('SKU / Code Reference') }}</label>
                    <input type="text" id="sku" name="sku" required placeholder="e.g., SKU-101" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono uppercase" readonly>
                </div>

                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Material Part Description') }}</label>
                    <input type="text" name="description" required placeholder="{{ __('e.g., High-Pressure Hydraulic Coupler') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                </div>

                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Barcode') }}</label>
                    <input type="text" name="barcode" placeholder="{{ __('Optional EAN or Code 128 value') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 font-mono">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Buying Price') }}</label>
                        <input type="number" step="0.000001" name="buying_price" required placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 font-mono">
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Selling Price') }}</label>
                        <input type="number" step="0.000001" name="selling_price" required placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 font-mono">
                    </div>
                </div>

                <div>
                    <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Unit Price') }}</label>
                    <input type="number" step="0.01" name="unit_price" required placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Reorder point') }}</label>
                        <input type="number" step="0.000001" name="reorder_point" value="5" min="0" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-2.5 font-mono text-slate-800">
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Reorder quantity') }}</label>
                        <input type="number" step="0.000001" name="reorder_quantity" min="0" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-2.5 font-mono text-slate-800">
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Cost method') }}</label>
                        <select name="cost_method" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-2.5 text-slate-800">
                            <option value="weighted_average">{{ __('Weighted average') }}</option>
                            <option value="fifo">{{ __('FIFO') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Category') }}</label>
                        <select name="category" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800">
                            <option value="">{{ __('Select category') }}</option>
                            @foreach ($inventoryCategories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }} ({{ $category->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Unit') }}</label>
                        <select name="unit_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800">
                            <option value="">{{ __('Select unit') }}</option>
                            @foreach ($inventoryUnits as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Initial Stock Count') }}</label>
                        <input type="number" name="stock_count" value="0" min="0" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Safety Minimum Threshold') }}</label>
                        <input type="number" name="safety_threshold" value="5" min="0" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3 mt-4">
                    <button type="button" @click="showModal = false" class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold hover:bg-slate-50 transition shadow-xs">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-xl font-semibold transition shadow-xs">
                        {{ __('Save Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Return & Stocktake Modal Overlays -->
<div x-show="showReturn || showStocktake || scannerOpen" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/50 backdrop-blur-xs p-4" style="display:none">
    <div @click.outside="closeOverlays()" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100">
        <template x-if="scannerOpen">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Camera scanner') }}</h3>
                    <button @click="closeOverlays()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <video x-ref="scannerVideo" autoplay playsinline class="mt-4 aspect-video w-full rounded-xl bg-slate-900 object-cover"></video>
                <p class="mt-3 text-xs text-slate-500">{{ __('Point the camera at a barcode. Scans are queued locally if the device is offline.') }}</p>
            </div>
        </template>
        <template x-if="showReturn">
            <form method="POST" action="{{ route('inventory.returns.store') }}" class="space-y-4">
                @csrf
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Record inventory return') }}</h3>
                    <button type="button" @click="closeOverlays()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <select name="direction" required class="w-full rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                    <option value="customer">{{ __('Customer return') }}</option>
                    <option value="supplier">{{ __('Supplier return') }}</option>
                </select>
                <div class="grid grid-cols-2 gap-3">
                    <select name="inventory_id" required class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                        <option value="">{{ __('Product') }}</option>
                        @foreach($inventory as $item)
                            <option value="{{ $item->id }}">{{ $item->sku }} · {{ $item->description }}</option>
                        @endforeach
                    </select>
                    <select name="warehouse_location_id" required class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                        <option value="">{{ __('Location') }}</option>
                        @foreach($inventoryLocations as $location)
                            <option value="{{ $location->id }}">{{ $location->warehouse->code }} / {{ $location->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input name="reference_number" required placeholder="{{ __('Return reference') }}" class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                    <input name="quantity" required type="number" step="0.000001" min="0.000001" placeholder="{{ __('Quantity') }}" class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input name="unit_cost" required type="number" step="0.01" min="0" placeholder="{{ __('Unit cost') }}" class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                    <input name="reason" placeholder="{{ __('Reason') }}" class="rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="closeOverlays()" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">{{ __('Save return') }}</button>
                </div>
            </form>
        </template>
        <template x-if="showStocktake">
            <form @submit.prevent="createStocktake()" class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Start stocktake') }}</h3>
                    <button type="button" @click="closeOverlays()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <input x-model="stocktakeReference" required placeholder="{{ __('Stocktake reference') }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                <select x-model="stocktakeLocation" required class="w-full rounded-xl border border-slate-200 p-2.5 text-xs bg-slate-50/50">
                    <option value="">{{ __('Select location') }}</option>
                    @foreach($inventoryLocations as $location)
                        <option value="{{ $location->id }}">{{ $location->warehouse->name }} / {{ $location->code }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500">{{ __('The system will snapshot expected quantities for every product in the selected location and send the count for reconciliation.') }}</p>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="closeOverlays()" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">{{ __('Create stocktake') }}</button>
                </div>
            </form>
        </template>
    </div>
</div>

<script>
    function inventoryOperations() {
        return {
            showModal: false,
            showReturn: false,
            showStocktake: false,
            scannerOpen: false,
            alerts: [],
            valuation: [],
            totalValue: 0,
            scanCode: '',
            scanResult: null,
            scanError: '',
            stocktakeReference: '',
            stocktakeLocation: '',
            async loadOperations() {
                try {
                    const [alertsResponse, valuationResponse] = await Promise.all([
                        fetch('{{ route('inventory.alerts') }}', { headers: { Accept: 'application/json' } }),
                        fetch('{{ route('inventory.valuation') }}', { headers: { Accept: 'application/json' } }),
                    ]);
                    this.alerts = alertsResponse.ok ? await alertsResponse.json() : [];
                    this.valuation = valuationResponse.ok ? await valuationResponse.json() : [];
                    this.totalValue = this.valuation.reduce((sum, row) => sum + Number(row.value || 0), 0);
                } catch (e) {
                    // Silent catch for resilience
                }
            },
            formatMoney(value) {
                return `TZS ${Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            },
            async lookupCode() {
                this.scanError = '';
                this.scanResult = null;
                const response = await fetch(`{{ route('inventory.lookup') }}?code=${encodeURIComponent(this.scanCode)}`, { headers: { Accept: 'application/json' } });
                if (! response.ok) {
                    if (! navigator.onLine && window.inventoryScanner) await window.inventoryScanner.queue(this.scanCode);
                    this.scanError = response.status === 404 ? '{{ __('Product was not found in this company.') }}' : '{{ __('Lookup failed. The scan was queued if you are offline.') }}';
                    return;
                }
                this.scanResult = await response.json();
            },
            async startScanner() {
                if (! window.inventoryScanner?.supportsCamera) {
                    this.scanError = '{{ __('Camera barcode scanning is not supported by this browser. Enter the code manually.') }}';
                    return;
                }
                this.scannerOpen = true;
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                this.$refs.scannerVideo.srcObject = stream;
                const detector = new BarcodeDetector();
                const scan = async () => {
                    if (! this.scannerOpen) return;
                    const codes = await detector.detect(this.$refs.scannerVideo);
                    if (codes.length) {
                        this.scanCode = codes[0].rawValue;
                        this.closeOverlays();
                        await this.lookupCode();
                        return;
                    }
                    requestAnimationFrame(scan);
                };
                scan();
            },
            closeOverlays() {
                const video = this.$refs.scannerVideo;
                video?.srcObject?.getTracks().forEach(track => track.stop());
                this.showReturn = false;
                this.showStocktake = false;
                this.scannerOpen = false;
            },
            async createStocktake() {
                const response = await fetch('{{ route('inventory.stocktakes.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
                    body: JSON.stringify({ warehouse_location_id: this.stocktakeLocation, reference_number: this.stocktakeReference }),
                });
                if (response.ok) {
                    this.closeOverlays();
                    this.stocktakeReference = '';
                    this.stocktakeLocation = '';
                }
            },
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const skuInput = document.getElementById('sku');
        if(skuInput){
            const randomNumber = Math.floor(Math.random() * 900) + 100;
            const skuCode = `SKU-${randomNumber}`;
            skuInput.value = skuCode;
        }
    });
</script>
