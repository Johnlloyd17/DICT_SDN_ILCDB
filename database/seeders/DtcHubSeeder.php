<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DtcHubSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dtc_hubs')->insert([
            [
                'name' => 'Surigao City DTC Main Hub',
                'municipality' => 'Surigao City',
                'latitude' => 9.7894,
                'longitude' => 125.4958,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Claver Digital Hub',
                'municipality' => 'Claver',
                'latitude' => 9.5714,
                'longitude' => 125.5925,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siargao Tech Hub (Dapa)',
                'municipality' => 'Dapa, Siargao',
                'latitude' => 9.7562,
                'longitude' => 126.0543,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mainit Tech Hub',
                'municipality' => 'Mainit',
                'latitude' => 9.5372,
                'longitude' => 125.5231,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
