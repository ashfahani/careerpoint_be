<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_activity_type')->insert([
            [
                'name' => 'Akademik', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Non Akademik', 
                'created_at' => date('Y-m-d H:i:s')
            ] 
        ]);     
    }
}
