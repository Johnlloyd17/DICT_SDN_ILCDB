<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparkTraineeSeeder extends Seeder
{
    public function run(): void
    {
        $trainees = [
            ['trainee_code' => 'SPK-2026-001', 'full_name' => 'Grace B. Gonzaga', 'specialty' => 'Virtual Assistant & Email Mgmt', 'course' => 'Virtual Assistance Masterclass', 'municipality' => 'Surigao City', 'employment_status' => 'Full-Time Freelancer', 'monthly_earnings' => 35000],
            ['trainee_code' => 'SPK-2026-002', 'full_name' => 'Kevin Roy D. Tagalog', 'specialty' => 'Full-Stack Web Dev', 'course' => 'PHP & Laravel Web Development', 'municipality' => 'Claver', 'employment_status' => 'Full-Time Freelancer', 'monthly_earnings' => 42000],
            ['trainee_code' => 'SPK-2026-003', 'full_name' => 'Maria Clara Santos', 'specialty' => 'SEO & Digital Marketing', 'course' => 'Digital Marketing Mastery', 'municipality' => 'Surigao City', 'employment_status' => 'Self-Employed', 'monthly_earnings' => 28000],
            ['trainee_code' => 'SPK-2026-004', 'full_name' => 'Ronalyn Petallo', 'specialty' => 'AI & Python', 'course' => 'Applied AI & Machine Learning', 'municipality' => 'Dapa (Siargao)', 'employment_status' => 'Employed', 'monthly_earnings' => 38000],
            ['trainee_code' => 'SPK-2026-005', 'full_name' => 'Juan Dela Cruz', 'specialty' => 'Cloud Computing', 'course' => 'AWS Cloud Foundations', 'municipality' => 'Mainit', 'employment_status' => 'Part-Time Freelancer', 'monthly_earnings' => 18000],
            ['trainee_code' => 'SPK-2026-006', 'full_name' => 'Elena Ramos', 'specialty' => 'Graphic Design', 'course' => 'Creative Multimedia Design', 'municipality' => 'Surigao City', 'employment_status' => 'Self-Employed', 'monthly_earnings' => 25000],
            ['trainee_code' => 'SPK-2026-007', 'full_name' => 'Mark Anthony Vega', 'specialty' => 'Cybersecurity', 'course' => 'Ethical Hacking & Defense', 'municipality' => 'Claver', 'employment_status' => 'Employed', 'monthly_earnings' => 45000],
            ['trainee_code' => 'SPK-2026-008', 'full_name' => 'Ana Reyne Calago', 'specialty' => 'Data Science', 'course' => 'Data Analytics Fundamentals', 'municipality' => 'Surigao City', 'employment_status' => 'Full-Time Freelancer', 'monthly_earnings' => 32000],
        ];

        foreach ($trainees as &$t) {
            $t['created_at'] = now();
            $t['updated_at'] = now();
        }

        DB::table('spark_trainees')->insert($trainees);
    }
}
