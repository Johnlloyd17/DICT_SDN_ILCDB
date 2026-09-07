<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $logs = DB::table('dtc_visitor_logs')->orderBy('id')->get();

        File::put(
            storage_path('app/backup_dtc_visitor_logs_before_move.json'),
            json_encode($logs->map(fn ($l) => (array) $l)->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->seedHubServices();

        $visitorIds = [];
        $year = date('Y');
        $seq = 0;

        foreach ($logs as $log) {
            $nameKey = mb_strtolower(trim($log->visitor_name));

            if (!isset($visitorIds[$nameKey])) {
                $visitorIds[$nameKey] = DB::table('visitors')->insertGetId([
                    'name' => $log->visitor_name,
                    'contact_number' => null,
                    'gender' => $log->gender,
                    'age' => $log->age,
                    'demographic_sector' => $log->demographic_sector,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $checkIn = $log->visit_date;
            $checkOut = $this->parseCheckOut($checkIn, $log->session_duration);

            $seq++;
            $visitId = DB::table('visits')->insertGetId([
                'visit_code' => 'DTC-VIS-' . $year . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                'visitor_id' => $visitorIds[$nameKey],
                'dtc_hub_id' => $log->dtc_hub_id,
                'purpose_of_visit' => null,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $serviceNames = is_array($log->services_ailed)
                ? $log->services_ailed
                : json_decode($log->services_ailed, true) ?? [];

            foreach ($serviceNames as $serviceName) {
                $service = DB::table('dtc_services')
                    ->where('dtc_hub_id', $log->dtc_hub_id)
                    ->where('service_name', $serviceName)
                    ->first();

                if (!$service) {
                    $service = DB::table('dtc_services')
                        ->where('service_name', $serviceName)
                        ->first();
                }

                if ($service) {
                    DB::table('visit_services')->insert([
                        'visit_id' => $visitId,
                        'service_id' => $service->id,
                        'status' => 'Completed',
                        'remarks' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::drop('dtc_visitor_logs');
    }

    public function down(): void
    {
        Schema::create('dtc_visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_code', 50)->unique();
            $table->string('visitor_name');
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->integer('age');
            $table->string('demographic_sector', 100);
            $table->foreignId('dtc_hub_id')->constrained('dtc_hubs')->cascadeOnDelete();
            $table->json('services_ailed');
            $table->string('session_duration', 50);
            $table->dateTime('visit_date');
            $table->timestamps();

            $table->index('dtc_hub_id');
            $table->index('visit_date');
        });

        $backupPath = storage_path('app/backup_dtc_visitor_logs_before_move.json');
        if (File::exists($backupPath)) {
            $rows = json_decode(File::get($backupPath), true);
            foreach ($rows as $row) {
                unset($row['id']);
                $row['services_ailed'] = is_array($row['services_ailed'] ?? null)
                    ? json_encode($row['services_ailed'])
                    : ($row['services_ailed'] ?? '[]');
                $row['created_at'] = $row['created_at'] ?? now();
                $row['updated_at'] = $row['updated_at'] ?? now();
                DB::table('dtc_visitor_logs')->insert($row);
            }
        }
    }

    private function seedHubServices(): void
    {
        if ((int) DB::table('dtc_services')->count() > 0) {
            return;
        }

        $services = [
            ['Free High-Speed Internet', 'Internet/Connectivity'],
            ['eGov PH & Government Portal Access', 'Internet/Connectivity'],
            ['Printing & Document Scanning', 'Productivity'],
            ['Co-working & Freelance Space', 'Productivity'],
            ['Tech Assistance & Consultation', 'Support'],
        ];

        foreach (DB::table('dtc_hubs')->pluck('id') as $hubId) {
            foreach ($services as [$name, $category]) {
                DB::table('dtc_services')->insert([
                    'dtc_hub_id' => $hubId,
                    'service_name' => $name,
                    'category' => $category,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function parseCheckOut(?string $checkIn, ?string $duration): ?string
    {
        if (!$checkIn || !$duration) {
            return null;
        }

        $totalMinutes = 0;
        if (preg_match('/(\d+)\s*hrs?/', $duration, $m)) {
            $totalMinutes += ((int) $m[1]) * 60;
        }
        if (preg_match('/(\d+)\s*mins?/', $duration, $m)) {
            $totalMinutes += (int) $m[1];
        }

        if ($totalMinutes <= 0) {
            return null;
        }

        return Carbon::parse($checkIn)->addMinutes($totalMinutes)->format('Y-m-d H:i:s');
    }
};