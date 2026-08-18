<x-app-layout title="SPARK Trainees">
    {{-- SPARK TRAINEES BANNER --}}
    <div class="bg-gradient-to-r from-amber-800 to-yellow-900 text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-users text-yellow-400"></i> SPARK Trainees & Employment Tracking
            </h2>
            <p class="text-sm text-yellow-100 mt-1">Participant records, employment tracking, earnings, and completion monitoring.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button x-data x-on:click="$dispatch('open-modal', 'addTrainee')" class="bg-white/20 hover:bg-white/30 border border-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center shadow transition">
                <i class="fa-solid fa-user-plus mr-1"></i> Add Trainee
            </button>
            <a href="{{ route('export.csv', 'spark-trainees') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-medium flex items-center transition">
                <i class="fa-solid fa-download mr-1"></i> Export
            </a>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Trainees</span>
                <i class="fa-solid fa-users text-amber-600"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800">{{ $totalTrainees }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">Registered SPARK Graduates</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Avg Monthly Earnings</span>
                <i class="fa-solid fa-peso-sign text-emerald-600"></i>
            </div>
            <h3 class="text-2xl font-black text-emerald-700">₱{{ number_format($avgEarnings) }}</h3>
            <p class="text-[10px] text-emerald-600 font-semibold">From {{ $freelancers + $selfEmployed }} Freelancers/Self-Employed</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Employed</span>
                <i class="fa-solid fa-briefcase text-blue-600"></i>
            </div>
            <h3 class="text-2xl font-black text-blue-700">{{ $employed }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">In Traditional Employment</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Freelancers</span>
                <i class="fa-solid fa-laptop-code text-purple-600"></i>
            </div>
            <h3 class="text-2xl font-black text-purple-700">{{ $freelancers }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">Full-Time & Part-Time</p>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-pie text-amber-600 mr-2"></i> Employment Status Distribution</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-3/4 animate-pulse">
                        <div class="h-36 w-36 rounded-full bg-slate-100 mx-auto"></div>
                        <div class="h-2 bg-slate-200 rounded w-1/2 mx-auto"></div>
                    </div>
                </div>
                <canvas id="sparkEmploymentChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-bar text-blue-600 mr-2"></i> Trainees by Municipality</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-full px-8 animate-pulse">
                        <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-48 bg-slate-100 rounded-lg"></div>
                    </div>
                </div>
                <canvas id="sparkMunicipalityChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
    </div>

    {{-- TRAINEES TABLE --}}
    @php $seedTrainees = json_encode($trainees->getCollection()); @endphp
    <div x-data="sparkTraineesCrud({{ $seedTrainees }})" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        {{-- FLASH NOTICE --}}
        <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2 mb-4"
             :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
            <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
            <span x-text="notice"></span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-graduate text-amber-600"></i> SPARK Graduate Trainees Register
                </h3>
                <p class="text-xs text-slate-500">Individual SPARK graduates with employment status, specialty, and earnings data.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select x-model="employmentFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                    <option value="ALL">All Employment</option>
                    <option value="Employed">Employed</option>
                    <option value="Full-Time Freelancer">Full-Time Freelancer</option>
                    <option value="Part-Time Freelancer">Part-Time Freelancer</option>
                    <option value="Self-Employed">Self-Employed</option>
                </select>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Search trainees..."
                        class="w-full sm:w-48 text-xs pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-200 custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Trainee ID</th>
                        <th class="p-3">Name & Specialty</th>
                        <th class="p-3">Course</th>
                        <th class="p-3">Municipality</th>
                        <th class="p-3 text-center">Employment</th>
                        <th class="p-3 text-right">Monthly Earnings</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-700">
                    <template x-for="t in pagedTrainees" :key="t.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-mono font-bold text-amber-900" x-text="t.trainee_code"></td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800" x-text="t.full_name"></span><br>
                                <span class="text-[10px] text-amber-700 font-semibold" x-text="t.specialty"></span>
                            </td>
                            <td class="p-3 text-slate-600" x-text="t.course"></td>
                            <td class="p-3" x-text="t.municipality"></td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]" x-text="t.employment_status"></span>
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-700" x-text="'₱' + Number(t.monthly_earnings || 0).toLocaleString()"></td>
                            <td class="p-3 text-center">
                                <button x-on:click="deleteRecord(t)" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="filteredTrainees.length === 0" class="text-center text-slate-400 py-12 text-xs">
                <i class="fa-solid fa-user-slash text-3xl mb-2 block text-slate-300"></i>
                No trainees found.
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="border-t border-slate-200/80 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3 mt-4">
            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                <span>Rows per page:</span>
                <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                    <template x-for="n in [5, 10, 15, 20, 30, 50]" :key="n"><option :value="n" x-text="n"></option></template>
                </select>
            </div>
            <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}–${pageTo} of ${filteredTrainees.length}`"></div>
            <div class="flex items-center gap-1">
                <button x-on:click="page--" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                <template x-for="p in pageNumbers" :key="'tp'+p">
                    <button x-on:click="page = p" :class="page === p ? 'bg-amber-600 text-white border-amber-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border" x-text="p"></button>
                </template>
                <button x-on:click="page++" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
            </div>
        </div>
    </div>

    {{-- ADD TRAINEE MODAL --}}
    <div x-data="{ show: false }" x-on:open-modal.window="show = ($event.detail === 'addTrainee')" x-on:close-modal.window="if ($event.detail === 'addTrainee') show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-amber-800 text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-user-plus text-yellow-400"></i> Add SPARK Trainee</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="addTraineeForm" x-on:submit.prevent="addTrainee($event.target)" class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" required placeholder="e.g. Grace B. Gonzaga" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Specialty</label>
                        <input type="text" name="specialty" required placeholder="e.g. Virtual Assistant" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Course</label>
                        <input type="text" name="course" required placeholder="e.g. Virtual Assistance Masterclass" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Municipality</label>
                        <input type="text" name="municipality" required placeholder="e.g. Surigao City" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Employment Status</label>
                        <select name="employment_status" required class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <option value="Full-Time Freelancer">Full-Time Freelancer</option>
                            <option value="Part-Time Freelancer">Part-Time Freelancer</option>
                            <option value="Self-Employed">Self-Employed</option>
                            <option value="Employed">Employed</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Earnings (₱)</label>
                    <input type="number" name="monthly_earnings" min="0" step="0.01" value="0" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                    <button type="submit" form="addTraineeForm" :disabled="saving" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg shadow text-xs disabled:opacity-50"><i class="fa-solid fa-check mr-1" :class="saving && 'fa-spinner fa-spin'"></i> Add Trainee</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    window.sparkTraineesCrud = function(seed) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const storeUrl = '{{ route("spark.trainees.store") }}';
        const destroyUrl = '{{ route("spark.trainees.destroy", ["trainee" => "__ID__"]) }}';
        return {
            trainees: seed,
            search: '',
            employmentFilter: 'ALL',
            saving: false,
            notice: '',
            noticeType: 'success',
            page: 1,
            perPage: 10,
            get filteredTrainees() {
                const q = this.search.trim().toLowerCase();
                return this.trainees.filter(t => {
                    const haystack = [t.trainee_code, t.full_name, t.specialty, t.course, t.municipality, t.employment_status].join(' ').toLowerCase();
                    const matchQ = !q || haystack.includes(q);
                    const matchE = this.employmentFilter === 'ALL' || t.employment_status === this.employmentFilter;
                    return matchQ && matchE;
                });
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filteredTrainees.length / this.perPage)); },
            get pageNumbers() {
                const total = this.totalPages, cur = this.page;
                const start = Math.max(1, cur - 2), end = Math.min(total, start + 4);
                const pages = []; for (let p = start; p <= end; p++) pages.push(p); return pages;
            },
            get pageFrom() { return this.filteredTrainees.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get pageTo() { return Math.min(this.page * this.perPage, this.filteredTrainees.length); },
            get pagedTrainees() {
                if (this.page > this.totalPages) this.page = this.totalPages;
                const start = (this.page - 1) * this.perPage;
                return this.filteredTrainees.slice(start, start + this.perPage);
            },
            flash(msg, type) { this.notice = msg; this.noticeType = type || 'success'; clearTimeout(this._t); this._t = setTimeout(() => this.notice = '', 4000); },
            init() { ['search','employmentFilter'].forEach(k => this.$watch(k, () => this.page = 1)); },
            async addTrainee(form) {
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
                    if (!res.ok) throw new Error(data.message || 'Failed to add trainee.');
                    this.trainees.unshift(data.trainee);
                    form.reset();
                    this.$dispatch('close-modal', 'addTrainee');
                    this.flash('Trainee added successfully.');
                } catch(e) { this.flash(e.message, 'error'); } finally { this.saving = false; }
            },
            async deleteRecord(t) {
                if (!confirm('Delete this trainee?')) return;
                if (this.saving) return;
                this.saving = true;
                try {
                    const url = destroyUrl.replace('__ID__', t.id);
                    const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Delete failed.');
                    this.trainees = this.trainees.filter(x => x.id !== t.id);
                    this.flash('Trainee deleted.');
                } catch(e) { this.flash(e.message, 'error'); } finally { this.saving = false; }
            },
        };
    };

    document.addEventListener('alpine:init', () => {
        fetch('{{ route("api.spark.demographics") }}')
            .then(r => r.json())
            .then(data => {
                const empLabels = Object.keys(data.employment);
                const empValues = Object.values(data.employment);
                const empColors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899'];

                new Chart(document.getElementById('sparkEmploymentChart'), {
                    type: 'doughnut',
                    data: {
                        labels: empLabels,
                        datasets: [{ data: empValues, backgroundColor: empColors.slice(0, empLabels.length) }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } } }
                });

                const muniLabels = Object.keys(data.municipalities);
                const muniValues = Object.values(data.municipalities);

                new Chart(document.getElementById('sparkMunicipalityChart'), {
                    type: 'bar',
                    data: {
                        labels: muniLabels,
                        datasets: [{ label: 'Trainees', data: muniValues, backgroundColor: '#d97706', borderRadius: 4 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } } }
                });
            });
    });
    </script>
    @endpush
</x-app-layout>
