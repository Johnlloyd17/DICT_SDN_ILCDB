<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'SDN and PDI Tech4ED']); ?>
    <div x-data="{ techTab: '<?php echo e($activeTab); ?>', sdnView: '<?php echo e($sdnView); ?>' }">

        
        <nav class="flex items-center gap-2 text-xs font-semibold mb-4">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-500 hover:text-slate-700 flex items-center gap-1">
                <i class="fa-solid fa-chart-line"></i> Main Overview
            </a>
            <i class="fa-solid fa-chevron-right text-slate-300 text-[10px]"></i>
            <button @click="sdnView = 'overview'; techTab = 'dashboard'; const u1 = new URL(location.href); u1.searchParams.set('tab', 'dashboard'); u1.searchParams.delete('sdn_view'); history.replaceState(null, '', u1)" class="text-teal-600 hover:text-teal-800 flex items-center gap-1">
                <i class="fa-solid fa-map-location-dot"></i> SDN and PDI Tech4ED
            </button>
            <i class="fa-solid fa-chevron-right text-slate-300 text-[10px]" x-show="sdnView === 'manage'"></i>
            <span class="text-slate-600" x-show="sdnView === 'manage'">Center Management</span>
        </nav>

        
        <div class="flex space-x-2 border-b border-slate-200 pb-2 overflow-x-auto custom-scrollbar mb-6">
            <button @click="techTab = 'dashboard'; const u2 = new URL(location.href); u2.searchParams.set('tab', 'dashboard'); u2.searchParams.delete('sdn_view'); history.replaceState(null, '', u2)"
                :class="techTab === 'dashboard' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-bold px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-amber-400"></i> Dashboard
            </button>
            <button @click="techTab = 'pdi'; const u3 = new URL(location.href); u3.searchParams.set('tab', 'pdi'); history.replaceState(null, '', u3)"
                :class="techTab === 'pdi' ? 'bg-gradient-to-r from-cyan-900 to-dict-blue text-white shadow-sm' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'"
                class="text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-building-shield text-indigo-300"></i> PDI Tech4ED
            </button>
        </div>

        
        <div x-show="techTab === 'dashboard'" class="space-y-6"
            x-effect="if (techTab === 'dashboard') { setTimeout(initDashboardCharts, 60); }">

            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-cyan-900 text-white text-[10px] font-black px-2 py-1 rounded-md">SECTION 1</span>
                    <i class="fa-solid fa-tower-cell text-cyan-600"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide mt-1 mb-4">Tech4ED DTC</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <?php $__currentLoopData = $centersByHostProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $counts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 mb-2"><?php echo e($province); ?></p>
                        <div class="h-44"><canvas id="dtc-<?php echo e(Str::slug($province)); ?>"></canvas></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="overflow-x-auto">
                    <table class="row-hover w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                <?php $__currentLoopData = $hostTypeLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $dbValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider"><?php echo e($label); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total No. of Center Established</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $centersByHostProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $counts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-3 py-2 text-slate-700 font-semibold"><?php echo e($province); ?></td>
                                <?php $__currentLoopData = $hostTypeLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $dbValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-flex items-center justify-center bg-cyan-100 text-cyan-800 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($counts[$label] ?? 0); ?></span>
                                </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-3 py-2 text-center font-black text-cyan-900"><?php echo e(array_sum($counts)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-cyan-50">
                                <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                <?php $__currentLoopData = $hostTypeLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $dbValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-3 py-2.5 text-center font-black text-slate-800"><?php echo e(collect($centersByHostProvince)->sum(fn ($c) => $c[$label] ?? 0)); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-3 py-2.5 text-center font-black text-slate-900"><?php echo e(collect($centersByHostProvince)->sum(fn ($c) => array_sum($c))); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-cyan-900 text-white text-[10px] font-black px-2 py-1 rounded-md">SECTION 2</span>
                        <i class="fa-solid fa-warehouse text-cyan-600"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide mt-1 mb-4">Tech4ED Centers Established per Municipality in Province of Surigao del Norte</h4>
                    <div class="h-56 mb-4"><canvas id="sdnMuniPie"></canvas></div>
                    <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar">
                        <table class="row-hover w-full text-xs">
                            <thead class="sticky top-0">
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Municipality</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">No. of Centers</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $sdnMuniCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-medium"><?php echo e($row->municipality_city); ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-cyan-100 text-cyan-800 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($row->total); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="2" class="px-3 py-4 text-center text-slate-400">No centers recorded.</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                    <td class="px-3 py-2.5 text-center font-black text-cyan-800"><?php echo e($sdnMuniCenters->sum('total')); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-cyan-900 text-white text-[10px] font-black px-2 py-1 rounded-md">SECTION 3</span>
                        <i class="fa-solid fa-warehouse text-teal-600"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide mt-1 mb-4">Tech4ED Centers Established per Municipality in Province of Dinagat Islands</h4>
                    <div class="h-56 mb-4"><canvas id="dinagatMuniPie"></canvas></div>
                    <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar">
                        <table class="row-hover w-full text-xs">
                            <thead class="sticky top-0">
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Municipality</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">No. of Centers</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $dinagatMuniCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-medium"><?php echo e($row->municipality_city); ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-teal-100 text-teal-800 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($row->total); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="2" class="px-3 py-4 text-center text-slate-400">No centers recorded.</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-teal-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Total</td>
                                    <td class="px-3 py-2.5 text-center font-black text-teal-800"><?php echo e($dinagatMuniCenters->sum('total')); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-cyan-900 text-white text-[10px] font-black px-2 py-1 rounded-md">SECTION 4</span>
                        <i class="fa-solid fa-heart-circle-check text-emerald-600"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide mt-1 mb-4">Operational and Non-Operational Tech4ED Centers per Province</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <?php $__currentLoopData = $operationalByProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2"><?php echo e($province); ?></p>
                            <div class="h-44"><canvas id="op-<?php echo e(Str::slug($province)); ?>"></canvas></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="row-hover w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Operational</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Non-Operational</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $operationalByProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-semibold"><?php echo e($province); ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-emerald-100 text-emerald-800 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($statuses['Operational'] ?? 0); ?></span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-red-100 text-red-700 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($statuses['Non-Operational'] ?? 0); ?></span>
                                    </td>
                                    <td class="px-3 py-2 text-center font-bold text-slate-700"><?php echo e(array_sum($statuses)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Overall</td>
                                    <td class="px-3 py-2.5 text-center font-black text-emerald-700"><?php echo e(collect($operationalByProvince)->sum(fn ($s) => $s['Operational'] ?? 0)); ?></td>
                                    <td class="px-3 py-2.5 text-center font-black text-red-700"><?php echo e(collect($operationalByProvince)->sum(fn ($s) => $s['Non-Operational'] ?? 0)); ?></td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-800"><?php echo e(collect($operationalByProvince)->sum(fn ($s) => array_sum($s))); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-cyan-900 text-white text-[10px] font-black px-2 py-1 rounded-md">SECTION 5</span>
                        <i class="fa-solid fa-tower-broadcast text-blue-600"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide mt-1 mb-4">Tech4ED Centers Connectivity Status per Province</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <?php $__currentLoopData = $connectivityByProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-2"><?php echo e($province); ?></p>
                            <div class="h-44"><canvas id="conn-<?php echo e(Str::slug($province)); ?>"></canvas></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="row-hover w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase tracking-wider">Province</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Online</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Offline</th>
                                    <th class="text-center px-3 py-2.5 font-bold uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $connectivityByProvince; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province => $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-3 py-2 text-slate-700 font-semibold"><?php echo e($province); ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-blue-100 text-blue-800 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($statuses['Online'] ?? 0); ?></span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center justify-center bg-slate-200 text-slate-700 font-bold rounded-full min-w-7 px-2 py-0.5"><?php echo e($statuses['Offline'] ?? 0); ?></span>
                                    </td>
                                    <td class="px-3 py-2 text-center font-bold text-slate-700"><?php echo e(array_sum($statuses)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-cyan-50">
                                    <td class="px-3 py-2.5 font-bold text-slate-700">Overall</td>
                                    <td class="px-3 py-2.5 text-center font-black text-blue-700"><?php echo e(collect($connectivityByProvince)->sum(fn ($s) => $s['Online'] ?? 0)); ?></td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-700"><?php echo e(collect($connectivityByProvince)->sum(fn ($s) => $s['Offline'] ?? 0)); ?></td>
                                    <td class="px-3 py-2.5 text-center font-black text-slate-800"><?php echo e(collect($connectivityByProvince)->sum(fn ($s) => array_sum($s))); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
        </div>

        
        <div x-show="techTab === 'pdi'" class="space-y-6">

        
        <div x-show="sdnView === 'overview'">

            
            <div class="bg-gradient-to-r from-cyan-900 via-teal-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-cyan-400"></i> SDN Hub Overview
                    </h2>
                    <p class="text-sm text-cyan-200 mt-1">Municipality-level visitor analytics, hub distribution, and center inventory for Surigao del Norte.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="sdnView = 'manage'" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                        <i class="fa-solid fa-warehouse mr-1.5"></i> Manage Centers
                    </button>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                <?php $__currentLoopData = $districtStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district => $ds): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider"><?php echo e($district); ?></span>
                        <i class="fa-solid fa-landmark text-amber-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800"><?php echo e(number_format($ds['center_count'])); ?></h3>
                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($ds['municipality_count']); ?> municipalities &bull; <?php echo e($ds['center_count']); ?> Tech4ED centers</p>
                    <p class="text-[10px] font-semibold text-amber-700 mt-1"><?php echo e($ds['description']); ?></p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <?php $__currentLoopData = $ds['municipalities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-[10px] px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full border border-slate-200"><?php echo e($muni); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <?php $__currentLoopData = $municipalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $ms = $municipalityStats[$muni] ?? []; ?>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider"><?php echo e($muni); ?></span>
                        <i class="fa-solid fa-map-pin text-teal-600"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800"><?php echo e(number_format($ms['visitor_count'] ?? 0)); ?></h3>
                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($ms['hub_count'] ?? 0); ?> hub(s) &bull; <?php echo e($ms['unique_citizens'] ?? 0); ?> unique</p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-filter text-teal-500 text-sm"></i>
                    <select name="muni" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500">
                        <option value="ALL">All Municipalities</option>
                        <?php $__currentLoopData = $municipalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m); ?>" <?php echo e($selectedMuni === $m ? 'selected' : ''); ?>><?php echo e($m); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                        <i class="fa-solid fa-magnifying-glass"></i> Filter
                    </button>
                </div>
            </form>

            
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-teal-600"></i> DTC Center Inventory
                    </h3>
                    <div class="flex items-center gap-3">
                        <form method="GET" class="flex items-center gap-2">
                            <select name="per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-teal-500" title="Rows per page">
                                <option value="5" <?php echo e((int)request('per_page', 5) === 5 ? 'selected' : ''); ?>>5 rows</option>
                                <option value="10" <?php echo e((int)request('per_page') === 10 ? 'selected' : ''); ?>>10 rows</option>
                                <option value="20" <?php echo e((int)request('per_page') === 20 ? 'selected' : ''); ?>>20 rows</option>
                                <option value="30" <?php echo e((int)request('per_page') === 30 ? 'selected' : ''); ?>>30 rows</option>
                                <option value="40" <?php echo e((int)request('per_page') === 40 ? 'selected' : ''); ?>>40 rows</option>
                                <option value="50" <?php echo e((int)request('per_page') === 50 ? 'selected' : ''); ?>>50 rows</option>
                                <option value="100" <?php echo e((int)request('per_page') === 100 ? 'selected' : ''); ?>>100 rows</option>
                                <option value="150" <?php echo e((int)request('per_page') === 150 ? 'selected' : ''); ?>>150 rows</option>
                                <option value="200" <?php echo e((int)request('per_page') === 200 ? 'selected' : ''); ?>>200 rows</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="row-hover w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
                        <thead>
                            <tr style="background-color: #9DC3E6;">
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold;">No.</th>
                                <th colspan="5" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">CENTER DETAILS</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">GPS Coordinates</th>
                                <th colspan="4" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Date Established</th>
                                <th colspan="3" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TCMS</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">ODK<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Connectivity<br>Status</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">TYPE OF<br>CENTER HOST</th>
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">Operational<br>Status</th>
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
                            <?php $__empty_1 = true; $__currentLoopData = $sdnCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;"><?php echo e($loop->iteration); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->congressional_district ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->province ?? 'Surigao del Norte'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->municipality_city); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->barangay ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;"><?php echo e($c->center_name); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->longitude ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->latitude ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->verified ? '✓' : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->moa_date_of_signing ? $c->moa_date_of_signing->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->date_of_launching ? $c->date_of_launching->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->date_of_platform_registration ? $c->date_of_platform_registration->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_key ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_identifier ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_verification_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->odk_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->connectivity_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->type_of_center_host ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->operational_status ?? '—'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="21" style="border: 1px solid #000; text-align: center; padding: 20px; color: #999;">
                                    <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                    No centers registered yet.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4"><?php echo e($sdnCenters->links()); ?></div>
            </div>
        </div>

        
        <div x-show="sdnView === 'manage'">

            
            <div class="bg-gradient-to-r from-cyan-900 via-teal-900 to-dict-blue text-white rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-cyan-400"></i> DTC Center Inventory
                    </h2>
                    <p class="text-sm text-cyan-200 mt-1">Comprehensive registry of all DTC, Tech4ED, and partner centers across Surigao del Norte.</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button x-data x-on:click="sdnView = 'overview'" class="bg-white/15 hover:bg-white/25 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Registry
                    </button>
                    <a href="<?php echo e(route('export.csv', 'centers')); ?>" class="bg-green-700 hover:bg-green-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                        <i class="fa-solid fa-download mr-1.5"></i> Export
                    </a>
                    <button x-data x-on:click="$dispatch('open-import-center')" class="bg-blue-700 hover:bg-blue-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                        <i class="fa-solid fa-upload mr-1.5"></i> Import
                    </button>
                    <button x-data x-on:click="$dispatch('open-add-center')" class="bg-cyan-600 hover:bg-cyan-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center shadow transition">
                        <i class="fa-solid fa-plus mr-1.5"></i> Add Center
                    </button>
                </div>
            </div>

            
            <div x-data="{
                selectedIds: [],
                allIds: [<?php echo e($sdnCenters->pluck('id')->join(',')); ?>],
                toggleSelectAll() {
                    if (this.selectedIds.length === this.allIds.length && this.allIds.length > 0) {
                        this.selectedIds = [];
                    } else {
                        this.selectedIds = [...this.allIds];
                    }
                }
            }" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-list text-cyan-600"></i> Center Inventory Registry
                        </h3>
                        <form method="POST" action="<?php echo e(route('dtc.centers.batchDelete')); ?>" x-show="selectedIds.length > 0" x-cloak x-on:submit="if (!confirm('Are you sure you want to delete ' + selectedIds.length + ' selected center(s)? This action cannot be undone.')) $event.preventDefault()" class="inline-flex items-center">
                            <?php echo csrf_field(); ?>
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow transition">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Delete Selected (<span x-text="selectedIds.length"></span>)</span>
                            </button>
                        </form>
                    </div>
                    <form method="GET" class="flex items-center gap-2 flex-wrap">
                        <select name="municipality" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500">
                            <option value="ALL">All Municipalities</option>
                            <?php $__currentLoopData = $municipalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m); ?>" <?php echo e(request('municipality') === $m ? 'selected' : ''); ?>><?php echo e($m); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select name="per_page" onchange="this.form.submit()" class="text-xs p-2 border border-slate-300 rounded-lg outline-none bg-slate-50 font-medium text-slate-700 focus:ring-2 focus:ring-cyan-500" title="Rows per page">
                            <option value="5" <?php echo e((int)request('per_page', 5) === 5 ? 'selected' : ''); ?>>5 rows</option>
                            <option value="10" <?php echo e((int)request('per_page') === 10 ? 'selected' : ''); ?>>10 rows</option>
                            <option value="20" <?php echo e((int)request('per_page') === 20 ? 'selected' : ''); ?>>20 rows</option>
                            <option value="30" <?php echo e((int)request('per_page') === 30 ? 'selected' : ''); ?>>30 rows</option>
                            <option value="40" <?php echo e((int)request('per_page') === 40 ? 'selected' : ''); ?>>40 rows</option>
                            <option value="50" <?php echo e((int)request('per_page') === 50 ? 'selected' : ''); ?>>50 rows</option>
                            <option value="100" <?php echo e((int)request('per_page') === 100 ? 'selected' : ''); ?>>100 rows</option>
                            <option value="150" <?php echo e((int)request('per_page') === 150 ? 'selected' : ''); ?>>150 rows</option>
                            <option value="200" <?php echo e((int)request('per_page') === 200 ? 'selected' : ''); ?>>200 rows</option>
                        </select>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search center, municipality..." class="w-full sm:w-48 text-xs p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="row-hover w-full" style="border-collapse: collapse; min-width: 1600px; font-size: 12px; line-height: 1.2;">
                        <thead>
                            <tr style="background-color: #9DC3E6;">
                                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-size: 12px; font-weight: bold; background-color: #9DC3E6;">
                                    <input type="checkbox" :checked="selectedIds.length > 0 && selectedIds.length === allIds.length" x-on:change="toggleSelectAll()" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer" title="Select All On This Page">
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
                            <?php $__empty_1 = true; $__currentLoopData = $sdnCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr :class="selectedIds.includes(<?php echo e($c->id); ?>) ? 'bg-cyan-50/60' : ''">
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;">
                                    <input type="checkbox" value="<?php echo e($c->id); ?>" x-model.number="selectedIds" class="rounded text-cyan-700 focus:ring-cyan-500 cursor-pointer">
                                </td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; height: 32px;"><?php echo e($sdnCenters->firstItem() + $loop->index); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->congressional_district ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->province ?? 'Surigao del Norte'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->municipality_city); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->barangay ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; font-weight: bold;"><?php echo e($c->center_name); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->longitude ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->latitude ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->verified ? 'True' : 'False'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->moa_date_of_signing ? $c->moa_date_of_signing->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->date_of_launching ? $c->date_of_launching->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->date_of_platform_registration ? $c->date_of_platform_registration->format('M d, Y') : '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_key ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_identifier ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->tcms_verification_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->odk_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->connectivity_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->type_of_center_host ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px;"><?php echo e($c->operational_status ?? '—'); ?></td>
                                <td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 4px 6px; white-space: nowrap;">
                                    <button x-data x-on:click="$dispatch('edit-center', { center: <?php echo e($c->toJson()); ?> })" class="text-blue-600 hover:text-blue-800" title="Edit" style="background:none;border:none;cursor:pointer;font-size:12px;">✏️</button>
                                    <form action="<?php echo e(route('dtc.centers.destroy', $c)); ?>" method="POST" onsubmit="return confirm('Delete this center?')" class="inline" style="display:inline;">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete" style="background:none;border:none;cursor:pointer;font-size:12px;">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="22" style="border: 1px solid #000; text-align: center; padding: 20px; color: #999;">
                                    <i class="fa-solid fa-warehouse" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                    No centers found.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                    <span>Showing <?php echo e($sdnCenters->total()); ?> centers</span>
                </div>
                <div class="mt-2"><?php echo e($sdnCenters->links()); ?></div>
            </div>
        </div>

        </div>


    
    <div x-data="{ show: false }" x-on:open-add-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-plus text-cyan-400"></i> Add DTC Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="<?php echo e(route('dtc.centers.store')); ?>" method="POST" class="flex flex-col" style="max-height: 80vh;">
                <?php echo csrf_field(); ?>
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-4">
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
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Connectivity Status</label>
                            <select name="connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Connected">Connected</option>
                                <option value="Disconnected">Disconnected</option>
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
                    <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow"><i class="fa-solid fa-check mr-1"></i> Add Center</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-data="{ show: false }" x-on:open-import-center.window="show = true" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-blue-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-upload text-blue-400"></i> Import Centers</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="<?php echo e(route('dtc.centers.import')); ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                <?php echo csrf_field(); ?>
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

    
    <div x-data="{ show: false, center: {} }" x-on:edit-center.window="show = true; center = $event.detail.center" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-cyan-900 to-dict-blue text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2"><i class="fa-solid fa-pen text-cyan-400"></i> Edit Center</h3>
                <button x-on:click="show = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form method="POST" class="flex flex-col" :action="'<?php echo e(url('dtc/centers')); ?>/' + (center?.id || '')" style="max-height: 80vh;">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Center Name <span class="text-red-500">*</span></label>
                            <input type="text" name="center_name" required x-model="center.center_name" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Congressional District</label>
                            <input type="text" name="congressional_district" x-model="center.congressional_district" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Province</label>
                            <input type="text" name="province" x-model="center.province" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Municipality/City <span class="text-red-500">*</span></label>
                            <input type="text" name="municipality_city" required x-model="center.municipality_city" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Barangay</label>
                            <input type="text" name="barangay" x-model="center.barangay" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" step="any" x-model="center.longitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" step="any" x-model="center.latitude" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 font-semibold text-slate-700">
                                <input type="checkbox" name="verified" value="1" x-bind:checked="center.verified == 1 || center.verified === true" class="rounded text-cyan-600 focus:ring-cyan-500">
                                Verified
                            </label>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">MOA Date of Signing</label>
                            <input type="date" name="moa_date_of_signing" x-model="center.moa_date_of_signing" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Launching</label>
                            <input type="date" name="date_of_launching" x-model="center.date_of_launching" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Date of Platform Registration</label>
                            <input type="date" name="date_of_platform_registration" x-model="center.date_of_platform_registration" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status</label>
                            <input type="text" name="tcms_status" x-model="center.tcms_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Key</label>
                            <input type="text" name="tcms_key" x-model="center.tcms_key" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Identifier</label>
                            <input type="text" name="tcms_identifier" x-model="center.tcms_identifier" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">TCMS Verification Status</label>
                            <input type="text" name="tcms_verification_status" x-model="center.tcms_verification_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">ODK Status</label>
                            <select name="odk_status" x-model="center.odk_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Pending">Pending</option>
                                <option value="Not Started">Not Started</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Connectivity Status</label>
                            <select name="connectivity_status" x-model="center.connectivity_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Connected">Connected</option>
                                <option value="Disconnected">Disconnected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Type of Center Host</label>
                            <input type="text" name="type_of_center_host" x-model="center.type_of_center_host" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Operational Status</label>
                            <select name="operational_status" x-model="center.operational_status" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
                                <option value="">—</option>
                                <option value="Operational">Operational</option>
                                <option value="Non-Operational">Non-Operational</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 pb-6 pt-4 border-t border-slate-200 bg-white">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg shadow"><i class="fa-solid fa-save mr-1"></i> Update Center</button>
                </div>
            </form>
        </div>
    </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        window.dashboardChartInstances = window.dashboardChartInstances || {};

        window.initDashboardCharts = function () {
            if (typeof Chart === 'undefined') {
                setTimeout(window.initDashboardCharts, 250);
                return;
            }

            var PALETTE = ['#0e7490', '#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#db2777', '#0891b2', '#65a30d', '#9333ea', '#ea580c', '#0d9488', '#4f46e5', '#c026d3', '#16a34a', '#f59e0b', '#ef4444', '#0ea5e9', '#84cc16', '#f43f5e', '#78716c'];

            var sdnMuni = <?php echo json_encode($sdnMuniCenters, 15, 512) ?>;
            var dinagatMuni = <?php echo json_encode($dinagatMuniCenters, 15, 512) ?>;
            var opByProvince = <?php echo json_encode($operationalByProvince, 15, 512) ?>;
            var connByProvince = <?php echo json_encode($connectivityByProvince, 15, 512) ?>;
            var dtcByProvince = <?php echo json_encode($centersByHostProvince, 15, 512) ?>;
            var dtcHostLabels = Object.keys(<?php echo json_encode($hostTypeLabels, 15, 512) ?>);

            function slugify(text) {
                return String(text).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            }

            function buildPie(id, labels, values, colors, title) {
                var el = document.getElementById(id);
                if (!el) return;
                if (window.dashboardChartInstances[id]) {
                    window.dashboardChartInstances[id].destroy();
                }
                var options = {
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
                        datasets: [{
                            data: values,
                            backgroundColor: colors
                        }]
                    },
                    options: options
                });
            }

            buildPie('sdnMuniPie', sdnMuni.map(function (r) { return r.municipality_city; }), sdnMuni.map(function (r) { return r.total; }), PALETTE, 'Surigao del Norte');
            buildPie('dinagatMuniPie', dinagatMuni.map(function (r) { return r.municipality_city; }), dinagatMuni.map(function (r) { return r.total; }), PALETTE, 'Dinagat Islands');

            Object.keys(dtcByProvince).forEach(function (province) {
                var counts = dtcByProvince[province] || {};
                var labels = dtcHostLabels;
                var values = labels.map(function (l) { return counts[l] || 0; });
                var colors = labels.map(function (_, i) { return PALETTE[i % PALETTE.length]; });
                buildPie('dtc-' + slugify(province), labels, values, colors, province);
            });

            Object.keys(opByProvince).forEach(function (province) {
                var s = opByProvince[province] || {};
                buildPie('op-' + slugify(province), ['Operational', 'Non-Operational'], [s.Operational || 0, s['Non-Operational'] || 0], ['#059669', '#dc2626'], province);
            });

            Object.keys(connByProvince).forEach(function (province) {
                var s = connByProvince[province] || {};
                buildPie('conn-' + slugify(province), ['Online', 'Offline'], [s.Online || 0, s.Offline || 0], ['#2563eb', '#94a3b8'], province);
            });
        };
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
<?php /**PATH C:\xampp\htdocs\DICT_SDN_ILCDB\dict_sdn_ilcdb\resources\views/sdn-pdi/index.blade.php ENDPATH**/ ?>