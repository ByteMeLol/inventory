<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f6f8f7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-[#15221d]" x-data="{ mobileSidebarOpen: false }">
    @php
        $hideSalesAndInventory = (int) session('company_id') === 2;
        $navItem = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition';
        $activeNav = 'bg-[#c9f7d4] text-[#143c22] shadow-sm';
        $inactiveNav = 'text-[#718078] hover:bg-[#eef4ef] hover:text-[#183c28]';
    @endphp

    <div class="flex min-h-screen">
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/30 lg:hidden"></div>

        <aside
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-[248px] -translate-x-full flex-col border-r border-[#e2eae4] bg-[#fbfcfb] px-4 py-5 transition-transform duration-300 lg:static lg:translate-x-0"
        >
            <div class="flex items-center justify-between px-2">
                <a href="/home" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#173b26] text-white shadow-sm">
                        <i class="fa-solid fa-boxes-stacked text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="text-[17px] font-bold tracking-[-0.03em] text-[#193524]">Inventory<span class="text-[#78c98d]">.</span></span>
                </a>
                <button @click="mobileSidebarOpen = false" class="p-2 text-[#849189] lg:hidden" aria-label="Close navigation"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="mt-7 rounded-2xl border border-[#e6eee8] bg-white p-2.5 shadow-[0_4px_16px_rgba(31,66,44,0.04)]" x-data="{ open: false }">
                <p class="px-2 pb-1 text-[9px] font-bold uppercase tracking-[0.14em] text-[#98a59c]">Workspace</p>
                @if(auth()->user()->isSuperAdmin() || is_null(auth()->user()->company_id))
                    <button @click="open = !open" class="flex w-full items-center justify-between rounded-xl bg-[#f2f8f3] px-2.5 py-2 text-left text-xs font-semibold text-[#284d35]">
                        <span class="flex min-w-0 items-center gap-2"><i class="fa-regular fa-building text-[#72b780]"></i><span class="truncate">{{ session('company_name', 'Select Company') }}</span></span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-[#94a199]" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute mt-2 w-[218px] rounded-xl border border-[#e3ebe5] bg-white p-1.5 shadow-xl">
                        @foreach ($companies as $company)
                            <a href="/companies/{{ $company->id }}" class="block rounded-lg px-3 py-2 text-xs font-medium text-[#45604d] hover:bg-[#eef8f0]"><i class="fa-regular fa-building mr-2 text-[#86c994]"></i>{{ $company->name }}</a>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center gap-2 px-2.5 py-2 text-xs font-semibold text-[#284d35]"><i class="fa-regular fa-building text-[#72b780]"></i><span class="truncate">{{ auth()->user()->company->name }}</span></div>
                @endif
            </div>

            <nav class="mt-7 flex-1 space-y-1 overflow-y-auto">
                <p class="px-3 pb-2 text-[9px] font-bold uppercase tracking-[0.16em] text-[#a0aaa3]">Main menu</p>
                <a href="/dashboard" class="{{ $navItem }} {{ Request::is('dashboard') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-chart-pie w-4 text-center text-[13px]"></i>Overview</a>
                @unless($hideSalesAndInventory)
                    <a href="/inventory" class="{{ $navItem }} {{ Request::is('inventory*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-cubes-stacked w-4 text-center text-[13px]"></i>Inventory</a>
                    <a href="/sales" class="{{ $navItem }} {{ Request::is('sales*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-receipt w-4 text-center text-[13px]"></i>Sales</a>
                    <a href="/purchases" class="{{ $navItem }} {{ Request::is('purchases*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-truck-ramp-box w-4 text-center text-[13px]"></i>Purchases</a>
                @endunless
                <a href="/customers" class="{{ $navItem }} {{ Request::is('customers*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-users w-4 text-center text-[13px]"></i>Customers</a>
                <a href="/finance" class="{{ $navItem }} {{ Request::is('finance*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-chart-line w-4 text-center text-[13px]"></i>Finance</a>
                <a href="/expenses" class="{{ $navItem }} {{ Request::is('expenses*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-wallet w-4 text-center text-[13px]"></i>Expenses</a>
                <a href="/reports" class="{{ $navItem }} {{ Request::is('reports*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-file-lines w-4 text-center text-[13px]"></i>Reports</a>

                @if(auth()->user()->canManageUsers())
                    <p class="px-3 pb-2 pt-7 text-[9px] font-bold uppercase tracking-[0.16em] text-[#a0aaa3]">Manage</p>
                    <a href="/staff" class="{{ $navItem }} {{ Request::is('staff*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-user-group w-4 text-center text-[13px]"></i>Team members</a>
                    <a href="/settings" class="{{ $navItem }} {{ Request::is('settings*') ? $activeNav : $inactiveNav }}"><i class="fa-solid fa-sliders w-4 text-center text-[13px]"></i>Settings</a>
                @endif
            </nav>

            <div class="mt-5 border-t border-[#e8eee9] pt-4">
                <a href="/profile" class="{{ $navItem }} {{ Request::is('profile*') ? $activeNav : $inactiveNav }}"><i class="fa-regular fa-circle-user w-4 text-center text-[13px]"></i>My profile</a>
                <form action="/logout" method="POST" class="mt-1">@csrf<button class="{{ $navItem }} w-full {{ $inactiveNav }}" type="submit"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center text-[13px]"></i>Sign out</button></form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-[72px] items-center justify-between gap-4 border-b border-[#e6ece7] bg-[#fbfcfb] px-5 sm:px-8">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <button @click="mobileSidebarOpen = true" class="rounded-lg p-2 text-[#6e7d73] hover:bg-[#edf4ee] lg:hidden" aria-label="Open navigation"><i class="fa-solid fa-bars"></i></button>
                    <div class="relative hidden w-full max-w-[360px] sm:block">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-[#9ba89f]"></i>
                        <input type="search" placeholder="Search anything..." class="h-10 w-full rounded-xl border border-[#e2e9e3] bg-white pl-10 pr-4 text-xs text-[#243c2b] outline-none transition placeholder:text-[#a4aea7] focus:border-[#9ad5a6] focus:ring-4 focus:ring-[#c9f7d4]/60">
                    </div>
                    <span class="truncate text-sm font-semibold text-[#213a2a] sm:hidden">{{ $title }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <button class="relative rounded-xl p-2.5 text-[#718078] transition hover:bg-[#eef4ef]" aria-label="Notifications"><i class="fa-regular fa-bell"></i><span class="absolute right-2 top-2 h-1.5 w-1.5 rounded-full bg-[#e99b53] ring-2 ring-[#fbfcfb]"></span></button>
                    <div class="h-7 w-px bg-[#e5ebe6]"></div>
                    <div class="flex items-center gap-2.5">
                        <img class="h-9 w-9 rounded-xl border border-[#dce8df] object-cover" src="{{ asset('images/bussiness-man.png') }}" alt="">
                        <div class="hidden text-left sm:block"><p class="text-xs font-bold text-[#263d2d]">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p><p class="mt-0.5 text-[10px] capitalize text-[#8b988f]">{{ str_replace('_', ' ', auth()->user()->role?->name ?? 'user') }}</p></div>
                        <i class="fa-solid fa-chevron-down ml-1 text-[10px] text-[#9ba69e]"></i>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto bg-[#f6f8f7] p-4 sm:p-7 lg:p-9">{{ $slot }}</main>
        </div>
    </div>
</body>
</html>
