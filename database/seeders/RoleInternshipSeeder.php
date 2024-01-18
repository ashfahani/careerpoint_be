<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleInternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_role_internship')->insert([
            [
                'name'  => 'Pelaksana',
                'score' => '1',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Decision Maker / Pembuat Improvement',
                'score' => '3',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Pelaksana / Officer',
                'score' => '1',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Pembuat Keputusan / Decision Maker',
                'score' => '3',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
