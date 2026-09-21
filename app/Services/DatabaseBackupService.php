<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DatabaseBackupService
{
    protected string $backupDir;

    /** @var array<int, string> candidate mysqldump binary paths (env override wins) */
    protected array $mysqldumpCandidates;

    /** @var array<int, string> candidate mysql client binary paths (env override wins) */
    protected array $mysqlCandidates;

    protected ?string $mysqldumpBinary = null;

    protected ?string $mysqlBinary = null;

    protected bool $binariesProbed = false;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (! is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }

        $this->mysqldumpCandidates = array_values(array_filter([
            env('DB_MYSQLDUMP'),
            'mysqldump',
            'C:\xampp\mysql\bin\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ]));

        $this->mysqlCandidates = array_values(array_filter([
            env('DB_MYSQL'),
            'mysql',
            'C:\xampp\mysql\bin\mysql.exe',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
        ]));
    }

    public function backupDirectory(): string
    {
        return $this->backupDir;
    }

    public function backupPath(string $filename): string
    {
        return $this->backupDir.DIRECTORY_SEPARATOR.basename($filename);
    }

    public function backups(): array
    {
        $files = glob($this->backupDir.'/*.sql') ?: [];
        rsort($files);

        return array_map(function (string $path) {
            return [
                'name' => basename($path),
                'path' => $path,
                'size' => filesize($path),
                'modified' => date('Y-m-d H:i:s', filemtime($path)),
            ];
        }, array_values($files));
    }

    public function makeFilename(): string
    {
        $base = 'ilcdb_backup_'.date('Ymd_His');
        $filename = $base.'.sql';
        $i = 1;
        while (is_file($this->backupPath($filename))) {
            $filename = $base.'_'.$i++.'.sql';
        }

        return $filename;
    }

    /**
     * Create a full SQL dump of the entire configured database.
     *
     * @return array{file: string, method: string}
     */
    public function export(?string $filename = null): array
    {
        $filename = $filename ?: $this->makeFilename();
        $path = $this->backupPath($filename);

        if ($this->mysqldumpAvailable() && $this->dumpWithBinary($path)) {
            return ['file' => $filename, 'method' => 'mysqldump'];
        }

        if ($this->dumpWithPhp($path)) {
            return ['file' => $filename, 'method' => 'php'];
        }

        throw new \RuntimeException('Export failed: mysqldump is unavailable and the PHP fallback could not complete.');
    }

    /**
     * Restore an uploaded .sql file into the database.
     *
     * @return array{method: string}
     */
    public function import(string $path): array
    {
        if ($this->mysqlAvailable() && $this->restoreWithBinary($path)) {
            return ['method' => 'mysql'];
        }

        if ($this->restoreWithPhp($path)) {
            return ['method' => 'php'];
        }

        throw new \RuntimeException('Import failed: the mysql client is unavailable and the PHP fallback could not complete.');
    }

    public function mysqldumpAvailable(): bool
    {
        $this->probeBinaries();

        return $this->mysqldumpBinary !== null;
    }

    public function mysqlAvailable(): bool
    {
        $this->probeBinaries();

        return $this->mysqlBinary !== null;
    }

    protected function probeBinaries(): void
    {
        if ($this->binariesProbed) {
            return;
        }
        $this->binariesProbed = true;

        foreach ($this->mysqldumpCandidates as $candidate) {
            if ($this->runsWithExitZero([$candidate, '--version'])) {
                $this->mysqldumpBinary = $candidate;
                break;
            }
        }

        foreach ($this->mysqlCandidates as $candidate) {
            if ($this->runsWithExitZero([$candidate, '--version'])) {
                $this->mysqlBinary = $candidate;
                break;
            }
        }
    }

    protected function dumpWithBinary(string $path): bool
    {
        if (! $this->mysqldumpBinary) {
            return false;
        }

        $cfg = $this->dbConfig();
        $args = [
            $this->mysqldumpBinary,
            '--host='.$cfg['host'],
            '--port='.(string) $cfg['port'],
            '--user='.$cfg['username'],
            '--result-file='.str_replace('\\', '/', $path),
            '--single-transaction',
            '--add-drop-table',
            '--default-character-set=utf8mb4',
            '--skip-lock-tables',
            $cfg['database'],
        ];

        return $this->run($args, sample: ['--result-file'], input: null, timeout: 900);
    }

    protected function restoreWithBinary(string $path): bool
    {
        if (! $this->mysqlBinary) {
            return false;
        }

        $cfg = $this->dbConfig();
        $args = [
            $this->mysqlBinary,
            '--host='.$cfg['host'],
            '--port='.(string) $cfg['port'],
            '--user='.$cfg['username'],
            '--default-character-set=utf8mb4',
            $cfg['database'],
        ];

        $stream = @fopen($path, 'r');
        if ($stream === false) {
            return false;
        }

        $ok = $this->run($args, sample: ['--default-character-set=utf8mb4'], input: $stream, timeout: 900);
        fclose($stream);

        return $ok;
    }

    /**
     * Determine whether the first candidate binary runs with exit code 0.
     */
    protected function runsWithExitZero(array $args): bool
    {
        try {
            $process = new Process($args, null, ['MYSQL_PWD' => $this->password()], null, 30);
            $process->run();

            return $process->isSuccessful();
        } catch (\Throwable $e) {
            Log::warning('Backup binary check failed: '.$e->getMessage());

            return false;
        }
    }

    protected function dumpWithPhp(string $path): bool
    {
        try {
            $tables = $this->allTables();
            $pdo = DB::connection()->getPdo();
            $handle = @fopen($path, 'w');
            if ($handle === false) {
                return false;
            }

            fwrite($handle, '-- ILCDB full database dump (PHP fallback) -- '.date('Y-m-d H:i:s')."\n");
            fwrite($handle, "/*!40101 SET NAMES utf8mb4 */;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n");

            foreach ($tables as $table) {
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

                $create = DB::selectOne('SHOW CREATE TABLE `'.$table.'`');
                fwrite($handle, $create->{'Create Table'}.";\n\n");

                $count = (int) DB::selectOne('SELECT COUNT(*) AS c FROM `'.$table.'`')->c;
                if ($count === 0) {
                    continue;
                }

                $columns = array_map(fn ($col) => $col->Field, DB::select('SHOW COLUMNS FROM `'.$table.'`'));
                $columnList = '`'.implode('`, `', $columns).'`';

                $buffer = [];
                foreach (DB::table($table)->cursor() as $row) {
                    $values = array_map(
                        fn ($col) => ($row->{$col} ?? null) === null ? 'NULL' : $pdo->quote((string) $row->{$col}),
                        $columns
                    );
                    $buffer[] = '('.implode(', ', $values).')';

                    if (count($buffer) >= 500) {
                        fwrite($handle, "INSERT INTO `{$table}` ({$columnList}) VALUES\n".implode(",\n", $buffer).";\n");
                        $buffer = [];
                    }
                }

                if (! empty($buffer)) {
                    fwrite($handle, "INSERT INTO `{$table}` ({$columnList}) VALUES\n".implode(",\n", $buffer).";\n");
                }

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            return true;
        } catch (\Throwable $e) {
            Log::error('PHP database dump failed: '.$e->getMessage());

            return false;
        }
    }

    protected function restoreWithPhp(string $path): bool
    {
        try {
            $handle = @fopen($path, 'r');
            if ($handle === false) {
                return false;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $buffer = '';
            while (($line = fgets($handle)) !== false) {
                $buffer .= $line;
                $trimmed = rtrim($line);
                if ($trimmed !== '' && str_ends_with($trimmed, ';')) {
                    $statement = trim($buffer);
                    $buffer = '';
                    if ($statement !== '') {
                        DB::statement($statement);
                    }
                }
            }

            if (trim($buffer) !== '') {
                DB::statement(trim($buffer));
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            fclose($handle);

            return true;
        } catch (\Throwable $e) {
            Log::error('PHP database restore failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Run a Symfony Process with MYSQL_PWD set for password auth and attempt
     * to throw on failure.
     */
    protected function run(array $args, ?array $sample = null, $input = null, int $timeout = 900): bool
    {
        try {
            $process = new Process($args, null, ['MYSQL_PWD' => $this->password()], $input, $timeout);
            $process->run();

            if (! $process->isSuccessful()) {
                Log::warning(
                    'Process failed ('.implode(' ', ($sample ?? array_slice($args, 0, 2))).'): '.$process->getErrorOutput()
                );

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Process could not be started: '.$e->getMessage());

            return false;
        }
    }

    protected function dbConfig(): array
    {
        return config('database.connections.'.config('database.default'));
    }

    protected function password(): string
    {
        return (string) $this->dbConfig()['password'];
    }

    protected function allTables(): array
    {
        $rows = DB::select('SHOW TABLES');
        if (empty($rows)) {
            return [];
        }
        $key = array_key_first((array) $rows[0]);

        return array_map(fn ($row) => $row->{$key}, $rows);
    }
}
