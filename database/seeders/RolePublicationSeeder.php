<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_role_publication')->insert([
            [
                'name'  => 'Penulis Jurnal Nasional Non-Akreditasi (Hasil PengMas)',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Artikel Populer',
                'score' => '2',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Internasional (Hasil PKM)',
                'score' => '3',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Nasional Non-Akreditasi (Hasil Riset)',
                'score' => '4',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Nasional Terakreditasi (Hasil PengMas)',
                'score' => '4',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Internasional (Hasil Riset)',
                'score' => '6',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Internasional Bereputasi (Hasil PengMas)',
                'score' => '6',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Nasional Terakreditasi (Hasil Riset)',
                'score' => '8',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Book Chapter',
                'score' => '8',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Jurnal Internasional Bereputasi (Hasil Riset)',
                'score' => '12',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Penulis Buku Ber-ISBN',
                'score' => '16',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'  => 'Pemilik HKI/Paten/Produk/Karya Seni',
                'score' => '16',
                'na'    => 'N',
                'created_by' => '~SEEDER~',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
