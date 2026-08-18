<x-app-layout title="DTC Hub">

    @push('styles')
    <style>[x-cloak]{display:none !important;}</style>
    @endpush

    {{-- ROOT ALPINE SCOPE --}}
    <div x-data="{
        view: '{{ $view }}',
        techTab: '{{ $activeTab }}',
        sdnView: {{ $sdnView ? 'true' : 'false' }},
        switchView(v) {
            this.view = v;
            const url = new URL(window.location.href);
            url.searchParams.set('view', v);
            ['page','v_page','c_page','sdn_page','s_page'].forEach(p => url.searchParams.delete(p));
            history.replaceState(null, '', url);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }" x-cloak>

        {{-- BREADCRUMBS --}}
        <x-breadcrumbs :items="[['label' => 'DTC HUB', 'icon' => 'fa-building-user text-cyan-600']]" />



        {{-- TAB BAR --}}
        <div class="flex pb-2 mb-6 space-x-2 overflow-x-auto border-b border-slate-200 custom-scrollbar">
            <button @click="switchView('dashboard')" :class="view === 'dashboard' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <i class="fa-solid fa-gauge-high text-amber-400"></i> Dashboard
            </button>
            <button @click="switchView('centers')" :class="view === 'centers' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <i class="fa-solid fa-warehouse text-emerald-400"></i> Centers
            </button>
            <button @click="switchView('services')" :class="view === 'services' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <i class="text-teal-400 fa-solid fa-map-location-dot"></i> Services
            </button>
        </div>
               {{-- HERO HEADER (Services tab only) --}}
        <div x-show="view === 'services'" x-cloak class="flex flex-col items-start gap-4 p-5 mb-6 text-white shadow-sm bg-gradient-to-r from-cyan-900 via-teal-900 to-dict-blue rounded-xl">
            <div>
                <h2 class="flex items-center gap-2 text-xl font-bold">
                    <i class="fa-solid fa-building-user text-cyan-400"></i> DTC HUB
                </h2>
                <p class="mt-1 text-sm text-cyan-200">Digital Transformation Centers — visitor analytics, center inventory, and SDN &amp; PDI Tech4ED overview in one place.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button x-data x-on:click="$dispatch('open-import-visitors')" class="bg-blue-700 hover:bg-blue-600 text-white px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center shadow transition">
                    <i class="fa-solid fa-upload text-blue-300 mr-1.5"></i> Import DTC Logs
                </button>
                <a href="{{ route('export.csv', 'dtc-visitors') }}" class="bg-cyan-700 hover:bg-cyan-600 text-white px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center shadow transition">
                    <i class="fa-solid fa-file-csv text-emerald-300 mr-1.5"></i> Export DTC Logs
                </a>
                <button x-data x-on:click="$dispatch('open-modal-dtc')" class="bg-amber-500 hover:bg-amber-400 text-slate-900 px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                    <i class="fa-solid fa-shoe-prints mr-1.5"></i> Log Visitor Session
                </button>
            </div>
        </div>

        {{-- DASHBOARD PANEL --}}
        <div x-show="view === 'dashboard'" x-cloak x-effect="if (view === 'dashboard') { setTimeout(initDashboardCharts, 60); }">
            @include('dtc.visitors.partials.services')
        </div>

        {{-- CENTERS PANEL --}}
        <div x-show="view === 'centers'" x-cloak>
            @include('dtc.visitors.partials.centers')
        </div>

        {{-- SERVICES PANEL --}}
        <div x-show="view === 'services'" x-cloak x-effect="if (view === 'services') { setTimeout(initDtcCharts, 60); }">
            @include('dtc.visitors.partials.dashboard')
        </div>

        {{-- ==================== ADD DTC VISITOR MODAL ==================== --}}
        <div x-data="{ show: false, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); try { const res = await fetch('{{ route('dtc.visitors.store') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || Object.values(d.errors || {}).flat().join('\n') || 'Error saving'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:open-modal-dtc.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-lg overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-cyan-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="fa-solid fa-shoe-prints text-cyan-400"></i> Log DTC Visitor / User Session</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="p-6 space-y-4 text-xs" x-data="{ services: ['Free High-Speed Internet'] }">
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Visitor Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="visitor_name" required placeholder="e.g. Maria Clara Santos" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Gender</label>
                            <select name="gender" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Age <span class="text-red-500">*</span></label>
                            <input type="number" name="age" required min="10" max="99" placeholder="Age" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Demographic Sector</label>
                            <select name="demographic_sector" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                @foreach(['Student / Youth', 'Senior Citizen / PWD', 'Jobseeker / Out-of-School Youth', 'MSME / Freelancer', 'LGU / Govt Employee'] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">DTC Hub Center</label>
                            <select name="dtc_hub_id" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                @foreach($hubs as $hub)
                                <option value="{{ $hub->id }}">{{ $hub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Session Duration <span class="text-red-500">*</span></label>
                        <input type="text" name="session_duration" required placeholder="e.g. 1 hr 45 mins" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Services Availed <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border rounded-lg bg-slate-50 border-slate-200">
                            @foreach(['Free High-Speed Internet', 'eGov PH & Government Portal Access', 'Printing & Document Scanning', 'Co-working & Freelance Space', 'Tech Assistance & Consultation'] as $i => $svc)
                            <label class="flex items-center space-x-2 text-[11px] font-medium text-slate-700 cursor-pointer">
                                <input type="checkbox" name="services[]" value="{{ $svc }}" {{ $i === 0 ? 'checked' : '' }} class="rounded text-cyan-600 focus:ring-cyan-500">
                                <span>{{ $svc }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-check"></i> Record Visitor Session</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== EDIT DTC VISITOR MODAL ==================== --}}
        <div x-data="{ show: false, visitor: {}, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); fd.append('_method', 'PUT'); try { const res = await fetch('{{ url('dtc/visitors') }}/' + this.visitor.id, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || Object.values(d.errors || {}).flat().join('\n') || 'Error updating'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:edit-visitor.window="show = true; visitor = $event.detail.visitor" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-lg overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-cyan-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="fa-solid fa-pen text-cyan-400"></i> Edit Visitor Log</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="p-6 space-y-4 text-xs">
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Visitor Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="visitor_name" required x-model="visitor.visitor_name" placeholder="e.g. Maria Clara Santos" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Gender</label>
                            <select name="gender" x-model="visitor.gender" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Age <span class="text-red-500">*</span></label>
                            <input type="number" name="age" required min="10" max="99" x-model="visitor.age" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">Demographic Sector</label>
                            <select name="demographic_sector" x-model="visitor.demographic_sector" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                @foreach(['Student / Youth', 'Senior Citizen / PWD', 'Jobseeker / Out-of-School Youth', 'MSME / Freelancer', 'LGU / Govt Employee'] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-slate-700">DTC Hub Center</label>
                            <select name="dtc_hub_id" x-model="visitor.dtc_hub_id" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                @foreach($hubs as $hub)
                                <option value="{{ $hub->id }}">{{ $hub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Session Duration <span class="text-red-500">*</span></label>
                        <input type="text" name="session_duration" required x-model="visitor.session_duration" placeholder="e.g. 1 hr 45 mins" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-slate-700">Services Availed <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border rounded-lg bg-slate-50 border-slate-200">
                            @foreach(['Free High-Speed Internet', 'eGov PH & Government Portal Access', 'Printing & Document Scanning', 'Co-working & Freelance Space', 'Tech Assistance & Consultation'] as $i => $svc)
                            <label class="flex items-center space-x-2 text-[11px] font-medium text-slate-700 cursor-pointer">
                                <input type="checkbox" name="services[]" value="{{ $svc }}" x-bind:checked="visitor?.services_ailed?.includes('{{ $svc }}')" class="rounded text-cyan-600 focus:ring-cyan-500">
                                <span>{{ $svc }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-save"></i> Update Visitor</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== ADD CENTER MODAL ==================== --}}
        <div x-data="{ show: false, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); try { const res = await fetch('{{ route('dtc.centers.store') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || Object.values(d.errors || {}).flat().join('\n') || 'Error saving'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:open-add-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-2xl overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-cyan-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="fa-solid fa-plus text-cyan-400"></i> Add DTC Center</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="flex flex-col" style="max-height: 80vh;">
                    <div class="flex-1 p-6 space-y-4 overflow-y-auto text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block mb-1 font-semibold text-slate-700">Center Name <span class="text-red-500">*</span></label>
                                <input type="text" name="center_name" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Congressional District</label>
                                <input type="text" name="congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Province</label>
                                <input type="text" name="province" value="Surigao del Norte" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Municipality/City <span class="text-red-500">*</span></label>
                                <input type="text" name="municipality_city" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Barangay</label>
                                <input type="text" name="barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Longitude</label>
                                <input type="text" name="longitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Latitude</label>
                                <input type="text" name="latitude" step="any" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 font-semibold text-slate-700">
                                    <input type="checkbox" name="verified" value="1" class="rounded text-cyan-600 focus:ring-cyan-500">
                                    Verified
                                </label>
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">MOA Date of Signing</label>
                                <input type="date" name="moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Date of Launching</label>
                                <input type="date" name="date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Date of Platform Registration</label>
                                <input type="date" name="date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Status</label>
                                <input type="text" name="tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Key</label>
                                <input type="text" name="tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Identifier</label>
                                <input type="text" name="tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Verification Status</label>
                                <input type="text" name="tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">ODK Status</label>
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
                                <label class="block mb-1 font-semibold text-slate-700">Connectivity Status</label>
                                <select name="connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                    <option value="">—</option>
                                    <option value="Connected">Connected</option>
                                    <option value="Disconnected">Disconnected</option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Type of Center Host</label>
                                <input type="text" name="type_of_center_host" placeholder="e.g. LGU, DICT, DepEd" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Operational Status</label>
                                <select name="operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                    <option value="">—</option>
                                    <option value="Operational">Operational</option>
                                    <option value="Non-Operational">Non-Operational</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 px-6 pt-4 pb-6 bg-white border-t border-slate-200">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-check"></i> Add Center</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== IMPORT DTC VISITOR LOGS MODAL ==================== --}}
        <div x-data="{ show: false, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); try { const res = await fetch('{{ route('dtc.visitors.import') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || 'Import failed'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:open-import-visitors.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-lg overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-blue-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="text-blue-400 fa-solid fa-upload"></i> Import DTC Visitor Logs</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="p-6 space-y-4 text-xs">
                    <div class="p-4 text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                        <p class="mb-1 font-semibold">Accepted formats: <strong>CSV, XLSX</strong></p>
                        <p class="text-blue-600">Download the template first to ensure correct column headers. Required columns: <strong>Visitor Name</strong>, <strong>Age</strong>, <strong>Demographic Sector</strong>, <strong>DTC Hub</strong>, <strong>Session Duration</strong>, and <strong>Visit Date</strong>.</p>
                        <a href="{{ route('export.template', 'dtc-visitors') }}" class="inline-flex items-center gap-1 mt-2 font-bold text-blue-700 underline hover:text-blue-900">
                            <i class="fa-solid fa-download"></i> Download template
                        </a>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700">Select File</label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-upload"></i> Import</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Importing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== IMPORT CENTER MODAL ==================== --}}
        <div x-data="{ show: false, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); try { const res = await fetch('{{ route('dtc.centers.import') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || 'Import failed'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:open-import-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-lg overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-blue-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="text-blue-400 fa-solid fa-upload"></i> Import Centers</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="p-6 space-y-4 text-xs">
                    <div class="p-4 text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                        <p class="mb-1 font-semibold">Accepted formats: <strong>CSV, XLSX</strong></p>
                        <p class="text-blue-600">Download the template first to ensure correct column headers. Required columns: <strong>Municipality/City</strong> and <strong>Center Name</strong>.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700">Select File</label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-upload"></i> Import</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Importing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== EDIT CENTER MODAL ==================== --}}
        <div x-data="{ show: false, center: {}, submitting: false, async submitForm(e) { this.submitting = true; const fd = new FormData(e.target); fd.append('_method', 'PUT'); try { const res = await fetch('{{ url('dtc/centers') }}/' + this.center.id, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd }); if (res.ok) { this.show = false; window.location.reload(); } else { const d = await res.json().catch(() => ({})); alert(d.message || Object.values(d.errors || {}).flat().join('\n') || 'Error updating'); } } catch(err) { console.error(err); alert('Network error'); } this.submitting = false; } }" x-on:edit-center.window="show = true; center = $event.detail.center" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[9999] flex items-end justify-center p-0 bg-slate-900/80 backdrop-blur-sm sm:items-center sm:p-4">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="w-full max-w-2xl overflow-x-hidden overflow-y-auto bg-white border shadow-2xl rounded-t-2xl sm:rounded-2xl max-h-[90vh] custom-scrollbar border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 text-white bg-gradient-to-r from-cyan-900 to-dict-blue">
                    <h3 class="flex items-center gap-2 font-bold"><i class="fa-solid fa-pen text-cyan-400"></i> Edit Center</h3>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="text-lg fa-solid fa-xmark"></i></button>
                </div>
                <form x-on:submit.prevent="submitForm($event)" class="flex flex-col" style="max-height: 80vh;">
                    <div class="flex-1 p-6 space-y-4 overflow-y-auto text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block mb-1 font-semibold text-slate-700">Center Name <span class="text-red-500">*</span></label>
                                <input type="text" name="center_name" required x-model="center.center_name" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Congressional District</label>
                                <input type="text" name="congressional_district" x-model="center.congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Province</label>
                                <input type="text" name="province" x-model="center.province" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Municipality/City <span class="text-red-500">*</span></label>
                                <input type="text" name="municipality_city" required x-model="center.municipality_city" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Barangay</label>
                                <input type="text" name="barangay" x-model="center.barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Longitude</label>
                                <input type="text" name="longitude" step="any" x-model="center.longitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Latitude</label>
                                <input type="text" name="latitude" step="any" x-model="center.latitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 font-semibold text-slate-700">
                                    <input type="checkbox" name="verified" value="1" x-bind:checked="center.verified == 1 || center.verified === true" class="rounded text-cyan-600 focus:ring-cyan-500">
                                    Verified
                                </label>
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">MOA Date of Signing</label>
                                <input type="date" name="moa_date_of_signing" x-model="center.moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Date of Launching</label>
                                <input type="date" name="date_of_launching" x-model="center.date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Date of Platform Registration</label>
                                <input type="date" name="date_of_platform_registration" x-model="center.date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Status</label>
                                <input type="text" name="tcms_status" x-model="center.tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Key</label>
                                <input type="text" name="tcms_key" x-model="center.tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Identifier</label>
                                <input type="text" name="tcms_identifier" x-model="center.tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">TCMS Verification Status</label>
                                <input type="text" name="tcms_verification_status" x-model="center.tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">ODK Status</label>
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
                                <label class="block mb-1 font-semibold text-slate-700">Connectivity Status</label>
                                <select name="connectivity_status" x-model="center.connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                    <option value="">—</option>
                                    <option value="Connected">Connected</option>
                                    <option value="Disconnected">Disconnected</option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Type of Center Host</label>
                                <input type="text" name="type_of_center_host" x-model="center.type_of_center_host" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-slate-700">Operational Status</label>
                                <select name="operational_status" x-model="center.operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                    <option value="">—</option>
                                    <option value="Operational">Operational</option>
                                    <option value="Non-Operational">Non-Operational</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 px-6 pt-4 pb-6 bg-white border-t border-slate-200">
                        <button type="button" x-on:click="show = false" class="px-4 py-2 font-semibold rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 font-bold text-white rounded-lg shadow bg-cyan-600 hover:bg-cyan-500 disabled:opacity-50">
                            <span x-show="!submitting"><i class="mr-1 fa-solid fa-save"></i> Update Center</span>
                            <span x-show="submitting"><i class="mr-1 fa-solid fa-spinner fa-spin"></i> Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // ==================== DASHBOARD TAB CHARTS (API-driven) ====================
        window.dtcChartInstances = window.dtcChartInstances || {};
        let dtcTrafficChart = null, dtcDemoChart = null, dtcServicesChart = null;
        const DTC_MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        function destroyDtcCharts() {
            ['dtcTrafficChart', 'dtcDemoChart', 'dtcServicesChart'].forEach(function (key) {
                if (window[key]) { window[key].destroy(); window[key] = null; }
            });
        }

        window.initDtcCharts = function () {
            if (typeof Chart === 'undefined') {
                setTimeout(window.initDtcCharts, 250);
                return;
            }
            loadDtcCharts('{{ date("Y") }}');
        };

        function loadDtcCharts(year) {
            const trafficUrl = '{{ route("api.dtc.traffic") }}?year=' + year;
            const visitorsUrl = '{{ route("api.dtc.visitors") }}?year=' + year;
            const servicesUrl = '{{ route("api.dtc.services") }}?year=' + year;

            fetch(trafficUrl)
                .then(r => r.json())
                .then(data => {
                    const vals = Object.values(data);
                    if (window.dtcTrafficChart) window.dtcTrafficChart.destroy();
                    const ctx = document.getElementById('dtcFootTrafficChart');
                    if (ctx) {
                        window.dtcTrafficChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: DTC_MONTHS,
                                datasets: [{ label: 'Visitors', data: vals, backgroundColor: '#0891b2', borderRadius: 6 }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                        });
                    }
                });

            fetch(visitorsUrl)
                .then(r => r.json())
                .then(data => {
                    const sectors = {};
                    data.forEach(v => { sectors[v.demographic_sector] = (sectors[v.demographic_sector] || 0) + 1; });
                    const colors = ['#0891b2','#003366','#CE1126','#D4AF37','#10b981','#8b5cf6'];
                    if (window.dtcDemoChart) window.dtcDemoChart.destroy();
                    const ctx = document.getElementById('dtcDemographicsChart');
                    if (ctx) {
                        window.dtcDemoChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: Object.keys(sectors),
                                datasets: [{ data: Object.values(sectors), backgroundColor: colors.slice(0, Object.keys(sectors).length) }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
                        });
                    }
                });

            fetch(servicesUrl)
                .then(r => r.json())
                .then(data => {
                    if (window.dtcServicesChart) window.dtcServicesChart.destroy();
                    const ctx = document.getElementById('dtcServicesAvailedChart');
                    if (ctx) {
                        window.dtcServicesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(data).map(s => s.length > 25 ? s.substring(0, 25) + '...' : s),
                                datasets: [{ label: 'Sessions', data: Object.values(data), backgroundColor: '#10b981', borderRadius: 6 }]
                            },
                            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
                        });
                    }
                });
        }

        // ==================== SERVICES TAB CHARTS (server-side data) ====================
        window.dashboardChartInstances = window.dashboardChartInstances || {};

        window.initDashboardCharts = function () {
            if (typeof Chart === 'undefined') {
                setTimeout(window.initDashboardCharts, 250);
                return;
            }

            const PALETTE = ['#0e7490', '#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#db2777', '#0891b2', '#65a30d', '#9333ea', '#ea580c', '#0d9488', '#4f46e5', '#c026d3', '#16a34a', '#f59e0b', '#ef4444', '#0ea5e9', '#84cc16', '#f43f5e', '#78716c'];

            const sdnMuni = @json($sdnMuniCenters);
            const dinagatMuni = @json($dinagatMuniCenters);
            const opByProvince = @json($operationalByProvince);
            const connByProvince = @json($connectivityByProvince);
            const dtcByProvince = @json($centersByHostProvince);
            const dtcHostLabels = Object.keys(@json($hostTypeLabels));

            function slugify(text) {
                return String(text).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            }

            function buildPie(id, labels, values, colors, title) {
                const el = document.getElementById(id);
                if (!el) return;
                if (window.dashboardChartInstances[id]) {
                    window.dashboardChartInstances[id].destroy();
                }
                const options = {
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
                        datasets: [{ data: values, backgroundColor: colors }]
                    },
                    options: options
                });
            }

            const sdnLabels = sdnMuni.map(r => r.municipality_city);
            const sdnValues = sdnMuni.map(r => r.total);
            const totalSdn = sdnValues.reduce((acc, val) => acc + val, 0);
            sdnLabels.push('Total Centers');
            sdnValues.push(totalSdn);

            buildPie('sdnMuniPie', sdnLabels, sdnValues, PALETTE, 'Surigao del Norte');

            buildPie('dinagatMuniPie', dinagatMuni.map(r => r.municipality_city), dinagatMuni.map(r => r.total), PALETTE, 'Dinagat Islands');

            Object.keys(dtcByProvince).forEach(function (province) {
                const counts = dtcByProvince[province] || {};
                const labels = dtcHostLabels;
                const values = labels.map(l => counts[l] || 0);
                const colors = labels.map((_, i) => PALETTE[i % PALETTE.length]);
                buildPie('dtc-' + slugify(province), labels, values, colors, province);
            });

            const sdnOp = opByProvince['Surigao del Norte'] || {};
            const dinOp = opByProvince['Dinagat Islands'] || {};
            buildPie('op-operational', ['Surigao del Norte', 'Dinagat Islands'], [sdnOp['Operational'] || 0, dinOp['Operational'] || 0], [PALETTE[0], PALETTE[1]], 'Operational');
            buildPie('op-non-operational', ['Surigao del Norte', 'Dinagat Islands'], [sdnOp['Non-Operational'] || 0, dinOp['Non-Operational'] || 0], [PALETTE[5], PALETTE[16]], 'Non-Operational');

            const sdnConn = connByProvince['Surigao del Norte'] || {};
            const dinConn = connByProvince['Dinagat Islands'] || {};
            buildPie('conn-online', ['Surigao del Norte', 'Dinagat Islands'], [sdnConn['Online'] || 0, dinConn['Online'] || 0], [PALETTE[1], PALETTE[17]], 'Online');
            buildPie('conn-offline', ['Surigao del Norte', 'Dinagat Islands'], [sdnConn['Offline'] || 0, dinConn['Offline'] || 0], [PALETTE[10], PALETTE[4]], 'Offline');
        };

        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('year-change', function (e) { loadDtcCharts(e.detail.year); });
        });
    </script>
    @endpush
</x-app-layout>
