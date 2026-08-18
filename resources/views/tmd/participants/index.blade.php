<x-app-layout title="DWIA-TMD Training Participants">
    {{-- BREADCRUMBS --}}
    <x-breadcrumbs :items="[['label' => 'DWIA - TMD', 'icon' => 'fa-graduation-cap text-amber-500']]" />

    {{-- TMD BANNER --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-amber-400"></i> DWIA-TMD (Digital Workforce in ICT Academy - Training Management Division)
            </h2>
            <p class="text-sm text-blue-200 mt-1">Provincial ICT capacity building tracker, participant records, and certification management.</p>
        </div>
    </div>

    {{-- SUB-TAB NAVIGATION --}}
    <div x-data="{
        activeTab: 'participants',
        init() {
            const valid = ['participants', 'tracker', 'penetration', 'hub', 'trainers'];
            const fromHash = window.location.hash.replace('#', '');
            if (valid.includes(fromHash)) this.activeTab = fromHash;
            window.addEventListener('hashchange', () => {
                const h = window.location.hash.replace('#', '');
                if (valid.includes(h)) this.activeTab = h;
            });
        },
        setTab(tab) {
            this.activeTab = tab;
            if (window.location.hash === '#' + tab) return;
            window.location.hash = tab;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }" x-cloak class="space-y-6">
        <div class="flex space-x-2 border-b border-slate-200 pb-2 overflow-x-auto custom-scrollbar">
            <button x-on:click="setTab('participants')" :class="activeTab === 'participants' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'" class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2 transition whitespace-nowrap">
                <i class="fa-solid fa-users-between-lines text-amber-400"></i> DWIA-TMD TRAINING PARTICIPANTS
            </button>
            <button x-on:click="setTab('tracker')" :class="activeTab === 'tracker' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'" class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition whitespace-nowrap">
                <i class="fa-solid fa-list-check"></i> Batch Tracker Schedule
            </button>
            <button x-on:click="setTab('penetration')" :class="activeTab === 'penetration' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'" class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition whitespace-nowrap">
                <i class="fa-solid fa-chart-pie"></i> Participant Penetration
            </button>
            <button x-on:click="setTab('hub')" :class="activeTab === 'hub' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'" class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition whitespace-nowrap">
                <i class="fa-solid fa-chalkboard-user"></i> Course
            </button>
            <button x-on:click="setTab('trainers')" :class="activeTab === 'trainers' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'" class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition whitespace-nowrap">
                <i class="fa-solid fa-user-tie"></i> Trainer Profile
            </button>
        </div>

        {{-- ==================== PARTICIPANTS SUB-TAB ==================== --}}
        <div x-data="participantsCrud(@json($participants->getCollection()))" x-show="activeTab === 'participants'" x-cloak x-transition class="space-y-6">
            <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2"
                 :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
                <span x-text="notice"></span>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Total Trainees</span>
                        <i class="fa-solid fa-users text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">{{ $total }}</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Registered Participants</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Passed / Certified</span>
                        <i class="fa-solid fa-certificate text-emerald-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-emerald-700">{{ $certified }}</h3>
                    <p class="text-[10px] text-emerald-600 font-semibold">{{ $completionRate }}% Completion</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Certs Uploaded</span>
                        <i class="fa-solid fa-file-arrow-up text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-indigo-700">{{ $uploaded }}</h3>
                    <p class="text-[10px] text-indigo-600 font-semibold">Available for View/Print</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Ongoing Training</span>
                        <i class="fa-solid fa-user-clock text-amber-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-amber-600">{{ $ongoing }}</h3>
                    <p class="text-[10px] text-amber-600 font-semibold">Active Enrollment</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Municipal LGUs</span>
                        <i class="fa-solid fa-city text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-purple-700">{{ $lgus }}</h3>
                    <p class="text-[10px] text-slate-400">Coverage across SDN</p>
                </div>
            </div>

            {{-- PARTICIPANTS TABLE --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-id-card text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">DWIA-TMD Training Participants</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Manage participant records, certificates, and credentials.</p>
                        </div>
                    </div>
                    <button x-on:click="$dispatch('open-modal', 'addParticipant')" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 rounded-lg text-[11px] font-semibold transition inline-flex items-center justify-center gap-1.5 shadow-sm w-fit sm:w-auto">
                        <i class="fa-solid fa-user-plus"></i> Add Participant
                    </button>
                </div>
                <div class="px-5 py-3 border-b border-slate-100">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-2">
                        <div class="relative flex-1 max-w-md min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <input type="text" x-model="search" placeholder="Search name, LGU, course..." class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <select x-model="batchFilter" class="text-xs px-3 py-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                                <option value="">All Batches</option>
                                <template x-for="b in batchOptions" :key="b.id">
                                    <option :value="String(b.id)" x-text="b.batch_code + ' (' + (b.course_title || '').substring(0, 20) + ')'"></option>
                                </template>
                            </select>
                            <select x-model="certFilter" class="text-xs px-3 py-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="Uploaded">Uploaded</option>
                                <option value="Pending">Pending</option>
                                <option value="Ongoing">Ongoing</option>
                            </select>
                            <button x-on:click="search = ''; batchFilter = ''; certFilter = ''" x-show="search || batchFilter || certFilter" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
                            </button>
                            <div class="h-6 w-px bg-slate-200 mx-1 hidden lg:block"></div>
                            <label class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                                Rows:
                                <select x-model.number="perPage" x-on:change="page = 1" class="text-xs px-2 py-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                                    <template x-for="n in [5, 10, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                                        <option :value="n" x-text="n"></option>
                                    </template>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Participant ID</th>
                                <th class="px-5 py-3">Full Name</th>
                                <th class="px-5 py-3">Batch & Course</th>
                                <th class="px-5 py-3">Agency / LGU / Sector</th>
                                <th class="px-5 py-3">Municipality</th>
                                <th class="px-5 py-3">Completion Date</th>
                                <th class="px-5 py-3 text-center">Certificate Status</th>
                                <th class="px-5 py-3 text-center">Certificate Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700 bg-white">
                            <template x-for="p in pagedParticipants" :key="p.id">
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3">
                                        <span class="font-mono text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-md inline-block" x-text="p.participant_code"></span>
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-slate-800" x-text="p.full_name"></td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded w-fit" x-text="(p.trainingBatch || {}).batch_code || '-'"></span>
                                            <span class="text-[10px] text-slate-400 mt-0.5" x-text="((p.trainingBatch || {}).course_title || '').substring(0, 30)"></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-[11px]" x-text="p.agency_sector"></td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1.5 text-[11px]">
                                            <i class="fa-solid fa-city text-slate-300 text-[10px]"></i><span x-text="p.municipality"></span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-[11px] text-slate-500 whitespace-nowrap" x-text="fmtDate(p.completion_date)"></td>
                                    <td class="px-5 py-3 text-center">
                                        <span x-show="p.completion_status === 'Completed'" class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-circle-check text-[9px]"></i>Certified</span>
                                        <span x-show="p.completion_status === 'Ongoing'" class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-rotate text-[9px]"></i>Ongoing</span>
                                        <span x-show="p.completion_status !== 'Completed' && p.completion_status !== 'Ongoing'" class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1"><i class="fa-solid fa-hourglass-half text-[9px]"></i>Pending</span>
                                    </td>
                                    <td class="px-5 py-3 text-center whitespace-nowrap">
                                        <template x-if="p.certificate_file">
                                            <span class="inline-flex gap-1">
                                                <button x-on:click="$dispatch('open-cert', { participant: p, url: window.location.origin + '/storage/' + p.certificate_file })" class="bg-indigo-100 text-indigo-700 px-2.5 py-1.5 rounded-lg text-[10px] font-bold hover:bg-indigo-200 transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-eye"></i>View
                                                </button>
                                                <button x-on:click="deleteCert(p)" class="bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg text-[10px] font-bold hover:bg-red-200 transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-trash-can"></i>Remove
                                                </button>
                                            </span>
                                        </template>
                                        <template x-if="!p.certificate_file">
                                            <button x-on:click="$dispatch('open-upload', { id: p.id, name: p.full_name, code: p.participant_code })" class="bg-slate-100 text-slate-500 px-2.5 py-1.5 rounded-lg text-[10px] font-bold hover:bg-slate-200 transition inline-flex items-center gap-1">
                                                <i class="fa-solid fa-upload"></i>Upload
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <template x-if="filteredParticipants.length === 0">
                        <div class="text-center text-slate-400 py-14">
                            <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-users-slash text-xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-500">No participants found</p>
                            <p class="text-[11px] mt-1">Try adjusting your search or filters.</p>
                        </div>
                    </template>
                </div>
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/40">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-3">
                        <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}\u2013${pageTo} of ${filteredParticipants.length}`"></div>
                        <div class="flex items-center gap-1">
                            <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Previous"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                            <template x-for="pg in pageNumbers" :key="'pp' + pg">
                                <button x-on:click="setPage(pg)" :class="page === pg ? 'bg-blue-800 text-white border-blue-800' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border"><span x-text="pg"></span></button>
                            </template>
                            <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Next"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TRACKER SUB-TAB ==================== --}}
        <div x-data="batchesCrud(@json($batchesAll->getCollection()))" x-show="activeTab === 'tracker'" x-cloak x-transition class="space-y-6">
            <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2"
                 :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
                <span x-text="notice"></span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-blue-600"></i> TMD Batch Training Schedule
                    </h3>
                    <div class="flex items-center gap-2">
                        <button x-on:click="$dispatch('open-modal', 'addBatch')" class="bg-blue-800 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-plus mr-1"></i>Add Batch
                        </button>
                        <a href="{{ route('export.template', 'tmd-batches') }}" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-download mr-1"></i>Download Template
                        </a>
                        <a href="{{ route('export.csv', 'tmd-batches') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-file-csv mr-1"></i>Export CSV
                        </a>
                    </div>
                </div>
                <div class="px-4 py-2 mb-3">
                    <div class="relative max-w-xs">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" x-model="search" placeholder="Search batches..." class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-600 uppercase font-semibold border-b">
                            <tr>
                                <th class="px-4 py-3">Batch Code</th>
                                <th class="px-4 py-3">Course Title</th>
                                <th class="px-4 py-3">Venue / Location</th>
                                <th class="px-4 py-3 text-center">Target</th>
                                <th class="px-4 py-3 text-center">Enrolled</th>
                                <th class="px-4 py-3">Trainer</th>
                                <th class="px-4 py-3">Schedule</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-medium text-slate-700">
                            <template x-for="b in pagedBatches" :key="b.id">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-mono text-[11px] font-bold text-blue-700" x-text="b.batch_code"></td>
                                    <td class="px-4 py-3 font-semibold" x-text="b.course_title"></td>
                                    <td class="px-4 py-3 text-[11px]" x-text="b.venue"></td>
                                    <td class="px-4 py-3 text-center font-bold" x-text="b.target_count"></td>
                                    <td class="px-4 py-3 text-center font-bold" x-text="b.enrolled_count"></td>
                                    <td class="px-4 py-3 text-[11px]" x-text="b.trainer_name"></td>
                                    <td class="px-4 py-3 text-[11px]" x-text="fmtDateShort(b.start_date) + ' - ' + fmtDate(b.end_date)"></td>
                                    <td class="px-4 py-3">
                                        <span x-show="b.status === 'Completed'" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Completed</span>
                                        <span x-show="b.status === 'Ongoing'" class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Ongoing</span>
                                        <span x-show="b.status !== 'Completed' && b.status !== 'Ongoing'" class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Upcoming</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <template x-if="filteredBatches.length === 0">
                        <div class="text-center text-slate-400 py-12 text-xs">No batches found.</div>
                    </template>
                </div>
                <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                        <span>Rows per page:</span>
                        <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                            <template x-for="n in [5, 10, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                                <option :value="n" x-text="n"></option>
                            </template>
                        </select>
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}\u2013${pageTo} of ${filteredBatches.length}`"></div>
                    <div class="flex items-center gap-1">
                        <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <template x-for="pg in pageNumbers" :key="'bp' + pg">
                            <button x-on:click="setPage(pg)" :class="page === pg ? 'bg-blue-800 text-white border-blue-800' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border"><span x-text="pg"></span></button>
                        </template>
                        <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== PENETRATION SUB-TAB ==================== --}}
        <div x-data="penetrationCrud(@json($penetration))" x-show="activeTab === 'penetration'" x-cloak x-transition x-effect="if (activeTab === 'penetration') { setTimeout(buildTmdCharts, 60); }" class="space-y-6">
            <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2"
                 :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
                <span x-text="notice"></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-map-location-dot text-blue-600"></i> Municipal Trainee Distribution
                    </h3>
                    <div class="h-64 relative">
                        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                            <div class="space-y-3 w-full px-8 animate-pulse">
                                <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                                <div class="h-48 bg-slate-100 rounded-lg"></div>
                            </div>
                        </div>
                        <canvas id="tmdPenetrationChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-users text-purple-600"></i> Target Demographic Breakdown
                    </h3>
                    <div class="h-64 relative">
                        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                            <div class="space-y-3 w-3/4 animate-pulse">
                                <div class="h-36 w-36 rounded-full bg-slate-100 mx-auto"></div>
                                <div class="h-2 bg-slate-200 rounded w-1/2 mx-auto"></div>
                            </div>
                        </div>
                        <canvas id="tmdDemographicsChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-venus-mars text-blue-700"></i> Gender-Based Municipal Penetration
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400 font-medium" x-text="rows.length + ' municipalities'"></span>
                        <button x-on:click="$dispatch('open-modal', 'addPenetration')" class="bg-purple-700 hover:bg-purple-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-plus mr-1"></i>Add Penetration
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-800 text-white uppercase font-bold text-[11px] tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Municipality</th>
                                <th class="px-4 py-3 text-center">Male</th>
                                <th class="px-4 py-3 text-center">Female</th>
                                <th class="px-4 py-3 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                            <template x-for="row in pagedRows" :key="row.id">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-semibold" x-text="row.municipality"></td>
                                    <td class="px-4 py-3 text-center font-mono text-blue-700" x-text="row.male"></td>
                                    <td class="px-4 py-3 text-center font-mono text-pink-700" x-text="row.female"></td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-slate-800" x-text="row.total"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-100 font-bold text-slate-800 text-xs">
                            <tr>
                                <td class="px-4 py-3 uppercase tracking-wider">Grand Total</td>
                                <td class="px-4 py-3 text-center font-mono text-blue-700" x-text="grandMale"></td>
                                <td class="px-4 py-3 text-center font-mono text-pink-700" x-text="grandFemale"></td>
                                <td class="px-4 py-3 text-center font-mono" x-text="grandTotal"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <template x-if="rows.length === 0">
                        <div class="text-center text-slate-400 py-12 text-xs">No penetration data available.</div>
                    </template>
                </div>
                <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                        <span>Rows per page:</span>
                        <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                            <template x-for="n in [5, 10, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                                <option :value="n" x-text="n"></option>
                            </template>
                        </select>
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}\u2013${pageTo} of ${rows.length}`"></div>
                    <div class="flex items-center gap-1">
                        <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <template x-for="pg in pageNumbers" :key="'pen' + pg">
                            <button x-on:click="setPage(pg)" :class="page === pg ? 'bg-blue-800 text-white border-blue-800' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border"><span x-text="pg"></span></button>
                        </template>
                        <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== HUB SUB-TAB ==================== --}}
        <div x-data="coursesCrud(@json($allCourses->getCollection()))" x-show="activeTab === 'hub'" x-cloak x-transition class="space-y-6">
            <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2"
                 :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
                <span x-text="notice"></span>
            </div>
            @php
                $coursesCount = \App\Models\Course::count();
                $trainersCount = \App\Models\Trainer::where('status', 'Active')->count();
                $avgHours = \App\Models\Course::avg('duration_hours') ? round(\App\Models\Course::avg('duration_hours')) : 0;
                $certsCount = \App\Models\Course::sum(DB::raw('JSON_LENGTH(credentials)'));
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-book text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cataloged Courses</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $coursesCount }}</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-emerald-50 text-emerald-500 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-user-tie text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Resource Speakers</p>
                    <h3 class="text-2xl font-black text-emerald-600">{{ $trainersCount }}</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-hourglass-half text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Avg Course Speed</p>
                    <h3 class="text-2xl font-black text-purple-600">{{ $avgHours }}h</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-amber-50 text-amber-500 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-certificate text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Certifications Mapped</p>
                    <h3 class="text-2xl font-black text-amber-600">{{ $certsCount }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
                <div class="bg-slate-50/50 px-5 py-4 border-b border-slate-200/80 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span class="bg-indigo-100 text-indigo-700 rounded-lg p-1.5"><i class="fa-solid fa-graduation-cap"></i></span>
                        Offered Courses Registry
                    </h3>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" x-model="search" placeholder="Search courses..." class="w-52 pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button x-on:click="$dispatch('open-modal', 'addCourse')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-plus mr-1"></i>Add Course
                        </button>
                        <a href="{{ route('export.csv', 'tmd-courses') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-download mr-1"></i>Export
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Course Code</th>
                                <th class="px-5 py-3">Syllabus Title & Curriculum Details</th>
                                <th class="px-5 py-3">Specialty Track</th>
                                <th class="px-5 py-3">Format / Type</th>
                                <th class="px-5 py-3 text-center">Duration</th>
                                <th class="px-5 py-3">Accredited Credentials</th>
                                <th class="px-5 py-3 text-center">Live Runs (Completed/Total)</th>
                                <th class="px-5 py-3 text-center">Reference Folders</th>
                                <th class="px-5 py-3 text-center">Action Deck</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <template x-for="c in pagedCourses" :key="c.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-3 font-mono text-[11px] font-bold text-indigo-700" x-text="c.course_code"></td>
                                    <td class="px-5 py-3 font-semibold" x-text="c.title"></td>
                                    <td class="px-5 py-3"><span class="bg-slate-100 px-2 py-0.5 rounded text-[10px] font-bold" x-text="c.specialty_track"></span></td>
                                    <td class="px-5 py-3 text-[11px]" x-text="c.format_type"></td>
                                    <td class="px-5 py-3 text-center font-bold" x-text="c.duration_hours + 'h'"></td>
                                    <td class="px-5 py-3 text-[10px] text-slate-500" x-text="Array.isArray(c.credentials) ? c.credentials.join(', ') : (c.credentials || '')"></td>
                                    <td class="px-5 py-3 text-center font-mono text-[11px] text-slate-700" x-text="c.live_runs_completed + '/' + c.live_runs_total"></td>
                                    <td class="px-5 py-3 text-center">
                                        <template x-if="c.reference_folders">
                                            <a :href="c.reference_folders" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-[10px] font-semibold"><i class="fa-solid fa-folder-open mr-1"></i>View</a>
                                        </template>
                                        <template x-if="!c.reference_folders">
                                            <span class="text-slate-400 text-[10px]">-</span>
                                        </template>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <button x-on:click="$dispatch('edit-course', { course: c })" class="text-indigo-600 hover:text-indigo-800 mx-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button x-on:click="deleteCourse(c)" class="text-red-500 hover:text-red-700 mx-1" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <template x-if="filteredCourses.length === 0">
                        <div class="text-center text-slate-400 py-12 text-xs">No courses found.</div>
                    </template>
                </div>
                <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                        <span>Rows per page:</span>
                        <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500">
                            <template x-for="n in [5, 10, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                                <option :value="n" x-text="n"></option>
                            </template>
                        </select>
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}\u2013${pageTo} of ${filteredCourses.length}`"></div>
                    <div class="flex items-center gap-1">
                        <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <template x-for="pg in pageNumbers" :key="'cp' + pg">
                            <button x-on:click="setPage(pg)" :class="page === pg ? 'bg-indigo-600 text-white border-indigo-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border"><span x-text="pg"></span></button>
                        </template>
                        <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ==================== TRAINER PROFILE SUB-TAB (BACKEND CRUD) ==================== --}}
        @php
            $trainerProfiles = $trainers->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->full_name,
                'designation' => $t->designation,
                'specialty' => $t->specialty,
                'agency' => $t->agency,
                'contact' => $t->contact,
                'phone' => $t->phone,
                'status' => $t->status,
                'courses' => (int) $t->courses,
                'rating' => (float) $t->rating,
            ])->values();
        @endphp

        <div x-data="trainerCrud()" x-show="activeTab === 'trainers'" x-cloak x-transition class="space-y-6">
            {{-- FLASH NOTICE --}}
            <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2"
                 :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
                <span x-text="notice"></span>
            </div>

            {{-- DYNAMIC KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-user-tie text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Resource Speakers</p>
                    <h3 class="text-2xl font-black text-slate-900" x-text="trainers.length"></h3>
                    <p class="text-[10px] text-emerald-600 font-semibold" x-text="activeCount + ' Active'"></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-layer-group text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Specialty Tracks</p>
                    <h3 class="text-2xl font-black text-slate-900" x-text="specialtyCount"></h3>
                    <p class="text-[10px] text-slate-400 font-medium">Fields of Expertise</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-graduation-cap text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Courses Handled</p>
                    <h3 class="text-2xl font-black text-slate-900" x-text="totalCourses"></h3>
                    <p class="text-[10px] text-slate-400 font-medium">Total Live Runs</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                    <div class="p-2.5 bg-amber-50 text-amber-500 rounded-xl text-xs w-fit mb-2"><i class="fa-solid fa-star text-lg"></i></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Avg Rating</p>
                    <h3 class="text-2xl font-black text-slate-900" x-text="avgRating"></h3>
                    <p class="text-[10px] text-amber-500 font-semibold">Participant Feedback</p>
                </div>
            </div>

            {{-- REGISTRY TOOLBAR + TABLE --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="bg-slate-50/50 px-5 py-4 border-b border-slate-200/80 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-700 rounded-lg p-1.5"><i class="fa-solid fa-user-tie"></i></span>
                            Resource Speakers Registry
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-1">Manage accredited resource speakers — saved to the database.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" x-model="search" placeholder="Search trainer..." class="w-52 pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <select x-model="specialtyFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Tracks</option>
                            <template x-for="s in specialties" :key="s">
                                <option :value="s" x-text="s"></option>
                            </template>
                        </select>
                        <select x-model="statusFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <button x-on:click="openAdd()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 rounded-lg text-[11px] font-semibold transition">
                            <i class="fa-solid fa-plus mr-1"></i>Add Trainer
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Trainer</th>
                                <th class="px-5 py-3">Specialty Track</th>
                                <th class="px-5 py-3">Agency / Affiliation</th>
                                <th class="px-5 py-3">Contact</th>
                                <th class="px-5 py-3 text-center">Courses</th>
                                <th class="px-5 py-3 text-center">Rating</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <template x-for="(t, i) in pagedTrainers" :key="t.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-900 to-indigo-700 text-white flex items-center justify-center font-black text-xs shadow shrink-0" x-text="initialsOf(t.name)"></div>
                                            <div>
                                                <p class="font-bold text-slate-800" x-text="t.name"></p>
                                                <p class="text-[10px] text-emerald-600 font-semibold" x-text="t.designation"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3"><span class="bg-slate-100 px-2 py-0.5 rounded text-[10px] font-bold" x-text="t.specialty"></span></td>
                                    <td class="px-5 py-3 text-[11px]" x-text="t.agency"></td>
                                    <td class="px-5 py-3">
                                        <p class="text-[11px]" x-text="t.contact"></p>
                                        <p class="text-[10px] text-slate-400" x-text="t.phone"></p>
                                    </td>
                                    <td class="px-5 py-3 text-center font-mono text-[11px]" x-text="t.courses"></td>
                                    <td class="px-5 py-3 text-center"><span class="text-amber-500 font-bold" x-text="'★ ' + t.rating"></span></td>
                                    <td class="px-5 py-3 text-center">
                                        <span x-show="t.status === 'Active'" class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold" x-text="t.status"></span>
                                        <span x-show="t.status !== 'Active'" class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-bold" x-text="t.status"></span>
                                    </td>
                                    <td class="px-5 py-3 text-center whitespace-nowrap">
                                        <button x-on:click="openEdit(t)" class="text-indigo-600 hover:text-indigo-800 mx-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button x-on:click="openDelete(t)" class="text-red-500 hover:text-red-700 mx-1" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div x-show="filteredTrainers.length === 0" class="text-center text-slate-400 py-12 text-xs">
                        <i class="fa-solid fa-user-slash text-3xl mb-2 block text-slate-300"></i>
                        No trainers found matching your filters.
                    </div>
                </div>

                <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                        <span>Rows per page:</span>
                        <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <template x-for="n in [5, 10, 20, 30, 40, 50, 100, 150, 200]" :key="n">
                                <option :value="n" x-text="n"></option>
                            </template>
                        </select>
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}–${pageTo} of ${filteredTrainers.length}`"></div>
                    <div class="flex items-center gap-1">
                        <button x-on:click="setPage(page - 1)" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Previous">
                            <i class="fa-solid fa-chevron-left text-[9px]"></i>
                        </button>
                        <template x-for="p in pageNumbers" :key="'p' + p">
                            <button x-on:click="setPage(p)" :class="page === p ? 'bg-emerald-600 text-white border-emerald-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border">
                                <span x-text="p"></span>
                            </button>
                        </template>
                        <button x-on:click="setPage(page + 1)" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Next">
                            <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ADD / EDIT TRAINER MODAL --}}
            <div x-show="showForm" x-cloak x-transition.opacity x-on:keydown.escape.window="showForm = false" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
                <div x-show="showForm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
                    <div class="bg-emerald-800 text-white px-6 py-4 flex items-center justify-between shrink-0">
                        <h3 class="font-bold flex items-center gap-2">
                            <i x-show="!editing" class="fa-solid fa-user-plus text-emerald-300"></i>
                            <i x-show="editing" class="fa-solid fa-user-pen text-emerald-300"></i>
                            <span x-text="editing ? 'Edit Trainer Profile' : 'Add New Trainer'"></span>
                        </h3>
                        <button x-on:click="showForm = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
                    </div>
                    <form id="trainerForm" x-on:submit.prevent="saveTrainer()" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" required placeholder="e.g. Juan D. Dela Cruz" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Designation / Role <span class="text-red-500">*</span></label>
                                <input type="text" x-model="form.designation" required placeholder="e.g. Resource Speaker" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Status</label>
                                <select x-model="form.status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Specialty Track <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.specialty" required list="specialtyList" placeholder="e.g. Cybersecurity & Data Privacy" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            <datalist id="specialtyList">
                                <template x-for="s in specialties" :key="s">
                                    <option :value="s"></option>
                                </template>
                            </datalist>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Agency / Affiliation <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.agency" required placeholder="e.g. DICT - Surigao del Norte" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                                <input type="email" x-model="form.contact" placeholder="e.g. juan@dict.gov.ph" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Contact Number</label>
                                <input type="text" x-model="form.phone" placeholder="e.g. 0917 000 0000" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Courses Handled</label>
                                <input type="number" x-model.number="form.courses" min="0" placeholder="e.g. 5" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Rating (0 - 5)</label>
                                <input type="number" x-model.number="form.rating" min="0" max="5" step="0.1" placeholder="e.g. 4.8" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>
                    </form>
                    <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                        <button type="button" x-on:click="showForm = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                        <button type="submit" form="trainerForm" :disabled="saving" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i><span x-text="editing ? 'Update Trainer' : 'Save Trainer'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- DELETE CONFIRM MODAL --}}
            <div x-show="showDelete" x-cloak x-transition.opacity x-on:keydown.escape.window="showDelete = false" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
                <div x-show="showDelete" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
                    <div class="bg-red-600 text-white px-6 py-4 flex items-center justify-between">
                        <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-trash-can text-red-200"></i> Delete Trainer Profile</h3>
                        <button x-on:click="showDelete = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
                    </div>
                    <div class="p-6 text-xs">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center"><i class="fa-solid fa-triangle-exclamation text-lg"></i></div>
                            <div>
                                <p class="font-bold text-slate-800" x-text="deleteTarget?.name"></p>
                                <p class="text-[10px] text-emerald-600 font-semibold" x-text="deleteTarget?.designation"></p>
                            </div>
                        </div>
                        <p class="text-slate-600">Are you sure you want to remove this trainer from the registry? This action cannot be undone.</p>
                        <div class="flex justify-end gap-3 pt-5">
                            <button type="button" x-on:click="showDelete = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                            <button type="button" x-on:click="doDelete()" :disabled="saving" class="bg-red-600 hover:bg-red-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50 disabled:cursor-not-allowed"><i class="fa-solid fa-trash mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Delete Trainer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ==================== ADD PARTICIPANT MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false,
        async addParticipant(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ route('tmd.participants.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to register participant.');
                window.dispatchEvent(new CustomEvent('participant-added', { detail: data.participant }));
                this.show = false;
                form.reset();
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:open-modal.window="show = ($event.detail === 'addParticipant')" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-dict-blue text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-user-plus text-amber-400"></i> Register New Participant</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addParticipantForm" x-on:submit.prevent="addParticipant($el)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" required placeholder="e.g. Juan D. Dela Cruz" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Training Batch</label>
                        <select name="training_batch_id" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Municipality <span class="text-red-500">*</span></label>
                        <input type="text" name="municipality" required placeholder="e.g. Surigao City" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Agency / Sector <span class="text-red-500">*</span></label>
                        <input type="text" name="agency_sector" required placeholder="e.g. LGU Surigao City" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Completion Status</label>
                        <select name="completion_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Ongoing">In Progress / Enrolled</option>
                            <option value="Completed">Completed / Certified</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Certificate File (optional)</label>
                    <input type="file" name="certificate_file" accept="image/*" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs">
                </div>
            </form>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="addParticipantForm" :disabled="saving" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Register Participant</button>
            </div>
        </div>
    </div>

    {{-- ==================== VIEW CERTIFICATE MODAL ==================== --}}
    <div x-data="{ show: false, participant: null, url: '' }" x-on:open-cert.window="show = true; participant = $event.detail.participant; url = $event.detail.url" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4 overflow-y-auto custom-scrollbar">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-4xl w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden my-8">
            <div class="bg-slate-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2 min-w-0"><i class="fa-solid fa-image text-amber-400 shrink-0"></i> Certificate — <span x-text="participant?.full_name" class="text-white/80 truncate"></span></h3>
                <div class="flex items-center gap-2">
                    <a :href="url" target="_blank" class="bg-amber-500 text-slate-900 font-bold rounded-lg hover:bg-amber-400 px-3 py-1.5 text-xs"><i class="fa-solid fa-eye mr-1"></i>Open Full Image</a>
                    <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
            </div>
            <div class="p-4 bg-slate-100 flex items-center justify-center overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <img :src="url" x-show="url" class="max-w-full h-auto rounded shadow-lg">
            </div>
        </div>
    </div>

    {{-- ==================== UPLOAD CERTIFICATE MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false, id: null, name: '', code: '',
        async uploadCert(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ url('tmd/participants') }}/' + this.id + '/certificate', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to upload certificate.');
                window.dispatchEvent(new CustomEvent('certificate-updated', { detail: data.participant }));
                this.show = false;
                form.reset();
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:open-upload.window="show = true; id = $event.detail.id; name = $event.detail.name; code = $event.detail.code; saving = false;" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-slate-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-file-arrow-up text-amber-400"></i> Upload Certificate</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                    <p class="font-bold text-slate-800 break-words" x-text="name"></p>
                    <p class="text-[11px] text-slate-400 font-mono break-words" x-text="code"></p>
                </div>
                <form id="uploadForm" x-on:submit.prevent="uploadCert($el)" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="certificate_file" accept="image/*" required class="w-full p-2.5 border border-slate-300 rounded-lg text-xs mb-4">
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-[11px] text-blue-700">
                        <i class="fa-solid fa-info-circle mr-1"></i> Supported formats: JPG, PNG, WebP. Max size: 5MB.
                    </div>
                </form>
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="uploadForm" :disabled="saving" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-save mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Save Certificate</button>
            </div>
        </div>
    </div>

    {{-- ==================== ADD COURSE MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false,
        async addCourse(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ route('tmd.courses.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to add course.');
                window.dispatchEvent(new CustomEvent('course-added', { detail: data.course }));
                this.show = false;
                form.reset();
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:open-modal.window="show = ($event.detail === 'addCourse')" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-indigo-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-graduation-cap text-amber-400"></i> Add New Course</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addCourseForm" x-on:submit.prevent="addCourse($el)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                        <input type="text" name="course_code" required placeholder="e.g. TMD-101" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Format / Type <span class="text-red-500">*</span></label>
                        <input type="text" name="format_type" required placeholder="e.g. In-Person" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Syllabus Title & Curriculum Details <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Basic Digital Literacy" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Specialty Track <span class="text-red-500">*</span></label>
                        <input type="text" name="specialty_track" required placeholder="e.g. ICT Literacy" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration (hours) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_hours" required min="0" placeholder="e.g. 40" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Accredited Credentials (comma-separated)</label>
                    <input type="text" name="credentials" placeholder="e.g. Certificate of Completion, NC II" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Live Runs Completed</label>
                        <input type="number" name="live_runs_completed" min="0" value="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Live Runs Total</label>
                        <input type="number" name="live_runs_total" min="0" value="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Reference Folder URL</label>
                    <input type="url" name="reference_folders" placeholder="e.g. https://drive.google.com/..." class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </form>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="addCourseForm" :disabled="saving" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Save Course</button>
            </div>
        </div>
    </div>

    {{-- ==================== ADD BATCH MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false,
        async addBatch(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ route('tmd.batches.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to add batch.');
                window.dispatchEvent(new CustomEvent('batch-added', { detail: data.batch }));
                this.show = false;
                form.reset();
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:open-modal.window="show = ($event.detail === 'addBatch')" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-blue-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-list-check text-amber-400"></i> Add New Training Batch</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addBatchForm" x-on:submit.prevent="addBatch($el)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Course Title <span class="text-red-500">*</span></label>
                    <input type="text" name="course_title" required placeholder="e.g. Cybersecurity Fundamentals" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Venue / Location <span class="text-red-500">*</span></label>
                    <input type="text" name="venue" required placeholder="e.g. DICT SDN Regional Office" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Target Count <span class="text-red-500">*</span></label>
                        <input type="number" name="target_count" required min="0" value="25" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Enrolled Count <span class="text-red-500">*</span></label>
                        <input type="number" name="enrolled_count" required min="0" value="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Trainer / Resource Speaker <span class="text-red-500">*</span></label>
                    <input type="text" name="trainer_name" required placeholder="e.g. Maria S. Santos" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="Upcoming">Upcoming</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </form>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="addBatchForm" :disabled="saving" class="bg-blue-800 hover:bg-blue-700 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Save Batch</button>
            </div>
        </div>
    </div>

    {{-- ==================== ADD PENETRATION MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false,
        async addPenetration(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ route('tmd.penetration.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to add penetration record.');
                window.dispatchEvent(new CustomEvent('penetration-added', { detail: data.record }));
                this.show = false;
                form.reset();
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:open-modal.window="show = ($event.detail === 'addPenetration')" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-purple-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-venus-mars text-amber-400"></i> Add Municipal Penetration</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addPenetrationForm" x-on:submit.prevent="addPenetration($el)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Municipality <span class="text-red-500">*</span></label>
                    <input type="text" name="municipality" required placeholder="e.g. Surigao City" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Male Trainees <span class="text-red-500">*</span></label>
                        <input type="number" name="male" required min="0" value="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Female Trainees <span class="text-red-500">*</span></label>
                        <input type="number" name="female" required min="0" value="0" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl border border-purple-100 text-[11px] text-purple-700">
                    <i class="fa-solid fa-info-circle mr-1"></i> The Total column is computed automatically as Male + Female.
                </div>
            </form>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="addPenetrationForm" :disabled="saving" class="bg-purple-700 hover:bg-purple-600 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Save Penetration</button>
            </div>
        </div>
    </div>

    {{-- ==================== EDIT COURSE MODAL (AJAX) ==================== --}}
    <div x-data="{
        show: false, saving: false, cid: null, ccode: '', ctitle: '', ctrack: '', cformat: '', cduration: 0, ccreds: '', crunsc: 0, crunst: 0, cfolder: '',
        async updateCourse(form) {
            if (this.saving) return; this.saving = true;
            try {
                const fd = new FormData(form);
                fd.append('_method', 'PUT');
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const res = await fetch('{{ url('tmd/courses') }}/' + this.cid, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to update course.');
                window.dispatchEvent(new CustomEvent('course-updated', { detail: data.course }));
                this.show = false;
            } catch(e) { alert(e.message); } finally { this.saving = false; }
        }
    }" x-on:edit-course.window="show = true; cid = $event.detail.course.id; ccode = $event.detail.course.course_code; ctitle = $event.detail.course.title; ctrack = $event.detail.course.specialty_track; cformat = $event.detail.course.format_type; cduration = $event.detail.course.duration_hours; ccreds = Array.isArray($event.detail.course.credentials) ? $event.detail.course.credentials.join(', ') : ($event.detail.course.credentials || ''); crunsc = $event.detail.course.live_runs_completed; crunst = $event.detail.course.live_runs_total; cfolder = $event.detail.course.reference_folders || ''; saving = false;" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
            <div class="bg-indigo-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-amber-400"></i> Edit Course</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="editCourseForm" x-on:submit.prevent="updateCourse($el)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                        <input type="text" name="course_code" required x-model="ccode" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Format / Type <span class="text-red-500">*</span></label>
                        <input type="text" name="format_type" required x-model="cformat" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Syllabus Title & Curriculum Details <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required x-model="ctitle" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Specialty Track <span class="text-red-500">*</span></label>
                        <input type="text" name="specialty_track" required x-model="ctrack" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration (hours) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_hours" required min="0" x-model="cduration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Accredited Credentials (comma-separated)</label>
                    <input type="text" name="credentials" x-model="ccreds" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Live Runs Completed</label>
                        <input type="number" name="live_runs_completed" min="0" x-model="crunsc" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Live Runs Total</label>
                        <input type="number" name="live_runs_total" min="0" x-model="crunst" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Reference Folder URL</label>
                    <input type="url" name="reference_folders" x-model="cfolder" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </form>
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button type="button" x-on:click="show = false" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                <button type="submit" form="editCourseForm" :disabled="saving" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50"><i class="fa-solid fa-save mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Update Course</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    window._fmtDate = function(d) {
        if (!d) return '-';
        try { return new Date(d + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); } catch(e) { return d; }
    };
    window._fmtDateShort = function(d) {
        if (!d) return '-';
        try { return new Date(d + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric' }); } catch(e) { return d; }
    };

    window.participantsCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const destroyCertUrl = '{{ route("tmd.participants.certificate.delete", ["participant" => "__ID__"]) }}';
        return {
            participants: seed,
            search: '', batchFilter: '', certFilter: '',
            saving: false, notice: '', noticeType: 'success',
            page: 1, perPage: 5,
            get filteredParticipants() {
                const q = this.search.trim().toLowerCase();
                return this.participants.filter(p => {
                    const batch = p.trainingBatch || {};
                    const haystack = [p.full_name, p.municipality, p.agency_sector, p.participant_code, batch.batch_code || '', batch.course_title || ''].join(' ').toLowerCase();
                    const matchQ = !q || haystack.includes(q);
                    const matchB = !this.batchFilter || String(p.training_batch_id) === this.batchFilter;
                    const matchC = !this.certFilter ||
                        (this.certFilter === 'Uploaded' && p.certificate_file) ||
                        (this.certFilter === 'Pending' && p.completion_status === 'Pending') ||
                        (this.certFilter === 'Ongoing' && p.completion_status === 'Ongoing');
                    return matchQ && matchB && matchC;
                });
            },
            get batchOptions() {
                const map = new Map();
                this.participants.forEach(p => {
                    const tb = p.trainingBatch;
                    if (tb && !map.has(String(tb.id))) map.set(String(tb.id), tb);
                });
                return [...map.values()];
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredParticipants.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, current = this.page, start = Math.max(1, current - 2), end = Math.min(total, start + 4), pages = [];
                for (let p = start; p <= end; p++) pages.push(p);
                return pages;
            },
            get pageFrom() { return this.filteredParticipants.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredParticipants.length); },
            get pagedParticipants() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const s = (this.page - 1) * this.perPage;
                return this.filteredParticipants.slice(s, s + this.perPage);
            },
            setPage(p) { if (p >= 1 && p <= this.totalPages) this.page = p; },
            fmtDate(d) { return window._fmtDate(d); },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() {
                ['search','batchFilter','certFilter'].forEach(k => this.$watch(k, () => this.page = 1));
                window.addEventListener('participant-added', (e) => {
                    this.participants.unshift(e.detail);
                    this.flash('Participant registered successfully.');
                });
                window.addEventListener('certificate-updated', (e) => {
                    const idx = this.participants.findIndex(p => p.id === e.detail.id);
                    if (idx > -1) this.participants[idx] = { ...this.participants[idx], ...e.detail };
                    this.flash('Certificate uploaded successfully.');
                });
            },
            async deleteCert(p) {
                if (!confirm('Remove this certificate?')) return;
                try {
                    const url = destroyCertUrl.replace('__ID__', p.id);
                    const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed.');
                    const idx = this.participants.findIndex(x => x.id === p.id);
                    if (idx > -1) this.participants[idx].certificate_file = null;
                    this.flash('Certificate removed.');
                } catch(e) { this.flash(e.message, 'error'); }
            },
        };
    };

    window.batchesCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        return {
            batches: seed,
            page: 1, perPage: 10,
            search: '',
            notice: '', noticeType: 'success',
            get filteredBatches() {
                const q = this.search.trim().toLowerCase();
                return this.batches.filter(b => !q || [b.batch_code, b.course_title, b.venue, b.trainer_name].join(' ').toLowerCase().includes(q));
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredBatches.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, current = this.page, start = Math.max(1, current - 2), end = Math.min(total, start + 4), pages = [];
                for (let p = start; p <= end; p++) pages.push(p);
                return pages;
            },
            get pageFrom() { return this.filteredBatches.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredBatches.length); },
            get pagedBatches() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const s = (this.page - 1) * this.perPage;
                return this.filteredBatches.slice(s, s + this.perPage);
            },
            setPage(p) { if (p >= 1 && p <= this.totalPages) this.page = p; },
            fmtDate(d) { return window._fmtDate(d); },
            fmtDateShort(d) { return window._fmtDateShort(d); },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() {
                this.$watch('search', () => this.page = 1);
                window.addEventListener('batch-added', (e) => {
                    this.batches.unshift(e.detail);
                    this.flash('Training batch added successfully.');
                });
            },
        };
    };

    window.penetrationCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        return {
            rows: seed,
            page: 1, perPage: 10,
            notice: '', noticeType: 'success',
            get grandMale() { return this.rows.reduce((s, r) => s + (r.male || 0), 0); },
            get grandFemale() { return this.rows.reduce((s, r) => s + (r.female || 0), 0); },
            get grandTotal() { return this.rows.reduce((s, r) => s + (r.total || 0), 0); },
            get totalPages() { return Math.max(1, Math.ceil(this.rows.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, current = this.page, start = Math.max(1, current - 2), end = Math.min(total, start + 4), pages = [];
                for (let p = start; p <= end; p++) pages.push(p);
                return pages;
            },
            get pageFrom() { return this.rows.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.rows.length); },
            get pagedRows() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const s = (this.page - 1) * this.perPage;
                return this.rows.slice(s, s + this.perPage);
            },
            setPage(p) { if (p >= 1 && p <= this.totalPages) this.page = p; },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() {
                window.addEventListener('penetration-added', (e) => {
                    this.rows.push(e.detail);
                    this.flash('Penetration record added successfully.');
                });
            },
        };
    };

    window.coursesCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const destroyUrl = '{{ url("tmd/courses") }}/__ID__';
        return {
            courses: seed,
            page: 1, perPage: 10,
            search: '',
            notice: '', noticeType: 'success',
            get filteredCourses() {
                const q = this.search.trim().toLowerCase();
                return this.courses.filter(c => !q || [c.course_code, c.title, c.specialty_track, c.format_type].join(' ').toLowerCase().includes(q));
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredCourses.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, current = this.page, start = Math.max(1, current - 2), end = Math.min(total, start + 4), pages = [];
                for (let p = start; p <= end; p++) pages.push(p);
                return pages;
            },
            get pageFrom() { return this.filteredCourses.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredCourses.length); },
            get pagedCourses() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const s = (this.page - 1) * this.perPage;
                return this.filteredCourses.slice(s, s + this.perPage);
            },
            setPage(p) { if (p >= 1 && p <= this.totalPages) this.page = p; },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() {
                this.$watch('search', () => this.page = 1);
                window.addEventListener('course-added', (e) => {
                    this.courses.push(e.detail);
                    this.flash('Course added successfully.');
                });
                window.addEventListener('course-updated', (e) => {
                    const idx = this.courses.findIndex(c => c.id === e.detail.id);
                    if (idx > -1) this.courses[idx] = e.detail;
                    this.flash('Course updated successfully.');
                });
            },
            async deleteCourse(c) {
                if (!confirm('Delete this course?')) return;
                try {
                    const res = await fetch(destroyUrl.replace('__ID__', c.id), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed to delete course.');
                    this.courses = this.courses.filter(x => x.id !== c.id);
                    this.flash('Course deleted.');
                } catch(e) { this.flash(e.message, 'error'); }
            },
        };
    };
    window.trainerCrud = function() {
        const seed = @json($trainerProfiles);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const storeUrl = '{{ route('tmd.trainers.store') }}';
        const updateUrl = '{{ route('tmd.trainers.update', ['trainer' => '__ID__']) }}';
        const destroyUrl = '{{ route('tmd.trainers.destroy', ['trainer' => '__ID__']) }}';
        return {
            trainers: seed,
            search: '',
            specialtyFilter: '',
            statusFilter: '',
            showForm: false,
            showDelete: false,
            editing: false,
            editIndex: null,
            deleteTarget: null,
            saving: false,
            notice: '',
            noticeType: 'success',
            page: 1,
            perPage: 5,
            form: { name: '', designation: '', specialty: '', agency: '', contact: '', phone: '', status: 'Active', courses: 0, rating: 0 },

            get filteredTrainers() {
                const q = this.search.trim().toLowerCase();
                return this.trainers.filter(t => {
                    const haystack = [t.name, t.designation, t.specialty, t.agency, t.contact].join(' ').toLowerCase();
                    const matchQ = !q || haystack.includes(q);
                    const matchS = !this.specialtyFilter || t.specialty === this.specialtyFilter;
                    const matchSt = !this.statusFilter || t.status === this.statusFilter;
                    return matchQ && matchS && matchSt;
                });
            },
            get specialties() {
                return [...new Set(this.trainers.map(t => t.specialty))].sort();
            },
            get activeCount() {
                return this.trainers.filter(t => t.status === 'Active').length;
            },
            get specialtyCount() {
                return new Set(this.trainers.map(t => t.specialty)).size;
            },
            get totalCourses() {
                return this.trainers.reduce((s, t) => s + (Number(t.courses) || 0), 0);
            },
            get avgRating() {
                if (!this.trainers.length) return '0.0';
                return (this.trainers.reduce((s, t) => s + (Number(t.rating) || 0), 0) / this.trainers.length).toFixed(1);
            },
            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredTrainers.length / this.perPage));
            },
            get pageNumbers() {
                const total = this.totalPages;
                const current = this.page;
                const start = Math.max(1, current - 2);
                const end = Math.min(total, start + 4);
                const pages = [];
                for (let p = start; p <= end; p++) pages.push(p);
                return pages;
            },
            get pageFrom() {
                return this.filteredTrainers.length === 0 ? 0 : (this.page - 1) * this.perPage + 1;
            },
            get pageTo() {
                return Math.min(this.page * this.perPage, this.filteredTrainers.length);
            },
            get pagedTrainers() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const start = (this.page - 1) * this.perPage;
                return this.filteredTrainers.slice(start, start + this.perPage);
            },
            setPage(p) {
                if (p >= 1 && p <= this.totalPages) this.page = p;
            },
            init() {
                ['search', 'specialtyFilter', 'statusFilter'].forEach(k => this.$watch(k, () => this.page = 1));
            },

            initialsOf(name) {
                const parts = String(name || '').trim().split(/\s+/).filter(p => p.length);
                if (!parts.length) return '??';
                const first = parts[0][0];
                const last = parts.length > 1 ? parts[parts.length - 1][0] : (parts[0][1] || '');
                return (first + last).toUpperCase();
            },
            resetForm() {
                this.form = { name: '', designation: '', specialty: '', agency: '', contact: '', phone: '', status: 'Active', courses: 0, rating: 0 };
            },
            flash(message, type = 'success') {
                this.notice = message;
                this.noticeType = type;
                clearTimeout(this._flashTimer);
                this._flashTimer = setTimeout(() => { this.notice = ''; }, 4000);
            },
            openAdd() {
                this.editing = false;
                this.editIndex = null;
                this.resetForm();
                this.showForm = true;
            },
            openEdit(t) {
                this.editing = true;
                this.editIndex = t.id;
                this.form = { ...t };
                this.showForm = true;
            },
            async saveTrainer() {
                if (this.saving) return;
                this.saving = true;
                try {
                    const url = this.editing ? updateUrl.replace('__ID__', this.editIndex) : storeUrl;
                    const res = await fetch(url, {
                        method: this.editing ? 'PUT' : 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify(this.form),
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Something went wrong. Please try again.');
                    if (this.editing) {
                        const idx = this.trainers.findIndex(t => t.id === this.editIndex);
                        if (idx > -1) this.trainers.splice(idx, 1, data.trainer);
                    } else {
                        this.trainers.push(data.trainer);
                    }
                    this.showForm = false;
                    this.flash(this.editing ? 'Trainer updated successfully.' : 'Trainer added successfully.');
                } catch (e) {
                    this.flash(e.message, 'error');
                } finally {
                    this.saving = false;
                }
            },
            openDelete(t) {
                this.deleteTarget = t;
                this.showDelete = true;
            },
            async doDelete() {
                if (this.saving || !this.deleteTarget) return;
                this.saving = true;
                try {
                    const url = destroyUrl.replace('__ID__', this.deleteTarget.id);
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Delete failed. Please try again.');
                    this.trainers = this.trainers.filter(t => t.id !== this.deleteTarget.id);
                    this.showDelete = false;
                    this.deleteTarget = null;
                    this.flash('Trainer deleted successfully.');
                } catch (e) {
                    this.flash(e.message, 'error');
                } finally {
                    this.saving = false;
                }
            },
        };
    };

    let tmdChartsBuilt = false;
    window.buildTmdCharts = function() {
        if (tmdChartsBuilt) return;
        tmdChartsBuilt = true;

        const penetrationData = @json($penetration);

        const muniCtx = document.getElementById('tmdPenetrationChart');
        if (muniCtx) {
            const labels = penetrationData.map(r => r.municipality);
            new Chart(muniCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Male', data: penetrationData.map(r => r.male), backgroundColor: '#2563eb', borderRadius: 4 },
                        { label: 'Female', data: penetrationData.map(r => r.female), backgroundColor: '#ec4899', borderRadius: 4 },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 10 } } } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { ticks: { font: { size: 9 } } }
                    }
                }
            });
        }

        const demoCtx = document.getElementById('tmdDemographicsChart');
        if (demoCtx) {
            fetch('{{ route('api.tmd.participants') }}')
                .then(r => r.json())
                .then(data => {
                    const sectors = {};
                    data.forEach(p => { sectors[p.agency_sector] = (sectors[p.agency_sector] || 0) + 1; });
                    const colors = ['#003366','#0055A5','#CE1126','#D4AF37','#FCD116','#10b981','#8b5cf6'];
                    new Chart(demoCtx, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(sectors),
                            datasets: [{ data: Object.values(sectors), backgroundColor: colors.slice(0, Object.keys(sectors).length) }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
                    });
                });
        }
    };
    </script>
    @endpush
</x-app-layout>