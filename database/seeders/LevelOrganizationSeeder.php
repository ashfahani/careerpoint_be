<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_level_organization')->insert([
            [
                'name' => 'Kelas', 
                'id_activity_category' => '1',
                'score' => '0.5',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Batch Representative', 
                'id_activity_category' => '2',
                'score' => '1',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'SAC / SCC', 
                'id_activity_category' => '1',
                'score' => '2',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'SAC / Project Student Board', 
                'id_activity_category' => '1',
                'score' => '3',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Student Board / HIMA / AIESEC', 
                'id_activity_category' => '1',
                'score' => '4',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'International', 
                'id_activity_category' => '2',
                'score' => '5',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Student Board - Ketua/Wakil', 
                'id_activity_category' => '1',
                'score' => '20',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Student Board - Sekretaris/Bendahara/BOA', 
                'id_activity_category' => '1',
                'score' => '19',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Student Board - Ketua Divisi/Koordinator', 
                'id_activity_category' => '1',
                'score' => '12',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Student Board - Staff', 
                'id_activity_category' => '1',
                'score' => '8',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Batch Representative', 
                'id_activity_category' => '1',
                'score' => '1',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Himpunan Mahasiswa', 
                'id_activity_category' => '1',
                'score' => '4',
                'na' => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Lodestar', 
                'id_activity_category' => '1',
                'score' => '4',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Jabodetabek', 
                'id_activity_category' => '2',
                'score' => '1',
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
                'name' => 'Regional', 
                'id_activity_category' => '2',
                'score' => '4',
                'na' => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ]  
        ]);
    }
}
