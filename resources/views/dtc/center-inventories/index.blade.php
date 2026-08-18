<x-app-layout title="DTC Center Inventory">
    @push('styles')
    <style>[x-cloak]{display:none !important;}</style>
    @endpush
    @php $seedCenters = $centers->getCollection(); @endphp

    <div x-data="centersCrud(@json($seedCenters))" x-on:center-added.window="addCenter($event.detail)" x-on:center-updated.window="updateCenter($event.detail)">
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
            <button x-on:click="$dispatch('open-import-center')" class="bg-blue-700 hover:bg-blue-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                <i class="fa-solid fa-upload mr-1.5"></i> Import
            </button>
            <button x-on:click="openAdd()" class="bg-cyan-600 hover:bg-cyan-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
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
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        {{-- FLASH NOTICE --}}
        <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2 mb-4"
             :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
            <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
            <span x-text="notice"></span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-list text-cyan-600"></i> Center Inventory Registry
                </h3>
                <button x-show="selectedIds.length > 0" x-cloak x-on:click="batchDelete()" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow transition">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete Selected (<span x-text="selectedIds.length"></span>)</span>
                </button>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select x-model="municipalityFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                    <option value="ALL">All Municipalities</option>
                    @foreach($municipalities as $m)
                    <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
                <select x-model="operationalFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                    <option value="ALL">All Status</option>
                    <option value="Operational">Operational</option>
                    <option value="Non-Operational">Non-Operational</option>
                </select>
                <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                    <template x-for="n in [5, 10, 15, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                        <option :value="n" x-text="n + ' rows'"></option>
                    </template>
                </select>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Search center, municipality..."
                        class="w-full sm:w-48 text-xs pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
                <thead>
                    <tr style="background-color: #9DC3E6;">
                        <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">
                            <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === pagedCenters.length && pagedCenters.length > 0" x-on:change="toggleSelectAll()" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer" title="Select All On This Page">
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
                    <template x-for="(c, idx) in pagedCenters" :key="c.id">
                        <tr :class="selectedIds.includes(c.id) ? 'bg-cyan-50/60' : ''">
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">
                                <input type="checkbox" :value="c.id" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                            </td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;" x-text="(page - 1) * perPage + idx + 1"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.congressional_district || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.province || 'Surigao del Norte'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.municipality_city"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.barangay || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;" x-text="c.center_name"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.longitude || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.latitude || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.verified ? 'True' : 'False'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.moa_date_of_signing || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.date_of_launching || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.date_of_platform_registration || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_status || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_key || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_identifier || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.tcms_verification_status || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.odk_status || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.connectivity_status || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.type_of_center_host || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;" x-text="c.operational_status || '—'"></td>
                            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; white-space: nowrap;">
                                <button x-on:click="openEdit(c)" class="text-blue-600 hover:text-blue-800" title="Edit" style="background:none;border:none;cursor:pointer;font-size:12px;">✏️</button>
                                <button x-on:click="openDelete(c)" class="text-red-600 hover:text-red-800" title="Delete" style="background:none;border:none;cursor:pointer;font-size:12px;">🗑️</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="filteredCenters.length === 0" class="text-center text-slate-400 py-12 text-xs">
                <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px; color: #cbd5e1;"></i>
                No centers found.
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3 mt-4">
            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                <span x-text="`Showing ${pageFrom}–${pageTo} of ${filteredCenters.length} centers`"></span>
            </div>
            <div class="flex items-center gap-1">
                <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                <template x-for="p in pageNumbers" :key="'cp'+p">
                    <button x-on:click="setPage(p)" :class="page === p ? 'bg-cyan-600 text-white border-cyan-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border" x-text="p"></button>
                </template>
                <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
            </div>
        </div>
    </div>
    </div>

    {{-- ADD CENTER MODAL --}}
    <div x-data="addCenterModal()" x-on:open-add-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-plus text-cyan-400"></i> Add DTC Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form x-on:submit.prevent="submit($event.target)" class="flex flex-col" style="max-height: 80vh;">
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
                    <button type="submit" :disabled="saving" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Add Center</button>
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
    <div x-data="editCenterModal()" x-on:edit-center.window="openEdit($event.detail.center)" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen text-cyan-400"></i> Edit Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form x-on:submit.prevent="submit()" class="flex flex-col" style="max-height: 80vh;">
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Center Name <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.center_name" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Congressional District</label>
                            <input type="text" x-model="form.congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Province</label>
                            <input type="text" x-model="form.province" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Municipality/City <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.municipality_city" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Barangay</label>
                            <input type="text" x-model="form.barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Longitude</label>
                            <input type="text" x-model="form.longitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Latitude</label>
                            <input type="text" x-model="form.latitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 font-semibold text-slate-700">
                                <input type="checkbox" x-model="form.verified" :true-value="1" :false-value="0" class="rounded text-cyan-600 focus:ring-cyan-500">
                                Verified
                            </label>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">MOA Date of Signing</label>
                            <input type="date" x-model="form.moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Launching</label>
                            <input type="date" x-model="form.date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Platform Registration</label>
                            <input type="date" x-model="form.date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status</label>
                            <input type="text" x-model="form.tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Key</label>
                            <input type="text" x-model="form.tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Identifier</label>
                            <input type="text" x-model="form.tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Verification Status</label>
                            <input type="text" x-model="form.tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">ODK Status</label>
                            <select x-model="form.odk_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
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
                            <select x-model="form.connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Connected">Connected</option>
                                <option value="Disconnected">Disconnected</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Type of Center Host</label>
                            <input type="text" x-model="form.type_of_center_host" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Operational Status</label>
                            <select x-model="form.operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Operational">Operational</option>
                                <option value="Non-Operational">Non-Operational</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 pb-6 pt-4 border-t border-slate-200 bg-white">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300">Cancel</button>
                    <button type="submit" :disabled="saving" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow disabled:opacity-50"><i class="fa-solid fa-save mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Update Center</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function centersCrud(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const storeUrl = '{{ route("dtc.centers.store") }}';
        const updateUrl = '{{ route("dtc.centers.update", ["center" => "__ID__"]) }}';
        const destroyUrl = '{{ route("dtc.centers.destroy", ["center" => "__ID__"]) }}';
        const batchDeleteUrl = '{{ route("dtc.centers.batchDelete") }}';
        return {
            centers: seed,
            search: '',
            municipalityFilter: 'ALL',
            operationalFilter: 'ALL',
            selectedIds: [],
            saving: false,
            notice: '',
            noticeType: 'success',
            page: 1,
            perPage: 15,

            get filteredCenters() {
                const q = this.search.trim().toLowerCase();
                return this.centers.filter(c => {
                    const haystack = [c.center_name, c.municipality_city, c.barangay, c.province, c.congressional_district, c.type_of_center_host, c.tcms_status, c.odk_status, c.connectivity_status].join(' ').toLowerCase();
                    const matchQ = !q || haystack.includes(q);
                    const matchM = this.municipalityFilter === 'ALL' || c.municipality_city === this.municipalityFilter;
                    const matchO = this.operationalFilter === 'ALL' || c.operational_status === this.operationalFilter;
                    return matchQ && matchM && matchO;
                });
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredCenters.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, cur = this.page;
                const start = Math.max(1, cur - 2), end = Math.min(total, start + 4);
                const pages = []; for (let p = start; p <= end; p++) pages.push(p); return pages;
            },
            get pageFrom() { return this.filteredCenters.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredCenters.length); },
            get pagedCenters() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const start = (this.page - 1) * this.perPage;
                return this.filteredCenters.slice(start, start + this.perPage);
            },
            toggleSelectAll() {
                const pageIds = this.pagedCenters.map(c => c.id);
                if (this.selectedIds.length === pageIds.length && pageIds.length > 0) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = [...pageIds];
                }
            },
            setPage(p) {
                if (p >= 1 && p <= this.totalPages) this.page = p;
            },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() {
                ['search', 'municipalityFilter', 'operationalFilter'].forEach(k => this.$watch(k, () => { this.page = 1; this.selectedIds = []; }));
            },
            formatDate(val) {
                if (!val) return '—';
                try { const d = new Date(val); return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' }); } catch { return val; }
            },

            openEdit(c) {
                this.$dispatch('edit-center', { center: {
                    id: c.id,
                    congressional_district: c.congressional_district,
                    province: c.province,
                    municipality_city: c.municipality_city,
                    barangay: c.barangay,
                    center_name: c.center_name,
                    longitude: c.longitude,
                    latitude: c.latitude,
                    verified: c.verified,
                    moa_date_of_signing: c.moa_date_of_signing_raw || '',
                    date_of_launching: c.date_of_launching_raw || '',
                    date_of_platform_registration: c.date_of_platform_registration_raw || '',
                    tcms_status: c.tcms_status,
                    tcms_key: c.tcms_key,
                    tcms_identifier: c.tcms_identifier,
                    tcms_verification_status: c.tcms_verification_status,
                    odk_status: c.odk_status,
                    connectivity_status: c.connectivity_status,
                    type_of_center_host: c.type_of_center_host,
                    operational_status: c.operational_status,
                }});
            },

            openDelete(c) {
                if (!confirm('Delete "' + c.center_name + '"?')) return;
                this.doDelete(c);
            },

            async doDelete(c) {
                if (this.saving) return;
                this.saving = true;
                try {
                    const url = destroyUrl.replace('__ID__', c.id);
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Delete failed.');
                    this.centers = this.centers.filter(x => x.id !== c.id);
                    this.selectedIds = this.selectedIds.filter(id => id !== c.id);
                    this.flash('Center deleted successfully.');
                } catch (e) {
                    this.flash(e.message, 'error');
                } finally {
                    this.saving = false;
                }
            },

            async batchDelete() {
                if (!confirm('Are you sure you want to delete ' + this.selectedIds.length + ' selected center(s)? This action cannot be undone.')) return;
                if (this.saving) return;
                this.saving = true;
                try {
                    const res = await fetch(batchDeleteUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ ids: this.selectedIds }),
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Batch delete failed.');
                    this.centers = this.centers.filter(c => !this.selectedIds.includes(c.id));
                    this.selectedIds = [];
                    this.flash(data.message || 'Selected centers deleted successfully.');
                } catch (e) {
                    this.flash(e.message, 'error');
                } finally {
                    this.saving = false;
                }
            },

            addCenter(center) {
                this.centers.unshift(center);
                this.flash('Center added successfully.');
            },

            updateCenter(center) {
                const idx = this.centers.findIndex(c => c.id === center.id);
                if (idx > -1) this.centers.splice(idx, 1, center);
                this.flash('Center updated successfully.');
            },
        };
    }

    function addCenterModal() {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const storeUrl = @json(route('dtc.centers.store'));
        return {
            show: false,
            saving: false,
            async submit(form) {
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
                    if (!res.ok) throw new Error(data.message || 'Failed to add center.');
                    window.dispatchEvent(new CustomEvent('center-added', { detail: data.center }));
                    form.reset();
                    this.show = false;
                } catch(e) {
                    alert(e.message);
                } finally { this.saving = false; }
            },
        };
    }

    function editCenterModal() {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const updateUrlBase = @json(route('dtc.centers.update', ['center' => '__ID__']));
        return {
            show: false,
            saving: false,
            form: {},
            openEdit(center) {
                this.form = { ...center };
                this.show = true;
            },
            async submit() {
                if (this.saving) return;
                this.saving = true;
                try {
                    const updateUrl = updateUrlBase.replace('__ID__', this.form.id);
                    const res = await fetch(updateUrl, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify(this.form),
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Update failed.');
                    window.dispatchEvent(new CustomEvent('center-updated', { detail: data.center }));
                    this.show = false;
                } catch(e) {
                    alert(e.message);
                } finally { this.saving = false; }
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
