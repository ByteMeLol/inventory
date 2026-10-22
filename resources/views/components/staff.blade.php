<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="border border-[#c9d9cc] bg-[#edf2ee] px-6 py-7 text-[#1b3525]">
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider">
                    <span class="h-2 w-2 bg-[#3f9b58]"></span>
                    Staff administration
                </div>
                <div>
                    <h1 class="text-3xl font-semibold tracking-[-0.04em] sm:text-4xl">Manage staff accounts</h1>
                    <p class="mt-2 max-w-2xl text-sm text-[#6f7d73]">Create users, assign roles, and keep every account tied to the correct company scope.</p>
                </div>
            </div>

            <button type="button" onclick="toggleModal(true)"
                    class="inline-flex items-center justify-center border border-[#cbd8ce] bg-[#173b26] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#285b38]">
                <i class="fa-solid fa-user-plus mr-2 text-slate-500"></i>
                Add Staff
            </button>
        </div>

        <div class="relative mt-6 flex flex-wrap gap-2 text-[11px] font-semibold">
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">Companies: {{ $companies->count() }}</span>
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">Roles: {{ $roles->count() }}</span>
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">Accounts: {{ $staffMembers->total() }}</span>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        @foreach($companies->take(3) as $company)
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Company</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $company->name }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Selected from the companies table.</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Staff Roster</h3>
                <p class="mt-1 text-[11px] text-slate-500">People, company scope, and role assignments from the user table.</p>
            </div>
            <div class="text-[11px] font-medium text-slate-500">
                Showing {{ $staffMembers->count() }} of {{ $staffMembers->total() }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-3">Identity</th>
                        <th class="px-5 py-3">Company</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($staffMembers as $staffMember)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 font-bold uppercase text-slate-600">
                                        {{ strtoupper(substr($staffMember->first_name, 0, 1) . substr($staffMember->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $staffMember->first_name }} {{ $staffMember->last_name }}</p>
                                        <p class="mt-0.5 text-[11px] text-slate-400">{{ $staffMember->email }}</p>
                                        <p class="mt-0.5 text-[11px] text-slate-400">{{ $staffMember->phone ?? 'No phone stored' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $staffMember->company->name ?? 'Unassigned' }}</p>
                                <p class="mt-0.5 text-[11px] text-slate-400">Company ID: {{ $staffMember->company_id ?? 'N/A' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                    {{ str_replace('_', ' ', $staffMember->role?->name ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-400">No staff accounts have been created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $staffMembers->links() }}
    </div>

    <div id="onboard-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal(false)"></div>

        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Create Staff Account</h3>
                    <p class="mt-1 text-[11px] text-slate-500">New users will be saved in the users table with the selected company and role.</p>
                </div>
                <button type="button" onclick="toggleModal(false)" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('staff.store') }}" method="POST" class="space-y-5 px-5 py-5">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="first_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="first_name" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="last_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="last_name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="email" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">Phone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="phone" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="role_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">Role</label>
                        <select name="role_id" id="role_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected((string) old('role_id') === (string) $role->id)>{{ str_replace('_', ' ', $role->name) }}</option>
                            @endforeach
                        </select>
                        <x-error_handling name="role_id" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="company_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">Company</label>
                        <select name="company_id" id="company_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="">Select company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected((string) old('company_id') === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <x-error_handling name="company_id" />
                    </div>
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-[11px] text-amber-700">
                    Temporary password will be the staff last name in uppercase.
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="toggleModal(false)" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('onboard-modal');

        if (show) {
            modal.classList.remove('hidden');
            return;
        }

        modal.classList.add('hidden');
    }
</script>
