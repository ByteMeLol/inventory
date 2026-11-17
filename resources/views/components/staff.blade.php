<div class="max-w-7xl mx-auto px-4 py-8" x-data="{
    onboardModalOpen: false,
    editModalOpen: false,
    editStaff: { id: '', first_name: '', last_name: '', email: '', phone: '', role_id: '', company_id: '' },
    openEdit(staff) {
        this.editStaff = { ...staff };
        this.editModalOpen = true;
    }
}">
    <!-- HEADER AREA -->
    <div class="border border-[#c9d9cc] bg-[#edf2ee] px-6 py-7 text-[#1b3525]">
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider">
                    <span class="h-2 w-2 bg-[#3f9b58]"></span>
                    {{ __('Staff & Company Administration') }}
                </div>
                <div>
                    <h1 class="text-3xl font-semibold tracking-[-0.04em] sm:text-4xl">{{ __('Manage Staff & Companies') }}</h1>
                    <p class="mt-2 max-w-2xl text-sm text-[#6f7d73]">
                        {{ __('Track your staff, assign them across your companies, and manage your multi-company workspace independently.') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="addCompanyModalOpen = true"
                        class="inline-flex items-center justify-center border border-[#cbd8ce] bg-white px-4 py-2.5 text-sm font-semibold text-[#173b26] transition hover:bg-[#e6efe8]">
                    <i class="fa-solid fa-plus mr-2 text-[#3f9b58]"></i>
                    {{ __('Add Company') }}
                </button>
                <button type="button" @click="onboardModalOpen = true"
                        class="inline-flex items-center justify-center border border-[#cbd8ce] bg-[#173b26] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#285b38]">
                    <i class="fa-solid fa-user-plus mr-2 text-[#8cd6a0]"></i>
                    {{ __('Add Staff') }}
                </button>
            </div>
        </div>

        <div class="relative mt-6 flex flex-wrap gap-2 text-[11px] font-semibold">
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">{{ __('My Companies') }}: {{ $companies->count() }}</span>
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">{{ __('Roles') }}: {{ $roles->count() }}</span>
            <span class="border border-[#cbd8ce] bg-white px-3 py-1.5 text-[#52675a]">{{ __('My Staff Members') }}: {{ $staffMembers->total() }}</span>
        </div>
    </div>

    <!-- MY COMPANIES CARDS -->
    <div class="mt-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('My Companies') }} ({{ $companies->count() }})</h3>
            <button type="button" @click="addCompanyModalOpen = true" class="text-xs font-semibold text-[#1f5431] hover:underline flex items-center gap-1">
                <i class="fa-solid fa-plus-circle text-xs"></i> {{ __('New Company') }}
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($companies as $company)
                @php
                    $isCurrentActive = (int) session('company_id') === (int) $company->id;
                    $staffCountInCompany = \App\Models\User::where('company_id', $company->id)->where('creator_id', auth()->id())->count();
                @endphp
                <div class="rounded-2xl border {{ $isCurrentActive ? 'border-[#3f9b58] bg-[#f7fbf8] ring-1 ring-[#3f9b58]' : 'border-slate-200 bg-white' }} p-4 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider {{ $isCurrentActive ? 'text-[#1e4b2d]' : 'text-slate-400' }}">
                                <i class="fa-regular fa-building"></i>
                                {{ $isCurrentActive ? __('Active Workspace') : __('Company') }}
                            </span>
                            @if($isCurrentActive)
                                <span class="h-2 w-2 rounded-full bg-[#3f9b58]" title="{{ __('Active Workspace') }}"></span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm font-bold text-slate-900 truncate" title="{{ $company->name }}">{{ $company->name }}</p>
                        <p class="mt-1 text-[11px] text-slate-500">{{ $staffCountInCompany }} {{ __('assigned staff') }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <a href="/staff?company_id={{ $company->id }}" class="text-[11px] font-medium text-slate-600 hover:text-[#173b26]">
                            <i class="fa-solid fa-filter mr-1 text-[10px]"></i> {{ __('View Staff') }}
                        </a>
                        @if(! $isCurrentActive)
                            <a href="/companies/{{ $company->id }}" class="text-[11px] font-semibold text-[#173b26] hover:underline">
                                {{ __('Switch') }} <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-6 text-center">
                    <p class="text-sm font-medium text-slate-500">{{ __('You haven\'t created any companies yet.') }}</p>
                    <button type="button" @click="addCompanyModalOpen = true" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-[#173b26] hover:underline">
                        <i class="fa-solid fa-plus"></i> {{ __('Create your first company') }}
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- FILTER & ROSTER TABLE -->
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ __('Staff Roster') }}</h3>
                <p class="mt-1 text-[11px] text-slate-500">{{ __('Track and assign your staff members across your companies.') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="company_filter" class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">{{ __('Filter Company:') }}</label>
                    <select id="company_filter"
                            onchange="window.location.href = this.value"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="{{ route('staff.index') }}" @selected(empty($selectedCompanyId))>{{ __('All Companies') }} ({{ $companies->count() }})</option>
                        @foreach($companies as $company)
                            <option value="{{ route('staff.index', ['company_id' => $company->id]) }}" @selected((int) $selectedCompanyId === (int) $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-[11px] font-medium text-slate-500 whitespace-nowrap">
                    {{ __('Showing') }} {{ $staffMembers->count() }} {{ __('of') }} {{ $staffMembers->total() }}
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-3">{{ __('Identity') }}</th>
                        <th class="px-5 py-3">{{ __('Assigned Company') }}</th>
                        <th class="px-5 py-3">{{ __('Role') }}</th>
                        <th class="px-5 py-3">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-right">{{ __('Actions') }}</th>
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
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                    <i class="fa-regular fa-building text-[#52a768]"></i>
                                    {{ $staffMember->company->name ?? __('Unassigned') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                    {{ str_replace('_', ' ', $staffMember->role?->name ?? 'staff') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                                    {{ __('Active') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button"
                                            @click="openEdit({
                                                id: {{ $staffMember->id }},
                                                first_name: '{{ addslashes($staffMember->first_name) }}',
                                                last_name: '{{ addslashes($staffMember->last_name) }}',
                                                email: '{{ addslashes($staffMember->email) }}',
                                                phone: '{{ addslashes($staffMember->phone ?? '') }}',
                                                role_id: '{{ $staffMember->role_id }}',
                                                company_id: '{{ $staffMember->company_id }}'
                                            })"
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs"
                                            title="{{ __('Assign / Edit') }}">
                                        <i class="fa-solid fa-pen-to-square text-[10px] text-slate-500"></i>
                                        <span>{{ __('Assign / Edit') }}</span>
                                    </button>

                                    <form action="{{ route('staff.destroy', $staffMember->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff member?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-200 bg-white p-1.5 text-rose-600 hover:bg-rose-50 transition" title="{{ __('Remove') }}">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                {{ __('No staff accounts have been added yet under this scope. Click "Add Staff" to create accounts for your team.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $staffMembers->links() }}
    </div>

    <!-- CREATE STAFF MODAL -->
    <div x-show="onboardModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="onboardModalOpen = false"></div>

        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ __('Create Staff Account') }}</h3>
                    <p class="mt-1 text-[11px] text-slate-500">{{ __('Add a staff member and assign them to one of your companies.') }}</p>
                </div>
                <button type="button" @click="onboardModalOpen = false" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('staff.store') }}" method="POST" class="space-y-5 px-5 py-5">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="create_first_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" id="create_first_name" value="{{ old('first_name') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="first_name" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="create_last_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" id="create_last_name" value="{{ old('last_name') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="last_name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="create_email" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="create_email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="email" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="create_phone" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Phone') }}</label>
                        <input type="tel" name="phone" id="create_phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <x-error_handling name="phone" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="create_role_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Role') }}</label>
                        <select name="role_id" id="create_role_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="">{{ __('Select role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected((string) old('role_id') === (string) $role->id)>{{ str_replace('_', ' ', $role->name) }}</option>
                            @endforeach
                        </select>
                        <x-error_handling name="role_id" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="create_company_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Assign to Company') }}</label>
                        <select name="company_id" id="create_company_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="">{{ __('Select your company') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected((string) (old('company_id') ?: session('company_id')) === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <x-error_handling name="company_id" />
                    </div>
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-[11px] text-amber-700">
                    <i class="fa-solid fa-key mr-1"></i> {{ __('Temporary password will be set to the staff member\'s last name in UPPERCASE.') }}
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="onboardModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ __('Save Staff') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT / REASSIGN STAFF MODAL -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="editModalOpen = false"></div>

        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ __('Edit / Reassign Staff Member') }}</h3>
                    <p class="mt-1 text-[11px] text-slate-500">{{ __('Update staff details or switch them to another of your companies.') }}</p>
                </div>
                <button type="button" @click="editModalOpen = false" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form :action="'/staff/' + editStaff.id" method="POST" class="space-y-5 px-5 py-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="edit_first_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" id="edit_first_name" x-model="editStaff.first_name" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_last_name" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" id="edit_last_name" x-model="editStaff.last_name" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="edit_email" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="edit_email" x-model="editStaff.email" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_phone" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Phone') }}</label>
                        <input type="tel" name="phone" id="edit_phone" x-model="editStaff.phone" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="edit_role_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Role') }}</label>
                        <select name="role_id" id="edit_role_id" x-model="editStaff.role_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ str_replace('_', ' ', $role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="edit_company_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600">{{ __('Assigned Company') }}</label>
                        <select name="company_id" id="edit_company_id" x-model="editStaff.company_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-[11px] text-blue-700">
                    <i class="fa-solid fa-circle-info mr-1"></i> {{ __('Reassigning a staff member will immediately update their company scope and memberships.') }}
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="editModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-lg bg-[#173b26] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#285b38]">{{ __('Update Staff') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
