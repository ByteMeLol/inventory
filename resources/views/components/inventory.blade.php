<div class="inventory-page mx-auto w-full max-w-[1380px] px-1 py-2 sm:px-2 lg:px-3"
     x-data="inventoryOperations()"
     x-init="loadOperations()"
     @open-modal.window="if ($event.detail === 'add-item') showModal = true"
     @close-modal.window="showModal = false">
            
    <div class="mb-8 flex flex-col gap-6 border-b border-[#d8e2da] pb-7 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-12 w-12 items-center justify-center border border-[#c4d8c8] bg-[#e7f2e9] text-[#1d6535]"><i class="fa-solid fa-cubes-stacked"></i></span>
                <div>
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#6f8976]">Stock workspace</p>
                    <h1 class="text-3xl font-semibold tracking-[-0.04em] text-[#17231b]">Inventory overview</h1>
                    <p class="mt-2 text-sm text-[#718077]">Catalog, stock health, traceability, and warehouse operations in one view.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="$dispatch('open-modal', 'add-item')" class="flex items-center gap-2 bg-[#173b26] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#285b38]">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Register New SKU</span>
            </button>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-0 border-y border-l border-[#dfe6e0] bg-white sm:grid-cols-3">
        <div class="flex items-center justify-between border-r border-b border-[#dfe6e0] p-6 sm:border-b-0">
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-[0.14em] text-[#849389]">Total catalog items</span>
                <span class="mt-2 block text-3xl font-semibold tracking-tight text-[#1b3525]">{{$inventory->count()}} <small class="text-sm font-normal text-[#93a098]">SKUs</small></span>
            </div>
            <div class="flex h-10 w-10 items-center justify-center border border-[#dce9de] bg-[#f5faf5] text-[#6eb27d]">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
        <div class="flex items-center justify-between border-r border-b border-[#eadfce] bg-[#fffdf9] p-6 sm:border-b-0">
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-[0.14em] text-[#9b866c]">Critical low stock</span>
                <span id='low_stock' class="mt-2 block text-3xl font-semibold tracking-tight text-[#a9682e]">{{ $lowStockCount }} <small class="text-sm font-normal text-[#c69d70]">lines</small></span>
            </div>
            <div class="flex h-10 w-10 items-center justify-center border border-[#f0dfc9] bg-[#fff8ed] text-[#c77e39]">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div class="flex items-center justify-between border-r border-[#dfe6e0] p-6">
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-[0.14em] text-[#849389]">Total capital valuation</span>
                <span class="mt-2 block text-3xl font-semibold tracking-tight text-[#1b3525]" x-text="formatMoney(totalValue)">TZS 0.00</span>
            </div>
            <div class="flex h-10 w-10 items-center justify-center border border-[#dce9de] bg-[#f5faf5] text-[#6eb27d]">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>
    </div>

    <div class="hidden">
    <div class="mb-7 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section class="border border-[#e6d7c2] bg-[#fffdf9] p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-[#6c4d2c]">Action centre</h2>
                    <p class="mt-1 text-xs text-[#9a7b59]">Reorder and expiry signals for this company.</p>
                </div>
                <button type="button" @click="loadOperations()" class="border border-[#dfc8a9] bg-white px-3 py-2 text-xs font-semibold text-[#8b633c] hover:bg-[#fff7eb]">Refresh</button>
            </div>
            <div class="mt-3 space-y-2">
                <template x-for="alert in alerts" :key="alert.id">
                    <div class="flex items-center justify-between border border-[#eadcc9] bg-white px-4 py-3 text-xs">
                        <span class="font-medium text-slate-700" x-text="alert.message"></span>
                        <span class="border border-[#ead5b9] bg-[#fff8ed] px-2 py-1 text-[10px] font-bold uppercase text-[#9a6c38]" x-text="alert.type"></span>
                    </div>
                </template>
                <p x-show="!alerts.length" class="border border-dashed border-[#dfc8a9] bg-white/60 p-4 text-xs text-[#9a7b59]">No open inventory alerts.</p>
            </div>
        </section>
        <section class="border border-[#dfe6e0] bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-[#263b2c]">Inventory value</h2>
                    <p class="mt-1 text-xs text-[#8a978e]">Weighted average or FIFO.</p>
                </div>
                <i class="fa-solid fa-chart-line text-slate-300"></i>
            </div>
            <p class="mt-6 text-3xl font-semibold text-[#1b2b20]" x-text="formatMoney(totalValue)">TZS 0.00</p>
            <p class="mt-2 text-xs text-[#8a978e]" x-text="valuation.length + ' products valued'"></p>
        </section>
    </div>

    <div class="mb-7 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section class="border border-[#dfe6e0] bg-white p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-[#263b2c]">Barcode scanner</h2>
                    <p class="mt-1 text-xs text-[#8a978e]">Scan a SKU or barcode to open live stock balances.</p>
                </div>
                <button type="button" @click="startScanner()" class="bg-[#173b26] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#285b38]">
                    <i class="fa-solid fa-camera mr-1"></i> Open camera
                </button>
            </div>
            <form class="mt-5 flex gap-2" @submit.prevent="lookupCode()">
                <input x-model="scanCode" required placeholder="Enter or scan code" class="min-w-0 flex-1 border border-[#dfe6e0] bg-[#fbfcfb] px-4 py-3 text-sm font-mono outline-none focus:border-[#8db59a]">
                <button class="bg-[#263b2c] px-5 py-3 text-xs font-semibold text-white">Lookup</button>
            </form>
            <div x-show="scanResult" class="mt-3 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-[11px]" style="display:none">
                <p class="font-bold text-emerald-900" x-text="scanResult?.description"></p>
                <p class="mt-1 text-emerald-800">SKU <span class="font-mono" x-text="scanResult?.sku"></span> · <span x-text="scanResult?.stock_balances?.length || 0"></span> locations</p>
            </div>
            <p x-show="scanError" class="mt-3 rounded-lg bg-red-50 p-3 text-[11px] text-red-700" x-text="scanError" style="display:none"></p>
        </section>
        <section class="border border-[#dfe6e0] bg-white p-6">
            <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-[#263b2c]">Operations</h2>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <button type="button" @click="showReturn = true" class="rounded-lg border border-slate-200 p-3 text-left text-[10px] font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-rotate-left mb-1 block text-slate-400"></i>Record return</button>
                <button type="button" @click="showStocktake = true" class="rounded-lg border border-slate-200 p-3 text-left text-[10px] font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-clipboard-check mb-1 block text-slate-400"></i>Start stocktake</button>
            </div>
        </section>
    </div>

    </div>

    <div class="mb-5 flex items-center justify-between border-b border-[#dfe6e0] pb-4">
        <div>
            <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-[#263b2c]">SKU catalog</h2>
            <p class="mt-1 text-xs text-[#8a978e]">Manage products, pricing, and available stock.</p>
        </div>
        <span class="text-xs text-[#8a978e]">{{ $inventory->total() }} records</span>
    </div>

    <div class="mb-6 border border-[#dfe6e0] bg-white p-4">
        <form method="GET" action="/inventory" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative w-full md:flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" placeholder="Filter by SKU code or part description..." class="w-full bg-gray-50/50 border border-gray-200 rounded-lg py-1.5 pl-9 pr-4 text-xs text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all">
            </div>
            <div class="w-full md:w-48">
                <select name="category" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg py-1.5 px-2.5 text-xs text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all">
                    <option value="">All Categories</option>
                    @foreach ($inventoryCategories as $category)
                        <option value="{{ $category->name }}" @selected(request('category') === $category->name)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs py-1.5 px-4 rounded-lg transition shadow-xs">
                Apply Filters
            </button>
        </form>
    </div>

    <div class="hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Warehouse network</h2>
                    <p class="text-[10px] text-gray-400 mt-1">Stock is controlled by warehouse and bin location.</p>
                </div>
                <i class="fa-solid fa-location-crosshairs text-gray-300"></i>
            </div>
            <div class="space-y-2">
                @forelse ($warehouses as $warehouse)
                    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50/50 px-3 py-2">
                        <div>
                            <span class="font-mono text-[10px] font-bold text-gray-900">{{ $warehouse->code }}</span>
                            <span class="ml-2 text-[11px] text-gray-600">{{ $warehouse->name }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $warehouse->locations->count() }} locations</span>
                    </div>
                @empty
                    <p class="rounded-lg border border-dashed border-gray-200 p-3 text-[11px] text-gray-400">No warehouse has been configured yet.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <form method="POST" action="{{ route('inventory.categories.store') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs space-y-2">
                @csrf
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Add product category</h2>
                <div class="grid grid-cols-2 gap-2"><input name="name" required placeholder="Category name" class="rounded border p-2 text-xs"><input name="code" required placeholder="Code" class="rounded border p-2 text-xs"></div>
                <button class="rounded bg-slate-900 px-3 py-2 text-[11px] font-semibold text-white">Create category</button>
            </form>
            <form method="POST" action="{{ route('inventory.units.store') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs space-y-2">
                @csrf
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Add measurement unit</h2>
                <div class="grid grid-cols-3 gap-2"><input name="name" required placeholder="Kilogram" class="rounded border p-2 text-xs"><input name="symbol" required placeholder="kg" class="rounded border p-2 text-xs"><input name="conversion_factor" required value="1" type="number" step="0.000001" class="rounded border p-2 text-xs"></div>
                <button class="rounded bg-slate-900 px-3 py-2 text-[11px] font-semibold text-white">Create unit</button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div><h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Product categories</h2><p class="mt-1 text-[10px] text-gray-400">Categories available to SKU records.</p></div>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] font-semibold text-gray-500">{{ $inventoryCategories->count() }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @forelse($inventoryCategories as $category)
                        <span class="rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-[10px] font-semibold text-gray-700">{{ $category->name }} <span class="font-mono font-normal text-gray-400">{{ $category->code }}</span></span>
                    @empty
                        <span class="text-xs text-gray-400">No categories created yet.</span>
                    @endforelse
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div><h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Measurement units</h2><p class="mt-1 text-[10px] text-gray-400">Units available when registering goods.</p></div>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] font-semibold text-gray-500">{{ $inventoryUnits->count() }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @forelse($inventoryUnits as $unit)
                        <span class="rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-[10px] font-semibold text-gray-700">{{ $unit->name }} <span class="font-mono font-normal text-gray-400">{{ $unit->symbol }}</span></span>
                    @empty
                        <span class="text-xs text-gray-400">No units created yet.</span>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="bg-slate-900 rounded-xl p-4 shadow-xs text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider">Operational controls</h2>
                    <p class="text-[10px] text-slate-400 mt-1">Use the stock ledger for receipts, issues, adjustments, and reservations.</p>
                </div>
                <i class="fa-solid fa-arrow-right-arrow-left text-slate-500"></i>
            </div>
            <div class="grid grid-cols-2 gap-2 text-[10px]">
                <span class="rounded-lg bg-white/10 px-3 py-2">Receipts are traceable</span>
                <span class="rounded-lg bg-white/10 px-3 py-2">Issues cannot go negative</span>
                <span class="rounded-lg bg-white/10 px-3 py-2">Reservations are isolated</span>
                <span class="rounded-lg bg-white/10 px-3 py-2">Every movement is immutable</span>
            </div>
        </div>
    </div>

    </div>

    <div class="border border-[#dfe6e0] bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider border-b border-gray-200/80 text-[10px]">
                        <th class="p-4 w-32">SKU Code</th>
                        <th class="p-4">Material Part Description</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Unit</th>
                        <th class="p-4 text-right">Unit Price</th>
                        <th class="p-4 text-center w-32">Available Stock</th>
                        <th class="p-4 text-center w-32">Safety Minimum</th>
                        <th class="p-4 text-right w-40">Status Matrix</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600 font-medium">
                    @forelse ($inventory as $item)
                        <tr class="hover:bg-gray-50/40 transition-colors">
                        <td class="p-4 font-mono font-bold text-gray-900">{{ $item->sku }}</td>
                        <td class="p-4 text-gray-900 font-semibold">{{ $item->description }}</td>
                        <td class="p-4 text-[#66766b]">{{ $item->category }}</td>
                        <td class="p-4 text-[#66766b]">{{ $item->unit?->symbol ?? '—' }}</td>
                        <td class="p-4 text-right font-mono text-gray-700">TZS {{ number_format($item->unit_price ?? 0, 2) }}</td>
                        <td class="p-4 text-center font-bold text-red-600 bg-red-50/30">{{ $item->stock_count }}</td>
                        <td class="p-4 text-center font-mono text-gray-400">{{ $item->safety_threshold }}</td>
                        <td class="p-4 text-right">
                            <span class="text-[9px] font-bold
                                {{ $item->stock_count <= $item->safety_threshold
                                    ? 'text-red-700 bg-red-50 border-red-200'
                                    : 'text-green-700 bg-green-50 border-green-200' }}
                                px-2 py-0.5 rounded border uppercase tracking-wide">
                                
                                {{ $item->stock_count <= $item->safety_threshold ? 'Low Stock' : 'Available' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-xs text-[#8a978e]">No SKU records found for the current company.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div 
        x-show="showModal" 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
        style="display: none;"
    >
        <div 
            x-show="showModal" 
            x-transition.opacity 
            @click="showModal = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"
        ></div>

        <div 
            x-show="showModal" 
            x-transition.scale.95
            class="relative w-full max-w-md transform rounded-xl bg-white p-5 text-left shadow-xl border border-gray-200 transition-all z-10"
        >
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Register New SKU Resource</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Append a new part record to your current active company scope.</p>
                </div>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="/inventory" method="POST" class="space-y-4 text-xs font-medium">
                @csrf
                
                <div>
                    <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">SKU / Code Reference</label>
                    <input type="text" id="sku" name="sku" required placeholder="e.g., CPL-HP-90" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all font-mono uppercase" readonly>
                </div>

                <div>
                    <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Material Part Description</label>
                    <input type="text" name="description" required placeholder="e.g., High-Pressure Hydraulic Coupler" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all">
                </div>

                <div>
                    <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Barcode</label>
                    <input type="text" name="barcode" placeholder="Optional EAN or Code 128 value" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 font-mono">
                </div>

                <div>
                    <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Unit Price</label>
                    <input type="number" step="0.01" name="unit_price" required placeholder="0.00" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all font-mono">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Buying Price</label>
                        <input type="number" step="0.000001" name="buying_price" required placeholder="0.00" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 font-mono">
                    </div>
                    <div>
                        <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Selling Price</label>
                        <input type="number" step="0.000001" name="selling_price" required placeholder="0.00" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div><label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Reorder point</label><input type="number" step="0.000001" name="reorder_point" value="5" min="0" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 p-2 font-mono text-gray-800"></div>
                    <div><label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Reorder quantity</label><input type="number" step="0.000001" name="reorder_quantity" min="0" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 p-2 font-mono text-gray-800"></div>
                    <div><label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Cost method</label><select name="cost_method" class="w-full rounded-lg border border-gray-200 bg-gray-50/50 p-2 text-gray-800"><option value="weighted_average">Weighted average</option><option value="fifo">FIFO</option></select></div>
                </div>
                <div class="grid grid-cols-3 gap-3 text-[10px] text-slate-600">
                    <label class="flex items-center gap-2"><input type="checkbox" name="track_batches" value="1" class="rounded"> Track batches</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="track_serials" value="1" class="rounded"> Track serials</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="track_expiry" value="1" class="rounded"> Track expiry</label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Initial Stock Count</label>
                        <input type="number" name="stock_count" value="0" min="0" required class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all font-mono">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Category</label>
                            <select name="category" required class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800">
                                <option value="">Select category</option>
                                @foreach ($inventoryCategories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }} ({{ $category->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Unit</label>
                            <select name="unit_id" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800">
                                <option value="">Select unit</option>
                                @foreach ($inventoryUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Safety Minimum Threshold</label>
                        <input type="number" name="safety_threshold" value="5" min="0" required class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all font-mono">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-2 mt-2">
                    <button type="button" @click="showModal = false" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-semibold hover:bg-gray-50 transition shadow-xs">
                        Cancel
                    </button>
                    <button type="submit" class="bg-[#025c78] hover:bg-[#014d64] text-white px-3 py-1.5 rounded-lg font-semibold transition shadow-xs">
                        Commit SKU File
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<div x-show="showReturn || showStocktake || scannerOpen" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/50 p-4" style="display:none">
    <div @click.outside="closeOverlays()" class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl">
        <template x-if="scannerOpen">
            <div>
                <div class="flex items-center justify-between"><h3 class="text-sm font-bold text-slate-900">Camera scanner</h3><button @click="closeOverlays()" class="text-slate-400"><i class="fa-solid fa-xmark"></i></button></div>
                <video x-ref="scannerVideo" autoplay playsinline class="mt-4 aspect-video w-full rounded-xl bg-slate-900 object-cover"></video>
                <p class="mt-3 text-xs text-slate-500">Point the camera at a barcode. Scans are queued locally if the device is offline.</p>
            </div>
        </template>
        <template x-if="showReturn">
            <form method="POST" action="{{ route('inventory.returns.store') }}" class="space-y-3">
                @csrf
                <div class="flex items-center justify-between"><h3 class="text-sm font-bold text-slate-900">Record inventory return</h3><button type="button" @click="closeOverlays()" class="text-slate-400"><i class="fa-solid fa-xmark"></i></button></div>
                <select name="direction" required class="w-full rounded-lg border border-slate-200 p-2 text-xs"><option value="customer">Customer return</option><option value="supplier">Supplier return</option></select>
                <div class="grid grid-cols-2 gap-2"><select name="inventory_id" required class="rounded-lg border border-slate-200 p-2 text-xs"><option value="">Product</option>@foreach($inventory as $item)<option value="{{ $item->id }}">{{ $item->sku }} · {{ $item->description }}</option>@endforeach</select><select name="warehouse_location_id" required class="rounded-lg border border-slate-200 p-2 text-xs"><option value="">Location</option>@foreach($inventoryLocations as $location)<option value="{{ $location->id }}">{{ $location->warehouse->code }} / {{ $location->code }}</option>@endforeach</select></div>
                <div class="grid grid-cols-2 gap-2"><input name="reference_number" required placeholder="Return reference" class="rounded-lg border border-slate-200 p-2 text-xs"><input name="quantity" required type="number" step="0.000001" min="0.000001" placeholder="Quantity" class="rounded-lg border border-slate-200 p-2 text-xs"></div>
                <div class="grid grid-cols-2 gap-2"><input name="unit_cost" required type="number" step="0.01" min="0" placeholder="Unit cost" class="rounded-lg border border-slate-200 p-2 text-xs"><input name="reason" placeholder="Reason" class="rounded-lg border border-slate-200 p-2 text-xs"></div>
                <div class="flex justify-end"><button class="rounded-lg bg-[#025c78] px-4 py-2 text-xs font-semibold text-white">Save return</button></div>
            </form>
        </template>
        <template x-if="showStocktake">
            <form @submit.prevent="createStocktake()" class="space-y-3">
                <div class="flex items-center justify-between"><h3 class="text-sm font-bold text-slate-900">Start stocktake</h3><button type="button" @click="closeOverlays()" class="text-slate-400"><i class="fa-solid fa-xmark"></i></button></div>
                <input x-model="stocktakeReference" required placeholder="Stocktake reference" class="w-full rounded-lg border border-slate-200 p-2 text-xs">
                <select x-model="stocktakeLocation" required class="w-full rounded-lg border border-slate-200 p-2 text-xs"><option value="">Select location</option>@foreach($inventoryLocations as $location)<option value="{{ $location->id }}">{{ $location->warehouse->name }} / {{ $location->code }}</option>@endforeach</select>
                <p class="text-[11px] text-slate-500">The system will snapshot expected quantities for every product in the selected location and send the count for reconciliation.</p>
                <div class="flex justify-end"><button class="rounded-lg bg-[#025c78] px-4 py-2 text-xs font-semibold text-white">Create stocktake</button></div>
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
                const [alertsResponse, valuationResponse] = await Promise.all([
                    fetch('{{ route('inventory.alerts') }}', { headers: { Accept: 'application/json' } }),
                    fetch('{{ route('inventory.valuation') }}', { headers: { Accept: 'application/json' } }),
                ]);
                this.alerts = alertsResponse.ok ? await alertsResponse.json() : [];
                this.valuation = valuationResponse.ok ? await valuationResponse.json() : [];
                this.totalValue = this.valuation.reduce((sum, row) => sum + Number(row.value || 0), 0);
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
                    this.scanError = response.status === 404 ? 'Product was not found in this company.' : 'Lookup failed. The scan was queued if you are offline.';
                    return;
                }
                this.scanResult = await response.json();
            },
            async startScanner() {
                if (! window.inventoryScanner?.supportsCamera) {
                    this.scanError = 'Camera barcode scanning is not supported by this browser. Enter the code manually.';
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
            const randomNumber = Math.floor(Math.random() * 900) + 100; // Generates a random number between 100 and 999
            const skuCode = `2BRO-${randomNumber}`;
            skuInput.value = skuCode;
        }
    });

    //this is the script which will help in counting lines where status matrix is low

    
    

</script>