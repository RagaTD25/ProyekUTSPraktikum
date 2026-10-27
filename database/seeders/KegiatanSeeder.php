<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $akademik = DB::table('jenis_kegiatan')
            ->where('nama_jenis', 'Akademik')
            ->value('id');

        $sosial = DB::table('jenis_kegiatan')
            ->where('nama_jenis', 'Sosial')
            ->value('id');

        DB::table('kegiatan')->insert([
            [
                'kode_kegiatan' => 'KG00000001',
                'nama_kegiatan' => 'Pelatihan Web',
                'email_pic' => 'web@kampus.ac.id',
                'kuota' => 40,
                'jenis_kegiatan_id' => $akademik,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kegiatan' => 'KG00000002',
                'nama_kegiatan' => 'Seminar Karier',
                'email_pic' => 'karier@kampus.ac.id',
                'kuota' => 100,
                'jenis_kegiatan_id' => $akademik,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kegiatan' => 'KG00000003',
                'nama_kegiatan' => 'Bakti Pesisir',
                'email_pic' => 'pesisir@kampus.ac.id',
                'kuota' => 60,
                'jenis_kegiatan_id' => $sosial,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
