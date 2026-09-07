<?php

namespace Database\Seeders;

use App\Models\DtcService;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class DtcVisitSeeder extends Seeder
{
    public function run(): void
    {
        if (Visit::exists()) {
            return;
        }

        $services = [
            ['Free High-Speed Internet'],
            ['Free High-Speed Internet', 'eGov PH & Government Portal Access'],
            ['Free High-Speed Internet', 'Printing & Document Scanning'],
            ['Free High-Speed Internet', 'Co-working & Freelance Space', 'Tech Assistance & Consultation'],
            ['eGov PH & Government Portal Access', 'Printing & Document Scanning'],
            ['Free High-Speed Internet', 'Tech Assistance & Consultation'],
            ['Co-working & Freelance Space'],
            ['Free High-Speed Internet', 'eGov PH & Government Portal Access', 'Printing & Document Scanning'],
        ];

        $durations = ['45 mins', '1 hr 15 mins', '2 hrs', '1 hr 30 mins', '3 hrs', '2 hrs 45 mins', '1 hr', '1 hr 45 mins'];

        $rows = [
            ['Maria Clara Santos', 1],
            ['Juan Dela Cruz', 1],
            ['Ronalyn Petallo', 2],
            ['Ana Reyne Calago', 1],
            ['Mark Anthony Vega', 3],
            ['Elena Ramos', 4],
            ['Kevin Roy Tagalog', 2],
            ['Grace Gonzaga', 1],
        ];

        $year = date('Y');
        $seq = 0;

        foreach ($rows as $i => [$visitorName, $hubId]) {
            $visitor = Visitor::where('name', $visitorName)->first();
            if (!$visitor) {
                continue;
            }

            $seq++;
            $checkIn = now()->subDays(rand(0, 60))->subHours(rand(0, 12));
            $checkOut = $this->addDuration($checkIn, $durations[$i % count($durations)]);

            $visit = Visit::create([
                'visit_code' => 'DTC-VIS-' . $year . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                'visitor_id' => $visitor->id,
                'dtc_hub_id' => $hubId,
                'purpose_of_visit' => null,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'status' => 'Completed',
            ]);

            foreach ($services[$i % count($services)] as $serviceName) {
                $service = DtcService::where('dtc_hub_id', $hubId)
                    ->where('service_name', $serviceName)
                    ->first()
                    ?? DtcService::where('service_name', $serviceName)->first();

                if ($service) {
                    $visit->visitServices()->create([
                        'service_id' => $service->id,
                        'status' => 'Completed',
                    ]);
                }
            }
        }
    }

    private function addDuration($checkIn, string $duration): string
    {
        $totalMinutes = 0;
        if (preg_match('/(\d+)\s*hrs?/', $duration, $m)) {
            $totalMinutes += ((int) $m[1]) * 60;
        }
        if (preg_match('/(\d+)\s*mins?/', $duration, $m)) {
            $totalMinutes += (int) $m[1];
        }

        return $checkIn->copy()->addMinutes(max(1, $totalMinutes));
    }
}