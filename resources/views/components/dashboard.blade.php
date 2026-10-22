<div class="mx-auto w-full max-w-[1380px]">
    <section class="mb-7 border border-[#dfe5e1] bg-[#edf2ee] px-6 py-7 sm:px-8">
        <div class="flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 border border-[#c7d8ca] bg-white px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.14em] text-[#315c3b]">
                    <span class="h-1.5 w-1.5 bg-[#3f9b58]"></span>
                    {{ $currentCompany->name ?? 'No company selected' }}
                </div>
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#738278]">Overview</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-[#17231b] sm:text-4xl">Welcome back, {{ auth()->user()->first_name }}</h1>
                <p class="mt-2 text-sm text-[#6f7d73]">A clear view of stock, orders, and operational health.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="border border-[#cbd8ce] bg-white px-3.5 py-2 text-xs font-medium text-[#415247] hover:bg-[#f8fbf8]"><i class="fa-regular fa-calendar mr-1.5 text-[#77917d]"></i>This month</button>
                <button class="bg-[#173b26] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#285b38]"><i class="fa-solid fa-download mr-1.5 text-[#acd8b4]"></i>Export report</button>
            </div>
        </div>
        <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 border-t border-[#d6e1d8] pt-4 text-[11px] text-[#6c7d70]">
            <span><i class="fa-regular fa-building mr-1.5 text-[#7fa188]"></i>{{ $currentCompany->name ?? 'Workspace' }}</span>
            <span><i class="fa-regular fa-user mr-1.5 text-[#7fa188]"></i>{{ auth()->user()->email }}</span>
            <span><i class="fa-regular fa-clock mr-1.5 text-[#7fa188]"></i>Updated {{ now()->format('M d, Y') }}</span>
        </div>
    </section>

    <section class="mb-7 grid grid-cols-1 border-y border-l border-[#dfe5e1] bg-white sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex items-center justify-between border-b border-r border-[#dfe5e1] p-5 lg:border-b-0">
            <div><p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#849389]">Active inventory</p><p class="mt-2 text-2xl font-semibold tracking-tight text-[#1b2b20]">{{ number_format($activeInventoryCount) }} <small class="text-xs font-normal text-[#94a199]">units</small></p></div>
            <i class="fa-solid fa-cubes-stacked text-[#86a58c]"></i>
        </div>
        <div class="flex items-center justify-between border-b border-r border-[#dfe5e1] p-5 lg:border-b-0">
            <div><p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#849389]">Low stock alerts</p><p class="mt-2 text-2xl font-semibold tracking-tight text-[#1b2b20]">{{ $lowStockCount }} <small class="text-xs font-normal text-[#b37a42]">lines</small></p></div>
            <i class="fa-solid fa-triangle-exclamation text-[#c58b50]"></i>
        </div>
        <div class="flex items-center justify-between border-b border-r border-[#dfe5e1] p-5 lg:border-b-0">
            <div><p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#849389]">Monthly orders</p><p class="mt-2 text-2xl font-semibold tracking-tight text-[#1b2b20]">TZS {{ number_format($monthlyOrders, 2) }}</p></div>
            <i class="fa-solid fa-arrow-trend-up text-[#86a58c]"></i>
        </div>
        <div class="flex items-center justify-between border-r border-[#dfe5e1] p-5">
            <div><p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#849389]">Team accounts</p><p class="mt-2 text-2xl font-semibold tracking-tight text-[#1b2b20]">{{ $teamAccounts }} <small class="text-xs font-normal text-[#94a199]">active</small></p></div>
            <i class="fa-solid fa-users text-[#86a58c]"></i>
        </div>
    </section>

    <div class="mb-7 grid grid-cols-1 gap-7 lg:grid-cols-3">
        <section class="border border-[#dfe5e1] bg-white p-5 lg:col-span-2">
            <div class="mb-5 flex items-start justify-between border-b border-[#e8ede9] pb-4">
                <div><h2 class="text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">Revenue trend</h2><p class="mt-1 text-[11px] text-[#8a978e]">Order value across the last six months</p></div>
                <span class="border border-[#e1e9e2] px-2 py-1 text-[9px] text-[#8a978e]">{{ $currentCompany->name ?? 'Workspace' }}</span>
            </div>
            <div class="h-64"><canvas id="revenueTrendChart"></canvas></div>
        </section>
        <section class="border border-[#dfe5e1] bg-white p-5">
            <div class="mb-5 flex items-start justify-between border-b border-[#e8ede9] pb-4"><div><h2 class="text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">Inventory share</h2><p class="mt-1 text-[11px] text-[#8a978e]">Stock by category</p></div><i class="fa-solid fa-chart-pie text-xs text-[#9aad9f]"></i></div>
            <div class="h-64"><canvas id="inventoryShareChart"></canvas></div>
        </section>
    </div>

    <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">
        <section class="overflow-hidden border border-[#dfe5e1] bg-white lg:col-span-2">
            <div class="flex items-center justify-between border-b border-[#e8ede9] px-5 py-4"><h2 class="text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">Critical material supply</h2><span class="text-[9px] text-[#8a978e]">Live metrics</span></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead><tr class="border-b border-[#e8ede9] bg-[#fafcfb] text-[9px] font-bold uppercase tracking-[0.13em] text-[#92a097]"><th class="px-5 py-3">Material</th><th class="px-5 py-3">SKU / code</th><th class="px-5 py-3">In stock</th><th class="px-5 py-3 text-right">Status</th></tr></thead>
                    <tbody class="divide-y divide-[#edf1ee]">
                        @forelse ($lowStockItems as $item)
                            <tr class="text-[#55645a] hover:bg-[#fbfdfb]"><td class="px-5 py-4 font-medium text-[#293b2e]">{{ $item->description }}</td><td class="px-5 py-4 font-mono text-[10px] text-[#94a199]">{{ $item->sku }}</td><td class="px-5 py-4 font-semibold text-[#293b2e]">{{ $item->stock_count }}</td><td class="px-5 py-4 text-right"><span class="border border-[#ead8c6] px-2 py-1 text-[9px] font-bold uppercase text-[#a56c35]">{{ $item->stock_count === 0 ? 'Out of stock' : 'Low stock' }}</span></td></tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-xs text-[#9aa69d]">No low stock items for the current company.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <section class="border border-[#dfe5e1] bg-white p-5">
            <h2 class="border-b border-[#e8ede9] pb-4 text-xs font-bold uppercase tracking-[0.12em] text-[#263b2c]">System activity</h2>
            <div class="divide-y divide-[#edf1ee]">
                <div class="flex gap-3 py-4 text-xs"><i class="fa-regular fa-building mt-0.5 text-[#86a58c]"></i><div><p class="font-semibold text-[#35493a]">Current company</p><p class="mt-1 text-[10px] text-[#8d9a91]">{{ $currentCompany->name ?? 'No company selected' }}</p></div></div>
                <div class="flex gap-3 py-4 text-xs"><i class="fa-solid fa-triangle-exclamation mt-0.5 text-[#c58b50]"></i><div><p class="font-semibold text-[#35493a]">Low stock alerts</p><p class="mt-1 text-[10px] text-[#8d9a91]">{{ $lowStockCount }} items need review.</p></div></div>
                @forelse ($recentSales as $sale)
                    <div class="flex gap-3 py-4 text-xs"><i class="fa-regular fa-file-lines mt-0.5 text-[#86a58c]"></i><div><p class="font-semibold text-[#35493a]">{{ $sale->invoice_number }}</p><p class="mt-1 text-[10px] text-[#8d9a91]">TZS {{ number_format($sale->total_amount, 2) }}</p></div></div>
                @empty
                    <div class="flex gap-3 py-4 text-xs"><i class="fa-regular fa-file-lines mt-0.5 text-[#86a58c]"></i><div><p class="font-semibold text-[#35493a]">No recent sales</p><p class="mt-1 text-[10px] text-[#8d9a91]">No sales recorded yet.</p></div></div>
                @endforelse
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartFont = { family: 'Instrument Sans', size: 10 };
        new Chart(document.getElementById('revenueTrendChart'), { type: 'line', data: { labels: @json($revenueTrendLabels), datasets: [{ data: @json($revenueTrendValues), borderColor: '#315c3b', borderWidth: 1.5, pointRadius: 2, pointBackgroundColor: '#315c3b', tension: .25, fill: false }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { font: chartFont, color: '#94a199' } }, y: { grid: { color: '#edf1ee' }, ticks: { font: chartFont, color: '#94a199' } } } } });
        new Chart(document.getElementById('inventoryShareChart'), { type: 'doughnut', data: { labels: @json($inventoryShareLabels), datasets: [{ data: @json($inventoryShareValues), backgroundColor: ['#315c3b', '#86a58c', '#b8cdbb', '#dce8dd'], borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '76%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 7, boxHeight: 7, padding: 14, font: chartFont, color: '#718078' } } } } });
    </script>
</div>
