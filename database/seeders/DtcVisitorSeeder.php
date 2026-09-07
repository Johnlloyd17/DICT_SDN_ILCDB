<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Seeder;

class DtcVisitorSeeder extends Seeder
{
    public function run(): void
    {
        $demographics = ['Student / Youth', 'Senior Citizen / PWD', 'Jobseeker / Out-of-School Youth', 'MSME / Freelancer', 'LGU / Govt Employee'];

        $visitors = [
            ['name' => 'Maria Clara Santos', 'gender' => 'Female', 'age' => 22],
            ['name' => 'Juan Dela Cruz', 'gender' => 'Male', 'age' => 34],
            ['name' => 'Ronalyn Petallo', 'gender' => 'Female', 'age' => 19],
            ['name' => 'Ana Reyne Calago', 'gender' => 'Female', 'age' => 28],
            ['name' => 'Mark Anthony Vega', 'gender' => 'Male', 'age' => 45],
            ['name' => 'Elena Ramos', 'gender' => 'Female', 'age' => 67],
            ['name' => 'Kevin Roy Tagalog', 'gender' => 'Male', 'age' => 17],
            ['name' => 'Grace Gonzaga', 'gender' => 'Female', 'age' => 31],
        ];

        foreach ($visitors as $i => $v) {
            Visitor::updateOrCreate(
                ['name' => $v['name'], 'contact_number' => null],
                [
                    'gender' => $v['gender'],
                    'age' => $v['age'],
                    'demographic_sector' => $demographics[$i % count($demographics)],
                ]
            );
        }
    }
}