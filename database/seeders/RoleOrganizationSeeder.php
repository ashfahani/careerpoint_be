<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_role_organization')->insert([
            [
                'name'  => 'Anggota (Khusus SAC & SCC)',
                'score' => '1',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Staf',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Koordinator Divisi',
                'score' => '3',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Ketua / Wakil Ketua',
                'score' => '5',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Sekretaris / Bendahara / Board of Advisor',
                'score' => '4',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Ketua/Wakil (SB/HIMA)',
                'score' => '20',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Sekretaris, Bendahara (SB / HIMA), BOA',
                'score' => '16',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Lodestar',
                'score' => '8',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Koordinator Lodestar',
                'score' => '15',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Lodestar-',
                'score' => '4',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Lodestar-',
                'score' => '4',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Staf / Staff',
                'score' => '1',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Anggota',
                'score' => '1',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Koordinator / Coordinator',
                'score' => '3',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'BPH (Sekretaris, Bendahara)',
                'score' => '4',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Ketua dan Wakil Ketua / President and Vice President',
                'score' => '5',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
