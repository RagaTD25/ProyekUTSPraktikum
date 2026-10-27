<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_kegiatan')->insert([
            ['nama_jenis' => 'Akademik'],
            ['nama_jenis' => 'Sosial'],
        ]);
    }
}
