{{-- KPI CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Total Foot Traffic</span>
            <i class="fa-solid fa-shoe-prints text-cyan-600"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalTraffic) }}</h3>
        <p class="text-[10px] text-emerald-600 font-semibold">All recorded visits</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Unique Citizens</span>
            <i class="fa-solid fa-users text-blue-600"></i>
        </div>
        <h3 class="text-2xl font-black text-blue-700">{{ number_format($uniqueCitizens) }}</h3>
        <p class="text-[10px] text-slate-400 font-medium">Registered Visitors</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Top Service</span>
            <i class="fa-solid fa-wifi text-emerald-600"></i>
        </div>
        <h3 class="text-lg font-black text-emerald-700 leading-tight">{{ Str::limit($topService, 20) }}</h3>
        <p class="text-[10px] text-emerald-600 font-semibold">{{ $servicesCount[$topService] ?? 0 }} sessions</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Avg Daily Visitors</span>
            <i class="fa-solid fa-user-clock text-amber-600"></i>
        </div>
        <h3 class="text-2xl font-black text-amber-600">{{ $avgDaily }} / day</h3>
        <p class="text-[10px] text-slate-400">Across all hubs</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0 col-span-2 md:col-span-1">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Active Hub Centers</span>
            <i class="fa-solid fa-building-circle-check text-purple-600"></i>
        </div>
        <h3 class="text-2xl font-black text-purple-700">{{ $activeHubs }} Centers</h3>
        <p class="text-[10px] text-slate-400 break-words line-clamp-2">{{ $hubs->pluck('municipality')->implode(', ') }}</p>
    </div>
</div>

{{-- CHARTS --}}
<div x-data="{ year: '{{ date('Y') }}' }" class="space-y-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-cyan-600"></i> DTC Users Foot Traffic & Visitor Trends
                    </h3>
                    <p class="text-xs text-slate-500">Monthly visitor counts and daily average foot traffic across DTC hubs</p>
                </div>
                <select x-model="year" x-on:change="$dispatch('year-change', { year: year })" class="text-xs bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="{{ date('Y') }}">Year {{ date('Y') }} (YTD)</option>
                    <option value="{{ date('Y') - 1 }}">Year {{ date('Y') - 1 }} Historical</option>
                </select>
            </div>
            <div class="h-[clamp(12rem,26vw,18rem)] relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-full px-8 animate-pulse">
                        <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-48 bg-slate-100 rounded-lg"></div>
                    </div>
                </div>
                <canvas id="dtcFootTrafficChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
        <div class="lg:col-span-5 bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pie-chart text-indigo-600"></i> Users Demographic Breakdown
                </h3>
                <p class="text-xs text-slate-500">Categorization of citizens using DTC Hub resources</p>
            </div>
            <div class="h-[clamp(12rem,26vw,18rem)] relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-3/4 animate-pulse">
                        <div class="h-3 bg-slate-200 rounded w-1/3 mx-auto"></div>
                        <div class="h-36 w-36 rounded-full bg-slate-100 mx-auto"></div>
                        <div class="h-2 bg-slate-200 rounded w-1/2 mx-auto"></div>
                        <div class="h-2 bg-slate-200 rounded w-2/3 mx-auto"></div>
                    </div>
                </div>
                <canvas id="dtcDemographicsChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
        <div class="mb-4">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-list-check text-emerald-600"></i> DTC Services Availed Volume & Distribution
            </h3>
            <p class="text-xs text-slate-500">Total sessions logged for each ICT service offered at the centers</p>
        </div>
        <div class="h-[clamp(12rem,24vw,16rem)] relative">
            <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                <div class="space-y-3 w-full px-8 animate-pulse">
                    <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                    <div class="h-48 bg-slate-100 rounded-lg"></div>
                </div>
            </div>
            <canvas id="dtcServicesAvailedChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
        </div>
    </div>
</div>

{{-- VISITOR LOGS TABLE --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-cyan-600"></i> DTC Hub Visitor & Service Availed Register
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive table listing individual citizen visits, demographic classification, and specific services utilized.</p>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-2 p-3 mb-4 bg-slate-50 border border-slate-200 rounded-lg">
        <input type="hidden" name="view" value="services">
        <select name="hub" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
            <option value="ALL">All DTC Hubs</option>
            @foreach($hubs as $hub)
            <option value="{{ $hub->name }}" {{ request('hub') === $hub->name ? 'selected' : '' }}>{{ $hub->name }}</option>
            @endforeach
        </select>
        <select name="demo" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
            <option value="ALL">All Demographics</option>
            @foreach(['Student / Youth', 'Senior Citizen / PWD', 'Jobseeker / Out-of-School Youth', 'MSME / Freelancer', 'LGU / Govt Employee'] as $d)
            <option value="{{ $d }}" {{ request('demo') === $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select name="service" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
            <option value="ALL">All Services</option>
            @foreach(['Free High-Speed Internet', 'eGov PH & Government Portal Access', 'Printing & Document Scanning', 'Co-working & Freelance Space', 'Tech Assistance & Consultation'] as $s)
            <option value="{{ $s }}" {{ request('service') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <input type="text" name="v_search" value="{{ request('v_search') }}" placeholder="Search name, ID, sector..." class="flex-1 min-w-40 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none bg-white">
        <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                <tr>
                    <th class="px-4 py-3">Log ID & Date</th>
                    <th class="px-4 py-3">User Name & Gender/Age</th>
                    <th class="px-4 py-3 hidden md:table-cell">Demographic Sector</th>
                    <th class="px-4 py-3 hidden md:table-cell">DTC Hub Location</th>
                    <th class="px-4 py-3">Services Availed</th>
                    <th class="px-4 py-3 text-center hidden md:table-cell">Duration</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                @forelse($visitors as $v)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3">
                        <span class="font-mono text-[11px] font-bold text-cyan-700">{{ $v->log_code }}</span>
                        <br><span class="text-[10px] text-slate-400">{{ $v->visit_date->format('M d, Y') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold">{{ $v->visitor_name }}</span>
                        <br><span class="text-[10px] text-slate-400">{{ $v->gender }}, {{ $v->age }} yrs</span>
                    </td>
<td class="px-4 py-3 hidden md:table-cell">
                            <span class="bg-slate-100 px-2 py-0.5 rounded text-[10px] font-bold">{{ $v->demographic_sector }}</span>
                        </td>
                    <td class="px-4 py-3 text-[11px] hidden md:table-cell">{{ $v->dtcHub->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @foreach(($v->services_ailed ?? []) as $svc)
                        <span class="bg-cyan-50 text-cyan-700 px-1.5 py-0.5 rounded text-[9px] font-bold inline-block mb-0.5">{{ Str::limit($svc, 18) }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-center text-[11px] font-bold hidden md:table-cell">{{ $v->session_duration }}</td>
                    <td class="px-4 py-3 text-center">
                        <button x-data x-on:click="$dispatch('edit-visitor', { visitor: {{ $v->toJson() }} })" class="text-blue-400 hover:text-blue-600 text-[11px] mr-2" title="Edit"><i class="fa-solid fa-pen"></i></button>
                        <form action="{{ route('dtc.visitors.destroy', $v) }}" method="POST" onsubmit="return confirm('Delete this log?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-[11px]"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-clipboard-list text-3xl mb-2 block"></i>
                        No visitor logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
        <span>Showing {{ $visitors->total() }} visitor logs</span>
        <span class="flex items-center gap-1.5"><span class="inline-block w-2.5 h-2.5 rounded-full bg-cyan-500"></span> Live Sync Active</span>
    </div>
    <div class="mt-2">{{ $visitors->links() }}</div>
</div>
