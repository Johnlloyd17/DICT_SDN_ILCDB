<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Main Overview']); ?>
    <div class="grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-blue-100 text-dict-blue rounded-lg">
                <i class="fa-solid fa-users-viewfinder text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Total Trainees</p>
                <h3 class="text-2xl font-bold text-slate-800"><?php echo e(number_format($totalTrainees)); ?></h3>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
                <i class="fa-solid fa-coins text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Total Allocated</p>
                <h3 class="text-2xl font-bold text-dict-blue" id="stat-funding-allocated">₱<?php echo e(number_format($totalAllocated)); ?></h3>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-amber-100 text-amber-600 rounded-lg">
                <i class="fa-solid fa-sack-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Budget Disbursed</p>
                <h3 class="text-2xl font-bold text-slate-800">₱<?php echo e(number_format($totalBudget)); ?></h3>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-cyan-100 text-cyan-600 rounded-lg">
                <i class="fa-solid fa-person-walking text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">DTC Foot Traffic</p>
                <h3 class="text-2xl font-bold text-slate-800"><?php echo e(number_format($totalFootTraffic)); ?></h3>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
                <i class="fa-solid fa-laptop-code text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">CLICK Beneficiaries</p>
                <h3 class="text-2xl font-bold text-slate-800"><?php echo e(number_format($clickBeneficiaries)); ?></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        <div class="lg:col-span-7 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 mb-4">
                <i class="fa-solid fa-map-location-dot text-blue-600"></i> Provincial Training Heatmap & Locators
            </h3>
            <div id="provincial-map" class="w-full h-96 rounded-xl border border-slate-200 relative z-0"></div>
        </div>
        <div class="lg:col-span-5 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 mb-4">
                <i class="fa-solid fa-calendar-days text-amber-500"></i> Training & Event Calendar
            </h3>
            <div id="calendar-container" class="w-full text-xs min-h-[360px]"></div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 mb-6">
        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 mb-3">
            <i class="fa-solid fa-chart-pie text-blue-600"></i> Program Completion
        </h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-500">Certified</span>
                    <span class="font-bold text-emerald-600"><?php echo e($certifiedCount); ?></span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full">
                    <div class="h-2 bg-emerald-500 rounded-full" style="width: <?php echo e($totalTrainees > 0 ? round($certifiedCount / $totalTrainees * 100) : 0); ?>%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-500">Ongoing</span>
                    <span class="font-bold text-amber-600"><?php echo e($ongoingCount); ?></span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full">
                    <div class="h-2 bg-amber-500 rounded-full" style="width: <?php echo e($totalTrainees > 0 ? round($ongoingCount / $totalTrainees * 100) : 0); ?>%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-500">Pending</span>
                    <span class="font-bold text-slate-600"><?php echo e($pendingCount); ?></span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full">
                    <div class="h-2 bg-slate-400 rounded-full" style="width: <?php echo e($totalTrainees > 0 ? round($pendingCount / $totalTrainees * 100) : 0); ?>%"></div>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
            <div>
                <p class="text-xs text-slate-400">Municipal LGUs</p>
                <p class="text-lg font-bold text-slate-800"><?php echo e($municipalLGUs); ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Courses</p>
                <p class="text-lg font-bold text-slate-800"><?php echo e($totalCourses); ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Trainers</p>
                <p class="text-lg font-bold text-slate-800"><?php echo e($totalTrainers); ?></p>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <?php
            $projectStyles = [
                'DWIA-TMD' => ['icon' => 'fa-graduation-cap', 'classes' => 'border-amber-200', 'iconClass' => 'text-amber-500', 'barColor' => '#d97706'],
                'DTC HUB' => ['icon' => 'fa-building-user', 'classes' => 'border-cyan-200', 'iconClass' => 'text-cyan-500', 'barColor' => '#0891b2'],
                'SPARK' => ['icon' => 'fa-bolt', 'classes' => 'border-yellow-200', 'iconClass' => 'text-yellow-500', 'barColor' => '#ca8a04'],
                'PROJECT CLICK' => ['icon' => 'fa-laptop-code', 'classes' => 'border-emerald-200', 'iconClass' => 'text-emerald-500', 'barColor' => '#059669'],
            ];
        ?>
        <?php $__currentLoopData = $projectFunding; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $s = $projectStyles[$pf->project] ?? ['icon' => 'fa-sack-dollar', 'classes' => 'border-purple-200', 'iconClass' => 'text-purple-500', 'barColor' => '#7c3aed']; ?>
        <div class="bg-white rounded-xl p-5 shadow-sm border <?php echo e($s['classes']); ?>">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 mb-3">
                <i class="fa-solid <?php echo e($s['icon']); ?> <?php echo e($s['iconClass']); ?>"></i> <?php echo e($pf->project); ?>

            </h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Allocated</span>
                    <span class="font-bold text-slate-800">₱<?php echo e(number_format($pf->total_allocated)); ?></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Obligated</span>
                    <span class="font-bold text-blue-600">₱<?php echo e(number_format($pf->total_obligated)); ?></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Disbursed</span>
                    <span class="font-bold text-emerald-600">₱<?php echo e(number_format($pf->total_disbursed)); ?></span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-2 rounded-full" style="width: <?php echo e($pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0); ?>%; background-color: <?php echo e($s['barColor']); ?>;"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 text-right"><?php echo e($pf->total_allocated > 0 ? round($pf->total_disbursed / $pf->total_allocated * 100) : 0); ?>% disbursed</p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $overallRate = $totalAllocated > 0 ? round($totalBudget / $totalAllocated * 100) : 0; ?>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-purple-200 sm:col-span-2 lg:col-span-1">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 mb-3">
                <i class="fa-solid fa-sack-dollar text-purple-500"></i> Funding Monitoring
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Allocated</span>
                    <span class="font-bold text-slate-800">₱<?php echo e(number_format($totalAllocated)); ?></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Obligated</span>
                    <span class="font-bold text-purple-600">₱<?php echo e(number_format($totalObligated)); ?></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Disbursed</span>
                    <span class="font-bold text-emerald-600">₱<?php echo e(number_format($totalBudget)); ?></span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-2 rounded-full" style="width: <?php echo e($overallRate); ?>%; background-color: #7c3aed;"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 text-right"><?php echo e($overallRate); ?>% disbursed</p>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <div>
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left text-blue-600"></i> Annual Historical Performance (2022 - <?php echo e(date('Y')); ?>)
                </h3>
                <p class="text-xs text-slate-500">Yearly comparison of total trainees, budget disbursed, DTC foot traffic, and CLICK beneficiaries</p>
            </div>
            <a href="<?php echo e(route('export.csv', 'dashboard-history')); ?>" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg border border-slate-300 flex items-center gap-1.5 transition shadow-sm">
                <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i> Export History
            </a>
        </div>
        <div class="overflow-x-auto rounded-lg border border-slate-200 custom-scrollbar">
            <table class="w-full text-left text-xs" id="overview-history-table">
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
                <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                    <?php $__currentLoopData = $historicalData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="p-3 font-bold text-slate-800"><?php echo e($h->year); ?></td>
                        <td class="p-3 font-bold text-blue-600"><?php echo e(number_format($h->trainees)); ?></td>
                        <td class="p-3 font-bold text-emerald-700 font-mono">₱<?php echo e(number_format($h->budget)); ?></td>
                        <td class="p-3 font-bold text-purple-700"><?php echo e(number_format($h->foot_traffic)); ?></td>
                        <td class="p-3 font-bold text-amber-600"><?php echo e(number_format($h->beneficiaries)); ?></td>
                        <td class="p-3">
                            <?php if($h->growth !== null): ?>
                                <?php if($h->growth > 0): ?>
                                <span class="text-emerald-600 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up"></i> <?php echo e($h->growth); ?>%
                                </span>
                                <?php elseif($h->growth < 0): ?>
                                <span class="text-red-600 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-down"></i> <?php echo e(abs($h->growth)); ?>%
                                </span>
                                <?php else: ?>
                                <span class="text-slate-400">0%</span>
                                <?php endif; ?>
                            <?php else: ?>
                            <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-3">
                            <?php if($h->trainees > 0): ?>
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Active</span>
                            <?php else: ?>
                            <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[10px] font-bold">No Data</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapContainer = document.getElementById('provincial-map');
        if (mapContainer && typeof L !== 'undefined') {
            var map = L.map('provincial-map').setView([9.7894, 125.4958], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            var hubs = <?php echo json_encode($hubs, 15, 512) ?>;
            hubs.forEach(function(hub) {
                L.marker([hub.latitude, hub.longitude]).addTo(map)
                    .bindPopup('<b>' + hub.name + '</b><br>' + hub.municipality + '<br><span class="text-xs text-slate-500">DICT SDN Active Center</span>');
            });
        }
        var calContainer = document.getElementById('calendar-container');
        if (calContainer && typeof FullCalendar !== 'undefined') {
            var calendar = new FullCalendar.Calendar(calContainer, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
                events: <?php echo json_encode($calendarEvents, 15, 512) ?>,
                height: 340
            });
            calendar.render();
        }
    });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\DICT_SDN_ILCDB\dict_sdn_ilcdb\resources\views/dashboard.blade.php ENDPATH**/ ?>