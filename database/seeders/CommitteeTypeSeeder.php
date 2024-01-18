<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommitteeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_committee_type')->insert([
            [
                'name'          => 'Perlombaan',
                'created_by'    => '~SEEDER~',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Seminar',
                'created_by'    => '~SEEDER~',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Kepanitiaan',
                'created_by'    => '~SEEDER~',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
