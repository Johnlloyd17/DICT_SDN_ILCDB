<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DtcHubSeeder::class,
            DtcServiceSeeder::class,
            DtcVisitorSeeder::class,
            DtcVisitSeeder::class,
            CourseSeeder::class,
            TrainerSeeder::class,
            TrainingBatchSeeder::class,
            ParticipantSeeder::class,
            FundingRecordSeeder::class,
            SparkTrainingSeeder::class,
            SparkTraineeSeeder::class,
            ClickDeviceSeeder::class,
            TmdPenetrationSeeder::class,
            DtcCenterInventorySeeder::class,
        ]);
    }
}
