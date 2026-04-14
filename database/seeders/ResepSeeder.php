<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resep;
use App\Models\Antrian;
use App\Models\User;

class ResepSeeder extends Seeder
{
    public function run(): void
    {
        $antrians = Antrian::whereIn('status', ['dipanggil', 'selesai'])->get();
        $dokters = User::where('role', 'dokter')->get();
        
        $diagnosaList = [
            'Demam typoid',
            'ISPA (Infeksi Saluran Pernapasan Akut)',
            'Gastritis (Maag)',
            'Hipertensi',
            'Diabetes Mellitus tipe 2',
            'Diare akut',
            'Migrain',
            'Dermatitis kontak',
            'Konjungtivitis',
            'Otitis media akut',
        ];
        
        foreach ($antrians as $index => $antrian) {
            if ($index < 30) { // Buat 30 resep sample
                $status = ['pending', 'diproses', 'selesai'][array_rand(['pending', 'diproses', 'selesai'])];
                
                Resep::create([
                    'antrian_id' => $antrian->id,
                    'dokter_id' => $dokters->random()->id,
                    'pasien_id' => $antrian->pasien_id,
                    'diagnosa' => $diagnosaList[array_rand($diagnosaList)],
                    'catatan' => 'Pasien disarankan istirahat cukup dan minum air putih yang banyak',
                    'status' => $status,
                    'diproses_at' => in_array($status, ['diproses', 'selesai']) ? now()->subHours(rand(1, 5)) : null,
                    'selesai_at' => $status == 'selesai' ? now()->subHours(rand(1, 3)) : null,
                ]);
            }
        }
    }
}