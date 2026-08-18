<x-app-layout title="PROJECT CLICK Devices">
    @php $seedDevices = $devices->getCollection(); @endphp
    {{-- BREADCRUMBS --}}
    <x-breadcrumbs :items="[['label' => 'PROJECT CLICK', 'icon' => 'fa-laptop-code text-emerald-600']]" />

    {{-- CLICK BANNER --}}
    <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-laptop-code text-emerald-300"></i> PROJECT CLICK - Device Donation Program
            </h2>
            <p class="text-sm text-emerald-100 mt-1">Community Learning Innovation for ICT Knowledge - device deployment and beneficiary tracking across Surigao del Norte LGUs.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button x-data x-on:click="$dispatch('open-modal', 'addDevice')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                <i class="fa-solid fa-hand-holding-hand mr-1.5"></i> Log Device Donation
            </button>
            <a href="{{ route('export.csv', 'click-devices') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-2 rounded-lg text-xs font-medium flex items-center transition">
                <i class="fa-solid fa-file-csv mr-2 text-emerald-300"></i> Export Donations
            </a>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-[11px] font-bold uppercase tracking-wider">Devices Donated</span>
                <i class="fa-solid fa-laptop text-emerald-600"></i>
            </div>
            <h4 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($totalDevices) }} Units</h4>
            <span class="text-[10px] text-emerald-600 font-semibold">{{ $turnedOver }} Turned Over</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Batches</span>
                <i class="fa-solid fa-boxes-stacked text-blue-600"></i>
            </div>
            <h4 class="text-2xl font-black text-blue-700 mt-1">{{ $totalBatches }}</h4>
            <span class="text-[10px] text-slate-400 font-medium">Donation Batches Logged</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-[11px] font-bold uppercase tracking-wider">Pending</span>
                <i class="fa-solid fa-hourglass-half text-amber-600"></i>
            </div>
            <h4 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($pending) }} Units</h4>
            <span class="text-[10px] text-amber-600 font-semibold">Awaiting Turnover</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-[11px] font-bold uppercase tracking-wider">Municipalities</span>
                <i class="fa-solid fa-location-dot text-purple-600"></i>
            </div>
            <h4 class="text-2xl font-black text-purple-700 mt-1">{{ $municipalities->count() }}</h4>
            <span class="text-[10px] text-slate-400 font-medium">SDN LGU Coverage</span>
        </div>
    </div>

    {{-- DEVICE STATUS CHART --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-pie text-emerald-600 mr-2"></i> Device Status Distribution</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-3/4 animate-pulse">
                        <div class="h-36 w-36 rounded-full bg-slate-100 mx-auto"></div>
                        <div class="h-2 bg-slate-200 rounded w-1/2 mx-auto"></div>
                    </div>
                </div>
                <canvas id="clickStatusChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-bar text-teal-600 mr-2"></i> Devices by Municipality</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-full px-8 animate-pulse">
                        <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-48 bg-slate-100 rounded-lg"></div>
                    </div>
                </div>
                <canvas id="clickMuniChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
    </div>

    {{-- DEVICES TABLE --}}
    <div x-data="clickDevicesCrud(@json($seedDevices))" x-on:device-added.window="devices.unshift($event.detail)" x-on:device-updated.window="const idx = devices.findIndex(x => x.id === $event.detail.id); if (idx > -1) devices[idx] = $event.detail;" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
        {{-- Flash notice --}}
        <template x-if="notice">
            <div :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'" class="border rounded-lg px-4 py-2.5 text-xs font-semibold flex items-center gap-2" x-transition>
                <i :class="noticeType === 'error' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check'" class="mr-1"></i>
                <span x-text="notice"></span>
            </div>
        </template>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-box-archive text-emerald-600"></i> PROJECT CLICK Device Donations Register
                </h3>
                <p class="text-xs text-slate-500">List of hardware equipment donated to schools, LGUs, and Tech4ED centers.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select x-model="statusFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                    <option value="ALL">All Status</option>
                    <option value="Turned Over">Turned Over</option>
                    <option value="Pending">Pending</option>
                    <option value="In Transit">In Transit</option>
                </select>
                <div class="flex items-center gap-2">
                    <input x-model="search" type="text" placeholder="Search batches..."
                        class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-200 custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="p-3">Batch ID & Date</th>
                        <th class="p-3">Device Type & Model</th>
                        <th class="p-3 text-center">Quantity</th>
                        <th class="p-3">Beneficiary Institution / School</th>
                        <th class="p-3">Municipality</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                    <template x-for="d in pagedDevices" :key="d.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3">
                                <span class="font-mono font-bold text-emerald-900" x-text="d.batch_id"></span><br>
                                <span class="text-[10px] text-slate-400 font-normal" x-text="d.donation_date ? new Date(d.donation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : ''"></span>
                            </td>
                            <td class="p-3 font-bold text-slate-800" x-text="d.device_type"></td>
                            <td class="p-3 text-center font-bold text-emerald-700"><span x-text="d.quantity"></span> Units</td>
                            <td class="p-3 text-slate-700 font-medium" x-text="d.beneficiary"></td>
                            <td class="p-3" x-text="d.municipality"></td>
                            <td class="p-3 text-center">
                                <template x-if="d.status === 'Turned Over'">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">Turned Over</span>
                                </template>
                                <template x-if="d.status === 'Pending'">
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">Pending</span>
                                </template>
                                <template x-if="d.status === 'In Transit'">
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full font-bold text-[10px]">In Transit</span>
                                </template>
                            </td>
                            <td class="p-3 text-center">
                                <button x-on:click="$dispatch('edit-device', { device: d })" class="text-blue-400 hover:text-blue-600 p-1 mr-1" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                <button x-on:click="deleteRecord(d)" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="pagedDevices.length === 0">
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400 font-medium">No device donation records found.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="pt-2 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-2">
            <span class="font-semibold text-slate-700">Showing <span x-text="filteredDevices.length"></span> device donation records</span>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                    <label class="text-[10px] text-slate-400 font-medium mr-1">Per page:</label>
                    <template x-for="n in [5, 10, 15, 20, 50]" :key="n">
                        <button x-on:click="perPage = n; page = 1" :class="perPage === n ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600 hover:bg-slate-300'" class="w-7 h-7 rounded-lg text-[11px] font-bold transition" x-text="n"></button>
                    </template>
                </div>
                <div class="flex items-center gap-1">
                    <button x-on:click="if (page > 1) page--" :disabled="page <= 1" class="px-2 py-1 rounded-lg bg-slate-200 text-slate-600 hover:bg-slate-300 disabled:opacity-40 text-[11px] font-bold">&laquo;</button>
                    <template x-for="p in pageNumbers" :key="'cp'+p">
                        <button x-on:click="page = p" :class="p === page ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600 hover:bg-slate-300'" class="w-7 h-7 rounded-lg text-[11px] font-bold transition" x-text="p"></button>
                    </template>
                    <button x-on:click="if (page < totalPages) page++" :disabled="page >= totalPages" class="px-2 py-1 rounded-lg bg-slate-200 text-slate-600 hover:bg-slate-300 disabled:opacity-40 text-[11px] font-bold">&raquo;</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD DEVICE MODAL --}}
    <div x-data="addDeviceModal()" x-on:open-modal.window="if($event.detail==='addDevice'){ show=true }" x-on:close-modal.window="if($event.detail==='addDevice'){ show=false }" x-on:keydown.escape.window="show = false" x-show="show" x-cloak style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-emerald-800 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-hand-holding-hand text-emerald-400"></i> Log Device Donation</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form x-on:submit.prevent="addDevice($event.target)" class="p-6 space-y-4 text-xs">
                <template x-if="formError">
                    <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-2.5 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span x-text="formError"></span>
                    </div>
                </template>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Batch ID</label>
                        <input type="text" name="batch_id" required placeholder="CLK-2026-X1" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Donation Date</label>
                        <input type="date" name="donation_date" required class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Device Type & Model</label>
                        <input type="text" name="device_type" required placeholder="e.g. Lenovo Chromebook 300e" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" required min="1" value="1" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Beneficiary Institution / School</label>
                    <input type="text" name="beneficiary" required placeholder="e.g. Surigao National High School" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Municipality</label>
                        <input type="text" name="municipality" required placeholder="e.g. Surigao City" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" required class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Pending">Pending</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Turned Over">Turned Over</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                    <button type="submit" :disabled="saving" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow text-xs disabled:opacity-50">
                        <template x-if="saving"><span class="flex items-center gap-1"><i class="fa-solid fa-spinner fa-spin"></i> Saving...</span></template>
                        <template x-if="!saving"><span>Log Donation</span></template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT DEVICE MODAL --}}
    <div x-data="editDeviceModal()" x-on:edit-device.window="openEdit($event.detail.device)" x-on:keydown.escape.window="show = false" x-show="show" x-cloak style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-emerald-800 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen text-emerald-400"></i> Edit Device Donation</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form x-on:submit.prevent="updateDevice($event.target)" class="p-6 space-y-4 text-xs">
                <template x-if="formError">
                    <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-2.5 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span x-text="formError"></span>
                    </div>
                </template>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Batch ID</label>
                        <input type="text" name="batch_id" required x-model="device.batch_id" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Donation Date</label>
                        <input type="date" name="donation_date" required x-model="device.donation_date" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Device Type & Model</label>
                        <input type="text" name="device_type" required x-model="device.device_type" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" required min="1" x-model="device.quantity" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Beneficiary Institution / School</label>
                    <input type="text" name="beneficiary" required x-model="device.beneficiary" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Municipality</label>
                        <input type="text" name="municipality" required x-model="device.municipality" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" required x-model="device.status" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Pending">Pending</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Turned Over">Turned Over</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                    <button type="submit" :disabled="saving" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow text-xs disabled:opacity-50">
                        <template x-if="saving"><span class="flex items-center gap-1"><i class="fa-solid fa-spinner fa-spin"></i> Updating...</span></template>
                        <template x-if="!saving"><span><i class="fa-solid fa-save mr-1"></i> Update Record</span></template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function clickDevicesCrud(seed) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const storeUrl = '{{ route("click.devices.store") }}';
            const destroyUrl = '{{ route("click.devices.destroy", ["device" => "__ID__"]) }}';
            return {
                devices: seed,
                search: '',
                statusFilter: 'ALL',
                saving: false,
                notice: '',
                noticeType: 'success',
                page: 1,
                perPage: 10,
                init() {
                    this.$watch('search', () => this.page = 1);
                    this.$watch('statusFilter', () => this.page = 1);
                    this.$watch('perPage', () => this.page = 1);
                },
                get filteredDevices() {
                    const q = this.search.trim().toLowerCase();
                    return this.devices.filter(d => {
                        const matchStatus = this.statusFilter === 'ALL' || d.status === this.statusFilter;
                        const haystack = [d.batch_id, d.device_type, d.beneficiary, d.municipality, d.status, String(d.quantity)].join(' ').toLowerCase();
                        const matchSearch = !q || haystack.includes(q);
                        return matchStatus && matchSearch;
                    });
                },
                get totalPages() { return Math.max(1, Math.ceil(this.filteredDevices.length / this.perPage)); },
                get pageFrom() { return this.filteredDevices.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
                get pageTo() { return Math.min(this.page * this.perPage, this.filteredDevices.length); },
                get pageNumbers() {
                    const total = this.totalPages, cur = this.page;
                    if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
                    const pages = [];
                    if (cur <= 4) { for (let i = 1; i <= 5; i++) pages.push(i); pages.push('...'); pages.push(total); }
                    else if (cur >= total - 3) { pages.push(1); pages.push('...'); for (let i = total - 4; i <= total; i++) pages.push(i); }
                    else { pages.push(1); pages.push('...'); for (let i = cur - 1; i <= cur + 1; i++) pages.push(i); pages.push('...'); pages.push(total); }
                    return pages;
                },
                get pagedDevices() {
                    if (this.page > this.totalPages) this.page = this.totalPages;
                    const start = (this.page - 1) * this.perPage;
                    return this.filteredDevices.slice(start, start + this.perPage);
                },
                flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
                async addDevice(form) {
                    if (this.saving) return; this.saving = true;
                    try {
                        const fd = new FormData(form);
                        const res = await fetch(storeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed to log device donation.');
                        this.devices.unshift(data.device);
                        form.reset();
                        this.$dispatch('close-modal', 'addDevice');
                        this.flash('Device donation logged successfully.');
                    } catch(e) { this.flash(e.message, 'error'); } finally { this.saving = false; }
                },
                async deleteRecord(d) {
                    if (!confirm('Delete this donation record?')) return;
                    try {
                        const url = destroyUrl.replace('__ID__', d.id);
                        const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed to delete.');
                        this.devices = this.devices.filter(x => x.id !== d.id);
                        this.flash('Device donation record deleted.');
                    } catch(e) { this.flash(e.message, 'error'); }
                },
            };
        }

        function addDeviceModal() {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            return {
                show: false,
                saving: false,
                formError: '',
                async addDevice(form) {
                    if (this.saving) return; this.saving = true;
                    try {
                        const fd = new FormData(form);
                        const res = await fetch('{{ route("click.devices.store") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed.');
                        window.dispatchEvent(new CustomEvent('device-added', { detail: data.device }));
                        form.reset();
                        this.show = false;
                    } catch(e) { this.formError = e.message; } finally { this.saving = false; }
                },
            };
        }

        function editDeviceModal() {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            return {
                show: false,
                device: {},
                saving: false,
                formError: '',
                openEdit(d) {
                    this.device = Object.assign({}, d);
                    if (this.device.donation_date) this.device.donation_date = this.device.donation_date.substring(0, 10);
                    this.formError = '';
                    this.show = true;
                },
                async updateDevice(form) {
                    if (this.saving) return; this.saving = true;
                    try {
                        const fd = new FormData(form);
                        fd.append('_method', 'PUT');
                        const url = '{{ url("click/devices") }}/' + this.device.id;
                        const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-HTTP-Method-Override': 'PUT' }, body: fd });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed.');
                        window.dispatchEvent(new CustomEvent('device-updated', { detail: data.device }));
                        this.show = false;
                    } catch(e) { this.formError = e.message; } finally { this.saving = false; }
                },
            };
        }

        document.addEventListener('alpine:init', () => {
            const turnedOver = {{ $turnedOver }};
            const pending = {{ $pending }};
            const inTransit = {{ $inTransit }};

            new Chart(document.getElementById('clickStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Turned Over', 'Pending', 'In Transit'],
                    datasets: [{ data: [turnedOver, pending, inTransit], backgroundColor: ['#10b981', '#f59e0b', '#3b82f6'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
            });

            fetch('{{ route("api.click.devices") }}')
                .then(r => r.json())
                .then(devices => {
                    const muniMap = {};
                    devices.forEach(d => { muniMap[d.municipality] = (muniMap[d.municipality] || 0) + d.quantity; });
                    const labels = Object.keys(muniMap);
                    const values = Object.values(muniMap);

                    new Chart(document.getElementById('clickMuniChart'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{ label: 'Devices', data: values, backgroundColor: '#059669', borderRadius: 4 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } } }
                    });
                });
        });
    </script>
    @endpush
</x-app-layout>
