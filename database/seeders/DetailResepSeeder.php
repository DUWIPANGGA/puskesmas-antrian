<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailResep;
use App\Models\Resep;

class DetailResepSeeder extends Seeder
{
    public function run(): void
    {
        $reseps = Resep::all();
        
        $obatList = [
            ['nama' => 'Paracetamol', 'dosis' => '500 mg', 'aturan' => '3x sehari 1 tablet'],
            ['nama' => 'Amoxicillin', 'dosis' => '500 mg', 'aturan' => '3x sehari 1 kapsul'],
            ['nama' => 'CTM', 'dosis' => '4 mg', 'aturan' => '2x sehari 1 tablet'],
            ['nama' => 'Antimo', 'dosis' => '50 mg', 'aturan' => 'Sesuai kebutuhan'],
            ['nama' => 'Promag', 'dosis' => '400 mg', 'aturan' => '3x sehari 1 tablet'],
            ['nama' => 'Omeprazole', 'dosis' => '20 mg', 'aturan' => '1x sehari 1 kapsul'],
            ['nama' => 'Amlodipine', 'dosis' => '10 mg', 'aturan' => '1x sehari 1 tablet'],
            ['nama' => 'Metformin', 'dosis' => '500 mg', 'aturan' => '2x sehari 1 tablet'],
            ['nama' => 'Loperamide', 'dosis' => '2 mg', 'aturan' => 'Sesuai kebutuhan'],
            ['nama' => 'Ibuprofen', 'dosis' => '400 mg', 'aturan' => '3x sehari 1 tablet'],
        ];
        
        foreach ($reseps as $resep) {
            // Setiap resep memiliki 1-3 obat
            $jumlahObat = rand(1, 3);
            $selectedObats = array_rand($obatList, $jumlahObat);
            
            if (!is_array($selectedObats)) {
                $selectedObats = [$selectedObats];
            }
            
            foreach ($selectedObats as $obatIndex) {
                $obat = $obatList[$obatIndex];
                DetailResep::create([
                    'resep_id' => $resep->id,
                    'nama_obat' => $obat['nama'],
                    'dosis' => $obat['dosis'],
                    'jumlah' => rand(1, 3) * 10,
                    'aturan_pakai' => $obat['aturan'],
                    'keterangan' => 'Diminum setelah makan',
                ]);
            }
        }
    }
}