<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeminarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_role_seminar')->insert([
            [
                'name'  => 'Peserta / Partisipan',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Pembicara / Peneliti',
                'score' => '5',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Pengisi Acara, MC / Talent MC',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Narasumber / Speaker / Moderator',
                'score' => '5',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Peserta / Participant',
                'score' => '2',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
