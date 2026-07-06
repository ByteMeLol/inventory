
    <div class="max-w-5xl mx-auto px-4 py-8" x-data="{ openSalesModal: false }">
        
        <!-- PAGE HEADER AREA -->
        <div class="mb-8 pb-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Sales Orders Ledger</h1>
                <p class="text-xs text-slate-500 mt-1.5">Manage invoices, counter transactions, and industrial supply deployments.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button class="bg-white border border-slate-200 text-slate-600 px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-filter mr-1.5 text-slate-400"></i> Filter
                </button>
                <!-- FIXED MODAL TRIGGER -->
                <button type="button" onclick="toggleModal('sales-modal', true)"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition shadow-sm shadow-slate-900/10">
                    <i class="fa-solid fa-plus mr-1.5 text-slate-300"></i> New Sales Order
                </button>
            </div>
        </div>

        <!-- 📊 UNIFORM SCOPED METRICS CONTAINER -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Open Invoices -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pending Orders</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-3xl font-bold text-slate-900 tracking-tight">{{$sales->where('status','pending')->count()}}</span>
                        <span class="text-xs font-medium text-slate-400">awaiting fulfillment</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- Card 2: Scoped Month Gross Revenue -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Monthly Gross Volume</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{number_format($paidsales,2)}}</span>
                        <span class="text-xs font-medium text-slate-400">TZS</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- Card 3: Completed Dispatches -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all duration-200">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Completed Dispatches</span>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-3xl font-bold text-slate-900 tracking-tight">{{$sales->where('status','paid')->count()}}</span>
                        <span class="text-xs font-medium text-slate-400">done</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg border border-slate-200/60 text-slate-400 flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-truck-flatbed"></i>
                </div>
            </div>
        </div>

        <!-- 📋 INTERACTIVE SALES TABLE -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Active Workspace Orders</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Showing accounts linked explicitly to your selected tenant company profile.</p>
                </div>
                <!-- Search bar -->
                <div class="relative max-w-xs w-full">
                    <input type="text" placeholder="Search invoices or SKUs..." 
                           class="w-full bg-white border border-slate-200 rounded-lg py-1.5 pl-8 pr-3 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-slate-400 transition-colors shadow-inner">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-[10px] text-slate-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4">Invoice ID</th>
                            <th class="p-4">Client / Industry Target</th>
                            <th class="p-4">Items Summary</th>
                            <th class="p-4 text-center">Total Value</th>
                            <th class="p-4">Fulfillment Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        <!-- Row 1 -->
                        @foreach ($sales as $sale)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-900">{{ $sale->invoice_number }}</td>
                                <td class="p-4">
                                    <span class="text-slate-900 block font-bold">{{ $sale->customer->company_name }}</span>
                                    <span class="text-[10px] text-slate-400 block font-normal">Terms: {{ $sale->billing_terms }}</span>
                                </td>
                                    <td class="p-4 text-slate-500 max-w-[240px] truncate">
                                        {{ $sale->item_summary }}
                                        @if($sale->inventory)
                                            <div class="text-[10px] text-slate-400 mt-0.5">SKU: {{ $sale->inventory->sku }} | Qty: {{ $sale->quantity }}</div>
                                        @endif
                                    </td>
                                <td class="p-4 text-center text-slate-900 font-mono font-bold">TZS {{ number_format($sale->total_amount, 2) }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold  {{$sale->status=='pending'? "text-red-500": "text-green-500"}} ">{{ $sale->status }}</span>
                                </td>
                                <td class="p-4 text-right space-x-1 flex items-center justify-end flex-row">
                                    <button class="text-slate-400 hover:text-slate-900 p-1 transition-colors" title="View Details"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <a href="{{ route('sales.invoice.download', $sale) }}" class="text-slate-400 hover:text-slate-900 p-1 transition-colors" title="Download Invoice PDF">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    <button class="text-slate-400 hover:text-slate-900 p-1 transition-colors" title="Delete Record"><i class="fa-solid fa-trash text-xs"></i></button>
                                    <form action="/sales/{{ $sale->id }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button id="paidcheck" type="submit" class="text-slate-400 hover:text-slate-900 p-1 transition-colors" title="Mark as Paid">
                                            Paid <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        
                        
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Control Component -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                <span>Showing 2 of 2 recorded entries</span>
                <div class="flex items-center space-x-1">
                    <button class="px-2.5 py-1 border border-slate-200 rounded bg-white hover:bg-slate-50 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-2.5 py-1 border border-slate-200 rounded bg-white hover:bg-slate-50 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- 📥 NEW SALES ORDER POP-UP MODAL OVERLAY -->
        <div id="sales-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Dim Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('sales-modal', false)"></div>

            <!-- Modal Window Box -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xl w-full max-w-md overflow-hidden relative z-10 transform transition-all">
                
                <!-- Modal Header -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Draft New Sales Order</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Initialize transaction logging parameters and customer binding lines.</p>
                    </div>
                    <button type="button" onclick="toggleModal('sales-modal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form action="/sales" method="POST" class="p-5 space-y-4">
                    @csrf

                    <!-- Select Customer -->
                    <div class="space-y-1">
                        <label for="customer_id" class="block text-[11px] font-semibold text-slate-700">Target Customer Account</label>
                        <div class="relative">
                            <select name="customer_id" id="customer_id" required 
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 pl-2.5 pr-8 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-900 appearance-none cursor-pointer transition-all">
                                <option value="">Select a managed corporate client...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                @endforeach
                                
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="inventory_id" class="block text-[11px] font-semibold text-slate-700">Inventory Item</label>
                        <div class="relative">
                            <select name="inventory_id" id="inventory_id" required
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 pl-2.5 pr-8 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-900 appearance-none cursor-pointer transition-all">
                                <option value="">Select an inventory item...</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->unit_price ?? 0 }}" data-description="{{ $item->description }}" data-stock="{{ $item->stock_count }}">
                                        {{ $item->sku }} - {{ $item->description }} ({{ $item->stock_count }} in stock)
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="quantity" class="block text-[11px] font-semibold text-slate-700">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="1" required class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono">
                        </div>
                        <div class="space-y-1">
                            <label for="unit_price" class="block text-[11px] font-semibold text-slate-700">Unit Price</label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" required class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono">
                        </div>
                    </div>
                    
                    <!-- Invoice metadata parameters -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="invoice_number" class="block text-[11px] font-semibold text-slate-700">Invoice Reference ID</label>
                            <input type="text" name="invoice_number" id="invoice_number" required placeholder="#INV-2026-XXX" 
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono" readonly>
                        </div>
                        <div class="space-y-1">
                            <label for="billing_terms" class="block text-[11px] font-semibold text-slate-700">Billing Terms Scope</label>
                            <div class="relative">
                                <select name="billing_terms" id="billing_terms" required 
                                        class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 pl-2.5 pr-8 text-xs text-slate-800 outline-none focus:bg-white focus:border-slate-900 appearance-none cursor-pointer transition-all">
                                    <option value="net_30">Net 30 Allocation</option>
                                    <option value="cash">Cash Collection</option>
                                    <option value="immediate">Immediate POS</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Summary Log -->
                    <div class="space-y-1">
                        <label for="items_summary" class="block text-[11px] font-semibold text-slate-700">Items Manifest Summary</label>
                        <textarea name="items_summary" id="items_summary" required rows="2" placeholder="e.g. Flange Bolts (x50), Hydraulic Pump Seal Kit (x4)"
                                  class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all resize-none"></textarea>
                    </div>

                    <!-- Financial Total Field -->
                    <div class="space-y-1">
                        <label for="total_amount" class="block text-[11px] font-semibold text-slate-700">Gross Transaction Value ($)</label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" required placeholder="0.00" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-900 outline-none focus:bg-white focus:border-slate-900 transition-all font-mono">
                    </div>

                    <!-- Form Controls Footer -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                        <button type="button" onclick="toggleModal('sales-modal', false)" 
                                class="bg-white border border-slate-200 text-slate-600 px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all shadow-sm">
                            Commit Sales Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
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
        const paidButton = document.getElementById('paidcheck');
            if(paidButton){
                paidButton.addEventListener('click', function(event){
                    event.preventDefault(); // Prevent the default form submission
                    Swal.fire({
                        title: 'Mark as Paid?',
                        text: "Are you sure you want to mark this order as paid?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, mark as paid!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form
                            paidButton.closest('form').submit();
                        }
                    })
                })
            }

        //invoice number auto-generation
        document.addEventListener('DOMContentLoaded', function() {

            const invoiceInput = document.getElementById('invoice_number');
            const inventorySelect = document.getElementById('inventory_id');
            const unitPriceInput = document.getElementById('unit_price');
            const quantityInput = document.getElementById('quantity');
            const totalAmountInput = document.getElementById('total_amount');
            const itemsSummaryInput = document.getElementById('items_summary');
            const paidButton = document.getElementById('paid');
            
            function updateAmounts() {
                const selectedOption = inventorySelect?.selectedOptions?.[0];
                const unitPrice = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
                const quantity = Number(quantityInput?.value || 0);
                const total = unitPrice * quantity;

                if (unitPriceInput && unitPrice > 0) {
                    unitPriceInput.value = unitPrice.toFixed(2);
                }

                if (totalAmountInput) {
                    totalAmountInput.value = total.toFixed(2);
                }

                if (itemsSummaryInput && selectedOption) {
                    itemsSummaryInput.value = `${selectedOption.dataset.description} x ${quantity}`;
                }
            }

            if (invoiceInput) {
                const randomNum = Math.floor(Math.random() * 900) + 100; // generates a random number between 100 and 999
                invoiceInput.value = `INV-2026-${randomNum}`;
            }

            if (inventorySelect) {
                inventorySelect.addEventListener('change', updateAmounts);
            }

            if (quantityInput) {
                quantityInput.addEventListener('input', updateAmounts);
            }

            updateAmounts();
        });
        
    </script>

