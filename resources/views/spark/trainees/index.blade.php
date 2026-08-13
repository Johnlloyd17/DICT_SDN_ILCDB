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
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-graduate text-amber-600"></i> SPARK Graduate Trainees Register
                </h3>
                <p class="text-xs text-slate-500">Individual SPARK graduates with employment status, specialty, and earnings data.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select onchange="window.location.href='{{ route('spark.trainees.index') }}?employment='+this.value+'&{{ http_build_query(request()->except('employment', 'page')) }}'" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-amber-500">
                    <option value="ALL" {{ request('employment', 'ALL') === 'ALL' ? 'selected' : '' }}>All Employment</option>
                    <option value="Employed" {{ request('employment') === 'Employed' ? 'selected' : '' }}>Employed</option>
                    <option value="Full-Time Freelancer" {{ request('employment') === 'Full-Time Freelancer' ? 'selected' : '' }}>Full-Time Freelancer</option>
                    <option value="Part-Time Freelancer" {{ request('employment') === 'Part-Time Freelancer' ? 'selected' : '' }}>Part-Time Freelancer</option>
                    <option value="Self-Employed" {{ request('employment') === 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                </select>
                <form method="GET" action="{{ route('spark.trainees.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainees..."
                        class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <button type="submit" class="text-xs p-2 bg-amber-600 text-white rounded-lg hover:bg-amber-500"><i class="fa-solid fa-search"></i></button>
                </form>
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
                    @forelse($trainees as $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-mono font-bold text-amber-900">{{ $t->trainee_code }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800">{{ $t->full_name }}</span><br>
                                <span class="text-[10px] text-amber-700 font-semibold">{{ $t->specialty }}</span>
                            </td>
                            <td class="p-3 text-slate-600">{{ $t->course }}</td>
                            <td class="p-3">{{ $t->municipality }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">{{ $t->employment_status }}</span>
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-700">₱{{ number_format($t->monthly_earnings ?? 0) }}</td>
                            <td class="p-3 text-center">
                                <form method="POST" action="{{ route('spark.trainees.destroy', $t) }}" class="inline" onsubmit="return confirm('Delete this trainee?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400 font-medium">No trainees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-2">{{ $trainees->links() }}</div>
    </div>

    {{-- ADD TRAINEE MODAL --}}
    <x-modal name="addTrainee" title="Add SPARK Trainee" maxWidth="lg">
        <form method="POST" action="{{ route('spark.trainees.store') }}" class="space-y-4">
            @csrf
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
                <button type="button" x-on:click="$dispatch('close-modal', 'addTrainee')" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg shadow text-xs">Add Trainee</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
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
