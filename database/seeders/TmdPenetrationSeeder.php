<?php

namespace Database\Seeders;

use App\Models\TmdPenetration;
use Illuminate\Database\Seeder;

class TmdPenetrationSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['municipality' => 'Surigao City',        'male' => 120, 'female' => 145, 'total' => 265],
            ['municipality' => 'Alegria',              'male' => 18,  'female' => 22,  'total' => 40],
            ['municipality' => 'Bacuag',               'male' => 22,  'female' => 28,  'total' => 50],
            ['municipality' => 'Burgos',               'male' => 10,  'female' => 14,  'total' => 24],
            ['municipality' => 'Claver',               'male' => 35,  'female' => 30,  'total' => 65],
            ['municipality' => 'Dapa',                 'male' => 28,  'female' => 32,  'total' => 60],
            ['municipality' => 'Del Carmen',           'male' => 20,  'female' => 25,  'total' => 45],
            ['municipality' => 'General Luna',         'male' => 25,  'female' => 30,  'total' => 55],
            ['municipality' => 'Gigaquit',             'male' => 15,  'female' => 18,  'total' => 33],
            ['municipality' => 'Mainit',               'male' => 30,  'female' => 35,  'total' => 65],
            ['municipality' => 'Malimono',             'male' => 12,  'female' => 15,  'total' => 27],
            ['municipality' => 'Pilar',                'male' => 14,  'female' => 16,  'total' => 30],
            ['municipality' => 'Placer',               'male' => 40,  'female' => 45,  'total' => 85],
            ['municipality' => 'San Benito',           'male' => 8,   'female' => 10,  'total' => 18],
            ['municipality' => 'San Francisco',        'male' => 16,  'female' => 20,  'total' => 36],
            ['municipality' => 'San Isidro',           'male' => 10,  'female' => 12,  'total' => 22],
            ['municipality' => 'Santa Monica',         'male' => 9,   'female' => 11,  'total' => 20],
            ['municipality' => 'Sison',                'male' => 24,  'female' => 26,  'total' => 50],
            ['municipality' => 'Socorro',              'male' => 20,  'female' => 22,  'total' => 42],
            ['municipality' => 'Tagana-an',            'male' => 14,  'female' => 18,  'total' => 32],
            ['municipality' => 'Tubod',                'male' => 11,  'female' => 14,  'total' => 25],
        ];

        foreach ($data as $row) {
            TmdPenetration::create($row);
        }
    }
}
