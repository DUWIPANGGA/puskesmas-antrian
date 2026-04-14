<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KuotaHarianPoli;
use App\Models\Poli;
use Carbon\Carbon;

class KuotaHarianPoliSeeder extends Seeder
{
    public function run(): void
    {
        $polis = Poli::all();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth()->addMonths(1);
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            foreach ($polis as $poli) {
                // Set kuota berdasarkan poli
                $kuota = match($poli->kode_poli) {
                    'UMUM' => rand(30, 50),
                    'ANAK' => rand(25, 40),
                    'GIGI' => rand(20, 30),
                    'KIA' => rand(20, 35),
                    'LANSIA' => rand(15, 25),
                    'MATA' => rand(15, 25),
                    'KULIT' => rand(15, 25),
                    'GIZI' => rand(10, 20),
                    default => 20,
                };
                
                // Hari Minggu kuota lebih sedikit
                if ($date->isSunday()) {
                    $kuota = round($kuota * 0.5);
                }
                
                // Hari Sabtu kuota sedikit berkurang
                if ($date->isSaturday()) {
                    $kuota = round($kuota * 0.7);
                }
                
                KuotaHarianPoli::create([
                    'poli_id' => $poli->id,
                    'tanggal' => $date->copy(),
                    'kuota' => $kuota,
                    'terpakai' => 0,
                ]);
            }
        }
    }
}