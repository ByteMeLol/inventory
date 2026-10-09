<x-layout>
    <x-slot:title>Purchases</x-slot:title>
    <x-success_alert />

    <div class="mx-auto max-w-5xl px-4 py-8" x-data="{ purchaseOpen: false, supplierOpen: false }">
        <div class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Procurement workspace</p>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Purchases & suppliers</h1>
                <p class="mt-1.5 text-xs text-slate-500">Manage supplier relationships, record buying costs, and receive goods into stock.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="supplierOpen = true" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i class="fa-solid fa-user-plus text-slate-400"></i>
                    Add supplier
                </button>
                <button type="button" @click="purchaseOpen = true" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-700">
                    <i class="fa-solid fa-plus"></i>
                    Record purchase
                </button>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active suppliers</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $suppliers->count() }}</p>
                <p class="mt-1 text-xs text-slate-500">Available for procurement</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Purchase records</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $purchases->total() }}</p>
                <p class="mt-1 text-xs text-slate-500">Current company scope</p>
            </div>
            <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Payment follow-up</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $purchases->whereIn('payment_status', ['unpaid', 'partial'])->count() }}</p>
                <p class="mt-1 text-xs text-slate-500">Unpaid or partially paid</p>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-5">
            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-5 lg:col-span-2">
                <div class="flex gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5 text-blue-500"></i>
                    <div>
                        <p class="text-xs font-bold text-blue-900">How purchasing works</p>
                        <p class="mt-1 text-xs leading-relaxed text-blue-800">Create a supplier, record the purchase, choose the receiving location, and goods are added to inventory. The purchase is also posted to Finance automatically.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:col-span-3">
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <div><h2 class="text-sm font-bold text-slate-900">Supplier directory</h2><p class="mt-1 text-[11px] text-slate-500">Suppliers available for purchase orders.</p></div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-500">{{ $suppliers->count() }} active</span>
                        </div>
                        <div class="max-h-56 space-y-2 overflow-y-auto">
                            @forelse($suppliers as $supplier)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2.5">
                                    <div><p class="text-xs font-semibold text-slate-800">{{ $supplier->name }}</p><p class="mt-0.5 text-[10px] text-slate-400">{{ $supplier->contact_name ?: 'No contact person' }} · {{ $supplier->payment_terms }}</p></div>
                                    <span class="text-slate-300"><i class="fa-solid fa-building"></i></span>
                                </div>
                            @empty
                                <p class="rounded-lg border border-dashed border-slate-200 p-4 text-center text-xs text-slate-400">No suppliers yet. Use “Add supplier”.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <div><h2 class="text-sm font-bold text-slate-900">Purchasing controls</h2><p class="mt-1 text-[11px] text-slate-500">Every purchase updates stock and Finance.</p></div>
                            <i class="fa-solid fa-shield-check text-slate-300"></i>
                        </div>
                        <div class="space-y-2 text-xs text-slate-600">
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2"><i class="fa-solid fa-check text-emerald-500"></i> Supplier is required on every purchase</div>
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2"><i class="fa-solid fa-check text-emerald-500"></i> Buying price is stored per purchase line</div>
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2"><i class="fa-solid fa-check text-emerald-500"></i> Receiving location is recorded</div>
                        </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/70 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Purchase register</h2>
                    <p class="mt-1 text-[11px] text-slate-500">Supplier purchases and their payment status.</p>
                </div>
                <div class="rounded-full border border-slate-200 bg-white px-3 py-1 text-[10px] font-semibold text-slate-500">
                    {{ $purchases->total() }} total records
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-xs">
                    <thead class="border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Purchase number</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Purchase date</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3">Payment status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($purchases as $purchase)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-4 py-4 font-mono font-semibold text-slate-900">{{ $purchase->purchase_number }}</td>
                                <td class="px-4 py-4"><span class="font-semibold text-slate-800">{{ $purchase->supplier->name }}</span><span class="mt-0.5 block text-[10px] text-slate-400">{{ $purchase->items->count() }} item line(s)</span></td>
                                <td class="px-4 py-4 text-slate-500">{{ $purchase->purchase_date->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-right font-mono font-bold text-slate-900">TZS {{ number_format($purchase->total_amount, 2) }}</td>
                                <td class="px-4 py-4"><span class="{{ $purchase->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} rounded-full px-2.5 py-1 text-[10px] font-semibold capitalize">{{ $purchase->payment_status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-12 text-center"><i class="fa-solid fa-truck-ramp-box mb-3 text-2xl text-slate-300"></i><p class="text-sm font-semibold text-slate-700">No purchases recorded</p><p class="mt-1 text-xs text-slate-400">Record your first supplier purchase to start receiving stock.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $purchases->links() }}</div>
            @endif
        </div>

        <div x-show="supplierOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" @keydown.escape.window="supplierOpen = false">
            <div class="rounded-2xl bg-white p-6 shadow-2xl" style="width: min(34rem, calc(100vw - 2rem));" @click.outside="supplierOpen = false">
                <div class="mb-5 flex items-start justify-between"><div><h2 class="text-base font-bold text-slate-900">Add supplier</h2><p class="mt-1 text-xs text-slate-500">Create a supplier profile for purchasing and payables.</p></div><button type="button" @click="supplierOpen = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button></div>
                <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-4">@csrf
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Supplier name</label><input name="name" required placeholder="e.g. Industrial Supplies Ltd" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Contact person</label><input name="contact_name" placeholder="Full name" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Phone</label><input name="phone" placeholder="+255..." class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Email</label><input name="email" type="email" placeholder="supplier@example.com" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Payment terms</label><select name="payment_terms" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="cash">Cash</option><option value="net_7">Net 7</option><option value="net_30">Net 30</option><option value="net_60">Net 60</option></select></div></div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Address <span class="font-normal text-slate-400">(optional)</span></label><textarea name="address" rows="2" placeholder="Supplier address" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></textarea></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" @click="supplierOpen = false" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white">Save supplier</button></div>
                </form>
            </div>
        </div>

        <div x-show="purchaseOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" @keydown.escape.window="purchaseOpen = false">
            <div class="max-h-[92vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl" style="width: min(42rem, calc(100vw - 2rem));" @click.outside="purchaseOpen = false">
                <div class="mb-5 flex items-start justify-between"><div><h2 class="text-base font-bold text-slate-900">Record purchase</h2><p class="mt-1 text-xs text-slate-500">Receive one purchase line into a warehouse location.</p></div><button type="button" @click="purchaseOpen = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button></div>
                <form method="POST" action="{{ route('purchases.store') }}" class="space-y-4">@csrf
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Supplier</label><select name="supplier_id" required class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="">Select supplier</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}">{{ $supplier->name }}</option>@endforeach</select></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Purchase number</label><input name="purchase_number" required placeholder="PO-2026-001" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div></div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Purchase date</label><input type="date" name="purchase_date" required value="{{ now()->toDateString() }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Receiving location</label><select name="warehouse_location_id" required class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="">Select location</option>@foreach($locations as $location)<option value="{{ $location->id }}">{{ $location->warehouse->code }} / {{ $location->code }}</option>@endforeach</select></div></div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4"><p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Purchase line</p><div class="grid grid-cols-1 gap-3 sm:grid-cols-3"><div class="sm:col-span-3"><label class="mb-1.5 block text-xs font-semibold text-slate-700">Good</label><select name="items[0][inventory_id]" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="">Select good</option>@foreach($inventoryItems as $item)<option value="{{ $item->id }}">{{ $item->sku }} — {{ $item->description }}</option>@endforeach</select></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Quantity</label><input type="number" step="0.000001" min="0.000001" name="items[0][quantity]" required placeholder="0" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Unit buying price</label><input type="number" step="0.000001" min="0" name="items[0][unit_cost]" required placeholder="0.00" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-slate-500"></div><div><label class="mb-1.5 block text-xs font-semibold text-slate-700">Payment status</label><select name="payment_status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-slate-500"><option value="unpaid">Unpaid</option><option value="paid">Paid</option><option value="partial">Partial</option></select></div></div></div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4"><button type="button" @click="purchaseOpen = false" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button><button class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white">Save purchase</button></div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
