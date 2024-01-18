<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_activity_category')->insert([
            [
                'name' => 'Internal', 
                'flag_data' => 'I',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'External', 
                'flag_data' => 'E',
                'created_at' => date('Y-m-d H:i:s')
            ] 
        ]);        
    }
}
