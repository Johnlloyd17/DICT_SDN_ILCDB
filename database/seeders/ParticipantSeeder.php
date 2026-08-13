<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('participants')->insert([
            [
                'participant_code' => 'TMD-2026-001',
                'full_name' => 'Maria Santos',
                'training_batch_id' => 1,
                'agency_sector' => 'LGU Mainit',
                'municipality' => 'Mainit',
                'completion_status' => 'Completed',
                'completion_date' => '2026-02-10',
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-002',
                'full_name' => 'Juan Dela Cruz',
                'training_batch_id' => 2,
                'agency_sector' => 'DepEd SDN',
                'municipality' => 'Surigao City',
                'completion_status' => 'Completed',
                'completion_date' => '2026-03-10',
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-003',
                'full_name' => 'Ronalyn Petallo',
                'training_batch_id' => 1,
                'agency_sector' => 'SK Council',
                'municipality' => 'Claver',
                'completion_status' => 'Completed',
                'completion_date' => '2026-02-10',
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-004',
                'full_name' => 'Ana Reyne Calago',
                'training_batch_id' => 3,
                'agency_sector' => 'LGU Surigao City',
                'municipality' => 'Surigao City',
                'completion_status' => 'Ongoing',
                'completion_date' => null,
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-005',
                'full_name' => 'Mark Anthony Vega',
                'training_batch_id' => 2,
                'agency_sector' => 'DICT Scholar',
                'municipality' => 'Surigao City',
                'completion_status' => 'Completed',
                'completion_date' => '2026-03-10',
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-006',
                'full_name' => 'Elena Ramos',
                'training_batch_id' => 4,
                'agency_sector' => 'LGU Mainit',
                'municipality' => 'Mainit',
                'completion_status' => 'Pending',
                'completion_date' => null,
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-007',
                'full_name' => 'Kevin Roy Tagalog',
                'training_batch_id' => 5,
                'agency_sector' => 'LGU Claver',
                'municipality' => 'Claver',
                'completion_status' => 'Ongoing',
                'completion_date' => null,
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'participant_code' => 'TMD-2026-008',
                'full_name' => 'Grace Gonzaga',
                'training_batch_id' => 5,
                'agency_sector' => 'DICT Trainee',
                'municipality' => 'Surigao City',
                'completion_status' => 'Ongoing',
                'completion_date' => null,
                'certificate_file' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
