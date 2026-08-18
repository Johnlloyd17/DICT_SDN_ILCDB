<x-app-layout title="SPARK Trainings">
    {{-- BREADCRUMBS --}}
    <x-breadcrumbs :items="[['label' => 'SPARK', 'icon' => 'fa-bolt text-yellow-600']]" />

    {{-- SPARK BANNER --}}
    <div class="bg-gradient-to-r from-amber-800 to-yellow-900 text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-400"></i> SPARK Program (Specialized ICT Accelerator)
            </h2>
            <p class="text-sm text-yellow-100 mt-1">High-impact specialized tech trainings, financial tracking, participant penetration, and master trainers.</p>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Budget</span>
                <i class="fa-solid fa-wallet text-amber-600"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800">₱{{ number_format($totalBudget) }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">Allocated Across All Batches</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Active Batches</span>
                <i class="fa-solid fa-bolt text-emerald-600"></i>
            </div>
            <h3 class="text-2xl font-black text-emerald-700">{{ $activeBatches }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">{{ $upcomingBatches }} Upcoming &middot; {{ $completedBatches }} Completed</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Enrolled</span>
                <i class="fa-solid fa-users text-blue-600"></i>
            </div>
            <h3 class="text-2xl font-black text-blue-700">{{ number_format($totalEnrolled) }}</h3>
            <p class="text-[10px] text-slate-400 font-medium">Trainees Across All Tracks</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Completion Rate</span>
                <i class="fa-solid fa-chart-line text-purple-600"></i>
            </div>
            <h3 class="text-2xl font-black text-purple-700">{{ $totalBudget > 0 ? round($completedBatches / ($activeBatches + $completedBatches + $upcomingBatches) * 100) : 0 }}%</h3>
            <p class="text-[10px] text-slate-400 font-medium">Batches Completed</p>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-pie text-amber-600 mr-2"></i> Training Status Distribution</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-3/4 animate-pulse">
                        <div class="h-36 w-36 rounded-full bg-slate-100 mx-auto"></div>
                        <div class="h-2 bg-slate-200 rounded w-1/2 mx-auto"></div>
                    </div>
                </div>
                <canvas id="sparkStatusChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm" x-data="{ loading: true }" x-init="$nextTick(() => setTimeout(() => loading = false, 800))">
            <h4 class="font-bold text-slate-800 text-sm mb-4"><i class="fa-solid fa-chart-bar text-yellow-600 mr-2"></i> Budget vs Enrollment by Track</h4>
            <div class="h-64 relative">
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                    <div class="space-y-3 w-full px-8 animate-pulse">
                        <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-48 bg-slate-100 rounded-lg"></div>
                    </div>
                </div>
                <canvas id="sparkBudgetChart" :class="loading ? 'opacity-0' : 'opacity-100'"></canvas>
            </div>
        </div>
    </div>

    {{-- TRAININGS TABLE + MODAL --}}
    @php $seedTrainings = json_encode($trainings->getCollection()); @endphp
    <div x-data="sparkTrainingsCrud({{ $seedTrainings }})" class="space-y-0">

        {{-- FLASH NOTICE --}}
        <div x-show="notice" x-cloak x-transition class="rounded-xl px-4 py-3 text-xs font-bold border shadow-sm flex items-center gap-2 mb-4"
             :class="noticeType === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
            <i class="fa-solid" :class="noticeType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'"></i>
            <span x-text="notice"></span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-amber-600"></i> SPARK Specialized Trainings Register
                    </h3>
                    <p class="text-xs text-slate-500">All SPARK training batches with track details, enrollment, and industry partners.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <select x-model="statusFilter" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                        <option value="ALL">All Status</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Upcoming">Upcoming</option>
                    </select>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" x-model="search" placeholder="Search courses..." class="w-full sm:w-48 pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                    <button x-on:click="showAdd = true" class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-2 rounded-lg text-[11px] font-semibold transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i> Add Training
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200 custom-scrollbar">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-600 uppercase font-semibold border-b">
                        <tr>
                            <th class="p-3">Track ID</th>
                            <th class="p-3">Specialization Course</th>
                            <th class="p-3">Master Trainer</th>
                            <th class="p-3 text-center">Enrolled Trainees</th>
                            <th class="p-3">Budget Allocated</th>
                            <th class="p-3">Industry Partner</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-medium text-slate-700">
                        <template x-for="(t, i) in pagedTrainings" :key="t.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3 font-mono text-amber-700 font-semibold" x-text="t.track_id"></td>
                                <td class="p-3 font-semibold" x-text="t.specialization"></td>
                                <td class="p-3" x-text="t.master_trainer"></td>
                                <td class="p-3 font-bold text-slate-800 text-center" x-text="t.enrolled_count"></td>
                                <td class="p-3 font-mono text-emerald-700" x-text="'₱' + Number(t.budget_allocated).toLocaleString()"></td>
                                <td class="p-3" x-text="t.industry_partner"></td>
                                <td class="p-3 text-center">
                                    <span x-show="t.status === 'Ongoing'" class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-semibold">Ongoing</span>
                                    <span x-show="t.status === 'Completed'" class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Completed</span>
                                    <span x-show="t.status === 'Upcoming'" class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Upcoming</span>
                                </td>
                                <td class="p-3 text-center">
                                    <button x-on:click="deleteRecord(t)" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div x-show="filteredTrainings.length === 0" class="text-center text-slate-400 py-12 text-xs">
                    <i class="fa-solid fa-graduation-cap text-3xl mb-2 block text-slate-300"></i>
                    No trainings found matching your filters.
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="border-t border-slate-200 pt-3 flex flex-col lg:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
                    <span>Rows per page:</span>
                    <select x-model.number="perPage" x-on:change="page = 1" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                        <template x-for="n in [5, 10, 20, 30, 50, 100]" :key="n">
                            <option :value="n" x-text="n"></option>
                        </template>
                    </select>
                </div>
                <div class="text-[11px] text-slate-500 font-medium" x-text="`Showing ${pageFrom}–${pageTo} of ${filteredTrainings.length}`"></div>
                <div class="flex items-center gap-1">
                    <button x-on:click="page = page - 1" :disabled="page <= 1" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Previous">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    </button>
                    <template x-for="p in pageNumbers" :key="'tp' + p">
                        <button x-on:click="page = p" :class="page === p ? 'bg-amber-600 text-white border-amber-600' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border">
                            <span x-text="p"></span>
                        </button>
                    </template>
                    <button x-on:click="page = page + 1" :disabled="page >= totalPages" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed" title="Next">
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ADD TRAINING MODAL --}}
        <div x-show="showAdd" x-cloak x-transition.opacity x-on:keydown.escape.window="showAdd = false" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div x-show="showAdd" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
                <div class="bg-gradient-to-r from-amber-800 to-yellow-900 text-white px-6 py-4 flex items-center justify-between shrink-0">
                    <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-bolt text-yellow-300"></i> Add SPARK Training</h3>
                    <button x-on:click="showAdd = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <form x-on:submit.prevent="addTraining($event.target)" class="p-6 space-y-4 text-xs overflow-y-auto custom-scrollbar flex-1 min-h-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Track ID</label>
                            <input type="text" name="track_id" required placeholder="SPARK-XX-00" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" required class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                                <option value="Upcoming">Upcoming</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Specialization Course</label>
                        <input type="text" name="specialization" required placeholder="e.g. Applied AI & Machine Learning" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Master Trainer</label>
                            <input type="text" name="master_trainer" required placeholder="Trainer name" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Industry Partner</label>
                            <input type="text" name="industry_partner" required placeholder="Partner org" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Enrolled Trainees</label>
                            <input type="number" name="enrolled_count" required min="0" value="0" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Budget Allocated (₱)</label>
                            <input type="number" name="budget_allocated" required min="0" step="0.01" value="0" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" x-on:click="showAdd = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg shadow text-xs">Add Training</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function sparkTrainingsCrud(seed) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const storeUrl = '{{ route("spark.trainings.store") }}';
            const destroyUrl = '{{ route("spark.trainings.destroy", ["training" => "__ID__"]) }}';
            return {
                trainings: seed,
                search: '',
                statusFilter: 'ALL',
                showAdd: false,
                notice: '',
                noticeType: 'success',
                page: 1,
                perPage: 10,

                get filteredTrainings() {
                    const q = this.search.trim().toLowerCase();
                    return this.trainings.filter(t => {
                        const haystack = [t.track_id, t.specialization, t.master_trainer, t.industry_partner, t.status].join(' ').toLowerCase();
                        const matchQ = !q || haystack.includes(q);
                        const matchSt = this.statusFilter === 'ALL' || t.status === this.statusFilter;
                        return matchQ && matchSt;
                    });
                },
                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredTrainings.length / this.perPage));
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
                    return this.filteredTrainings.length === 0 ? 0 : (this.page - 1) * this.perPage + 1;
                },
                get pageTo() {
                    return Math.min(this.page * this.perPage, this.filteredTrainings.length);
                },
                get pagedTrainings() {
                    if (this.page > this.totalPages) this.page = this.totalPages;
                    const start = (this.page - 1) * this.perPage;
                    return this.filteredTrainings.slice(start, start + this.perPage);
                },

                init() {
                    this.$watch('search', () => this.page = 1);
                    this.$watch('statusFilter', () => this.page = 1);
                },

                flash(message, type = 'success') {
                    this.notice = message;
                    this.noticeType = type;
                    clearTimeout(this._flashTimer);
                    this._flashTimer = setTimeout(() => { this.notice = ''; }, 4000);
                },

                async addTraining(form) {
                    const formData = new FormData(form);
                    try {
                        const res = await fetch(storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: formData,
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Something went wrong. Please try again.');
                        this.trainings.push(data.training);
                        form.reset();
                        this.showAdd = false;
                        this.flash('Training added successfully.');
                    } catch (e) {
                        this.flash(e.message, 'error');
                    }
                },

                async deleteRecord(r) {
                    if (!confirm('Delete this training?')) return;
                    try {
                        const url = destroyUrl.replace('__ID__', r.id);
                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Delete failed. Please try again.');
                        this.trainings = this.trainings.filter(t => t.id !== r.id);
                        this.flash('Training deleted successfully.');
                    } catch (e) {
                        this.flash(e.message, 'error');
                    }
                },
            };
        }

        document.addEventListener('alpine:init', () => {
            fetch('{{ route("api.spark.financials") }}')
                .then(r => r.json())
                .then(data => {
                    const statusLabels = Object.keys(data.budget_by_status);
                    const statusBudgets = Object.values(data.budget_by_status);

                    const colors = { 'Ongoing': '#10b981', 'Completed': '#3b82f6', 'Upcoming': '#f59e0b' };

                    new Chart(document.getElementById('sparkStatusChart'), {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{ data: statusBudgets, backgroundColor: statusLabels.map(s => colors[s] || '#94a3b8') }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
                    });

                    fetch('{{ route("api.spark.trainings") }}')
                        .then(r => r.json())
                        .then(trainings => {
                            const labels = trainings.map(t => t.track_id);
                            new Chart(document.getElementById('sparkBudgetChart'), {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        { label: 'Budget (₱)', data: trainings.map(t => t.budget_allocated), backgroundColor: '#d97706', borderRadius: 4 },
                                        { label: 'Enrolled', data: trainings.map(t => t.enrolled_count), backgroundColor: '#3b82f6', borderRadius: 4 }
                                    ]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
                                    scales: { y: { beginAtZero: true } }
                                }
                            });
                        });
                });
        });
    </script>
    @endpush
</x-app-layout>
