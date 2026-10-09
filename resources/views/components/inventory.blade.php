<div class="max-w-5xl mx-auto px-4 py-8" 
     x-data="{ showModal: false }" 
     @open-modal.window="if ($event.detail === 'add-item') showModal = true"
     @close-modal.window="showModal = false">
            
    <div class="mb-8 pb-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold tracking-tight text-gray-900">SKU Stock Inventory</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage mechanical component assets, assign classifications, and track safety allocation lines.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="$dispatch('open-modal', 'add-item')" class="bg-[#025c78] hover:bg-[#014d64] text-white text-xs font-semibold py-1.5 px-4 rounded-lg transition shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Register New SKU</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Catalog items</span>
                <span class="text-xl font-bold text-gray-900 tracking-tight">{{$inventory->count()}} SKUs</span>
            </div>
            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 text-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Critical Low Stock</span>
                <span id='low_stock' class="text-xl font-bold text-amber-600 tracking-tight">{{ $lowStockCount }} Lines</span>
            </div>
            <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 text-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Capital Valuation</span>
                <span class="text-xl font-bold text-gray-900 tracking-tight">TZS 0.00</span>
            </div>
            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 text-xs">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs mb-6">
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
                    <option value="water">water</option>
                    <option value="containers">Containers</option>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs py-1.5 px-4 rounded-lg transition shadow-xs">
                Apply Filters
            </button>
        </form>
    </div>

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
                    <div><h2 class="text-xs font-bold uppercase tracking-wider text-gray-900">Product categories</h2><p class="mt-1 text-[10px] text-gray-400">Classification groups used by goods.</p></div>
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

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider border-b border-gray-200/80 text-[10px]">
                        <th class="p-4 w-32">SKU Code</th>
                        <th class="p-4">Material Part Description</th>
                        <th class="p-4">Classification Group</th>
                        <th class="p-4 text-right">Unit Price</th>
                        <th class="p-4 text-center w-32">Available Stock</th>
                        <th class="p-4 text-center w-32">Safety Minimum</th>
                        <th class="p-4 text-right w-40">Status Matrix</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600 font-medium">
                    @foreach ($inventory as $item)
                        <tr class="hover:bg-gray-50/40 transition-colors">
                        <td class="p-4 font-mono font-bold text-gray-900">{{ $item->sku }}</td>
                        <td class="p-4 text-gray-900 font-semibold">{{ $item->description }}</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 border border-gray-200 bg-gray-50 text-gray-600 rounded text-[10px]">{{ $item->category }}</span>
                        </td>
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
                    @endforeach
                    
                     
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Initial Stock Count</label>
                        <input type="number" name="stock_count" value="0" min="0" required class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all font-mono">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Category</label>
                            <select name="category_id" class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800">
                                <option value="">Legacy category text</option>
                                @foreach ($inventoryCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->code }})</option>
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

                <div>
                    <label class="text-gray-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Classification Group</label>
                    <select name="category" required class="w-full bg-gray-50/50 border border-gray-200 rounded-lg p-2 text-gray-800 outline-hidden focus:bg-white focus:border-gray-400 transition-all">
                        <option value="water">water</option>
                        <option value="containers">Containers</option>
                    </select>
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
<script>
    //this script help ot generate sku. code
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