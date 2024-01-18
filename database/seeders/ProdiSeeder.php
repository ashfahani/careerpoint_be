<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_prodi')->insert([
            [
                'prodi_unique_id'   => 'Others',
                'prodi_name'        => 'FM / PS',
                'fakultas_name'     => 'Others', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1A',
                'prodi_name'        => 'S1 Accounting',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1B',
                'prodi_name'        => 'S1 Business',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1E',
                'prodi_name'        => 'S1 Event',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1F',
                'prodi_name'        => 'S1 Finance',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1P',
                'prodi_name'        => 'S1 Marketing',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1BM',
                'prodi_name'        => 'S1 Business Mathematics',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1ENR',
                'prodi_name'        => 'S1 Renewable Energy Engineering',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1FBT',
                'prodi_name'        => 'S1 Food Business Technology',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1SE',
                'prodi_name'        => 'S1 Software Engineering',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1CSE',
                'prodi_name'        => 'S1 Computer Systems Engineering',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1BE',
                'prodi_name'        => 'S1 Business Economics',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1TB',
                'prodi_name'        => 'S1 Hospitality Business',
                'fakultas_name'     => 'SBE', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1IBL',
                'prodi_name'        => 'S1 International Business Law',
                'fakultas_name'     => 'SHSI', 
                'created_at'        => date('Y-m-d H:i:s')
            ],
            [
                'prodi_unique_id'   => 'S1PDE',
                'prodi_name'        => 'S1 Product Design Engineering',
                'fakultas_name'     => 'STEM', 
                'created_at'        => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
