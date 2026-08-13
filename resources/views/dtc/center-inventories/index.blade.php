<x-app-layout title="DTC Center Inventory">
    <div x-data="{ activeTab: 'list' }">
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
            <a href="{{ route('export.template', 'centers') }}" class="bg-amber-600 hover:bg-amber-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                <i class="fa-solid fa-file-csv mr-1.5"></i> Template
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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Centers</span>
                <i class="fa-solid fa-warehouse text-cyan-600"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalCenters) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Operational</span>
                <i class="fa-solid fa-check-circle text-emerald-600"></i>
            </div>
            <h3 class="text-2xl font-black text-emerald-700">{{ number_format($operationalCenters) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">With Connectivity</span>
                <i class="fa-solid fa-wifi text-blue-600"></i>
            </div>
            <h3 class="text-2xl font-black text-blue-700">{{ number_format($withConnectivity) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
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
                <select name="municipality" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                    <option value="ALL">All Municipalities</option>
                    @foreach($municipalities as $m)
                    <option value="{{ $m }}" {{ request('municipality') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
                <select name="operational" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                    <option value="ALL">All Status</option>
                    <option value="Operational" {{ request('operational') === 'Operational' ? 'selected' : '' }}>Operational</option>
                    <option value="Non-Operational" {{ request('operational') === 'Non-Operational' ? 'selected' : '' }}>Non-Operational</option>
                </select>
                <select name="per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                    <option value="5" {{ (int)request('per_page') === 5 ? 'selected' : '' }}>5 rows</option>
                    <option value="10" {{ (int)request('per_page') === 10 ? 'selected' : '' }}>10 rows</option>
                    <option value="15" {{ (int)request('per_page', 15) === 15 ? 'selected' : '' }}>15 rows</option>
                    <option value="20" {{ (int)request('per_page') === 20 ? 'selected' : '' }}>20 rows</option>
                    <option value="30" {{ (int)request('per_page') === 30 ? 'selected' : '' }}>30 rows</option>
                    <option value="40" {{ (int)request('per_page') === 40 ? 'selected' : '' }}>40 rows</option>
                    <option value="50" {{ (int)request('per_page') === 50 ? 'selected' : '' }}>50 rows</option>
                    <option value="100" {{ (int)request('per_page') === 100 ? 'selected' : '' }}>100 rows</option>
                    <option value="150" {{ (int)request('per_page') === 150 ? 'selected' : '' }}>150 rows</option>
                    <option value="200" {{ (int)request('per_page') === 200 ? 'selected' : '' }}>200 rows</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
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
                    @forelse($centers as $i => $c)
                    <tr :class="selectedIds.includes({{ $c->id }}) ? 'bg-cyan-50/60' : ''">
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">
                            <input type="checkbox" value="{{ $c->id }}" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                        </td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;">{{ $centers->firstItem() + $i }}</td>
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
</x-app-layout>
