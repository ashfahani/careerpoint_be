<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_role_competition')->insert([
            [
                'name'  => 'Juara I',
                'score' => '8',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Juara II/III',
                'score' => '6',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Juara Harapan / Predikat Khusus',
                'score' => '3',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Partisipan',
                'score' => '1',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Finalis (10 Besar)',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Partisipan Internal',
                'score' => '1',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Peserta / Participant',
                'score' => '1',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Finalis, 10 Besar / Finalist, Big 10',
                'score' => '2',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Juara Harapan / Special Prize',
                'score' => '3',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Juara 2, 3 / 2nd, 3rd Place',
                'score' => '6',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Juara 1 / 1st Place',
                'score' => '8',
                'na'    => 'Y',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
