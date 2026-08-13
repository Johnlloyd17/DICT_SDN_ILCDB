<x-app-layout title="Funding Monitoring">
    {{-- BREADCRUMBS --}}
    <x-breadcrumbs :items="[['label' => 'Funding Monitoring', 'icon' => 'fa-sack-dollar text-purple-600']]" />

    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-sack-dollar text-yellow-400"></i> Project Funding & Financial Utilization Monitoring
            </h2>
            <p class="text-sm text-purple-200 mt-1">Real-time financial tracking, fund cluster allocations, and disbursement monitoring.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button x-data x-on:click="$dispatch('open-modal', 'addFunding')" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center shadow transition">
                <i class="fa-solid fa-plus mr-1"></i> Add Record
            </button>
            <a href="{{ route('export.csv', 'funding') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-medium flex items-center transition">
                <i class="fa-solid fa-download mr-1"></i> Export
            </a>
        </div>
    </div>

    {{-- OVERALL KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Allocated</span>
                <i class="fa-solid fa-coins text-blue-600"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800">₱{{ number_format($totalAllocated) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Obligated</span>
                <i class="fa-solid fa-file-invoice text-indigo-600"></i>
            </div>
            <h3 class="text-lg font-black text-indigo-700">₱{{ number_format($totalObligated) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Obligation Rate</span>
                <i class="fa-solid fa-percentage text-amber-600"></i>
            </div>
            <h3 class="text-lg font-black text-amber-600">{{ $obligationRate }}%</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Disbursed</span>
                <i class="fa-solid fa-hand-holding-dollar text-emerald-600"></i>
            </div>
            <h3 class="text-lg font-black text-emerald-700">₱{{ number_format($totalDisbursed) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Disbursement Rate</span>
                <i class="fa-solid fa-arrow-trend-down text-rose-600"></i>
            </div>
            <h3 class="text-lg font-black text-rose-600">{{ $disbursementRate }}%</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Unobligated</span>
                <i class="fa-solid fa-circle-exclamation text-slate-400"></i>
            </div>
            <h3 class="text-lg font-black text-slate-600">₱{{ number_format($remainingUnobligated) }}</h3>
        </div>
    </div>

    {{-- PER-PROJECT ALLOCATION CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php $projectIcons = ['DWIA-TMD' => 'fa-graduation-cap text-amber-400', 'DTC HUB' => 'fa-building-user text-cyan-400', 'SPARK' => 'fa-bolt text-yellow-400', 'PROJECT CLICK' => 'fa-laptop-code text-emerald-400']; @endphp
        @foreach($projectFunding as $pf)
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <h3 class="font-bold text-sm flex items-center gap-2 mb-4">
                <i class="fa-solid {{ $projectIcons[$pf->project] ?? 'fa-sack-dollar text-purple-400' }}"></i>
                {{ $pf->project }}
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Allocated</span>
                    <span class="font-bold text-slate-800">₱{{ number_format($pf->total_allocated) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Obligated</span>
                    <span class="font-bold text-indigo-600">₱{{ number_format($pf->total_obligated) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Disbursed</span>
                    <span class="font-bold text-emerald-600">₱{{ number_format($pf->total_disbursed) }}</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <div class="flex justify-between text-[10px] mb-1">
                    <span class="text-slate-400">Disbursement</span>
                    <span class="font-bold {{ $pf->total_allocated > 0 && $pf->total_disbursed / $pf->total_allocated > 0.75 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0 }}%
                    </span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all" style="width: {{ $pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                <i class="fa-solid fa-chart-bar text-blue-600"></i> Allocated vs Disbursed per Project
            </h3>
            <div class="h-[clamp(12rem,26vw,18rem)]">
                <canvas id="fundingBarChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                <i class="fa-solid fa-chart-pie text-purple-600"></i> Expense Category Breakdown
            </h3>
            <div class="h-[clamp(12rem,26vw,18rem)]">
                <canvas id="fundingDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    {{-- HISTORICAL PERFORMANCE TABLE --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-timeline text-indigo-600"></i> Historical Financial Performance (2022-2026)
            </h3>
        </div>
        <div class="overflow-x-auto p-5">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Fiscal Year</th>
                        <th class="px-4 py-3 text-right">Allocated</th>
                        <th class="px-4 py-3 text-right">Obligated</th>
                        <th class="px-4 py-3 text-right">Disbursed</th>
                        <th class="px-4 py-3 text-right">Obligation Rate</th>
                        <th class="px-4 py-3 text-right">Disbursement Rate</th>
                        <th class="px-4 py-3 text-right">YoY Growth</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-700">
                    @foreach($historicalData as $h)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-bold {{ $h['year'] == date('Y') ? 'text-blue-700' : 'text-slate-800' }}">
                            {{ $h['year'] }}
                            @if($h['year'] == date('Y'))
                            <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[9px] ml-1">Current</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($h['allocated']) }}</td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($h['obligated']) }}</td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($h['disbursed']) }}</td>
                        <td class="px-4 py-3 text-right font-bold {{ $h['allocated'] > 0 && $h['obligated'] / $h['allocated'] > 0.75 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $h['allocated'] > 0 ? round($h['obligated'] / $h['allocated'] * 100) : 0 }}%
                        </td>
                        <td class="px-4 py-3 text-right font-bold {{ $h['allocated'] > 0 && $h['disbursed'] / $h['allocated'] > 0.5 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $h['allocated'] > 0 ? round($h['disbursed'] / $h['allocated'] * 100) : 0 }}%
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($h['growth'] > 0)
                            <span class="text-emerald-600 font-bold"><i class="fa-solid fa-arrow-up mr-0.5"></i>{{ $h['growth'] }}%</span>
                            @elseif($h['growth'] < 0)
                            <span class="text-red-600 font-bold"><i class="fa-solid fa-arrow-down mr-0.5"></i>{{ abs($h['growth']) }}%</span>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- FINANCIAL LEDGER TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-ledger text-purple-700"></i> Financial Ledger - Disbursement Accountability
            </h3>
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <select name="project" class="text-xs p-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">
                    <option value="ALL">All Projects</option>
                    <option value="DWIA-TMD" {{ request('project') === 'DWIA-TMD' ? 'selected' : '' }}>DWIA-TMD</option>
                    <option value="DTC HUB" {{ request('project') === 'DTC HUB' ? 'selected' : '' }}>DTC HUB</option>
                    <option value="SPARK" {{ request('project') === 'SPARK' ? 'selected' : '' }}>SPARK</option>
                    <option value="PROJECT CLICK" {{ request('project') === 'PROJECT CLICK' ? 'selected' : '' }}>PROJECT CLICK</option>
                </select>
                <select name="status" class="text-xs p-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">
                    <option value="ALL">All Status</option>
                    <option value="Disbursed" {{ request('status') === 'Disbursed' ? 'selected' : '' }}>Disbursed</option>
                    <option value="Obligated" {{ request('status') === 'Obligated' ? 'selected' : '' }}>Obligated</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search voucher, description..." class="text-xs p-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500 w-full sm:w-56">
                <button type="submit" class="bg-purple-800 hover:bg-purple-700 text-white px-3 py-2.5 rounded-lg text-xs font-semibold transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Voucher Ref</th>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3 text-right">Allocated</th>
                        <th class="px-4 py-3 text-right">Obligated</th>
                        <th class="px-4 py-3 text-right">Disbursed</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                    @forelse($records as $r)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono text-[11px] font-bold text-purple-700">{{ $r->voucher_ref }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-bold">{{ $r->project }}</span>
                        </td>
                        <td class="px-4 py-3 max-w-[180px] truncate" title="{{ $r->description }}">{{ $r->description }}</td>
                        <td class="px-4 py-3 text-[11px]">{{ $r->expense_category }}</td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($r->allocated) }}</td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($r->obligated) }}</td>
                        <td class="px-4 py-3 text-right font-mono">₱{{ number_format($r->disbursed) }}</td>
                        <td class="px-4 py-3 text-[11px] whitespace-nowrap">{{ $r->transaction_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($r->status === 'Disbursed')
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Disbursed</span>
                            @elseif($r->status === 'Obligated')
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Obligated</span>
                            @else
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('funding.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this record?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-file-invoice text-3xl mb-2 block"></i>
                            No funding records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $records->links() }}
        </div>
    </div>

    {{-- ADD FUNDING MODAL --}}
    <div x-data="{ show: false }" x-on:open-modal.window="show = ($event.detail === 'addFunding')" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-sack-dollar text-yellow-400"></i> Add Funding Record</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('funding.store') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Project <span class="text-red-500">*</span></label>
                        <select name="project" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="DWIA-TMD">DWIA-TMD</option>
                            <option value="DTC HUB">DTC HUB</option>
                            <option value="SPARK">SPARK</option>
                            <option value="PROJECT CLICK">PROJECT CLICK</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Voucher # <span class="text-red-500">*</span></label>
                        <input type="text" name="voucher_ref" required placeholder="e.g. DV-2026-01-012" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="2" placeholder="e.g. Training Materials & Honoraria" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="expense_category" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="MOOE - Training & Seminars">MOOE - Training & Seminars</option>
                            <option value="Supplies & Logistics">Supplies & Logistics</option>
                            <option value="Honorarium & Consultancy">Honorarium & Consultancy</option>
                            <option value="Capital Outlay - Equipment">Capital Outlay - Equipment</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Pending">Pending</option>
                            <option value="Obligated">Obligated</option>
                            <option value="Disbursed">Disbursed</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Allocated <span class="text-red-500">*</span></label>
                        <input type="number" name="allocated" required step="0.01" min="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Obligated <span class="text-red-500">*</span></label>
                        <input type="number" name="obligated" required step="0.01" min="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Disbursed <span class="text-red-500">*</span></label>
                        <input type="number" name="disbursed" required step="0.01" min="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Transaction Date <span class="text-red-500">*</span></label>
                    <input type="date" name="transaction_date" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs"><i class="fa-solid fa-check mr-1"></i> Save Record</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        const barCtx = document.getElementById('fundingBarChart');
        if (barCtx) {
            const projects = @json($projectFunding);
            const labels = projects.map(p => p.project);
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Allocated', data: projects.map(p => p.total_allocated), backgroundColor: '#003366', borderRadius: 4 },
                        { label: 'Disbursed', data: projects.map(p => p.total_disbursed), backgroundColor: '#10b981', borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
                    scales: { y: { beginAtZero: true, ticks: { callback: v => '₱' + (v/1000).toFixed(0) + 'k' } } }
                }
            });
        }

        const doughnutCtx = document.getElementById('fundingDoughnutChart');
        if (doughnutCtx) {
            const categories = @json($categories);
            const colors = ['#003366','#FCD116','#CE1126','#10b981','#8b5cf6','#f59e0b'];
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: categories.map(c => c.expense_category),
                    datasets: [{ data: categories.map(c => c.total), backgroundColor: colors.slice(0, categories.length) }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } },
                        tooltip: { callbacks: { label: ctx => '₱' + Number(ctx.raw).toLocaleString() } }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
