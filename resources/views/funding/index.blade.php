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
    @php $seedRecords = $records->getCollection()->values(); @endphp
    <div x-data="fundingCrud(@json($seedRecords))" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        {{-- FLASH NOTICE --}}
        <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2 mb-4"
             :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
            <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
            <span x-text="notice"></span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-ledger text-purple-700"></i> Financial Ledger - Disbursement Accountability
            </h3>
            <div class="flex items-center gap-2 flex-wrap">
                <select x-model="projectFilter" class="text-xs p-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Projects</option>
                    <template x-for="p in projects" :key="p"><option :value="p" x-text="p"></option></template>
                </select>
                <select x-model="statusFilter" class="text-xs p-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="Disbursed">Disbursed</option>
                    <option value="Obligated">Obligated</option>
                    <option value="Pending">Pending</option>
                </select>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Search voucher, description..." class="text-xs pl-8 pr-3 py-2.5 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-purple-500 w-full sm:w-56">
                </div>
            </div>
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
                    <template x-for="r in pagedRecords" :key="r.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-mono text-[11px] font-bold text-purple-700" x-text="r.voucher_ref"></td>
                            <td class="px-4 py-3"><span class="bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="r.project"></span></td>
                            <td class="px-4 py-3 max-w-[180px] truncate" :title="r.description" x-text="r.description"></td>
                            <td class="px-4 py-3 text-[11px]" x-text="r.expense_category"></td>
                            <td class="px-4 py-3 text-right font-mono" x-text="'₱' + Number(r.allocated).toLocaleString()"></td>
                            <td class="px-4 py-3 text-right font-mono" x-text="'₱' + Number(r.obligated).toLocaleString()"></td>
                            <td class="px-4 py-3 text-right font-mono" x-text="'₱' + Number(r.disbursed).toLocaleString()"></td>
                            <td class="px-4 py-3 text-[11px] whitespace-nowrap" x-text="new Date(r.transaction_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'})"></td>
                            <td class="px-4 py-3 text-center">
                                <span x-show="r.status === 'Disbursed'" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Disbursed</span>
                                <span x-show="r.status === 'Obligated'" class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Obligated</span>
                                <span x-show="r.status === 'Pending'" class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Pending</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button x-on:click="deleteRecord(r)" class="text-red-500 hover:text-red-700 text-xs"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="filteredRecords.length === 0" class="text-center text-slate-400 py-12 text-xs">
                <i class="fa-solid fa-file-invoice text-3xl mb-2 block text-slate-300"></i>
                No funding records found.
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3 mt-4">
            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                <span>Rows per page:</span>
                <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700">
                    <template x-for="n in [5, 10, 15, 20, 30, 50]" :key="n"><option :value="n" x-text="n"></option></template>
                </select>
            </div>
            <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}–${pageTo} of ${filteredRecords.length}`"></div>
            <div class="flex items-center gap-1">
                <button x-on:click="page--" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                <template x-for="p in pageNumbers" :key="'fp'+p">
                    <button x-on:click="page = p" :class="page === p ? 'bg-purple-600 text-white border-purple-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border" x-text="p"></button>
                </template>
                <button x-on:click="page++" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
            </div>
        </div>
    </div>

    {{-- ADD FUNDING MODAL --}}
    <div x-data="{ show: false }" x-on:open-modal.window="show = ($event.detail === 'addFunding')" x-on:close-modal.window="if ($event.detail === 'addFunding') show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-sack-dollar text-yellow-400"></i> Add Funding Record</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addFundingForm" x-on:submit.prevent="addRecord($event.target)" class="p-6 space-y-4 text-xs">
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
                    <button type="submit" form="addFundingForm" :disabled="saving" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Save Record</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    window.fundingCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const storeUrl = '{{ route("funding.store") }}';
        const destroyUrl = '{{ route("funding.destroy", ["funding" => "__ID__"]) }}';
        return {
            records: seed,
            search: '',
            projectFilter: '',
            statusFilter: '',
            saving: false,
            notice: '',
            noticeType: 'success',
            page: 1,
            perPage: 10,
            get projects() { return [...new Set(this.records.map(r => r.project))].sort(); },
            get filteredRecords() {
                const q = this.search.trim().toLowerCase();
                return this.records.filter(r => {
                    const haystack = [r.voucher_ref, r.description, r.expense_category, r.project].join(' ').toLowerCase();
                    const matchQ = !q || haystack.includes(q);
                    const matchP = !this.projectFilter || r.project === this.projectFilter;
                    const matchS = !this.statusFilter || r.status === this.statusFilter;
                    return matchQ && matchP && matchS;
                });
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredRecords.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, cur = this.page;
                const start = Math.max(1, cur - 2), end = Math.min(total, start + 4);
                const pages = []; for (let p = start; p <= end; p++) pages.push(p); return pages;
            },
            get pageFrom() { return this.filteredRecords.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredRecords.length); },
            get pagedRecords() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const start = (this.page - 1) * this.perPage;
                return this.filteredRecords.slice(start, start + this.perPage);
            },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() { ['search','projectFilter','statusFilter'].forEach(k => this.$watch(k, () => this.page = 1)); },
            async addRecord(form) {
                if (this.saving) return;
                this.saving = true;
                try {
                    const fd = new FormData(form);
                    const res = await fetch(storeUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: fd,
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed to add record.');
                    this.records.unshift(data.record);
                    form.reset();
                    this.$dispatch('close-modal', 'addFunding');
                    this.flash('Funding record added successfully.');
                } catch(e) { this.flash(e.message, 'error'); } finally { this.saving = false; }
            },
            async deleteRecord(r) {
                if (!confirm('Delete this record?')) return;
                if (this.saving) return;
                this.saving = true;
                try {
                    const url = destroyUrl.replace('__ID__', r.id);
                    const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Delete failed.');
                    this.records = this.records.filter(x => x.id !== r.id);
                    this.flash('Funding record deleted.');
                } catch(e) { this.flash(e.message, 'error'); } finally { this.saving = false; }
            },
        };
    };

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
