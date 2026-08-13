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

    {{-- TRAININGS TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-amber-600"></i> SPARK Specialized Trainings Register
                </h3>
                <p class="text-xs text-slate-500">All SPARK training batches with track details, enrollment, and industry partners.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select onchange="window.location.href='{{ route('spark.trainings.index') }}?status='+this.value+'&{{ http_build_query(request()->except('status', 'page')) }}'" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                    <option value="ALL" {{ request('status', 'ALL') === 'ALL' ? 'selected' : '' }}>All Status</option>
                    <option value="Ongoing" {{ request('status') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Upcoming" {{ request('status') === 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
                <form method="GET" action="{{ route('spark.trainings.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..."
                        class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <button type="submit" class="text-xs p-2 bg-amber-600 text-white rounded-lg hover:bg-amber-500"><i class="fa-solid fa-search"></i></button>
                </form>
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
                    @forelse($trainings as $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-mono text-amber-700 font-semibold">{{ $t->track_id }}</td>
                            <td class="p-3 font-semibold">{{ $t->specialization }}</td>
                            <td class="p-3">{{ $t->master_trainer }}</td>
                            <td class="p-3 font-bold text-slate-800 text-center">{{ $t->enrolled_count }}</td>
                            <td class="p-3 font-mono text-emerald-700">₱{{ number_format($t->budget_allocated) }}</td>
                            <td class="p-3">{{ $t->industry_partner }}</td>
                            <td class="p-3 text-center">
                                @if($t->status === 'Ongoing')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-semibold">Ongoing</span>
                                @elseif($t->status === 'Completed')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Completed</span>
                                @else
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Upcoming</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <form method="POST" action="{{ route('spark.trainings.destroy', $t) }}" class="inline" onsubmit="return confirm('Delete this training?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400 font-medium">No trainings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-2">{{ $trainings->links() }}</div>
    </div>

    {{-- ADD TRAINING MODAL --}}
    <x-modal name="addTraining" title="Add SPARK Training" maxWidth="lg">
        <form method="POST" action="{{ route('spark.trainings.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Track ID</label>
                    <input type="text" name="track_id" required placeholder="SPARK-XX-00" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" required class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        <option value="Upcoming">Upcoming</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Specialization Course</label>
                <input type="text" name="specialization" required placeholder="e.g. Applied AI & Machine Learning" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Master Trainer</label>
                    <input type="text" name="master_trainer" required placeholder="Trainer name" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Industry Partner</label>
                    <input type="text" name="industry_partner" required placeholder="Partner org" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Enrolled Trainees</label>
                    <input type="number" name="enrolled_count" required min="0" value="0" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Budget Allocated (₱)</label>
                    <input type="number" name="budget_allocated" required min="0" step="0.01" value="0" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal', 'addTraining')" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg shadow text-xs">Add Training</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
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
