<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelInternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_level_internship')->insert([
            [
                'name' => 'Internal Prasetiya Mulya', 
                'id_activity_category' => '1',
                'score' => '1',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Wilayah (DKI Jakarta)', 
                'id_activity_category' => '2',
                'score' => '2',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Magang', 
                'id_activity_category' => '2',
                'score' => '3',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Usaha Mandiri / Bisnis', 
                'id_activity_category' => '2',
                'score' => '4',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Tutor, Riset / Tutor, Research', 
                'id_activity_category' => '2',
                'score' => '5',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ]  
        ]);
    }
}
