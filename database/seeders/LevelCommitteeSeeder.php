<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelCommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_level_committee')->insert([
            [
                'name' => 'Project - Event SB / HiMa / AIESEC / SAC-SCC / Event seluruh divisi PrasMul', 
                'id_activity_category' => '1',
                'score' => '4',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Jabodetabek', 
                'id_activity_category' => '2',
                'score' => '2',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'National', 
                'id_activity_category' => '2',
                'score' => '3',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'International', 
                'id_activity_category' => '2',
                'score' => '5',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Regional', 
                'id_activity_category' => '2',
                'score' => '4',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ]  
        ]);     
    }
}
