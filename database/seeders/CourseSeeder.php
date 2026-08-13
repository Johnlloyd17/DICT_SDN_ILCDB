<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'course_code' => 'BCIL-101',
                'title' => 'Basic Computer & Internet Literacy',
                'specialty_track' => 'Digital Literacy',
                'format_type' => 'In-Person',
                'duration_hours' => 24,
                'credentials' => json_encode(['DICT Certificate of Completion']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'CYBER-201',
                'title' => 'Cybersecurity Awareness & Network Defense',
                'specialty_track' => 'Cybersecurity',
                'format_type' => 'In-Person',
                'duration_hours' => 40,
                'credentials' => json_encode(['DICT Certificate', 'CompTIA Security+ Prep']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'WEBD-301',
                'title' => 'Full-Stack Web Development with PHP & Laravel',
                'specialty_track' => 'Web Development',
                'format_type' => 'In-Person',
                'duration_hours' => 48,
                'credentials' => json_encode(['DICT Certificate', 'Laravel Developer']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'DATA-401',
                'title' => 'Data Analytics with Python',
                'specialty_track' => 'Data Analytics',
                'format_type' => 'Hybrid',
                'duration_hours' => 36,
                'credentials' => json_encode(['DICT Certificate', 'Python Data Analyst']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'NETA-501',
                'title' => 'Network Administration & Linux',
                'specialty_track' => 'Networking',
                'format_type' => 'In-Person',
                'duration_hours' => 40,
                'credentials' => json_encode(['DICT Certificate', 'Linux Essentials']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'AICL-601',
                'title' => 'Applied AI & Machine Learning with Python',
                'specialty_track' => 'Artificial Intelligence',
                'format_type' => 'In-Person',
                'duration_hours' => 48,
                'credentials' => json_encode(['DICT Certificate', 'AWS Cloud Practitioner Prep']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
