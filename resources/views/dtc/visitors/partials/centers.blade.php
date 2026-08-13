<div class="bg-gradient-to-r from-cyan-900 via-teal-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-warehouse text-cyan-400"></i> DTC Center Inventory
        </h2>
        <p class="text-sm text-cyan-200 mt-1">Comprehensive registry of all DTC, Tech4ED, and partner centers across Surigao del Norte.</p>
    </div>
    <div class="flex gap-2">
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

{{-- KPI STRIPS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Total Centers</span>
            <i class="fa-solid fa-warehouse text-cyan-600"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalCenters) }}</h3>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Operational</span>
            <i class="fa-solid fa-check-circle text-emerald-600"></i>
        </div>
        <h3 class="text-2xl font-black text-emerald-700">{{ number_format($operationalCenters) }}</h3>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">With Connectivity</span>
            <i class="fa-solid fa-wifi text-blue-600"></i>
        </div>
        <h3 class="text-2xl font-black text-blue-700">{{ number_format($withConnectivity) }}</h3>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm min-w-0">
        <div class="flex items-center justify-between text-slate-500 mb-1">
            <span class="text-[11px] font-bold uppercase tracking-wider">Municipalities</span>
            <i class="fa-solid fa-city text-purple-600"></i>
        </div>
        <h3 class="text-2xl font-black text-purple-700">{{ $municipalities->count() }}</h3>
    </div>
</div>

{{-- TABLE SECTION --}}
<div x-data="{
    selectedIds: [],
    allIds: [{{ implode(',', $centers->pluck('id')->toArray()) }}],
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
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="hidden" name="view" value="centers">
            <select name="municipality" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                <option value="ALL">All Municipalities</option>
                @foreach($municipalities as $m)
                <option value="{{ $m }}" {{ request('municipality') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
            <select name="c_operational" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                <option value="ALL">All Status</option>
                <option value="Operational" {{ request('c_operational') === 'Operational' ? 'selected' : '' }}>Operational</option>
                <option value="Non-Operational" {{ request('c_operational') === 'Non-Operational' ? 'selected' : '' }}>Non-Operational</option>
            </select>
            <select name="c_per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                <option value="5" {{ (int)request('c_per_page') === 5 ? 'selected' : '' }}>5 rows</option>
                <option value="10" {{ (int)request('c_per_page') === 10 ? 'selected' : '' }}>10 rows</option>
                <option value="15" {{ (int)request('c_per_page', 15) === 15 ? 'selected' : '' }}>15 rows</option>
                <option value="20" {{ (int)request('c_per_page') === 20 ? 'selected' : '' }}>20 rows</option>
                <option value="30" {{ (int)request('c_per_page') === 30 ? 'selected' : '' }}>30 rows</option>
                <option value="40" {{ (int)request('c_per_page') === 40 ? 'selected' : '' }}>40 rows</option>
                <option value="50" {{ (int)request('c_per_page') === 50 ? 'selected' : '' }}>50 rows</option>
                <option value="100" {{ (int)request('c_per_page') === 100 ? 'selected' : '' }}>100 rows</option>
                <option value="150" {{ (int)request('c_per_page') === 150 ? 'selected' : '' }}>150 rows</option>
                <option value="200" {{ (int)request('c_per_page') === 200 ? 'selected' : '' }}>200 rows</option>
            </select>
            <input type="text" name="c_search" value="{{ request('c_search') }}" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
            <thead>
                <tr style="background-color: #9DC3E6;">
                    <th rowspan="2" class="sticky-col-head" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6; position: sticky; left: 0; z-index: 3; width: 36px;">
                        <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === allIds.length" x-on:change="toggleSelectAll()" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer" title="Select All On This Page">
                    </th>
                    <th rowspan="2" class="sticky-col-head" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; position: sticky; left: 36px; z-index: 3; width: 48px;">No.</th>
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
                @forelse($centers as $i => $c)
                <tr :class="selectedIds.includes({{ $c->id }}) ? 'bg-cyan-50/60' : ''">
                    <td class="sticky-col" :class="{ sel: selectedIds.includes({{ $c->id }}) }" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; position: sticky; left: 0; z-index: 2; width: 36px;">
                        <input type="checkbox" value="{{ $c->id }}" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                    </td>
                    <td class="sticky-col" :class="{ sel: selectedIds.includes({{ $c->id }}) }" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px; position: sticky; left: 36px; z-index: 2; width: 48px;">{{ $centers->firstItem() + $i }}</td>
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
        <span>Showing {{ $centers->total() }} centers</span>
    </div>
    <div class="mt-2">{{ $centers->links() }}</div>
</div>
