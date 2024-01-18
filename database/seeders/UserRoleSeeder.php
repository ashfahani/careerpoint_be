<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_user_role')->insert([
            ['name' => 'System Administrator', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Kemahasiswaan', 'created_at' => date('Y-m-d H:i:s')], 
            ['name' => 'Dosen / Mentor', 'created_at' => date('Y-m-d H:i:s')], 
            ['name' => 'Mahasiswa', 'created_at' => date('Y-m-d H:i:s')]
        ]);
    }
}
