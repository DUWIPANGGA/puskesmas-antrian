<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Antrian;
use App\Models\User;
use App\Models\Poli;
use Carbon\Carbon;

class AntrianSeeder extends Seeder
{
    public function run(): void
    {
        $pasiens = User::where('role', 'pasien')->get();
        $polis = Poli::all();
        
        $statuses = ['selesai', 'selesai', 'dipanggil', 'check_in', 'menunggu'];
        
        // Generate unique dates
        $dates = [
            Carbon::now()->subDays(2),
            Carbon::now()->subDay(),
            Carbon::now(),
            Carbon::now()->addDay(),
            Carbon::now()->addDays(2),
        ];
        
        $allNomorAntrian = [];
        
        foreach ($dates as $date) {
            foreach ($polis as $poli) {
                // Random jumlah antrian per poli per hari (5-15)
                $antrianPerPoli = rand(5, 15);
                
                for ($i = 1; $i <= $antrianPerPoli; $i++) {
                    // Generate unique nomor antrian
                    do {
                        $nomorAntrian = sprintf(
                            '%s-%d',
                            substr($poli->kode_poli, 0, 1),
                            $i
                        );
                    } while (in_array($nomorAntrian, $allNomorAntrian));
                    
                    $allNomorAntrian[] = $nomorAntrian;
                    
                    $pasien = $pasiens->random();
                    $status = $statuses[array_rand($statuses)];
                    
                    // Generate random times
                    $checkInTime = null;
                    $dipanggilTime = null;
                    $selesaiTime = null;
                    $createdAt = $date->copy()->setTime(rand(7, 20), rand(0, 59));
                    
                    if (in_array($status, ['check_in', 'dilayani', 'selesai'])) {
                        $checkInTime = $date->copy()->setTime(8, rand(0, 59));
                    }
                    
                    if (in_array($status, ['dilayani', 'selesai'])) {
                        $dipanggilTime = $date->copy()->setTime(9, rand(0, 59));
                    }
                    
                    if ($status == 'selesai') {
                        $selesaiTime = $date->copy()->setTime(10, rand(0, 59));
                    }
                    
                    try {
                        $antrian = Antrian::create([
                            'nomor_antrian' => $nomorAntrian,
                            'pasien_id' => $pasien->id,
                            'poli_id' => $poli->id,
                            'jadwal_dokter_id' => null,
                            'tanggal' => $date,
                            'nomor_urut' => $i,
                            'status' => $status,
                            'check_in_at' => $checkInTime,
                            'dipanggil_at' => $dipanggilTime,
                            'selesai_at' => $selesaiTime,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                        
                        // Update kuota terpakai jika status sudah check-in atau lebih
                        if (in_array($status, ['check_in', 'dipanggil', 'selesai'])) {
                            $kuotaHarian = $poli->kuotaHarian()->where('tanggal', $date)->first();
                            if ($kuotaHarian) {
                                $kuotaHarian->increment('terpakai');
                            }
                        }
                        
                    } catch (\Exception $e) {
                        // Skip if duplicate, try next iteration
                        continue;
                    }
                }
            }
        }
        
        // Add today's antrian with more realistic data
        $today = Carbon::now();
        $todayNomorAntrian = [];
        
        foreach ($polis as $poli) {
            $antrianCount = rand(8, 15);
            
            for ($i = 1; $i <= $antrianCount; $i++) {
                // Generate unique nomor antrian for today
                do {
                    $nomorAntrian = sprintf(
                        '%s-%d',
                        substr($poli->kode_poli, 0, 1),
                        $i
                    );
                } while (in_array($nomorAntrian, $todayNomorAntrian));
                
                $todayNomorAntrian[] = $nomorAntrian;
                
                $pasien = $pasiens->random();
                
                // More realistic status distribution for today
                if ($i <= 3) {
                    $status = 'selesai';
                    $checkInTime = $today->copy()->setTime(8, rand(0, 30));
                    $dipanggilTime = $today->copy()->setTime(9, rand(0, 30));
                    $selesaiTime = $today->copy()->setTime(10, rand(0, 30));
                } elseif ($i <= 6) {
                    $status = 'dipanggil';
                    $checkInTime = $today->copy()->setTime(9, rand(0, 30));
                    $dipanggilTime = $today->copy()->setTime(10, rand(0, 30));
                    $selesaiTime = null;
                } elseif ($i <= 9) {
                    $status = 'check_in';
                    $checkInTime = $today->copy()->setTime(10, rand(0, 30));
                    $dipanggilTime = null;
                    $selesaiTime = null;
                } else {
                    $status = 'menunggu';
                    $checkInTime = null;
                    $dipanggilTime = null;
                    $selesaiTime = null;
                }
                
                try {
                    Antrian::create([
                        'nomor_antrian' => $nomorAntrian,
                        'pasien_id' => $pasien->id,
                        'poli_id' => $poli->id,
                        'jadwal_dokter_id' => null,
                        'tanggal' => $today,
                        'nomor_urut' => $i,
                        'status' => $status,
                        'check_in_at' => $checkInTime,
                        'dipanggil_at' => $dipanggilTime,
                        'selesai_at' => $selesaiTime,
                        'created_at' => $today->copy()->setTime(rand(7, 12), rand(0, 59)),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Skip if duplicate
                    continue;
                }
            }
        }
    }
}