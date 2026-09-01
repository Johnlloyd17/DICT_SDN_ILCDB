<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? config('app.name', 'DICT SDN ILCDB')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|playfair-display:600,700|cinzel:600,700,800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased">

    
    <header class="bg-dict-blue text-white shadow-md sticky top-0 z-[1000]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-xl border border-blue-400/30 px-3 py-1.5 shadow-sm">
                        <span class="text-base leading-none">🇵🇭</span>
                        <span class="ml-2 text-sm font-black tracking-widest text-white">ILCDB</span>
                    </div>
                    <div class="hidden sm:block w-px h-8 bg-gradient-to-b from-blue-400/60 to-transparent"></div>
                    <div class="hidden sm:block">
                        <h1 class="text-[clamp(0.9rem,1rem+0.15vw,1.125rem)] font-bold leading-tight tracking-wide">DICT Provincial Portal</h1>
                        <p class="text-[clamp(0.625rem,0.6rem+0.1vw,0.75rem)] text-blue-200/80 font-medium tracking-wider uppercase">ICT Literacy & Competency Development Bureau</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center bg-blue-900/60 rounded-lg px-3 py-1.5 text-xs border border-blue-700">
                        <i class="fa-solid fa-location-dot text-yellow-400 mr-2"></i>
                        <span>Provincial Field Office: <strong>Surigao del Norte</strong></span>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-xs focus:outline-none bg-blue-900/40 p-1.5 rounded-full border border-blue-600">
                            <span class="w-7 h-7 bg-amber-400 text-dict-blue font-bold rounded-full flex items-center justify-center shadow">
                                <?php echo e(substr(Auth::user()->name, 0, 2)); ?>

                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-800"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-[10px] text-slate-400"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                            <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Profile</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </header>

    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 w-full">
        <?php echo e($slot); ?>

    </main>

    
    <?php if(session('success')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500); console.log(<?php echo \Illuminate\Support\Js::from(session('success'))->toHtml() ?>)" x-transition
         class="fixed bottom-5 right-5 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center space-x-3 text-xs z-50 border border-emerald-700">
        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500); console.error(<?php echo \Illuminate\Support\Js::from(session('error'))->toHtml() ?>)" x-transition
         class="fixed bottom-5 right-5 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center space-x-3 text-xs z-50 border border-red-700">
        <i class="fa-solid fa-circle-exclamation text-red-400 text-lg"></i>
        <span><?php echo e(session('error')); ?></span>
    </div>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\DICT_SDN_ILCDB\dict_sdn_ilcdb\resources\views/layouts/app.blade.php ENDPATH**/ ?>