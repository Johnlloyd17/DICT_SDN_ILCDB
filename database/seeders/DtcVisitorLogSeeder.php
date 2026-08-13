<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DtcVisitorLogSeeder extends Seeder
{
    public function run(): void
    {
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

        $demographics = ['Student / Youth', 'Senior Citizen / PWD', 'Jobseeker / Out-of-School Youth', 'MSME / Freelancer', 'LGU / Govt Employee'];
        $durations = ['45 mins', '1 hr 15 mins', '2 hrs', '1 hr 30 mins', '3 hrs', '2 hrs 45 mins', '1 hr', '1 hr 45 mins'];

        $visitors = [
            ['visitor_name' => 'Maria Clara Santos', 'gender' => 'Female', 'age' => 22, 'dtc_hub_id' => 1],
            ['visitor_name' => 'Juan Dela Cruz', 'gender' => 'Male', 'age' => 34, 'dtc_hub_id' => 1],
            ['visitor_name' => 'Ronalyn Petallo', 'gender' => 'Female', 'age' => 19, 'dtc_hub_id' => 2],
            ['visitor_name' => 'Ana Reyne Calago', 'gender' => 'Female', 'age' => 28, 'dtc_hub_id' => 1],
            ['visitor_name' => 'Mark Anthony Vega', 'gender' => 'Male', 'age' => 45, 'dtc_hub_id' => 3],
            ['visitor_name' => 'Elena Ramos', 'gender' => 'Female', 'age' => 67, 'dtc_hub_id' => 4],
            ['visitor_name' => 'Kevin Roy Tagalog', 'gender' => 'Male', 'age' => 17, 'dtc_hub_id' => 2],
            ['visitor_name' => 'Grace Gonzaga', 'gender' => 'Female', 'age' => 31, 'dtc_hub_id' => 1],
        ];

        $records = [];
        foreach ($visitors as $i => $v) {
            $records[] = [
                'log_code' => 'DTC-LOG-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'visitor_name' => $v['visitor_name'],
                'gender' => $v['gender'],
                'age' => $v['age'],
                'demographic_sector' => $demographics[$i % count($demographics)],
                'dtc_hub_id' => $v['dtc_hub_id'],
                'services_ailed' => json_encode($services[$i % count($services)]),
                'session_duration' => $durations[$i % count($durations)],
                'visit_date' => now()->subDays(rand(0, 60))->subHours(rand(0, 12)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('dtc_visitor_logs')->insert($records);
    }
}
