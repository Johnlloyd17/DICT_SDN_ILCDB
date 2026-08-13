<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            ['name' => 'Engr. Rodel T. Balintong', 'designation' => 'Lead Resource Speaker', 'specialty' => 'ICT Literacy & Digital Transformation', 'agency' => 'DICT - Surigao del Norte', 'contact' => 'rodel.balintong@dict.gov.ph', 'phone' => '0917 000 0000', 'status' => 'Active', 'courses' => 12, 'rating' => 4.9],
            ['name' => 'Maria Lourdes V. Cariño', 'designation' => 'Resource Speaker', 'specialty' => 'E-Government & Digital Services', 'agency' => 'DICT - Caraga Regional Office', 'contact' => 'mlc.carino@dict.gov.ph', 'phone' => '0918 000 0000', 'status' => 'Active', 'courses' => 8, 'rating' => 4.8],
            ['name' => 'Jason Paul S. Dizon', 'designation' => 'Resource Speaker', 'specialty' => 'Cybersecurity & Data Privacy', 'agency' => 'DICT - Surigao del Norte', 'contact' => 'jp.dizon@dict.gov.ph', 'phone' => '0919 000 0000', 'status' => 'Active', 'courses' => 10, 'rating' => 4.7],
            ['name' => 'Karen Grace M. Eclarin', 'designation' => 'Resource Speaker', 'specialty' => 'Digital Marketing & E-Commerce', 'agency' => 'LGU Surigao City', 'contact' => 'kg.eclarin@gmail.com', 'phone' => '0920 000 0000', 'status' => 'Active', 'courses' => 6, 'rating' => 4.6],
            ['name' => 'Rolando M. Fuentes', 'designation' => 'Resource Speaker', 'specialty' => 'Basic Programming & Web Development', 'agency' => 'Surigao State College of Technology', 'contact' => 'rm.fuentes@ssct.edu.ph', 'phone' => '0921 000 0000', 'status' => 'Active', 'courses' => 9, 'rating' => 4.8],
            ['name' => 'Diana Rose P. Galido', 'designation' => 'Resource Speaker', 'specialty' => 'E-Learning & Educational Technology', 'agency' => 'DepEd - Surigao del Norte', 'contact' => 'dr.galido@deped.gov.ph', 'phone' => '0922 000 0000', 'status' => 'Active', 'courses' => 7, 'rating' => 4.5],
            ['name' => 'Mark Anthony C. Lim', 'designation' => 'Resource Speaker', 'specialty' => 'Data Analytics & Spreadsheet Tools', 'agency' => 'DICT - Surigao del Norte', 'contact' => 'ma.lim@dict.gov.ph', 'phone' => '0923 000 0000', 'status' => 'Active', 'courses' => 5, 'rating' => 4.7],
            ['name' => 'Christine Joy D. Oporto', 'designation' => 'Resource Speaker', 'specialty' => 'ICT for Farmers & Fisherfolk', 'agency' => 'Provincial Agriculturist Office', 'contact' => 'cj.oporto@sdn.gov.ph', 'phone' => '0924 000 0000', 'status' => 'Active', 'courses' => 4, 'rating' => 4.6],
        ];

        Trainer::query()->truncate();

        foreach ($profiles as $p) {
            Trainer::create([
                'full_name' => $p['name'],
                'designation' => $p['designation'],
                'specialty' => $p['specialty'],
                'agency' => $p['agency'],
                'contact' => $p['contact'],
                'phone' => $p['phone'],
                'courses' => $p['courses'],
                'rating' => $p['rating'],
                'status' => $p['status'],
            ]);
        }
    }
}
