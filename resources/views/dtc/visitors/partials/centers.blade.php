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
    allCenters: @json($centers->items()),
    selectedIds: [],
    search: '{{ request('c_search', '') }}',
    filterMunicipality: '{{ request('municipality', 'ALL') }}',
    filterOperational: '{{ request('c_operational', 'ALL') }}',
    perPage: {{ (int) request('c_per_page', 15) }},
    currentPage: 1,
    deleting: null,
    deletingBatch: false,

    get filtered() {
        let items = [...this.allCenters];
        if (this.filterMunicipality !== 'ALL') items = items.filter(c => c.municipality_city === this.filterMunicipality);
        if (this.filterOperational !== 'ALL') items = items.filter(c => c.operational_status === this.filterOperational);
        if (this.search) {
            const q = this.search.toLowerCase();
            items = items.filter(c =>
                (c.center_name || '').toLowerCase().includes(q) ||
                (c.municipality_city || '').toLowerCase().includes(q) ||
                (c.barangay || '').toLowerCase().includes(q) ||
                (c.congressional_district || '').toLowerCase().includes(q)
            );
        }
        return items;
    },

    get paginated() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filtered.slice(start, start + this.perPage);
    },

    get totalPages() {
        return Math.ceil(this.filtered.length / this.perPage) || 1;
    },

    prevPage() { if (this.currentPage > 1) this.currentPage--; },
    nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
    goToPage(p) { this.currentPage = Math.max(1, Math.min(p, this.totalPages)); },

    toggleSelectAll() {
        const pageIds = this.paginated.map(c => c.id);
        if (this.selectedIds.length === pageIds.length && pageIds.length > 0) {
            this.selectedIds = [];
        } else {
            this.selectedIds = [...pageIds];
        }
    },

    formatDate(d) {
        if (!d) return '—';
        const dt = new Date(d);
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[dt.getMonth()] + ' ' + dt.getDate() + ', ' + dt.getFullYear();
    },

    formatYmd(d) {
        if (!d) return '';
        return new Date(d).toISOString().split('T')[0];
    },

    async deleteCenter(id) {
        if (!confirm('Delete this center?')) return;
        this.deleting = id;
        try {
            const res = await fetch('{{ url('dtc/centers') }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                this.allCenters = this.allCenters.filter(c => c.id !== id);
                this.selectedIds = this.selectedIds.filter(sid => sid !== id);
            }
        } catch(err) { console.error(err); }
        this.deleting = null;
    },

    async deleteSelected() {
        if (!this.selectedIds.length) return;
        if (!confirm('Are you sure you want to delete ' + this.selectedIds.length + ' selected center(s)? This action cannot be undone.')) return;
        this.deletingBatch = true;
        try {
            const res = await fetch('{{ route('dtc.centers.batchDelete') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: this.selectedIds })
            });
            if (res.ok) {
                this.allCenters = this.allCenters.filter(c => !this.selectedIds.includes(c.id));
                this.selectedIds = [];
            }
        } catch(err) { console.error(err); }
        this.deletingBatch = false;
    }
}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-3 flex-wrap">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-list text-cyan-600"></i> Center Inventory Registry
            </h3>
            <button x-show="selectedIds.length > 0" x-cloak x-on:click="deleteSelected()" :disabled="deletingBatch" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow transition">
                <i class="fa-solid fa-trash-can"></i>
                <span>Delete Selected (<span x-text="selectedIds.length"></span>)</span>
            </button>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <select x-model="filterMunicipality" x-on:change="currentPage = 1" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                <option value="ALL">All Municipalities</option>
                @foreach($municipalities as $m)
                <option value="{{ $m }}">{{ $m }}</option>
                @endforeach
            </select>
            <select x-model="filterOperational" x-on:change="currentPage = 1" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                <option value="ALL">All Status</option>
                <option value="Operational">Operational</option>
                <option value="Non-Operational">Non-Operational</option>
            </select>
            <select x-model="perPage" x-on:change="currentPage = 1" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                <option value="5">5 rows</option>
                <option value="10">10 rows</option>
                <option value="15">15 rows</option>
                <option value="20">20 rows</option>
                <option value="30">30 rows</option>
                <option value="40">40 rows</option>
                <option value="50">50 rows</option>
                <option value="100">100 rows</option>
                <option value="150">150 rows</option>
                <option value="200">200 rows</option>
            </select>
            <input type="text" x-model.debounce.300ms="search" x-on:input="currentPage = 1" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
        </div>
    </div>

    <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
            <thead>
                <tr style="background-color: #9DC3E6;">
                    <th rowspan="2" class="sticky-col-head" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6; position: sticky; left: 0; z-index: 3; width: 36px;">
                        <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === paginated.length && paginated.length > 0" x-on:change="toggleSelectAll()" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer" title="Select All On This Page">
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
                <template x-for="(c, idx) in paginated" :key="c.id">
                    <tr :class="selectedIds.includes(c.id) ? 'bg-cyan-50/60' : ''">
                        <td class="sticky-col" :class="{ sel: selectedIds.includes(c.id) }" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; position: sticky; left: 0; z-index: 2; width: 36px;">
                            <input type="checkbox" :value="c.id" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                        </td>
                        <td class="sticky-col" :class="{ sel: selectedIds.includes(c.id) }" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px; position: sticky; left: 36px; z-index: 2; width: 48px;" x-text="(currentPage - 1) * perPage + idx + 1"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.congressional_district || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.province || 'Surigao del Norte'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.municipality_city"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.barangay || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;" x-text="c.center_name"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.longitude || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.latitude || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.verified ? 'True' : 'False'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="formatDate(c.moa_date_of_signing)"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="formatDate(c.date_of_launching)"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="formatDate(c.date_of_platform_registration)"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_status || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_key || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_identifier || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_verification_status || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.odk_status || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.connectivity_status || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.type_of_center_host || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.operational_status || '—'"></td>
                        <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; white-space: nowrap;">
                            <button x-on:click="$dispatch('edit-center', { center: { ...c, moa_date_of_signing: formatYmd(c.moa_date_of_signing), date_of_launching: formatYmd(c.date_of_launching), date_of_platform_registration: formatYmd(c.date_of_platform_registration) } })" class="text-blue-600 hover:text-blue-800" title="Edit" style="background:none;border:none;cursor:pointer;font-size:12px;">✏️</button>
                            <button x-on:click="deleteCenter(c.id)" :disabled="deleting === c.id" class="text-red-600 hover:text-red-800" title="Delete" style="background:none;border:none;cursor:pointer;font-size:12px;">🗑️</button>
                        </td>
                    </tr>
                </template>
                <template x-if="filtered.length === 0">
                    <tr>
                        <td colspan="22" style="border: 1px solid #000; text-align: center; padding: 20px; color: #999;">
                            <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No centers found.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
        <span>Showing <span x-text="filtered.length ? ((currentPage - 1) * perPage + 1) : 0"></span>–<span x-text="Math.min(currentPage * perPage, filtered.length)"></span> of <span x-text="filtered.length"></span> centers</span>
    </div>
    <div class="mt-2 flex items-center gap-1" x-show="totalPages > 1">
        <button x-on:click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 rounded-lg text-xs font-semibold border border-slate-300 disabled:opacity-40 hover:bg-slate-100">&laquo; Prev</button>
        <template x-for="p in totalPages" :key="p">
            <button x-on:click="goToPage(p)" :class="p === currentPage ? 'bg-cyan-700 text-white border-cyan-700' : 'border-slate-300 hover:bg-slate-100'" class="px-3 py-1 rounded-lg text-xs font-semibold border" x-text="p"></button>
        </template>
        <button x-on:click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded-lg text-xs font-semibold border border-slate-300 disabled:opacity-40 hover:bg-slate-100">Next &raquo;</button>
    </div>
</div>
