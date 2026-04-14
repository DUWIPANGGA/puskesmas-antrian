<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Poli;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokters = [
            'dr. Ahmad Wijaya, Sp.PD' => ['keahlian' => 'Spesialis Penyakit Dalam', 'kode_poli' => 'UMUM'],
            'dr. Siti Rahmah, Sp.G' => ['keahlian' => 'Spesialis Gigi', 'kode_poli' => 'GIGI'],
            'dr. Budi Santoso, Sp.A' => ['keahlian' => 'Spesialis Anak', 'kode_poli' => 'ANAK'],
            'dr. Dewi Anggraeni' => ['keahlian' => 'Kandungan / KIA', 'kode_poli' => 'KIA'],
            'dr. Rizki Firmansyah' => ['keahlian' => 'Dokter Umum', 'kode_poli' => 'UMUM'],
        ];

        foreach ($dokters as $nama => $data) {
            $user = User::where('name', $nama)->first();
            $poli = Poli::where('kode_poli', $data['kode_poli'])->first();

            if ($user && $poli) {
                Dokter::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'poli_id' => $poli->id,
                        'keahlian' => $data['keahlian'],
                        'nip' => 'NIP' . rand(10000000, 99999999)
                    ]
                );
            }
        }

        // Just in case there are other doctors
        $users = User::where('role', 'dokter')->whereNotIn('name', array_keys($dokters))->get();
        $poliUmum = Poli::where('kode_poli', 'UMUM')->first();
        foreach ($users as $user) {
            Dokter::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'poli_id' => $poliUmum ? $poliUmum->id : null,
                    'keahlian' => 'Umum',
                    'nip' => 'NIP' . rand(10000000, 99999999)
                ]
            );
        }
    }
}
