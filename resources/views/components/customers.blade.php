<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ openCustomerModal: false }">
    
    <!-- PAGE HEADER AREA -->
    <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-xs">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Customer Directory') }}</h1>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        {{ $customers->count() }} {{ __('customers') }}
                    </span>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">{{ __('Manage your customer directory, contacts, and delivery addresses.') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleModal('customer-modal', true)"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                <i class="fa-solid fa-user-plus text-slate-300"></i>
                <span>{{ __('Add Customer') }}</span>
            </button>
        </div>
    </div>

    <!-- 📊 CONTACT INSIGHT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Card 1: Total Customers -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Total Customers') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $customers->count() }}</span>
                    <span class="text-xs text-slate-400 font-medium">{{ __('active profiles') }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-address-book"></i>
            </div>
        </div>

        <!-- Card 2: Industrial Categorization -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Industry Verticals') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $customers->count() }}</span>
                    <span class="text-xs text-slate-400 font-medium">{{ __('sectors mapped') }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-industry"></i>
            </div>
        </div>

        <!-- Card 3: Contact Verification -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">{{ __('Contact Verification') }}</span>
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $customers->count() }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ __('All Verified') }}</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- 📋 THE DETAILS LEDGER -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ __('Customer Directory') }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('All customer accounts associated with this workspace.') }}</p>
            </div>
            <!-- Search bar -->
            <div class="relative max-w-xs w-full">
                <input type="text" placeholder="{{ __('Search contacts, phone numbers, addresses...') }}" 
                       class="w-full bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-3 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 text-[10px]">
                        <th class="px-6 py-3.5">{{ __('Company Entity') }}</th>
                        <th class="px-6 py-3.5">{{ __('Primary Contact Person') }}</th>
                        <th class="px-6 py-3.5">{{ __('Direct Contact Info') }}</th>
                        <th class="px-6 py-3.5">{{ __('Primary Delivery Address') }}</th>
                        <th class="px-6 py-3.5">{{ __('Tax / Business ID') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                    @forelse ($customers as $cachedContents)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-slate-900 block font-semibold">{{ $cachedContents->company_name }}</span>
                                <span class="text-[10px] text-slate-400 block font-normal mt-0.5">{{ __('Registered:') }} {{ $cachedContents->created_at?->format('Y-m-d') ?? now()->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-900 block font-medium">{{ $cachedContents->contact_person }}</span>
                                <span class="text-[10px] text-slate-400 block font-normal mt-0.5">{{ $cachedContents->contact_role }}</span>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <span class="text-slate-700 block font-mono text-xs"><i class="fa-regular fa-envelope text-slate-400 mr-1.5"></i>{{ $cachedContents->email }}</span>
                                <span class="text-slate-500 block font-mono text-xs"><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i>{{ $cachedContents->phone }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-[220px] truncate text-slate-700">
                                {{ $cachedContents->address }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $cachedContents->tax_id ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-xs text-slate-400">
                                <i class="fa-solid fa-address-book text-slate-300 text-2xl block mb-2"></i>
                                {{ __('No customers found for the current company.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📥 CREATE CUSTOMER PROFILE POP-UP MODAL -->
    <div id="customer-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Dim Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" onclick="toggleModal('customer-modal', false)"></div>

        <!-- Modal Content Box -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative z-10 transform transition-all p-6 sm:p-7">
            
            <!-- Modal Header -->
            <div class="pb-4 border-b border-slate-100 flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Add Customer') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Create a customer profile with contact information.') }}</p>
                </div>
                <button type="button" onclick="toggleModal('customer-modal', false)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Form Content -->
            <form action="/customers" method="POST" class="space-y-4 text-xs font-medium">
                @csrf

                <!-- Company Field -->
                <div>
                    <label for="company_name" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Company Legal Name') }}</label>
                    <input type="text" name="name" id="company_name" required placeholder="{{ __('e.g. Apex Mining Solutions') }}" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                </div>

                <!-- Contact Agent Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="contact_name" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Primary Contact Person') }}</label>
                        <input type="text" name="contact_name" id="contact_name" required placeholder="Marcus Vance" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                    <div>
                        <label for="contact_role" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Agent Job Title') }}</label>
                        <input type="text" name="contact_role" id="contact_role" placeholder="{{ __('Procurement Director') }}" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                </div>

                <!-- Contact Info Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="email" required placeholder="name@company.com" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                    <div>
                        <label for="phone" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Phone Number') }}</label>
                        <input type="tel" name="phone" id="phone" placeholder="+255 689 254 189" 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                    </div>
                </div>

                <!-- Address & Tax Info -->
                <div>
                    <label for="delivery_address" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Primary Delivery Address') }}</label>
                    <input type="text" name="address" id="delivery_address" placeholder="{{ __('Warehouse 4B, Sector North') }}" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition">
                </div>

                <div>
                    <label for="tax_id" class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">{{ __('Tax / Business Registration ID') }}</label>
                    <input type="text" name="tax_id" id="tax_id" placeholder="992018442" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-2.5 text-slate-900 outline-none focus:bg-white focus:border-slate-400 transition font-mono">
                </div>

                <!-- Actions Footer -->
                <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3 mt-4">
                    <button type="button" onclick="toggleModal('customer-modal', false)" 
                            class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-semibold hover:bg-slate-50 transition">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" 
                            class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-xl font-semibold transition shadow-xs">
                        {{ __('Save Customer') }}
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
</script>
