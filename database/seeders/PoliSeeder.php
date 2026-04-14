<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Poli Umum',
                'kode_poli' => 'UMUM',
                'deskripsi' => 'Pelayanan kesehatan umum untuk segala keluhan penyakit ringan hingga sedang',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Gigi',
                'kode_poli' => 'GIGI',
                'deskripsi' => 'Pelayanan kesehatan gigi dan mulut',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli KIA',
                'kode_poli' => 'KIA',
                'deskripsi' => 'Kesehatan Ibu dan Anak, termasuk pemeriksaan kehamilan dan imunisasi',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Lansia',
                'kode_poli' => 'LANSIA',
                'deskripsi' => 'Pelayanan kesehatan khusus untuk lansia',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Anak',
                'kode_poli' => 'ANAK',
                'deskripsi' => 'Pelayanan kesehatan khusus untuk anak-anak',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Mata',
                'kode_poli' => 'MATA',
                'deskripsi' => 'Pelayanan kesehatan mata',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Kulit',
                'kode_poli' => 'KULIT',
                'deskripsi' => 'Pelayanan kesehatan kulit dan kelamin',
                'is_active' => true
            ],
            [
                'nama_poli' => 'Poli Gizi',
                'kode_poli' => 'GIZI',
                'deskripsi' => 'Konsultasi gizi dan pola makan sehat',
                'is_active' => true
            ],
        ];

        foreach ($polis as $poli) {
            Poli::create($poli);
        }
    }
}