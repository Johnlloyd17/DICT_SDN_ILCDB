<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClickDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['batch_id' => 'CLK-2026-A1', 'donation_date' => '2026-01-12', 'device_type' => 'Lenovo Chromebook 300e', 'quantity' => 35, 'beneficiary' => 'Surigao National High School', 'municipality' => 'Surigao City', 'status' => 'Turned Over'],
            ['batch_id' => 'CLK-2026-A2', 'donation_date' => '2026-02-18', 'device_type' => 'Samsung Galaxy Tab A8', 'quantity' => 25, 'beneficiary' => 'Dapa National High School', 'municipality' => 'Dapa (Siargao)', 'status' => 'Turned Over'],
            ['batch_id' => 'CLK-2026-B1', 'donation_date' => '2026-03-05', 'device_type' => 'HP Chromebook 11 G9', 'quantity' => 40, 'beneficiary' => 'Claver Central School', 'municipality' => 'Claver', 'status' => 'Turned Over'],
            ['batch_id' => 'CLK-2026-B2', 'donation_date' => '2026-03-20', 'device_type' => 'Acer Aspire Go 15 Laptop', 'quantity' => 30, 'beneficiary' => 'Mainit Central School', 'municipality' => 'Mainit', 'status' => 'Pending'],
            ['batch_id' => 'CLK-2026-C1', 'donation_date' => '2026-04-08', 'device_type' => 'Lenovo Tab M10 Plus', 'quantity' => 20, 'beneficiary' => 'Siargao Island Tech Center', 'municipality' => 'Dapa (Siargao)', 'status' => 'In Transit'],
            ['batch_id' => 'CLK-2026-C2', 'donation_date' => '2026-05-15', 'device_type' => 'Dell Latitude 3120 Chromebook', 'quantity' => 45, 'beneficiary' => 'Surigao del Norte Provincial Capitol Tech Lab', 'municipality' => 'Surigao City', 'status' => 'Turned Over'],
            ['batch_id' => 'CLK-2026-D1', 'donation_date' => '2026-06-01', 'device_type' => 'ASUS Chromebook Flip CX1', 'quantity' => 50, 'beneficiary' => 'Multiple LGUs - SDN-wide Distribution', 'municipality' => 'Surigao City', 'status' => 'Turned Over'],
        ];

        foreach ($devices as &$d) {
            $d['created_at'] = now();
            $d['updated_at'] = now();
        }

        DB::table('click_devices')->insert($devices);
    }
}
