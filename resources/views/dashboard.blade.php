<x-app-layout title="Main Overview">
    <div class="grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-4 mb-6">
        <div class="flex items-center p-4 space-x-4 bg-white border shadow-sm rounded-xl border-slate-200">
            <div class="p-3 bg-blue-100 rounded-lg text-dict-blue">
                <i class="text-xl fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-slate-500">Total Trainees</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalTrainees) }}</h3>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 bg-white border shadow-sm rounded-xl border-slate-200">
            <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600">
                <i class="text-xl fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-slate-500">Total Allocated</p>
                <h3 class="text-2xl font-bold text-dict-blue" id="stat-funding-allocated">₱{{ number_format($totalAllocated) }}</h3>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 bg-white border shadow-sm rounded-xl border-slate-200">
            <div class="p-3 rounded-lg bg-amber-100 text-amber-600">
                <i class="text-xl fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-slate-500">Budget Disbursed</p>
                <h3 class="text-2xl font-bold text-slate-800">₱{{ number_format($totalBudget) }}</h3>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 bg-white border shadow-sm rounded-xl border-slate-200">
            <div class="p-3 rounded-lg bg-cyan-100 text-cyan-600">
                <i class="text-xl fa-solid fa-person-walking"></i>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-slate-500">DTC Foot Traffic</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalFootTraffic) }}</h3>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 bg-white border shadow-sm rounded-xl border-slate-200">
            <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600">
                <i class="text-xl fa-solid fa-laptop-code"></i>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-slate-500">CLICK Beneficiaries</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($clickBeneficiaries) }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-12">
        <div class="p-5 bg-white border shadow-sm lg:col-span-7 rounded-xl border-slate-200">
            <h3 class="flex items-center gap-2 mb-4 text-base font-bold text-slate-800">
                <i class="text-blue-600 fa-solid fa-map-location-dot"></i> Provincial Training Heatmap & Locators
            </h3>
            <div id="provincial-map" class="relative z-0 w-full border h-96 rounded-xl border-slate-200"></div>
        </div>
        <div class="p-5 bg-white border shadow-sm lg:col-span-5 rounded-xl border-slate-200">
            <h3 class="flex items-center gap-2 mb-4 text-base font-bold text-slate-800">
                <i class="fa-solid fa-calendar-days text-amber-500"></i> Training & Event Calendar
            </h3>
            <div id="calendar-container" class="w-full text-xs min-h-[360px]"></div>
        </div>
    </div>

    {{-- ROW 1: PROGRAM COMPLETION --}}
    {{-- <div class="p-5 mb-6 bg-white border shadow-sm rounded-xl border-slate-200">
        <h3 class="flex items-center gap-2 mb-3 text-base font-bold text-slate-800">
            <i class="text-blue-600 fa-solid fa-chart-pie"></i> Program Completion
        </h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between mb-1 text-xs">
                    <span class="text-slate-500">Certified</span>
                    <span class="font-bold text-emerald-600">{{ $certifiedCount }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-slate-100">
                    <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $totalTrainees > 0 ? round($certifiedCount / $totalTrainees * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1 text-xs">
                    <span class="text-slate-500">Ongoing</span>
                    <span class="font-bold text-amber-600">{{ $ongoingCount }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-slate-100">
                    <div class="h-2 rounded-full bg-amber-500" style="width: {{ $totalTrainees > 0 ? round($ongoingCount / $totalTrainees * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1 text-xs">
                    <span class="text-slate-500">Pending</span>
                    <span class="font-bold text-slate-600">{{ $pendingCount }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-slate-100">
                    <div class="h-2 rounded-full bg-slate-400" style="width: {{ $totalTrainees > 0 ? round($pendingCount / $totalTrainees * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 pt-3 mt-4 text-center border-t border-slate-100">
            <div>
                <p class="text-xs text-slate-400">Municipal LGUs</p>
                <p class="text-lg font-bold text-slate-800">{{ $municipalLGUs }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Courses</p>
                <p class="text-lg font-bold text-slate-800">{{ $totalCourses }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Trainers</p>
                <p class="text-lg font-bold text-slate-800">{{ $totalTrainers }}</p>
            </div>
        </div>
    </div> --}}

    {{-- ROW 2: FUNDING MONITORING CARDS --}}
    {{-- <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3">
        @php
            $projectStyles = [
                'DWIA-TMD' => ['icon' => 'fa-graduation-cap', 'classes' => 'border-amber-200', 'iconClass' => 'text-amber-500', 'barColor' => '#d97706'],
                'DTC HUB' => ['icon' => 'fa-building-user', 'classes' => 'border-cyan-200', 'iconClass' => 'text-cyan-500', 'barColor' => '#0891b2'],
                'SPARK' => ['icon' => 'fa-bolt', 'classes' => 'border-yellow-200', 'iconClass' => 'text-yellow-500', 'barColor' => '#ca8a04'],
                'PROJECT CLICK' => ['icon' => 'fa-laptop-code', 'classes' => 'border-emerald-200', 'iconClass' => 'text-emerald-500', 'barColor' => '#059669'],
            ];
        @endphp
        @foreach($projectFunding as $pf)
        @php $s = $projectStyles[$pf->project] ?? ['icon' => 'fa-sack-dollar', 'classes' => 'border-purple-200', 'iconClass' => 'text-purple-500', 'barColor' => '#7c3aed']; @endphp
        <div class="bg-white rounded-xl p-5 shadow-sm border {{ $s['classes'] }}">
            <h3 class="flex items-center gap-2 mb-3 text-base font-bold text-slate-800">
                <i class="fa-solid {{ $s['icon'] }} {{ $s['iconClass'] }}"></i> {{ $pf->project }}
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Allocated</span>
                    <span class="font-bold text-slate-800">₱{{ number_format($pf->total_allocated) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Obligated</span>
                    <span class="font-bold text-blue-600">₱{{ number_format($pf->total_obligated) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Disbursed</span>
                    <span class="font-bold text-emerald-600">₱{{ number_format($pf->total_disbursed) }}</span>
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100">
                <div class="w-full h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-2 rounded-full" style="width: {{ $pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0 }}%; background-color: {{ $s['barColor'] }};"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 text-right">{{ $pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0 }}% disbursed</p>
            </div>
        </div>
        @endforeach
        @php $overallRate = $totalAllocated > 0 ? round($totalBudget / $totalAllocated * 100) : 0; @endphp
        <div class="p-5 bg-white border border-purple-200 shadow-sm rounded-xl sm:col-span-2 lg:col-span-1">
            <h3 class="flex items-center gap-2 mb-3 text-base font-bold text-slate-800">
                <i class="text-purple-500 fa-solid fa-sack-dollar"></i> Funding Monitoring
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Allocated</span>
                    <span class="font-bold text-slate-800">₱{{ number_format($totalAllocated) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Obligated</span>
                    <span class="font-bold text-purple-600">₱{{ number_format($totalObligated) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Disbursed</span>
                    <span class="font-bold text-emerald-600">₱{{ number_format($totalBudget) }}</span>
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100">
                <div class="w-full h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-2 rounded-full" style="width: {{ $overallRate }}%; background-color: #7c3aed;"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 text-right">{{ $overallRate }}% disbursed</p>
            </div>
        </div>
    </div> --}}

    {{-- ANNUAL HISTORICAL PERFORMANCE TABLE --}}
    <div class="p-5 mb-6 bg-white border shadow-sm rounded-xl border-slate-200">
        <div class="flex flex-col items-start justify-between gap-3 mb-4 sm:flex-row sm:items-center">
            <div>
                <h3 class="flex items-center gap-2 text-base font-bold text-slate-800">
                    <i class="text-blue-600 fa-solid fa-rotate-left"></i> Annual Historical Performance (2022 - {{ date('Y') }})
                </h3>
                <p class="text-xs text-slate-500">Yearly comparison of total trainees, budget disbursed, DTC foot traffic, and CLICK beneficiaries</p>
            </div>
            <a href="{{ route('export.csv', 'dashboard-history') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg border border-slate-300 flex items-center gap-1.5 transition shadow-sm">
                <i class="text-sm fa-solid fa-file-csv text-emerald-600"></i> Export History
            </a>
        </div>
        <div class="overflow-x-auto border rounded-lg border-slate-200 custom-scrollbar">
            <table class="w-full text-xs text-left" id="overview-history-table">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3 whitespace-nowrap">YEAR</th>
                        <th class="p-3 whitespace-nowrap">TOTAL TRAINEES</th>
                        <th class="p-3 whitespace-nowrap">BUDGET DISBURSED</th>
                        <th class="p-3 whitespace-nowrap">DTC FOOT TRAFFIC</th>
                        <th class="p-3 whitespace-nowrap">CLICK BENEFICIARIES</th>
                        <th class="p-3 whitespace-nowrap">YOY GROWTH RATE</th>
                        <th class="p-3 whitespace-nowrap">STATUS</th>
                    </tr>
                </thead>
                <tbody class="font-medium bg-white divide-y divide-slate-200 text-slate-700">
                    @foreach($historicalData as $h)
                    <tr class="transition hover:bg-blue-50/40">
                        <td class="p-3 font-bold text-slate-800">{{ $h->year }}</td>
                        <td class="p-3 font-bold text-blue-600">{{ number_format($h->trainees) }}</td>
                        <td class="p-3 font-mono font-bold text-emerald-700">₱{{ number_format($h->budget) }}</td>
                        <td class="p-3 font-bold text-purple-700">{{ number_format($h->foot_traffic) }}</td>
                        <td class="p-3 font-bold text-amber-600">{{ number_format($h->beneficiaries) }}</td>
                        <td class="p-3">
                            @if($h->growth !== null)
                                @if($h->growth > 0)
                                <span class="flex items-center gap-1 font-bold text-emerald-600">
                                    <i class="fa-solid fa-arrow-up"></i> {{ $h->growth }}%
                                </span>
                                @elseif($h->growth < 0)
                                <span class="flex items-center gap-1 font-bold text-red-600">
                                    <i class="fa-solid fa-arrow-down"></i> {{ abs($h->growth) }}%
                                </span>
                                @else
                                <span class="text-slate-400">0%</span>
                                @endif
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="p-3">
                            @if($h->trainees > 0)
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Active</span>
                            @else
                            <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[10px] font-bold">No Data</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapContainer = document.getElementById('provincial-map');
        if (mapContainer && typeof L !== 'undefined') {
            var map = L.map('provincial-map').setView([9.7894, 125.4958], 10);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                maxNativeZoom: 18,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics'
            }).addTo(map);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                maxNativeZoom: 18
            }).addTo(map);
            var hubs = @json($hubs);
            hubs.forEach(function(hub) {
                var lat = parseFloat(hub.latitude);
                var lng = parseFloat(hub.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                L.circleMarker([lat, lng], {
                    radius: 8,
                    color: '#1e3a8a',
                    weight: 2,
                    fillColor: '#2563eb',
                    fillOpacity: 1
                }).addTo(map)
                    .bindPopup('<b>' + hub.name + '</b><br>' + hub.municipality + '<br><span class="text-xs text-slate-500">DICT SDN Active Center</span>');
            });
            var tech4edCenters = @json($tech4edCenters);
            tech4edCenters.forEach(function(center) {
                var lat = parseFloat(center.latitude);
                var lng = parseFloat(center.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                L.circleMarker([lat, lng], {
                    radius: 7,
                    color: '#1e40af',
                    weight: 1.5,
                    fillColor: center.operational_status === 'Operational' ? '#059669' : '#dc2626',
                    fillOpacity: 0.8
                }).addTo(map).bindPopup(
                    '<b>' + center.center_name + '</b><br>' +
                    (center.municipality_city || '') + (center.barangay ? ', ' + center.barangay : '') + '<br>' +
                    (center.province ? center.province + '<br>' : '') +
                    '<span class="text-xs text-slate-500">Tech4ED Center &bull; ' + (center.operational_status || 'Unknown') + '</span>'
                );
            });
        }
        var calContainer = document.getElementById('calendar-container');
        if (calContainer && typeof FullCalendar !== 'undefined') {
            var calendar = new FullCalendar.Calendar(calContainer, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
                events: @json($calendarEvents),
                height: 340
            });
            calendar.render();
        }
    });
    </script>
    @endpush
</x-app-layout>
