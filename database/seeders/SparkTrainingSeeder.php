<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparkTrainingSeeder extends Seeder
{
    public function run(): void
    {
        $trainings = [
            ['track_id' => 'SPARK-AI-01', 'specialization' => 'Applied AI & Machine Learning with Python', 'master_trainer' => 'Dr. Ramon Reyes', 'enrolled_count' => 30, 'budget_allocated' => 250000, 'industry_partner' => 'DICT National / Analytics Council', 'status' => 'Ongoing'],
            ['track_id' => 'SPARK-CC-02', 'specialization' => 'Cloud Architecture & AWS Foundations', 'master_trainer' => 'Engr. Alex Santos', 'enrolled_count' => 25, 'budget_allocated' => 200000, 'industry_partner' => 'AWS Academy', 'status' => 'Completed'],
            ['track_id' => 'SPARK-CY-03', 'specialization' => 'Ethical Hacking & Network Defense', 'master_trainer' => 'Ms. Maria Clara Cruz', 'enrolled_count' => 20, 'budget_allocated' => 180000, 'industry_partner' => 'Cybersecurity Alliance', 'status' => 'Upcoming'],
            ['track_id' => 'SPARK-VA-04', 'specialization' => 'Virtual Assistance & Digital Marketing', 'master_trainer' => 'Mr. James Fernandez', 'enrolled_count' => 35, 'budget_allocated' => 150000, 'industry_partner' => 'OnlineJobs.ph Academy', 'status' => 'Ongoing'],
            ['track_id' => 'SPARK-WD-05', 'specialization' => 'Full-Stack Web Development (PHP/Laravel)', 'master_trainer' => 'Mr. Kevin Roy Tagalog', 'enrolled_count' => 28, 'budget_allocated' => 200000, 'industry_partner' => 'Laravel Philippines', 'status' => 'Ongoing'],
            ['track_id' => 'SPARK-DM-06', 'specialization' => 'SEO & Search Engine Marketing Mastery', 'master_trainer' => 'Ms. Ana Reyne Calago', 'enrolled_count' => 22, 'budget_allocated' => 120000, 'industry_partner' => 'Google Digital Garage', 'status' => 'Completed'],
            ['track_id' => 'SPARK-GD-07', 'specialization' => 'Graphic Design & Creative Multimedia', 'master_trainer' => 'Mr. Mark Anthony Vega', 'enrolled_count' => 18, 'budget_allocated' => 100000, 'industry_partner' => 'Adobe Creative Network', 'status' => 'Upcoming'],
            ['track_id' => 'SPARK-DS-08', 'specialization' => 'Data Science & Analytics Fundamentals', 'master_trainer' => 'Dr. Ramon Reyes', 'enrolled_count' => 32, 'budget_allocated' => 180000, 'industry_partner' => 'DICT National / Analytics Council', 'status' => 'Ongoing'],
        ];

        foreach ($trainings as $i => $t) {
            $t['created_at'] = now();
            $t['updated_at'] = now();
        }

        DB::table('spark_trainings')->insert($trainings);
    }
}
