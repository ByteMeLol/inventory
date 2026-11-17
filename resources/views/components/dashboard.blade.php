<div class="mx-auto w-full max-w-7xl px-4 py-8">
    <!-- Top Overview Hero Header -->
    <section class="mb-8 rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $currentCompany->name ?? __('No company selected') }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">{{ __('Welcome back, :name', ['name' => auth()->user()->first_name]) }}</h1>
                <p class="text-xs sm:text-sm text-slate-500">{{ __('A clear view of stock, orders, and operational health.') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span>{{ __('This month') }}</span>
                </button>
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800">
                    <i class="fa-solid fa-download text-slate-300"></i>
                    <span>{{ __('Export report') }}</span>
                </button>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-building text-slate-400"></i>{{ $currentCompany->name ?? __('Workspace') }}</span>
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-user text-slate-400"></i>{{ auth()->user()->email }}</span>
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock text-slate-400"></i>{{ __('Updated :date', ['date' => now()->format('M d, Y')]) }}</span>
        </div>
    </section>

    <!-- 4 Modern Metric Cards -->
    <section class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Inventory -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Active inventory') }}</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($activeInventoryCount) }} <span class="text-xs font-normal text-slate-400">{{ __('units') }}</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-cubes-stacked"></i>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Low stock alerts') }}</p>
                <p class="mt-2 text-2xl font-bold tracking-tight {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-400">{{ __('lines') }}</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }} flex items-center justify-center text-base">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Monthly Orders -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Monthly orders') }}</p>
                <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">TZS {{ number_format($monthlyOrders, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>

        <!-- Team Accounts -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between hover:border-slate-300 transition-colors">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Team accounts') }}</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $teamAccounts }} <span class="text-xs font-normal text-slate-400">{{ __('active') }}</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center text-base">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </section>

    <!-- Charts Section -->
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Revenue Trend Line Chart -->
        <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs lg:col-span-2">
            <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-sm font-bold tracking-tight text-slate-900">{{ __('Revenue trend') }}</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Order value across the last six months') }}</p>
                </div>
                <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600">{{ $currentCompany->name ?? __('Workspace') }}</span>
            </div>
            <div class="h-72"><canvas id="revenueTrendChart"></canvas></div>
        </section>

        <!-- Inventory Share Doughnut Chart -->
        <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="mb-5 flex items-start justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-sm font-bold tracking-tight text-slate-900">{{ __('Inventory share') }}</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Stock by category') }}</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-xs">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="h-72"><canvas id="inventoryShareChart"></canvas></div>
        </section>
    </div>

    <!-- Tables & Activity Feed -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Low Stock Items Table -->
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Critical material supply') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Stock requiring replenishment') }}</p>
                </div>
                <span class="rounded-full bg-amber-50 border border-amber-100 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">{{ __('Live alerts') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-3.5">{{ __('Material') }}</th>
                            <th class="px-6 py-3.5">{{ __('SKU / code') }}</th>
                            <th class="px-6 py-3.5">{{ __('In stock') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($lowStockItems as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->description }}</td>
                                <td class="px-6 py-4 font-mono text-slate-500">{{ $item->sku }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->stock_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->stock_count === 0 ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                        {{ $item->stock_count === 0 ? __('Out of stock') : __('Low stock') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-xs text-slate-400">
                                    <i class="fa-solid fa-check-circle text-emerald-500 text-lg block mb-1.5"></i>
                                    {{ __('No low stock items for the current company.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- System Activity -->
        <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="border-b border-slate-100 pb-4 mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">{{ __('System activity') }}</h2>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="flex gap-3 py-3.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-regular fa-building text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ __('Current company') }}</p>
                            <p class="mt-0.5 text-slate-500">{{ $currentCompany->name ?? __('No company selected') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 py-3.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ __('Low stock alerts') }}</p>
                            <p class="mt-0.5 text-slate-500">{{ __(':count items need review.', ['count' => $lowStockCount]) }}</p>
                        </div>
                    </div>
                    @forelse ($recentSales as $sale)
                        <div class="flex gap-3 py-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-file-lines text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $sale->invoice_number }}</p>
                                <p class="mt-0.5 font-medium text-slate-500">TZS {{ number_format($sale->total_amount, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex gap-3 py-3.5">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-file-lines text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ __('No recent sales') }}</p>
                                <p class="mt-0.5 text-slate-500">{{ __('No sales recorded yet.') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 mt-4 text-[11px] text-slate-400 text-center">
                {{ __('Real-time database sync active') }}
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartFont = { family: 'Instrument Sans', size: 11, weight: '500' };
        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: @json($revenueTrendLabels),
                datasets: [{
                    data: @json($revenueTrendValues),
                    borderColor: '#10b981',
                    borderWidth: 2.5,
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    pointRadius: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: chartFont, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { font: chartFont, color: '#94a3b8' }
                    }
                }
            }
        });

        new Chart(document.getElementById('inventoryShareChart'), {
            type: 'doughnut',
            data: {
                labels: @json($inventoryShareLabels),
                datasets: [{
                    data: @json($inventoryShareValues),
                    backgroundColor: ['#10b981', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ec4899', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 12,
                            font: chartFont,
                            color: '#64748b',
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
</div>
