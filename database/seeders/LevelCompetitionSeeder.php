<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_level_competition')->insert([
            [
                'name' => 'Internal Prasetiya Mulya', 
                'id_activity_category' => '1',
                'score' => '0.5',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Jabodetabek', 
                'id_activity_category' => '2',
                'score' => '1.5',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'National', 
                'id_activity_category' => '2',
                'score' => '3',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'International', 
                'id_activity_category' => '2',
                'score' => '6',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Regional', 
                'id_activity_category' => '2',
                'score' => '4',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Intern PM', 
                'id_activity_category' => '1',
                'score' => '1',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ]  
        ]);
    }
}
