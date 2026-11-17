<x-layout>
    <x-slot:title>{{ __('Purchases') }}</x-slot:title>
    <x-success_alert />

    <div class="mx-auto max-w-7xl px-4 py-8" x-data="{ purchaseOpen: false, supplierOpen: false }">
        <!-- Header -->
        <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 border border-violet-100 shadow-xs">
                    <i class="fa-solid fa-truck-ramp-box text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Purchases & suppliers') }}</h1>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                            {{ $purchases->total() }} {{ __('records') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Manage supplier relationships, record buying costs, and receive goods into stock.') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" @click="supplierOpen = true" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                    <i class="fa-solid fa-user-plus text-slate-400"></i>
                    <span>{{ __('Add supplier') }}</span>
                </button>
                <button type="button" @click="purchaseOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                    <i class="fa-solid fa-plus text-slate-300"></i>
                    <span>{{ __('Record purchase') }}</span>
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Active suppliers') }}</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $suppliers->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Available for procurement') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Purchase records') }}</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $purchases->total() }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Current company scope') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Payment follow-up') }}</p>
                    <p class="mt-2 text-2xl font-bold {{ $purchases->whereIn('payment_status', ['unpaid', 'partial'])->count() > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $purchases->whereIn('payment_status', ['unpaid', 'partial'])->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Unpaid or partially paid') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-6 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h3 class="text-sm font-bold text-blue-900">{{ __('How purchasing works') }}</h3>
                    </div>
                    <p class="text-xs leading-relaxed text-blue-800 mt-2">{{ __('Create a supplier, record the purchase, choose the receiving location, and goods are added to inventory. The purchase is also posted to Finance automatically.') }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-blue-100/80 text-[11px] text-blue-700">
                    <i class="fa-solid fa-check-double mr-1"></i> {{ __('Automatic stock increment & ledger sync') }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:col-span-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
                    <div class="mb-4 flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">{{ __('Supplier directory') }}</h2>
                            <p class="mt-0.5 text-xs text-slate-500">{{ __('Active procurement vendors') }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ $suppliers->count() }} {{ __('active') }}</span>
                    </div>
                    <div class="max-h-56 space-y-2.5 overflow-y-auto">
                        @forelse($suppliers as $supplier)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-3.5 py-2.5">
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $supplier->name }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400">{{ $supplier->contact_name ?: __('No contact person') }} · {{ __($supplier->payment_terms) }}</p>
                                </div>
                                <span class="text-slate-300"><i class="fa-solid fa-building text-xs"></i></span>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">{{ __('No suppliers yet. Use “Add supplier”.') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="mb-4 flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">{{ __('Purchasing controls') }}</h2>
                                <p class="mt-0.5 text-xs text-slate-500">{{ __('Compliance & verification') }}</p>
                            </div>
                            <i class="fa-solid fa-shield-check text-slate-300"></i>
                        </div>
                        <div class="space-y-2 text-xs text-slate-600">
                            <div class="flex items-center gap-2 rounded-xl bg-slate-50 p-2.5"><i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i> <span>{{ __('Supplier is required on every purchase') }}</span></div>
                            <div class="flex items-center gap-2 rounded-xl bg-slate-50 p-2.5"><i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i> <span>{{ __('Buying price is stored per purchase line') }}</span></div>
                            <div class="flex items-center gap-2 rounded-xl bg-slate-50 p-2.5"><i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i> <span>{{ __('Receiving location is recorded') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/50 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Purchase register') }}</h2>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('Supplier purchases and their payment status.') }}</p>
                </div>
                <div class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ $purchases->total() }} {{ __('total records') }}
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-xs">
                    <thead class="border-b border-slate-100 bg-slate-50/75 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-6 py-3.5">{{ __('Purchase number') }}</th>
                            <th class="px-6 py-3.5">{{ __('Supplier') }}</th>
                            <th class="px-6 py-3.5">{{ __('Purchase date') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Total') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Payment status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($purchases as $purchase)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $purchase->purchase_number }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $purchase->supplier->name }}</span>
                                    <span class="mt-0.5 block text-[10px] text-slate-400">{{ $purchase->items->count() }} {{ __('item line(s)') }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $purchase->purchase_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-slate-900">TZS {{ number_format($purchase->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="{{ $purchase->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold capitalize">
                                        {{ __($purchase->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fa-solid fa-truck-ramp-box mb-2 text-2xl text-slate-300 block"></i>
                                    <p class="text-sm font-semibold text-slate-700">{{ __('No purchases recorded') }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ __('Record your first supplier purchase to start receiving stock.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
                <div class="border-t border-slate-100 p-4 bg-slate-50/50">{{ $purchases->links() }}</div>
            @endif
        </div>

        <!-- Add Supplier Modal -->
        <div x-show="supplierOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" @keydown.escape.window="supplierOpen = false">
            <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-100" style="width: min(34rem, calc(100vw - 2rem));" @click.outside="supplierOpen = false">
                <div class="mb-5 flex items-start justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Add supplier') }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Create a supplier profile for purchasing and payables.') }}</p>
                    </div>
                    <button type="button" @click="supplierOpen = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-4 text-xs font-medium">
                    @csrf
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Supplier name') }}</label>
                        <input name="name" required placeholder="{{ __('e.g. Industrial Supplies Ltd') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Contact person') }}</label>
                            <input name="contact_name" placeholder="{{ __('Full name') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Phone') }}</label>
                            <input name="phone" placeholder="+255..." class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Email') }}</label>
                            <input name="email" type="email" placeholder="supplier@example.com" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Payment terms') }}</label>
                            <select name="payment_terms" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="net_7">Net 7</option>
                                <option value="net_30">Net 30</option>
                                <option value="net_60">Net 60</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Address') }}</label>
                        <textarea name="address" rows="2" placeholder="{{ __('Supplier address') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition resize-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 mt-2">
                        <button type="button" @click="supplierOpen = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50 transition">{{ __('Cancel') }}</button>
                        <button class="rounded-xl bg-slate-900 px-5 py-2 font-semibold text-white hover:bg-slate-800 transition shadow-xs">{{ __('Save supplier') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Record Purchase Modal -->
        <div x-show="purchaseOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" @keydown.escape.window="purchaseOpen = false">
            <div class="max-h-[92vh] overflow-y-auto rounded-2xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-100" style="width: min(44rem, calc(100vw - 2rem));" @click.outside="purchaseOpen = false">
                <div class="mb-5 flex items-start justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Record purchase') }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Receive items into a warehouse location and record payable.') }}</p>
                    </div>
                    <button type="button" @click="purchaseOpen = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('purchases.store') }}" class="space-y-4 text-xs font-medium">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Supplier') }}</label>
                            <select name="supplier_id" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                                <option value="">{{ __('Select supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Purchase number') }}</label>
                            <input name="purchase_number" required placeholder="PO-2026-001" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Purchase date') }}</label>
                            <input type="date" name="purchase_date" required value="{{ now()->toDateString() }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                        </div>
                        <div>
                            <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Receiving location') }}</label>
                            <select name="warehouse_location_id" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:bg-white focus:border-slate-400 transition">
                                <option value="">{{ __('Select location') }}</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->warehouse->code }} / {{ $location->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Purchase line') }}</p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="sm:col-span-3">
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Good') }}</label>
                                <select name="items[0][inventory_id]" required class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:border-slate-400 transition">
                                    <option value="">{{ __('Select good') }}</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->sku }} — {{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Quantity') }}</label>
                                <input type="number" step="0.000001" min="0.000001" name="items[0][quantity]" required placeholder="0" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:border-slate-400 transition font-mono">
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Unit buying price') }}</label>
                                <input type="number" step="0.000001" min="0" name="items[0][unit_cost]" required placeholder="0.00" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:border-slate-400 transition font-mono">
                            </div>
                            <div>
                                <label class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Payment status') }}</label>
                                <select name="payment_status" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 outline-none focus:border-slate-400 transition">
                                    <option value="unpaid">{{ __('Unpaid') }}</option>
                                    <option value="paid">{{ __('Paid') }}</option>
                                    <option value="partial">{{ __('Partial') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 mt-2">
                        <button type="button" @click="purchaseOpen = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50 transition">{{ __('Cancel') }}</button>
                        <button class="rounded-xl bg-slate-900 px-5 py-2 font-semibold text-white hover:bg-slate-800 transition shadow-xs">{{ __('Save purchase') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
