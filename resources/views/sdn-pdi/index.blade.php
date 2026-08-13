<x-app-layout title="SDN and PDI Tech4ED">
    <div x-data="{ techTab: '{{ $activeTab }}', sdnView: {{ $sdnView ? 'true' : 'false' }} }">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs font-semibold mb-4">
            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 flex items-center gap-1">
                <i class="fa-solid fa-chart-line"></i> Main Overview
            </a>
            <i class="fa-solid fa-chevron-right text-slate-300 text-[10px]"></i>
            <button @click="techTab = 'dashboard'; const u1 = new URL(location.href); u1.searchParams.set('tab', 'dashboard'); history.replaceState(null, '', u1)" class="text-teal-600 hover:text-teal-800 flex items-center gap-1">
                <i class="fa-solid fa-map-location-dot"></i> SDN and PDI Tech4ED
            </button>
        </nav>

        {{-- Tech4ED Sub Navigation Bar --}}
        <div class="flex space-x-2 border-b border-slate-200 pb-2 overflow-x-auto custom-scrollbar mb-6">
            <button @click="techTab = 'dashboard'; const u2 = new URL(location.href); u2.searchParams.set('tab', 'dashboard'); history.replaceState(null, '', u2)"
                :class="techTab === 'dashboard' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-amber-400"></i> Dashboard
            </button>
            <button @click="techTab = 'pdi'; const u3 = new URL(location.href); u3.searchParams.set('tab', 'pdi'); history.replaceState(null, '', u3)"
                :class="techTab === 'pdi' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-building-shield text-indigo-300"></i> PDI Tech4ED
            </button>
        </div>

        {{-- Dashboard Tab --}}
        <div x-show="techTab === 'dashboard'" class="space-y-6"
            x-effect="if (techTab === 'dashboard') { setTimeout(initDashboardCharts, 60); }">

            {{-- Section 1: Tech4ED DTC --}}
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <span class="w-10 h-10 rounded-lg bg-cyan-900/10 text-cyan-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-tower-cell"></i>
                    </span>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Tech4ED DTC</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    @foreach($centersByHostProvince as $province => $counts)
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 mb-2">{{ $province }}</p>
                        <div class="h-[clamp(8rem,14vw,10rem)]"><canvas id="dtc-{{ Str::slug($province) }}"></canvas></div>
                    </div>
                    @endforeach
                </div>
                <div class="overflow-x-auto">
                    <table class="row-hover w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                @foreach($hostTypeLabels as $label => $dbValue)
                                <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">{{ $label }}</th>
                                @endforeach
                                <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total No. of Center Established</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($centersByHostProvince as $province => $counts)
                            <tr>
                                <td class="px-3 py-2 text-slate-700 font-semibold">{{ $province }}</td>
                                @foreach($hostTypeLabels as $label => $dbValue)
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-flex items-center justify-center bg-cyan-100 text-cyan-800 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $counts[$label] ?? 0 }}</span>
                                </td>
                                @endforeach
                                <td class="px-3 py-2 text-center font-black text-cyan-900">{{ array_sum($counts) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-cyan-50">
                                <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                @foreach($hostTypeLabels as $label => $dbValue)
                                <td class="px-3 py-2.5 text-center font-black text-slate-800">{{ collect($centersByHostProvince)->sum(fn ($c) => $c[$label] ?? 0) }}</td>
                                @endforeach
                                <td class="px-3 py-2.5 text-center font-black text-slate-900">{{ collect($centersByHostProvince)->sum(fn ($c) => array_sum($c)) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Section 2: Centers per Municipality (Surigao del Norte) --}}
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <span class="w-10 h-10 rounded-lg bg-cyan-900/10 text-cyan-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-warehouse"></i>
                    </span>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Tech4ED Centers Established per Municipality in Province of Surigao del Norte</h4>
                </div>
                    <div class="h-56 mb-4"><canvas id="sdnMuniPie"></canvas></div>
                    <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar">
                        <table class="row-hover w-full text-xs">
                            <thead class="sticky top-0">
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Municipality</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">No. of Centers</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($sdnMuniCenters as $row)
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-medium">{{ $row->municipality_city }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-cyan-100 text-cyan-800 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $row->total }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-3 py-4 text-center text-slate-400">No centers recorded.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                    <td class="px-3 py-2.5 text-center font-black text-cyan-800">{{ $sdnMuniCenters->sum('total') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                        <span class="w-10 h-10 rounded-lg bg-teal-600/10 text-teal-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-warehouse"></i>
                        </span>
                        <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Tech4ED Centers Established per Municipality in Province of Dinagat Islands</h4>
                    </div>
                    <div class="h-56 mb-4"><canvas id="dinagatMuniPie"></canvas></div>
                    <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar">
                        <table class="row-hover w-full text-xs">
                            <thead class="sticky top-0">
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Municipality</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">No. of Centers</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($dinagatMuniCenters as $row)
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-medium">{{ $row->municipality_city }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-teal-100 text-teal-800 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $row->total }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-3 py-4 text-center text-slate-400">No centers recorded.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-teal-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                    <td class="px-3 py-2.5 text-center font-black text-teal-800">{{ $dinagatMuniCenters->sum('total') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            {{-- Section 4: Operational & Non-Operational per Province --}}
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <span class="w-10 h-10 rounded-lg bg-emerald-600/10 text-emerald-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-heart-circle-check"></i>
                    </span>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Operational and Non-Operational Tech4ED Centers per Province</h4>
                </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2">Operational</p>
                            <div class="h-[clamp(8rem,14vw,10rem)]"><canvas id="op-operational"></canvas></div>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2">Non-Operational</p>
                            <div class="h-[clamp(8rem,14vw,10rem)]"><canvas id="op-non-operational"></canvas></div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="row-hover w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Operational</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Non-Operational</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($operationalByProvince as $province => $statuses)
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-semibold">{{ $province }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-emerald-100 text-emerald-800 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $statuses['Operational'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-red-100 text-red-700 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $statuses['Non-Operational'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center font-bold text-slate-700">{{ array_sum($statuses) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Overall</td>
                                    <td class="px-3 py-2.5 text-center font-black text-emerald-700">{{ collect($operationalByProvince)->sum(fn ($s) => $s['Operational'] ?? 0) }}</td>
                                    <td class="px-3 py-2.5 text-center font-black text-red-700">{{ collect($operationalByProvince)->sum(fn ($s) => $s['Non-Operational'] ?? 0) }}</td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-800">{{ collect($operationalByProvince)->sum(fn ($s) => array_sum($s)) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                        <span class="w-10 h-10 rounded-lg bg-blue-600/10 text-blue-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </span>
                        <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Tech4ED Centers Connectivity Status per Province</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2">Online</p>
                            <div class="h-[clamp(8rem,14vw,10rem)]"><canvas id="conn-online"></canvas></div>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2">Offline</p>
                            <div class="h-[clamp(8rem,14vw,10rem)]"><canvas id="conn-offline"></canvas></div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="row-hover w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Online</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Offline</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($connectivityByProvince as $province => $statuses)
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-semibold">{{ $province }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-blue-100 text-blue-800 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $statuses['Online'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-slate-200 text-slate-700 font-bold rounded-full min-w-7 px-2 py-0.5">{{ $statuses['Offline'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center font-bold text-slate-700">{{ array_sum($statuses) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Overall</td>
                                    <td class="px-3 py-2.5 text-center font-black text-blue-700">{{ collect($connectivityByProvince)->sum(fn ($s) => $s['Online'] ?? 0) }}</td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-700">{{ collect($connectivityByProvince)->sum(fn ($s) => $s['Offline'] ?? 0) }}</td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-800">{{ collect($connectivityByProvince)->sum(fn ($s) => array_sum($s)) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
        </div>

        {{-- PDI Tech4ED Tab --}}
        <div x-show="techTab === 'pdi'" class="space-y-6">

        {{-- SDN Banner Header --}}
        <div class="bg-gradient-to-r from-cyan-900 via-teal-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-cyan-400"></i> SDN Hub Overview
                </h2>
                <p class="text-sm text-cyan-200 mt-1">Municipality-level visitor analytics, hub distribution, and center inventory for Surigao del Norte.</p>
            </div>
            <div class="flex gap-2">
                <button @click="sdnView = !sdnView"
                    :class="sdnView ? 'bg-amber-500 hover:bg-amber-400 text-slate-900' : 'bg-white/10 hover:bg-white/20 border border-white/30 text-white'"
                    class="px-3.5 py-2 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow transition">
                    <i class="fa-solid" :class="sdnView ? 'fa-eye' : 'fa-gears'"></i>
                    <span x-text="sdnView ? 'View Overview' : 'Manage Centers'"></span>
                </button>
            </div>
        </div>

        {{-- SDN Overview View --}}
        <div x-show="!sdnView">

            {{-- SDN Metric KPI Cards --}}
            @php
                $metricOpActive = in_array(request('operational'), ['Operational', 'Non-Operational'], true);
                $metricConnActive = in_array(request('connectivity'), ['Online', 'Connected', 'Offline'], true);
                $metricMuniActive = request('muni', 'ALL') !== 'ALL';
                $metricDistrictActive = request('district', 'ALL') !== 'ALL';
                $metricTotalActive = !$metricOpActive && !$metricConnActive && !$metricMuniActive && !$metricDistrictActive;

                $metricAllUrl = request()->fullUrlWithQuery([
                    'tab' => 'pdi', 'sdn_view' => '', 'muni' => 'ALL', 'district' => 'ALL', 'operational' => 'ALL',
                    'connectivity' => 'ALL', 'search' => '', 'center_page' => 1,
                ]);
                $metricOpUrl = request()->fullUrlWithQuery(['tab' => 'pdi', 'sdn_view' => '', 'operational' => $metricOpActive ? 'ALL' : 'Operational', 'center_page' => 1]);
                $metricConnUrl = request()->fullUrlWithQuery(['tab' => 'pdi', 'sdn_view' => '', 'connectivity' => $metricConnActive ? 'ALL' : 'Online', 'center_page' => 1]);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <a href="{{ $metricAllUrl }}"
                   title="Show all centers"
                   class="p-4 rounded-xl border {{ $metricTotalActive ? 'bg-gradient-to-br from-cyan-700 via-cyan-800 to-dict-blue text-white border-cyan-900 shadow-lg ring-4 ring-cyan-400/50 scale-[1.02]' : 'bg-white border-slate-200 hover:border-cyan-300 hover:shadow-md' }} shadow-sm transition-all duration-300 block cursor-pointer">
                    <div class="flex items-center justify-between {{ $metricTotalActive ? 'text-cyan-100' : 'text-slate-500' }} mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Total Centers</span>
                        <i class="fa-solid fa-warehouse {{ $metricTotalActive ? 'text-amber-300' : 'text-cyan-600' }}"></i>
                    </div>
                    <h3 class="text-2xl font-black {{ $metricTotalActive ? 'text-white' : 'text-slate-800' }}">{{ number_format($totalCenterCount) }}</h3>
                    <span class="text-[10px] font-semibold {{ $metricTotalActive ? 'text-cyan-200' : 'text-slate-400' }}">{{ $metricTotalActive ? 'Showing all centers' : 'Click to show all centers' }}</span>
                </a>
                <a href="{{ $metricOpUrl }}"
                   title="Filter to operational centers only"
                   class="p-4 rounded-xl border {{ $metricOpActive ? 'bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 text-white border-emerald-900 shadow-lg ring-4 ring-emerald-400/50 scale-[1.02]' : 'bg-white border-slate-200 hover:border-emerald-300 hover:shadow-md' }} shadow-sm transition-all duration-300 block cursor-pointer">
                    <div class="flex items-center justify-between {{ $metricOpActive ? 'text-emerald-100' : 'text-slate-500' }} mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Operational</span>
                        <i class="fa-solid fa-check-circle {{ $metricOpActive ? 'text-emerald-200' : 'text-emerald-600' }}"></i>
                    </div>
                    <h3 class="text-2xl font-black {{ $metricOpActive ? 'text-white' : 'text-emerald-700' }}">{{ number_format($operationalCenters) }}</h3>
                    <span class="text-[10px] font-semibold {{ $metricOpActive ? 'text-emerald-100' : 'text-slate-400' }}">{{ $metricOpActive ? 'Filter active' : 'Click to filter operational' }}</span>
                </a>
                <a href="{{ $metricConnUrl }}"
                   title="Filter to centers with connectivity only"
                   class="p-4 rounded-xl border {{ $metricConnActive ? 'bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white border-blue-900 shadow-lg ring-4 ring-blue-400/50 scale-[1.02]' : 'bg-white border-slate-200 hover:border-blue-300 hover:shadow-md' }} shadow-sm transition-all duration-300 block cursor-pointer">
                    <div class="flex items-center justify-between {{ $metricConnActive ? 'text-blue-100' : 'text-slate-500' }} mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">With Connectivity</span>
                        <i class="fa-solid fa-wifi {{ $metricConnActive ? 'text-blue-200' : 'text-blue-600' }}"></i>
                    </div>
                    <h3 class="text-2xl font-black {{ $metricConnActive ? 'text-white' : 'text-blue-700' }}">{{ number_format($withConnectivity) }}</h3>
                    <span class="text-[10px] font-semibold {{ $metricConnActive ? 'text-blue-100' : 'text-slate-400' }}">{{ $metricConnActive ? 'Filter active' : 'Click to filter with connectivity' }}</span>
                </a>
                <div x-data="{ open: false }"
                     title="Filter by municipality"
                     class="relative p-4 rounded-xl border {{ $metricMuniActive ? 'bg-gradient-to-br from-purple-600 via-purple-700 to-fuchsia-800 text-white border-purple-900 shadow-lg ring-4 ring-purple-400/50 scale-[1.02]' : 'bg-white border-slate-200 hover:border-purple-300 hover:shadow-md' }} shadow-sm transition-all duration-300">
                    <button type="button" @click="open = !open" @keydown.escape="open = false"
                            class="w-full text-left block cursor-pointer outline-none">
                        <div class="flex items-center justify-between {{ $metricMuniActive ? 'text-purple-100' : 'text-slate-500' }} mb-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider">Municipalities</span>
                            <i class="fa-solid fa-city {{ $metricMuniActive ? 'text-purple-200' : 'text-purple-600' }}"></i>
                        </div>
                        <h3 class="text-2xl font-black {{ $metricMuniActive ? 'text-white' : 'text-purple-700' }}">{{ number_format($centerMunicipalities) }}</h3>
                        <span class="text-[10px] font-semibold {{ $metricMuniActive ? 'text-purple-100' : 'text-slate-400' }}">{{ $metricMuniActive ? 'Municipality filter active' : 'Click to pick a municipality' }}</span>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute z-20 left-0 right-0 mt-2 max-h-64 overflow-y-auto custom-scrollbar bg-white rounded-xl border border-slate-200 shadow-lg p-1.5">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'pdi', 'sdn_view' => '', 'muni' => 'ALL', 'center_page' => 1]) }}"
                           class="block px-3 py-2 rounded-lg text-xs font-semibold {{ $metricMuniActive ? 'text-purple-700 bg-purple-50' : 'text-slate-700 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-globe mr-1.5"></i> All Municipalities
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        @foreach($municipalities as $m)
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'pdi', 'sdn_view' => '', 'muni' => $m, 'center_page' => 1]) }}"
                           class="block px-3 py-2 rounded-lg text-xs {{ $selectedMuni === $m ? 'font-bold text-purple-700 bg-purple-50' : 'text-slate-600 hover:bg-slate-100' }}">
                            {{ $m }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SDN District KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                @foreach($districtStats as $district => $ds)
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'pdi', 'sdn_view' => '', 'district' => $selectedDistrict === $district ? 'ALL' : $district, 'center_page' => 1]) }}" 
                   class="p-4 rounded-xl border {{ $selectedDistrict === $district ? 'bg-amber-50/40 border-amber-500 ring-4 ring-amber-500/20 shadow-md transform scale-[1.02]' : 'bg-white border-slate-200 hover:border-amber-300 hover:shadow-md' }} shadow-sm transition-all duration-300 block cursor-pointer">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">{{ $district }}</span>
                        <i class="fa-solid fa-landmark text-amber-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">{{ number_format($ds['center_count']) }}</h3>
                    <p class="text-[10px] text-slate-400 font-medium">{{ $ds['municipality_count'] }} municipalities &bull; {{ $ds['center_count'] }} Tech4ED centers</p>
                    <p class="text-[10px] font-semibold text-amber-700 mt-1">{{ $ds['description'] }}</p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($ds['municipalities'] as $muni)
                        <span class="text-[10px] px-2 py-0.5 {{ $selectedDistrict === $district ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} rounded-full border">{{ $muni }}</span>
                        @endforeach
                    </div>
                </a>
                @endforeach
            </div>

            {{-- SDN Filter --}}
            <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
                <input type="hidden" name="tab" :value="techTab">
                <input type="hidden" name="sdn_view" :value="sdnView ? '1' : ''">
                @if(request()->has('district'))
                <input type="hidden" name="district" value="{{ request('district') }}">
                @endif
                @if(request()->filled('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                @if(request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="flex items-center gap-3 flex-wrap">
                    <i class="fa-solid fa-filter text-teal-500 text-sm"></i>
                    <select name="muni" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500">
                        <option value="ALL">All Municipalities</option>
                        @foreach($municipalities as $m)
                        <option value="{{ $m }}" {{ $selectedMuni === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    <select name="operational" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500">
                        <option value="ALL">All Status</option>
                        <option value="Operational" {{ request('operational') === 'Operational' ? 'selected' : '' }}>Operational</option>
                        <option value="Non-Operational" {{ request('operational') === 'Non-Operational' ? 'selected' : '' }}>Non-Operational</option>
                    </select>
                    <select name="connectivity" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500">
                        <option value="ALL">All Connectivity</option>
                        <option value="Online" {{ request('connectivity') === 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Offline" {{ request('connectivity') === 'Offline' ? 'selected' : '' }}>Offline</option>
                    </select>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                        <i class="fa-solid fa-magnifying-glass"></i> Filter
                    </button>
                </div>
            </form>

            {{-- SDN Center Inventory --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-teal-600"></i> DTC Center Inventory
                    </h3>
                    <div class="flex items-center gap-3 flex-wrap">
                        <form method="GET" class="flex items-center gap-2 flex-wrap">
                            <input type="hidden" name="tab" :value="techTab">
                            <input type="hidden" name="sdn_view" :value="sdnView ? '1' : ''">
                            @if(request()->has('district'))
                            <input type="hidden" name="district" value="{{ request('district') }}">
                            @endif
                            @if(request()->filled('muni'))
                            <input type="hidden" name="muni" value="{{ request('muni') }}">
                            @endif
                            @if(request()->filled('operational'))
                            <input type="hidden" name="operational" value="{{ request('operational') }}">
                            @endif
                            @if(request()->filled('connectivity'))
                            <input type="hidden" name="connectivity" value="{{ request('connectivity') }}">
                            @endif
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                            <select name="per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500" title="Rows per page">
                                <option value="5" {{ (int)request('per_page') === 5 ? 'selected' : '' }}>5 rows</option>
                                <option value="10" {{ (int)request('per_page', 10) === 10 ? 'selected' : '' }}>10 rows</option>
                                <option value="20" {{ (int)request('per_page') === 20 ? 'selected' : '' }}>20 rows</option>
                                <option value="30" {{ (int)request('per_page') === 30 ? 'selected' : '' }}>30 rows</option>
                                <option value="40" {{ (int)request('per_page') === 40 ? 'selected' : '' }}>40 rows</option>
                                <option value="50" {{ (int)request('per_page') === 50 ? 'selected' : '' }}>50 rows</option>
                                <option value="100" {{ (int)request('per_page') === 100 ? 'selected' : '' }}>100 rows</option>
                                <option value="150" {{ (int)request('per_page') === 150 ? 'selected' : '' }}>150 rows</option>
                                <option value="200" {{ (int)request('per_page') === 200 ? 'selected' : '' }}>200 rows</option>
                            </select>
                            <button type="submit" class="bg-teal-700 hover:bg-teal-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="row-hover w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
                        <thead>
                            <tr style="background-color: #9DC3E6;">
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">No.</th>
                                <th colspan="5" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">CENTER DETAILS</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">GPS Coordinates</th>
                                <th colspan="4" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Date Established</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TCMS</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">ODK<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Connectivity<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TYPE OF<br>CENTER HOST</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Operational<br>Status</th>
                            </tr>
                            <tr style="background-color: #9DC3E6;">
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Congressional<br>District</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Province</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Municipality/<br>City</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Barangay</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Center Name</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Longitude<br>(e.g:<br>120.605522)</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Latitude<br>(e.g.:<br>16.575633)</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Verified</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">MOA Date of<br>Signing</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Date of<br>Launching</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Date of<br>Platform<br>Registration</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Status</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Key</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Identifier</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sdnCenters as $c)
                            <tr>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;">{{ $loop->iteration }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->congressional_district ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->province ?? 'Surigao del Norte' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->municipality_city }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->barangay ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;">{{ $c->center_name }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->longitude ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->latitude ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->verified ? '✓' : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->moa_date_of_signing ? $c->moa_date_of_signing->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->date_of_launching ? $c->date_of_launching->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->date_of_platform_registration ? $c->date_of_platform_registration->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_key ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_identifier ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_verification_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->odk_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->connectivity_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->type_of_center_host ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->operational_status ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="21" style="border: 1px solid #000; text-align: center; padding: 20px; color: #999;">
                                    <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                    No centers registered yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $sdnCenters->links() }}</div>
            </div>
        </div>

        {{-- SDN Management View --}}
        <div x-show="sdnView" x-cloak>
            <div x-data="{
                selectedIds: [],
                allIds: [{{ implode(',', $sdnCenters->pluck('id')->toArray()) }}],
                toggleSelectAll() {
                    if (this.selectedIds.length === this.allIds.length && this.allIds.length > 0) {
                        this.selectedIds = [];
                    } else {
                        this.selectedIds = [...this.allIds];
                    }
                }
            }" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-list text-cyan-600"></i> Center Inventory Registry
                        </h3>
                        <form method="POST" action="{{ route('dtc.centers.batchDelete') }}" x-show="selectedIds.length > 0" x-cloak x-on:submit="if (!confirm('Are you sure you want to delete ' + selectedIds.length + ' selected center(s)? This action cannot be undone.')) $event.preventDefault()" class="inline-flex items-center">
                            @csrf
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow transition">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Delete Selected (<span x-text="selectedIds.length"></span>)</span>
                            </button>
                        </form>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <form method="GET" class="flex items-center gap-2 flex-wrap">
                            <input type="hidden" name="tab" value="pdi">
                            <input type="hidden" name="sdn_view" value="1">
                            @if(request()->filled('muni'))
                            <input type="hidden" name="muni" value="{{ request('muni') }}">
                            @endif
                            @if(request()->filled('operational'))
                            <input type="hidden" name="operational" value="{{ request('operational') }}">
                            @endif
                            @if(request()->filled('connectivity'))
                            <input type="hidden" name="connectivity" value="{{ request('connectivity') }}">
                            @endif
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                            <select name="per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                                <option value="5" {{ (int)request('per_page') === 5 ? 'selected' : '' }}>5 rows</option>
                                <option value="10" {{ (int)request('per_page', 10) === 10 ? 'selected' : '' }}>10 rows</option>
                                <option value="20" {{ (int)request('per_page') === 20 ? 'selected' : '' }}>20 rows</option>
                                <option value="30" {{ (int)request('per_page') === 30 ? 'selected' : '' }}>30 rows</option>
                                <option value="40" {{ (int)request('per_page') === 40 ? 'selected' : '' }}>40 rows</option>
                                <option value="50" {{ (int)request('per_page') === 50 ? 'selected' : '' }}>50 rows</option>
                                <option value="100" {{ (int)request('per_page') === 100 ? 'selected' : '' }}>100 rows</option>
                                <option value="150" {{ (int)request('per_page') === 150 ? 'selected' : '' }}>150 rows</option>
                                <option value="200" {{ (int)request('per_page') === 200 ? 'selected' : '' }}>200 rows</option>
                            </select>
                            <button type="submit" class="bg-teal-700 hover:bg-teal-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                        <a href="{{ route('export.xlsx', 'centers') }}" class="bg-green-700 hover:bg-green-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                            <i class="fa-solid fa-download mr-1.5"></i> Export
                        </a>
                        <button x-data x-on:click="$dispatch('open-import-center')" class="bg-blue-700 hover:bg-blue-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                            <i class="fa-solid fa-upload mr-1.5"></i> Import
                        </button>
                        <button x-data x-on:click="$dispatch('open-add-center')" class="bg-cyan-600 hover:bg-cyan-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                            <i class="fa-solid fa-plus mr-1.5"></i> Add Center
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
                        <thead>
                            <tr style="background-color: #9DC3E6;">
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">
                                    <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === allIds.length" x-on:change="toggleSelectAll()" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer" title="Select All On This Page">
                                </th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">No.</th>
                                <th colspan="5" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">CENTER DETAILS</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">GPS Coordinates</th>
                                <th colspan="4" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Date Established</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TCMS</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">ODK<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Connectivity<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TYPE OF<br>CENTER HOST</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Operational<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Action</th>
                            </tr>
                            <tr style="background-color: #9DC3E6;">
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Congressional<br>District</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Province</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Municipality/<br>City</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Barangay</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Center Name</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Longitude<br>(e.g:<br>120.605522)</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Latitude<br>(e.g.:<br>16.575633)</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Verified</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">MOA Date of<br>Signing</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Date of<br>Launching</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Date of<br>Platform<br>Registration</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Status</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Key</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Identifier</th>
                                <th style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sdnCenters as $i => $c)
                            <tr :class="selectedIds.includes({{ $c->id }}) ? 'bg-cyan-50/60' : ''">
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">
                                    <input type="checkbox" value="{{ $c->id }}" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                                </td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;">{{ $sdnCenters->firstItem() + $i }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->congressional_district ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->province ?? 'Surigao del Norte' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->municipality_city }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->barangay ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;">{{ $c->center_name }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->longitude ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->latitude ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->verified ? 'True' : 'False' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->moa_date_of_signing ? $c->moa_date_of_signing->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->date_of_launching ? $c->date_of_launching->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->date_of_platform_registration ? $c->date_of_platform_registration->format('M d, Y') : '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_key ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_identifier ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->tcms_verification_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->odk_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->connectivity_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->type_of_center_host ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">{{ $c->operational_status ?? '—' }}</td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; white-space: nowrap;">
                                    <button x-data x-on:click="$dispatch('edit-center', { center: {{ json_encode([
                                        'id' => $c->id,
                                        'congressional_district' => $c->congressional_district,
                                        'province' => $c->province,
                                        'municipality_city' => $c->municipality_city,
                                        'barangay' => $c->barangay,
                                        'center_name' => $c->center_name,
                                        'longitude' => $c->longitude,
                                        'latitude' => $c->latitude,
                                        'verified' => $c->verified,
                                        'moa_date_of_signing' => $c->moa_date_of_signing?->format('Y-m-d'),
                                        'date_of_launching' => $c->date_of_launching?->format('Y-m-d'),
                                        'date_of_platform_registration' => $c->date_of_platform_registration?->format('Y-m-d'),
                                        'tcms_status' => $c->tcms_status,
                                        'tcms_key' => $c->tcms_key,
                                        'tcms_identifier' => $c->tcms_identifier,
                                        'tcms_verification_status' => $c->tcms_verification_status,
                                        'odk_status' => $c->odk_status,
                                        'connectivity_status' => $c->connectivity_status,
                                        'type_of_center_host' => $c->type_of_center_host,
                                        'operational_status' => $c->operational_status,
                                    ]) }} })" class="text-blue-600 hover:text-blue-800" title="Edit" style="background:none;border:none;cursor:pointer;font-size:12px;">✏️</button>
                                    <form action="{{ route('dtc.centers.destroy', $c) }}" method="POST" onsubmit="return confirm('Delete this center?')" class="inline" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete" style="background:none;border:none;cursor:pointer;font-size:12px;">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="22" style="border: 1px solid #000; text-align: center; padding: 20px; color: #999;">
                                    <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                    No centers found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                    <span>Showing {{ $sdnCenters->total() }} centers</span>
                </div>
                <div class="mt-2">{{ $sdnCenters->links() }}</div>
            </div>
        </div>


        </div>



    </div>

    {{-- ADD CENTER MODAL --}}
    <div x-data="{ show: false }" x-on:open-add-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-plus text-cyan-400"></i> Add DTC Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('dtc.centers.store') }}" method="POST" class="flex flex-col" style="max-height: 80vh;">
                @csrf
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Center Name <span class="text-red-500">*</span></label>
                            <input type="text" name="center_name" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Congressional District</label>
                            <input type="text" name="congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Province</label>
                            <input type="text" name="province" value="Surigao del Norte" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Municipality/City <span class="text-red-500">*</span></label>
                            <input type="text" name="municipality_city" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Barangay</label>
                            <input type="text" name="barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 font-semibold text-slate-700">
                                <input type="checkbox" name="verified" value="1" class="rounded text-cyan-600 focus:ring-cyan-500">
                                Verified
                            </label>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">MOA Date of Signing</label>
                            <input type="date" name="moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Launching</label>
                            <input type="date" name="date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Platform Registration</label>
                            <input type="date" name="date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status</label>
                            <input type="text" name="tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Key</label>
                            <input type="text" name="tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Identifier</label>
                            <input type="text" name="tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Verification Status</label>
                            <input type="text" name="tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">ODK Status</label>
                            <select name="odk_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Pending">Pending</option>
                                <option value="Not Started">Not Started</option>
                                <option value="TRUE">TRUE</option>
                                <option value="FALSE">FALSE</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Connectivity Status</label>
                            <select name="connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Connected">Connected</option>
                                <option value="Disconnected">Disconnected</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Type of Center Host</label>
                            <input type="text" name="type_of_center_host" placeholder="e.g. LGU, DICT, DepEd" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Operational Status</label>
                            <select name="operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Operational">Operational</option>
                                <option value="Non-Operational">Non-Operational</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 pb-6 pt-4 border-t border-slate-200 bg-white">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow"><i class="fa-solid fa-check mr-1"></i> Add Center</button>
                </div>
            </form>
        </div>
    </div>

    {{-- IMPORT MODAL --}}
    <div x-data="{ show: false }" x-on:open-import-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-blue-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-upload text-blue-400"></i> Import Centers</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('dtc.centers.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-blue-800">
                    <p class="font-semibold mb-1">Accepted formats: <strong>CSV, XLSX</strong></p>
                    <p class="text-blue-600">Download the template first to ensure correct column headers. Required columns: <strong>Municipality/City</strong> and <strong>Center Name</strong>.</p>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-2">Select File</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow"><i class="fa-solid fa-upload mr-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT CENTER MODAL --}}
    <div x-data="{ show: false, center: {} }" x-on:edit-center.window="show = true; center = $event.detail.center" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen text-cyan-400"></i> Edit Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form method="POST" class="flex flex-col" :action="'{{ url('dtc/centers') }}/' + (center?.id || '')" style="max-height: 80vh;">
                @csrf @method('PUT')
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Center Name <span class="text-red-500">*</span></label>
                            <input type="text" name="center_name" required x-model="center.center_name" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Congressional District</label>
                            <input type="text" name="congressional_district" x-model="center.congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Province</label>
                            <input type="text" name="province" x-model="center.province" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Municipality/City <span class="text-red-500">*</span></label>
                            <input type="text" name="municipality_city" required x-model="center.municipality_city" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Barangay</label>
                            <input type="text" name="barangay" x-model="center.barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" step="any" x-model="center.longitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" step="any" x-model="center.latitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 font-semibold text-slate-700">
                                <input type="checkbox" name="verified" value="1" x-bind:checked="center.verified == 1 || center.verified === true" class="rounded text-cyan-600 focus:ring-cyan-500">
                                Verified
                            </label>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">MOA Date of Signing</label>
                            <input type="date" name="moa_date_of_signing" x-model="center.moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Launching</label>
                            <input type="date" name="date_of_launching" x-model="center.date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Platform Registration</label>
                            <input type="date" name="date_of_platform_registration" x-model="center.date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status</label>
                            <input type="text" name="tcms_status" x-model="center.tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Key</label>
                            <input type="text" name="tcms_key" x-model="center.tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Identifier</label>
                            <input type="text" name="tcms_identifier" x-model="center.tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Verification Status</label>
                            <input type="text" name="tcms_verification_status" x-model="center.tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">ODK Status</label>
                            <select name="odk_status" x-model="center.odk_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Pending">Pending</option>
                                <option value="Not Started">Not Started</option>
                                <option value="TRUE">TRUE</option>
                                <option value="FALSE">FALSE</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Connectivity Status</label>
                            <select name="connectivity_status" x-model="center.connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Connected">Connected</option>
                                <option value="Disconnected">Disconnected</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Type of Center Host</label>
                            <input type="text" name="type_of_center_host" x-model="center.type_of_center_host" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Operational Status</label>
                            <select name="operational_status" x-model="center.operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Operational">Operational</option>
                                <option value="Non-Operational">Non-Operational</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 pb-6 pt-4 border-t border-slate-200 bg-white">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow"><i class="fa-solid fa-save mr-1"></i> Update Center</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        window.dashboardChartInstances = window.dashboardChartInstances || {};

        window.initDashboardCharts = function () {
            if (typeof Chart === 'undefined') {
                setTimeout(window.initDashboardCharts, 250);
                return;
            }

            var PALETTE = ['#0e7490', '#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#db2777', '#0891b2', '#65a30d', '#9333ea', '#ea580c', '#0d9488', '#4f46e5', '#c026d3', '#16a34a', '#f59e0b', '#ef4444', '#0ea5e9', '#84cc16', '#f43f5e', '#78716c'];

            var sdnMuni = @json($sdnMuniCenters);
            var dinagatMuni = @json($dinagatMuniCenters);
            var opByProvince = @json($operationalByProvince);
            var connByProvince = @json($connectivityByProvince);
            var dtcByProvince = @json($centersByHostProvince);
            var dtcHostLabels = Object.keys(@json($hostTypeLabels));

            function slugify(text) {
                return String(text).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            }

            function buildPie(id, labels, values, colors, title) {
                var el = document.getElementById(id);
                if (!el) return;
                if (window.dashboardChartInstances[id]) {
                    window.dashboardChartInstances[id].destroy();
                }
                var options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: title ? { display: true, text: title, font: { size: 12, weight: 'bold' }, color: '#334155' } : {},
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 }, padding: 8 } },
                        tooltip: { callbacks: { label: function (ctx) { return ' ' + ctx.label + ': ' + ctx.parsed + ' centers'; } } }
                    }
                };
                window.dashboardChartInstances[id] = new Chart(el, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors
                        }]
                    },
                    options: options
                });
            }

            var sdnLabels = sdnMuni.map(function (r) { return r.municipality_city; });
            var sdnValues = sdnMuni.map(function (r) { return r.total; });
            var totalSdn = sdnValues.reduce(function(acc, val) { return acc + val; }, 0);
            
            sdnLabels.push('Total Centers');
            sdnValues.push(totalSdn);
            
            buildPie('sdnMuniPie', sdnLabels, sdnValues, PALETTE, 'Surigao del Norte');

            buildPie('dinagatMuniPie', dinagatMuni.map(function (r) { return r.municipality_city; }), dinagatMuni.map(function (r) { return r.total; }), PALETTE, 'Dinagat Islands');

            Object.keys(dtcByProvince).forEach(function (province) {
                var counts = dtcByProvince[province] || {};
                var labels = dtcHostLabels;
                var values = labels.map(function (l) { return counts[l] || 0; });
                var colors = labels.map(function (_, i) { return PALETTE[i % PALETTE.length]; });
                buildPie('dtc-' + slugify(province), labels, values, colors, province);
            });

            var sdnOp = opByProvince['Surigao del Norte'] || {};
            var dinOp = opByProvince['Dinagat Islands'] || {};
            buildPie('op-operational', ['Surigao del Norte', 'Dinagat Islands'], [sdnOp['Operational'] || 0, dinOp['Operational'] || 0], [PALETTE[0], PALETTE[1]], 'Operational');
            buildPie('op-non-operational', ['Surigao del Norte', 'Dinagat Islands'], [sdnOp['Non-Operational'] || 0, dinOp['Non-Operational'] || 0], [PALETTE[5], PALETTE[16]], 'Non-Operational');

            var sdnConn = connByProvince['Surigao del Norte'] || {};
            var dinConn = connByProvince['Dinagat Islands'] || {};
            buildPie('conn-online', ['Surigao del Norte', 'Dinagat Islands'], [sdnConn['Online'] || 0, dinConn['Online'] || 0], [PALETTE[1], PALETTE[17]], 'Online');
            buildPie('conn-offline', ['Surigao del Norte', 'Dinagat Islands'], [sdnConn['Offline'] || 0, dinConn['Offline'] || 0], [PALETTE[10], PALETTE[4]], 'Offline');
        };
    </script>
    @endpush
</x-app-layout>
