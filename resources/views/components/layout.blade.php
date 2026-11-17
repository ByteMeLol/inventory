<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | {{ config('app.name', 'Inventory') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-slate-900 selection:bg-slate-900 selection:text-white" x-data="{ mobileSidebarOpen: false, addCompanyModalOpen: false }">
    @php
        $hideSalesAndInventory = (int) session('company_id') === 2;
        $navItem = 'group flex items-center gap-3 rounded-xl px-3 py-2 text-[13px] font-medium transition-all duration-150';
        $activeNav = 'bg-slate-900 text-white shadow-xs';
        $inactiveNav = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
    @endphp

    <div class="flex min-h-screen bg-slate-50">
        <!-- MOBILE BACKDROP -->
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-xs lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-[260px] -translate-x-full flex-col border-r border-slate-200 bg-white px-4 py-5 transition-transform duration-200 lg:static lg:translate-x-0"
        >
            <!-- BRAND LOGO -->
            <div class="flex items-center justify-between px-2">
                <a href="/home" class="flex items-center gap-2.5 group">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-white shadow-xs transition group-hover:scale-105">
                        <i class="fa-solid fa-boxes-stacked text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[17px] font-bold tracking-tight text-slate-900">Inventory<span class="text-emerald-600">.</span></span>
                </a>
                <button @click="mobileSidebarOpen = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 lg:hidden" aria-label="Close navigation">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- WORKSPACE SELECTOR -->
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50/70 p-2" x-data="{ open: false }">
                <div class="flex items-center justify-between px-1.5 pb-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Workspace') }}</p>
                    <button type="button" @click="addCompanyModalOpen = true" class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-800 transition inline-flex items-center gap-1" title="{{ __('Add Company') }}">
                        <i class="fa-solid fa-plus text-[9px]"></i> {{ __('New') }}
                    </button>
                </div>
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg bg-white border border-slate-200/80 px-2.5 py-1.5 text-left text-xs font-semibold text-slate-800 shadow-2xs hover:bg-slate-50 transition">
                    <span class="flex min-w-0 items-center gap-2">
                        <i class="fa-regular fa-building text-slate-400"></i>
                        <span class="truncate">{{ session('company_name', __('Select Company')) }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition class="absolute left-4 z-50 mt-1.5 w-[228px] rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">
                    <div class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                        @forelse ($companies as $company)
                            <a href="/companies/{{ $company->id }}" class="flex items-center justify-between rounded-lg px-2.5 py-2 text-xs font-medium {{ (int) session('company_id') === (int) $company->id ? 'bg-slate-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="truncate flex items-center gap-2">
                                    <i class="fa-regular fa-building text-[11px] {{ (int) session('company_id') === (int) $company->id ? 'text-slate-300' : 'text-slate-400' }}"></i>
                                    {{ $company->name }}
                                </span>
                                @if((int) session('company_id') === (int) $company->id)
                                    <i class="fa-solid fa-check text-[10px] text-emerald-400"></i>
                                @endif
                            </a>
                        @empty
                            <p class="px-3 py-2 text-[11px] text-slate-400">{{ __('No companies found.') }}</p>
                        @endforelse
                    </div>
                    <div class="border-t border-slate-100 pt-1 mt-1">
                        <button type="button" @click="open = false; addCompanyModalOpen = true" class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-900 hover:bg-slate-100 transition">
                            <i class="fa-solid fa-plus-circle text-emerald-600"></i>
                            <span>{{ __('Create Company') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="mt-5 flex-1 space-y-1 overflow-y-auto pr-1">
                <p class="px-3 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Main menu') }}</p>
                <a href="/dashboard" class="{{ $navItem }} {{ Request::is('dashboard') ? $activeNav : $inactiveNav }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center text-xs"></i>
                    <span>{{ __('Overview') }}</span>
                </a>
                @unless($hideSalesAndInventory)
                    <a href="/inventory" class="{{ $navItem }} {{ Request::is('inventory*') ? $activeNav : $inactiveNav }}">
                        <i class="fa-solid fa-cubes-stacked w-4 text-center text-xs"></i>
                        <span>{{ __('Inventory') }}</span>
                    </a>
                    <a href="/sales" class="{{ $navItem }} {{ Request::is('sales*') ? $activeNav : $inactiveNav }}">
                        <i class="fa-solid fa-receipt w-4 text-center text-xs"></i>
                        <span>{{ __('Sales') }}</span>
                    </a>
                    <a href="/purchases" class="{{ $navItem }} {{ Request::is('purchases*') ? $activeNav : $inactiveNav }}">
                        <i class="fa-solid fa-truck-ramp-box w-4 text-center text-xs"></i>
                        <span>{{ __('Purchases') }}</span>
                    </a>
                @endunless
                <a href="/customers" class="{{ $navItem }} {{ Request::is('customers*') ? $activeNav : $inactiveNav }}">
                    <i class="fa-solid fa-users w-4 text-center text-xs"></i>
                    <span>{{ __('Customers') }}</span>
                </a>
                <a href="/finance" class="{{ $navItem }} {{ Request::is('finance*') ? $activeNav : $inactiveNav }}">
                    <i class="fa-solid fa-coins w-4 text-center text-xs"></i>
                    <span>{{ __('Finance') }}</span>
                </a>
                <a href="/expenses" class="{{ $navItem }} {{ Request::is('expenses*') ? $activeNav : $inactiveNav }}">
                    <i class="fa-solid fa-wallet w-4 text-center text-xs"></i>
                    <span>{{ __('Expenses') }}</span>
                </a>
                <a href="/reports" class="{{ $navItem }} {{ Request::is('reports*') ? $activeNav : $inactiveNav }}">
                    <i class="fa-solid fa-file-lines w-4 text-center text-xs"></i>
                    <span>{{ __('Reports') }}</span>
                </a>

                @if(auth()->user()->canManageUsers())
                    <p class="px-3 pb-1.5 pt-5 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Manage') }}</p>
                    <a href="/staff" class="{{ $navItem }} {{ Request::is('staff*') ? $activeNav : $inactiveNav }}">
                        <i class="fa-solid fa-user-group w-4 text-center text-xs"></i>
                        <span>{{ __('Team members') }}</span>
                    </a>
                    <a href="/settings" class="{{ $navItem }} {{ Request::is('settings*') ? $activeNav : $inactiveNav }}">
                        <i class="fa-solid fa-sliders w-4 text-center text-xs"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                @endif
            </nav>

            <!-- FOOTER PROFILE & SIGN OUT -->
            <div class="mt-4 border-t border-slate-200 pt-3 space-y-1">
                <a href="/profile" class="{{ $navItem }} {{ Request::is('profile*') ? $activeNav : $inactiveNav }}">
                    <i class="fa-regular fa-circle-user w-4 text-center text-xs"></i>
                    <span>{{ __('My profile') }}</span>
                </a>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="{{ $navItem }} w-full {{ $inactiveNav }}" type="submit">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center text-xs"></i>
                        <span>{{ __('Sign out') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN VIEW CONTAINER -->
        <div class="flex min-w-0 flex-1 flex-col">
            <!-- HEADER -->
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/90 backdrop-blur-md px-5 sm:px-8">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <button @click="mobileSidebarOpen = true" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Open navigation">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <div class="relative hidden w-full max-w-sm sm:block">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        <input type="search" placeholder="{{ __('Search anything...') }}" class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50/70 pl-9 pr-4 text-xs text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-900/5">
                    </div>
                    <span class="truncate text-sm font-bold text-slate-900 sm:hidden">{{ $title }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <!-- LANGUAGE SWITCHER DROPDOWN -->
                    <div class="relative" x-data="{ langOpen: false }">
                        <button @click="langOpen = !langOpen" class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300 shadow-2xs">
                            @if(app()->getLocale() === 'sw')
                                <span class="text-sm">🇹🇿</span>
                                <span>SW</span>
                            @else
                                <span class="text-sm">🇬🇧</span>
                                <span>EN</span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform" :class="langOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="langOpen" @click.outside="langOpen = false" x-transition class="absolute right-0 mt-2 w-40 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl z-50">
                            <a href="{{ route('language.switch', 'en') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-xs font-medium {{ app()->getLocale() === 'en' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="flex items-center gap-2"><span class="text-sm">🇬🇧</span> English</span>
                                @if(app()->getLocale() === 'en')
                                    <i class="fa-solid fa-check text-[10px] text-emerald-400"></i>
                                @endif
                            </a>
                            <a href="{{ route('language.switch', 'sw') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-xs font-medium {{ app()->getLocale() === 'sw' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="flex items-center gap-2"><span class="text-sm">🇹🇿</span> Kiswahili</span>
                                @if(app()->getLocale() === 'sw')
                                    <i class="fa-solid fa-check text-[10px] text-emerald-400"></i>
                                @endif
                            </a>
                        </div>
                    </div>

                    <div class="h-5 w-px bg-slate-200"></div>

                    <!-- USER IDENTITY PILL -->
                    <a href="/profile" class="flex items-center gap-2.5 rounded-xl p-1 pr-2 hover:bg-slate-100 transition">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white text-xs font-bold uppercase shadow-2xs">
                            {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? '', 0, 1) }}
                        </div>
                        <div class="hidden text-left sm:block">
                            <p class="text-xs font-semibold text-slate-900 leading-tight">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-[10px] capitalize text-slate-400 leading-tight">{{ __(str_replace('_', ' ', auth()->user()->role?->name ?? 'user')) }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-y-auto bg-slate-50/50 p-4 sm:p-6 lg:p-8">{{ $slot }}</main>
        </div>
    </div>

    <!-- GLOBAL ADD COMPANY MODAL -->
    <div x-show="addCompanyModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm" @click="addCompanyModalOpen = false"></div>

        <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Create New Company') }}</h3>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('Add another company branch or entity under your account.') }}</p>
                </div>
                <button type="button" @click="addCompanyModalOpen = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('companies.store') }}" method="POST" class="space-y-4 px-6 py-5">
                @csrf
                <div class="space-y-1.5">
                    <label for="modal_company_name" class="block text-xs font-semibold text-slate-700">{{ __('Company Name') }}</label>
                    <input type="text" name="name" id="modal_company_name" required placeholder="{{ __('e.g. Acme Logistics Ltd') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-900/5">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label for="modal_company_phone" class="block text-xs font-semibold text-slate-700">{{ __('Phone (Optional)') }}</label>
                        <input type="tel" name="phone" id="modal_company_phone" placeholder="+255..." class="w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-900/5">
                    </div>
                    <div class="space-y-1.5">
                        <label for="modal_company_currency" class="block text-xs font-semibold text-slate-700">{{ __('Currency') }}</label>
                        <select name="currency" id="modal_company_currency" class="w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-900/5">
                            <option value="TZS">TZS (TSh)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="KES">KES (KSh)</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-3 text-xs text-emerald-800 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info mt-0.5 text-emerald-600"></i>
                    <span>{{ __('Once created, the workspace will automatically switch to your new company.') }}</span>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="addCompanyModalOpen = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">{{ __('Create Company') }}</button>
                </div>
            </form>
        </div>
    </div>
</body>
<x-form_persistence />
</html>
