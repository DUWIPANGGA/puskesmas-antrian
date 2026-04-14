<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalDokter;
use App\Models\User;
use App\Models\Poli;

class JadwalDokterSeeder extends Seeder
{
    public function run(): void
    {
        $dokters = User::where('role', 'dokter')->get();
        $polis = Poli::all();
        
        $jadwals = [
            // dr. Ahmad Wijaya - Poli Umum
            [
                'hari' => 'senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 25,
            ],
            [
                'hari' => 'rabu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'jumat',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 25,
            ],
            
            // dr. Siti Rahmah - Poli Gigi
            [
                'hari' => 'selasa',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'kamis',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'sabtu',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 15,
            ],
            
            // dr. Budi Santoso - Poli Anak
            [
                'hari' => 'senin',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'rabu',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 25,
            ],
            [
                'hari' => 'jumat',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 20,
            ],
            
            // dr. Dewi Anggraeni - Poli KIA
            [
                'hari' => 'selasa',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'kamis',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'sabtu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 15,
            ],
            
            // dr. Rizki Firmansyah - Poli Umum
            [
                'hari' => 'selasa',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'kuota' => 20,
            ],
            [
                'hari' => 'kamis',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kuota' => 25,
            ],
        ];
        
        foreach ($jadwals as $index => $jadwal) {
            // Assign dokter dan poli berdasarkan index
            if ($index < 3) {
                $dokter = $dokters[0];
                $poli = $polis->where('kode_poli', 'UMUM')->first();
            } elseif ($index < 6) {
                $dokter = $dokters[1];
                $poli = $polis->where('kode_poli', 'GIGI')->first();
            } elseif ($index < 9) {
                $dokter = $dokters[2];
                $poli = $polis->where('kode_poli', 'ANAK')->first();
            } elseif ($index < 12) {
                $dokter = $dokters[3];
                $poli = $polis->where('kode_poli', 'KIA')->first();
            } else {
                $dokter = $dokters[4];
                $poli = $polis->where('kode_poli', 'UMUM')->first();
            }
            
            JadwalDokter::create(array_merge($jadwal, [
                'dokter_id' => $dokter->id,
                'poli_id' => $poli->id,
                'is_active' => true,
            ]));
        }
    }
}