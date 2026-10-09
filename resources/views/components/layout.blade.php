<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
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
<body class="h-full font-sans antialiased text-gray-900" x-data="{ mobileSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        @php
            $hideSalesAndInventory = (int) session('company_id') === 2;
            $hideInsurance = (int) session('company_id') === 1;
        @endphp
        
        <aside 
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-[#025c78] text-white transform lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-300 ease-in-out flex-shrink-0 gap-2 border-r border-[#01465c]"
        >
            <div class="flex items-center justify-between h-16 px-6 bg-[#014d64] font-bold text-lg tracking-wide border-b border-[#01465c] gap-3">
                <a href="/home">
                    <div class="flex items-center h-full py-2">
                    <img src="{{ asset('images/logobros.png') }}" alt="Logo" class="max-h-12 w-auto object-contain">
                    </div>
                    <button @click="mobileSidebarOpen = false" class="text-white hover:text-blue-200 lg:hidden focus:outline-none">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </a>
                
            </div>

            <div class="px-4 py-3 border-b border-[#01465c]" x-data="{ open: false }">
                <label class="block text-[10px] text-blue-200 uppercase tracking-wider font-semibold mb-1">Active Scope</label>
                <div class="relative">
                    @if(auth()->user()->isSuperAdmin() || is_null(auth()->user()->company_id))
                        <button @click="open = !open" class="w-full flex items-center justify-between bg-[#FF8A37] hover:bg-[#FF720E] text-white px-3 py-2 rounded-lg text-sm font-medium transition focus:outline-none shadow-sm">
                            <span class="truncate">
                                <i class="fa-solid fa-building-user mr-1.5"></i> 
                                {{ session('company_name', 'Select Company') }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-full bg-white text-gray-800 rounded-lg shadow-xl py-1 z-50 border border-gray-200">
                            @foreach ($companies as $company)
                                    <a href="/companies/{{ $company->id }}" class="block px-4 py-2 text-sm bg-gray-100 font-semibold text-[#025c78]"><i class="fa-solid fa-building mr-2"></i>{{ $company->name }}</a>
                            @endforeach
                                 
                        </div>
                    @else
                        <div class="w-full flex items-center bg-[#014d64] text-blue-100 px-3 py-2 rounded-lg text-sm font-medium border border-[#01465c]">
                            <span class="truncate"><i class="fa-solid fa-building mr-1.5"></i> {{ auth()->user()->company->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
                <a href="/dashboard" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('dashboard') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition">
                    <i class="fa-solid fa-chart-pie w-5 text-center mr-3 text-blue-200 text-base"></i>
                    Dashboard
                </a>
                @unless($hideSalesAndInventory)
                    <a href="/sales" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('sales*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                        <i class="fa-solid fa-file-invoice-dollar w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                        Sales
                    </a>
                    <a href="/inventory" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('inventory*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                        <i class="fa-solid fa-store w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                        Inventory
                    </a>
                    <a href="/purchases" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('purchases*') || Request::is('suppliers*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                        <i class="fa-solid fa-truck-ramp-box w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                        Purchases & Suppliers
                    </a>
                @endunless
                <a href="/finance" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('finance*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                    <i class="fa-solid fa-coins w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                    Finance
                </a>
                <a href="/expenses" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('expenses*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                    <i class="fa-solid fa-credit-card w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                    Expenses
                </a>
                <a href="/customers" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('customers*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                    <i class="fa-solid fa-users w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                    Customers
                </a>
                @unless($hideInsurance)
                    <a href="/insurance" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('insurance*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                        <i class="fa-solid fa-shield-halved w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                        Insurance
                    </a>
                @endunless
                <a href="/sms" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('sms*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                    <i class="fa-solid fa-comment-sms w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                    Bulk SMS
                </a>
                <a href="/reports" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ Request::is('reports*') ? 'bg-[#12b1df] text-white' : 'text-blue-100 hover:bg-[#12b1df]/50 hover:text-white' }} transition group">
                    <i class="fa-solid fa-chart-line w-5 text-center mr-3 text-blue-300 group-hover:text-white text-base"></i>
                    Reports
                </a>
            </nav>

            <div class="p-4 border-t border-[#01465c] space-y-1">
                @if(auth()->user()->canManageUsers())
                    <a href="/staff" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ Request::is('staff*') ? 'bg-[#12b1df] text-white' : 'text-blue-200 hover:bg-[#12b1df]/30' }} transition mb-1">
                        <i class="fa-solid fa-user-plus w-5 text-center mr-3 text-blue-300 text-base"></i>
                        Add New User
                    </a>

                    <a href="/settings" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ Request::is('settings*') ? 'bg-[#12b1df] text-white' : 'text-blue-200 hover:bg-[#12b1df]/30' }} transition">
                        <i class="fa-solid fa-gear w-5 text-center mr-3 text-blue-300 text-base"></i>
                        Settings
                    </a>
                @endif
            </div>
        </aside>

        <div 
            x-show="mobileSidebarOpen" 
            @click="mobileSidebarOpen = false" 
            x-transition.opacity
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        ></div>

        <div class="flex flex-col flex-1 overflow-hidden w-full">
            
            <header class="flex items-center justify-between h-16 px-4 sm:px-8 bg-white border-b border-gray-200 z-10 gap-4">
                
                <div class="flex items-center gap-3 flex-1 md:flex-initial">
                    <button @click="mobileSidebarOpen = true" class="text-gray-600 hover:text-gray-900 lg:hidden focus:outline-none p-1">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    
                    <div class="w-full sm:w-64 md:w-96">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-lg focus:bg-white focus:border-blue-500 focus:outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-5 flex-shrink-0" x-data="{ userMenu: false }">
                    <button class="text-gray-500 hover:text-gray-700 relative text-lg p-1">
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                    
                    <div class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <img class="h-8 w-8 rounded-full border border-gray-300 object-cover" src="{{ asset('images/bussiness-man.png') }}" alt="Avatar">
                            <div class="text-left hidden md:block">
                                <p class="text-xs font-semibold leading-tight text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">{{ str_replace('_', ' ', auth()->user()->role?->name ?? 'user') }}</p>
                            </div>
                            <i class="fa-solid fa-angle-down text-xs text-gray-400"></i>
                        </button>
                        <div x-show="userMenu" @click.outside="userMenu = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fa-regular fa-user mr-2"></i> My Profile</a>
                            <hr class="border-gray-200">
                            
                            <form action="/logout" method="POST" id="logout-form" class="hidden">@csrf</form>
                            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>