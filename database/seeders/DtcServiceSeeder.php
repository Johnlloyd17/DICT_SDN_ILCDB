<?php

namespace Database\Seeders;

use App\Models\DtcHub;
use App\Models\DtcService;
use Illuminate\Database\Seeder;

class DtcServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Free High-Speed Internet', 'Internet/Connectivity'],
            ['eGov PH & Government Portal Access', 'Internet/Connectivity'],
            ['Printing & Document Scanning', 'Productivity'],
            ['Co-working & Freelance Space', 'Productivity'],
            ['Tech Assistance & Consultation', 'Support'],
        ];

        foreach (DtcHub::all() as $hub) {
            foreach ($services as [$name, $category]) {
                DtcService::updateOrCreate(
                    ['dtc_hub_id' => $hub->id, 'service_name' => $name],
                    ['category' => $category, 'is_active' => true]
                );
            }
        }
    }
}