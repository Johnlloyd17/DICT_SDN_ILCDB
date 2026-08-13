<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingBatchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('training_batches')->insert([
            [
                'batch_code' => 'TMD-SDN-2026-001',
                'course_title' => 'Basic Computer & Internet Literacy',
                'venue' => 'Surigao City DTC Main Hub',
                'target_count' => 30,
                'enrolled_count' => 25,
                'trainer_name' => 'Mr. Juan B. Madrigal',
                'start_date' => '2026-01-15',
                'end_date' => '2026-02-10',
                'program' => 'TMD',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'TMD-SDN-2026-002',
                'course_title' => 'Cybersecurity Awareness & Network Defense',
                'venue' => 'Claver Digital Hub',
                'target_count' => 25,
                'enrolled_count' => 22,
                'trainer_name' => 'Engr. Alex Santos',
                'start_date' => '2026-02-15',
                'end_date' => '2026-03-10',
                'program' => 'TMD',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'TMD-SDN-2026-003',
                'course_title' => 'Full-Stack Web Development with PHP & Laravel',
                'venue' => 'Surigao City DTC Main Hub',
                'target_count' => 20,
                'enrolled_count' => 18,
                'trainer_name' => 'Ms. Maria Clara Cruz',
                'start_date' => '2026-03-15',
                'end_date' => '2026-04-30',
                'program' => 'TMD',
                'status' => 'Ongoing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'TMD-SDN-2026-005',
                'course_title' => 'Data Analytics with Python',
                'venue' => 'Mainit Tech Hub',
                'target_count' => 20,
                'enrolled_count' => 15,
                'trainer_name' => 'Dr. Ramon Reyes',
                'start_date' => '2026-04-01',
                'end_date' => '2026-05-15',
                'program' => 'TMD',
                'status' => 'Upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'SPARK-SDN-2026-001',
                'course_title' => 'Applied AI & Machine Learning with Python',
                'venue' => 'Siargao Tech Hub (Dapa)',
                'target_count' => 30,
                'enrolled_count' => 28,
                'trainer_name' => 'Dr. Ramon Reyes',
                'start_date' => '2026-01-20',
                'end_date' => '2026-03-20',
                'program' => 'SPARK',
                'status' => 'Ongoing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
