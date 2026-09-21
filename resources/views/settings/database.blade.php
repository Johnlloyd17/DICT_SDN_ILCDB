<x-app-layout title="Settings - Database Backup & Restore">
    <x-breadcrumbs :items="[['label' => 'Settings', 'icon' => 'fa-gear text-slate-500'], ['label' => 'Database']]" />

    {{-- BANNER --}}
    <div class="bg-gradient-to-r from-slate-800 via-slate-900 to-dict-blue text-white rounded-xl p-5 shadow-sm mb-6">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-database text-emerald-400"></i> Settings - Database Backup & Restore
        </h2>
        <p class="text-sm text-slate-300 mt-1">Administrator-only tool for full-database export (mysqldump or PHP fallback) and restore, with a mandatory pre-import safety backup.</p>
    </div>

    {{-- INLINE FILE RESULT --}}
    @if (session('backup_file'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 15000)" x-transition class="rounded-xl px-4 py-3 mb-6 text-xs font-bold border shadow-sm flex flex-col gap-1 bg-emerald-50 text-emerald-800 border-emerald-200">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                @if (session('restore_method'))
                    Restore completed.
                @else
                    Export completed.
                @endif
                File: {{ session('backup_file') }} (via {{ session('backup_method') }})
            </span>
            <a href="{{ route('settings.database.download', ['file' => session('backup_file')]) }}" class="inline-flex items-center gap-1.5 mt-1 text-emerald-700 hover:text-emerald-900">
                <i class="fa-solid fa-download"></i> Download {{ basename(session('backup_file')) }}
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ==================== EXPORT ==================== --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-file-export"></i></div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Export Database</h3>
                    <p class="text-[11px] text-slate-500">Full backup of every table - timestamped .sql file</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.database.export') }}" class="p-5" x-data="{ exporting: false }" x-on:submit="exporting = true">
                @csrf
                <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                    <p><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Covers the <strong>entire database</strong>, not a single module: TMD, DTC, SPARK, PROJECT CLICK, Funding, users, and all supporting tables.</p>
                    <p><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Uses <strong>mysqldump</strong> when available; falls back to a pure-PHP dump if the server blocks shell access.</p>
                    <p><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Saved privately under <code class="px-1 py-0.5 bg-slate-100 rounded">storage/app/backups/</code> - never publicly reachable.</p>
                    <p><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Filename format: <code class="px-1 py-0.5 bg-slate-100 rounded">ilcdb_backup_YYYYMMDD_HHMM.sql</code></p>
                </div>

                <button type="submit" :disabled="exporting"
                        class="mt-5 w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-60 disabled:cursor-not-allowed text-white font-bold rounded-lg shadow px-4 py-3 text-sm transition">
                    <i class="fa-solid fa-spinner fa-spin mr-1" x-show="exporting" x-cloak></i>
                    <i class="fa-solid fa-file-export" x-show="!exporting"></i>
                    <span x-show="exporting">Creating backup, please wait...</span>
                    <span x-show="!exporting">Export Database</span>
                </button>
            </form>
        </div>

        {{-- ==================== IMPORT ==================== --}}
        <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-red-200 bg-red-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="fa-solid fa-file-import"></i></div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800 text-red-700">Import Database</h3>
                    <p class="text-[11px] text-slate-500">Restore a .sql backup - <strong class="text-red-600">destructive</strong></p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.database.import') }}" enctype="multipart/form-data"
                  class="p-5" x-data="{ confirmText: '', fileName: '' }">
                @csrf

                <div class="bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 mb-4 text-xs text-red-800 space-y-1.5">
                    <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Warning</p>
                    <p>Import <strong>overwrites all current data</strong> in the database. This cannot be undone by the browser.</p>
                    <p>Before restoring, a <strong>safety backup of the current database is created automatically</strong> so you can recover if the wrong file is uploaded.</p>
                </div>

                <label class="block mb-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider">Backup .sql file</label>
                <input type="file" name="sql_file" accept=".sql" required
                       x-on:change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                       class="block w-full text-xs text-slate-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:text-xs file:font-semibold hover:file:bg-slate-200" />
                <p class="mt-1 text-[11px] text-slate-400" x-cloak x-show="fileName">
                    <span x-text="fileName"></span> (max 500 MB, .sql only)
                </p>

                <label class="block mt-4 mb-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider">Type IMPORT to confirm</label>
                <input type="text" name="confirmation" autocomplete="off" placeholder="IMPORT"
                       x-model="confirmText"
                       x-on:input="confirmText = $event.target.value.toUpperCase()"
                       class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500" required />

                <p class="mt-1 text-[11px] font-semibold" :class="confirmText === 'IMPORT' ? 'text-emerald-600' : 'text-red-500'" x-cloak>
                    <span x-show="confirmText === 'IMPORT'"><i class="fa-solid fa-circle-check"></i> Confirmed - the restore button is now enabled.</span>
                    <span x-show="confirmText !== 'IMPORT'"><i class="fa-solid fa-asterisk"></i> Required: type exactly <code class="font-mono">IMPORT</code> (server-side verified too).</span>
                </p>

                <button type="submit" :disabled="confirmText !== 'IMPORT'"
                        class="mt-5 w-full inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-lg shadow px-4 py-3 text-sm transition">
                    <i class="fa-solid fa-file-import"></i> Restore Database from Backup
                </button>
            </form>
        </div>
    </div>

    {{-- ==================== STORED BACKUPS ==================== --}}
    <div class="mt-8 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-sm text-slate-800">Stored Backups <span class="ml-1 text-[11px] font-semibold text-slate-400">({{ count($backups) }})</span></h3>
            <p class="text-[11px] text-slate-500">Includes the automatic safety backups created before each import. Retrieved only through this page.</p>
        </div>

        @if (count($backups) === 0)
            <div class="p-8 text-center text-xs text-slate-400">
                <i class="fa-solid fa-box-open text-2xl mb-2"></i>
                <p>No backups created yet. Use the Export button above.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <th class="px-5 py-3">Filename</th>
                            <th class="px-5 py-3">Size</th>
                            <th class="px-5 py-3">Created</th>
                            <th class="px-5 py-3 text-right">Download</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($backups as $backup)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-mono text-[11px] text-slate-700">{{ $backup['name'] }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ number_format($backup['size'] / 1024, 1) }} KB</td>
                                <td class="px-5 py-3 text-slate-500">{{ $backup['modified'] }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('settings.database.download', ['file' => $backup['name']]) }}"
                                       class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 font-semibold">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>