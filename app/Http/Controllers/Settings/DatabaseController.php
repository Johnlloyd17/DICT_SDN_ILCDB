<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function index(DatabaseBackupService $backup)
    {
        return view('settings.database', [
            'backups' => $backup->backups(),
        ]);
    }

    public function export(DatabaseBackupService $backup)
    {
        $result = $backup->export();

        return back()->with('success', 'Database exported successfully')
            ->with('backup_file', $result['file'])
            ->with('backup_method', $result['method']);
    }

    public function download(Request $request, DatabaseBackupService $backup)
    {
        $request->validate(['file' => 'required|string']);

        $filename = basename($request->file);
        if (! str_ends_with(strtolower($filename), '.sql')) {
            abort(404);
        }

        $path = $backup->backupPath($filename);
        if (! is_file($path)) {
            abort(404);
        }

        return response()->download($path, $filename);
    }

    public function import(Request $request, DatabaseBackupService $backup)
    {
        $request->validate([
            'sql_file' => ['required', 'file', 'max:512000'],
            'confirmation' => ['required', 'string'],
        ], [
            'sql_file.required' => 'Please choose a .sql file to import.',
            'sql_file.file' => 'The upload must be a valid file.',
            'sql_file.max' => 'The uploaded file is too large (maximum 500 MB).',
            'confirmation.required' => 'Type IMPORT to confirm the destructive restore.',
        ]);

        $file = $request->file('sql_file');

        if (strtolower($file->getClientOriginalExtension()) !== 'sql') {
            return back()->with('error', 'Import rejected: only .sql files are accepted.');
        }

        if ($request->input('confirmation') !== 'IMPORT') {
            return back()->with('error', 'Import cancelled: type IMPORT in the confirmation box to proceed.');
        }

        $safety = $backup->export('pre_import_safety_'.date('Ymd_His').'.sql');

        try {
            $result = $backup->import($file->getRealPath());
        } catch (\Throwable $e) {
            \Log::error('Database import failed: '.$e->getMessage());

            return back()->with('error', 'Import failed. A safety backup was created: '.$safety['file']);
        }

        Artisan::call('cache:clear');

        return back()->with('success', 'Database restored from '.$file->getClientOriginalName().'.')
            ->with('backup_file', $safety['file'])
            ->with('backup_method', $safety['method'])
            ->with('restore_method', $result['method']);
    }
}
