<x-app-layout title="PROJECT CLICK Devices">
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
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-box-archive text-emerald-600"></i> PROJECT CLICK Device Donations Register
                </h3>
                <p class="text-xs text-slate-500">List of hardware equipment donated to schools, LGUs, and Tech4ED centers.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select onchange="window.location.href='{{ route('click.devices.index') }}?status='+this.value+'&{{ http_build_query(request()->except('status', 'page')) }}'" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500">
                    <option value="ALL" {{ request('status', 'ALL') === 'ALL' ? 'selected' : '' }}>All Status</option>
                    <option value="Turned Over" {{ request('status') === 'Turned Over' ? 'selected' : '' }}>Turned Over</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Transit" {{ request('status') === 'In Transit' ? 'selected' : '' }}>In Transit</option>
                </select>
                <form method="GET" action="{{ route('click.devices.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search batches..."
                        class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <button type="submit" class="text-xs p-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500"><i class="fa-solid fa-search"></i></button>
                </form>
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
                    @forelse($devices as $d)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3">
                                <span class="font-mono font-bold text-emerald-900">{{ $d->batch_id }}</span><br>
                                <span class="text-[10px] text-slate-400 font-normal">{{ $d->donation_date->format('M d, Y') }}</span>
                            </td>
                            <td class="p-3 font-bold text-slate-800">{{ $d->device_type }}</td>
                            <td class="p-3 text-center font-bold text-emerald-700">{{ $d->quantity }} Units</td>
                            <td class="p-3 text-slate-700 font-medium">{{ $d->beneficiary }}</td>
                            <td class="p-3">{{ $d->municipality }}</td>
                            <td class="p-3 text-center">
                                @if($d->status === 'Turned Over')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">Turned Over</span>
                                @elseif($d->status === 'Pending')
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">Pending</span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full font-bold text-[10px]">In Transit</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <button x-data x-on:click="$dispatch('edit-device', { device: {{ $d->toJson() }} })" class="text-blue-400 hover:text-blue-600 p-1 mr-1" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                <form method="POST" action="{{ route('click.devices.destroy', $d) }}" class="inline" onsubmit="return confirm('Delete this donation record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400 font-medium">No device donation records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-2 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-2">
            <span class="font-semibold text-slate-700">Showing {{ $devices->total() }} device donation records</span>
            {{ $devices->links() }}
        </div>
    </div>

    {{-- ADD DEVICE MODAL --}}
    <x-modal name="addDevice" title="Log Device Donation" maxWidth="lg">
        <form method="POST" action="{{ route('click.devices.store') }}" class="space-y-4">
            @csrf
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
                <button type="button" x-on:click="$dispatch('close-modal', 'addDevice')" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 text-xs">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow text-xs">Log Donation</button>
            </div>
        </form>
    </x-modal>

    {{-- EDIT DEVICE MODAL --}}
    <div x-data="{ show: false, device: {} }" x-on:edit-device.window="show = true; device = Object.assign({}, $event.detail.device); if (device.donation_date) device.donation_date = device.donation_date.substring(0, 10);" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-x-hidden overflow-y-auto border border-slate-200 max-h-[90vh] custom-scrollbar">
            <div class="bg-gradient-to-r from-emerald-800 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen text-emerald-400"></i> Edit Device Donation</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form method="POST" class="p-6 space-y-4 text-xs" :action="'{{ url('click/devices') }}/' + (device?.id || '')">
                @csrf @method('PUT')
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
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow text-xs"><i class="fa-solid fa-save mr-1"></i> Update Record</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
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
