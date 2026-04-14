<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PoliSeeder::class,
            UserSeeder::class,
            DokterSeeder::class,
            JadwalDokterSeeder::class,
            KuotaHarianPoliSeeder::class,
            AntrianSeeder::class,
            ResepSeeder::class,
            DetailResepSeeder::class,
            NotifikasiSeeder::class,
            LaporanKunjunganSeeder::class,
        ]);
    }
}