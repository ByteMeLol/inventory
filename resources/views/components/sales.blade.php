<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ openSalesModal: false }">
    
    <!-- PAGE HEADER AREA -->
    <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-cash-register text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Sales Orders') }}</h1>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $sales->count() }} {{ __('orders') }}
                    </span>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Manage customer invoices, counter orders, and fulfillment.') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleModal('sales-modal', true)"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                <i class="fa-solid fa-plus text-slate-300"></i>
                <span>{{ __('New Sales Order') }}</span>
            </button>
        </div>
    </div>

    <!-- 📊 UNIFORM SCOPED METRICS CONTAINER -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Card 1: Open Invoices -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Pending Orders') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $sales->where('status','pending')->count() }}</span>
                    <span class="text-xs text-slate-400 font-medium">{{ __('awaiting fulfillment') }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <!-- Card 2: Scoped Month Gross Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Monthly Gross Volume') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-xl font-bold text-slate-900 tracking-tight">TZS {{ number_format($paidsales,2) }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- Card 3: Completed Dispatches -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Completed Dispatches') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $sales->where('status','paid')->count() }}</span>
                    <span class="text-xs text-slate-400 font-medium">{{ __('completed') }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- 📋 INTERACTIVE SALES TABLE -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ __('Sales Register') }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('All sales orders recorded for the active workspace.') }}</p>
            </div>
            <!-- Search bar -->
            <div class="relative max-w-xs w-full">
                <input type="text" placeholder="{{ __('Search invoices or SKUs...') }}" 
                       class="w-full bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-3 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                        <th class="px-6 py-3.5">{{ __('Invoice ID') }}</th>
                        <th class="px-6 py-3.5">{{ __('Customer') }}</th>
                        <th class="px-6 py-3.5">{{ __('Items Summary') }}</th>
                        <th class="px-6 py-3.5 text-center">{{ __('Total Value') }}</th>
                        <th class="px-6 py-3.5">{{ __('Fulfillment Status') }}</th>
                        <th class="px-6 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $sale->invoice_number }}</td>
                            <td class="px-6 py-4">
                                <span class="text-slate-900 block font-semibold">{{ $sale->customer->company_name ?? __('Walk-in Customer') }}</span>
                                <span class="text-[10px] text-slate-400 block font-normal mt-0.5">{{ __('Terms:') }} {{ __($sale->billing_terms) }} · {{ __('Payment:') }} {{ $sale->payment_method ? __(str_replace('_', ' ', ucfirst($sale->payment_method))) : __('Not recorded') }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-[240px] truncate">
                                {{ $sale->item_summary }}
                                @if($sale->inventory)
                                    <div class="text-[10px] text-slate-400 mt-0.5 font-mono">{{ __('SKU:') }} {{ $sale->inventory->sku }} · {{ __('Qty:') }} {{ $sale->quantity }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="block text-slate-900 font-mono font-bold">TZS {{ number_format($sale->total_amount, 2) }}</span>
                                @if((float) $sale->discount_amount > 0)
                                    <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 px-2 py-0.5 text-[9px] font-semibold text-amber-700" title="{{ $sale->discount_reason }}">
                                        <i class="fa-solid fa-tag"></i> {{ __('Discount') }} TZS {{ number_format($sale->discount_amount, 2) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $sale->status == 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    {{ __($sale->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('sales.invoice.download', $sale) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="{{ __('Download Invoice PDF') }}">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    @if($sale->status != 'paid')
                                        <form action="/sales/{{ $sale->id }}" method="POST" class="inline mark-paid-form">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="mark-paid-btn inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition" title="{{ __('Mark as Paid') }}">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>{{ __('Paid') }}</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-xs text-slate-400">
                                <i class="fa-solid fa-receipt text-slate-300 text-2xl block mb-2"></i>
                                {{ __('No sales orders found for this company.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Control Component -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500 font-medium">
            <span>{{ __('Showing') }} {{ $sales->count() }} {{ __('records') }}</span>
        </div>
    </div>

    <!-- 📥 NEW SALES ORDER POP-UP MODAL OVERLAY -->
    <div id="sales-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Dim Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" onclick="toggleModal('sales-modal', false)"></div>

        <!-- Modal Window Box -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative z-10 transform transition-all p-6 sm:p-7">
            
            <!-- Modal Header -->
            <div class="pb-4 border-b border-slate-100 flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Draft New Sales Order') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Create a new invoice and fulfill items from stock.') }}</p>
                </div>
                <button type="button" onclick="toggleModal('sales-modal', false)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="/sales" method="POST" class="space-y-4 text-xs font-medium">
                @csrf

                <!-- Select Customer -->
                <div>
                    <label for="customer_id" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Customer') }}</label>
                    <select name="customer_id" id="customer_id" required 
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        <option value="">{{ __('Select customer...') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="inventory_id" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Inventory Item') }}</label>
                    <select name="inventory_id" id="inventory_id" required
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        <option value="">{{ __('Select an inventory item...') }}</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->unit_price ?? 0 }}" data-description="{{ $item->description }}" data-stock="{{ $item->stock_count }}">
                                {{ $item->sku }} - {{ $item->description }} ({{ $item->stock_count }} {{ __('in stock') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Quantity') }}</label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                    </div>
                    <div>
                        <label for="unit_price" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Unit Price') }}</label>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="discount_amount" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Discount amount') }}</label>
                        <input type="number" step="0.01" min="0" value="0" name="discount_amount" id="discount_amount" placeholder="0.00" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 font-mono">
                    </div>
                    <div>
                        <label for="discount_reason" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Discount reason') }}</label>
                        <input type="text" name="discount_reason" id="discount_reason" placeholder="{{ __('e.g. Approved customer discount') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="payment_method" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Payment Method') }}</label>
                        <select name="payment_method" id="payment_method" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400">
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="bank_transfer">{{ __('Bank transfer') }}</option>
                            <option value="card">{{ __('Card') }}</option>
                            <option value="mobile_money">{{ __('Mobile money') }}</option>
                            <option value="credit">{{ __('Credit / unpaid') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_reference" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Payment Reference') }}</label>
                        <input type="text" name="payment_reference" id="payment_reference" placeholder="{{ __('Receipt or transfer reference') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="invoice_number" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Invoice Reference ID') }}</label>
                        <input type="text" name="invoice_number" id="invoice_number" required placeholder="#INV-2026-XXX" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono" readonly>
                    </div>
                    <div>
                        <label for="billing_terms" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Payment Terms') }}</label>
                        <select name="billing_terms" id="billing_terms" required 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                            <option value="net_30">{{ __('Net 30') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="immediate">{{ __('Immediate POS') }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="items_summary" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Order Summary') }}</label>
                    <textarea name="items_summary" id="items_summary" required rows="2" placeholder="{{ __('e.g. Flange Bolts (x50)') }}"
                              class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition resize-none"></textarea>
                </div>

                <div>
                    <label for="total_amount" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Final amount after discount') }}</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" readonly required placeholder="0.00"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono font-bold">
                </div>

                <!-- Form Controls Footer -->
                <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3 mt-4">
                    <button type="button" onclick="toggleModal('sales-modal', false)" 
                            class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold hover:bg-slate-50 transition">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" 
                            class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-xl font-semibold transition shadow-xs">
                        {{ __('Create Sales Order') }}
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

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.mark-paid-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: '{{ __('Mark as Paid?') }}',
                    text: "{{ __('Are you sure you want to mark this order as paid?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '{{ __('Yes, mark as paid!') }}',
                    cancelButtonText: '{{ __('Cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // invoice number auto-generation
        const invoiceInput = document.getElementById('invoice_number');
        const inventorySelect = document.getElementById('inventory_id');
        const unitPriceInput = document.getElementById('unit_price');
        const quantityInput = document.getElementById('quantity');
        const totalAmountInput = document.getElementById('total_amount');
        const discountAmountInput = document.getElementById('discount_amount');
        const itemsSummaryInput = document.getElementById('items_summary');
        
        function updateAmounts() {
            const selectedOption = inventorySelect?.selectedOptions?.[0];
            const unitPrice = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
            const quantity = Number(quantityInput?.value || 0);
            const subtotal = unitPrice * quantity;
            const discount = Number(discountAmountInput?.value || 0);
            const total = Math.max(0, subtotal - discount);

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
            const randomNum = Math.floor(Math.random() * 900) + 100;
            invoiceInput.value = `INV-2026-${randomNum}`;
        }

        if (inventorySelect) {
            inventorySelect.addEventListener('change', updateAmounts);
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', updateAmounts);
        }
        if (discountAmountInput) {
            discountAmountInput.addEventListener('input', updateAmounts);
        }

        updateAmounts();
    });
</script>
