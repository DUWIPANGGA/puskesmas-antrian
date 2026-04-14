<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanKunjungan;
use App\Models\Antrian;

class LaporanKunjunganSeeder extends Seeder
{
    public function run(): void
    {
        $antrians = Antrian::where('status', 'selesai')->get();
        
        if ($antrians->isEmpty()) {
            $this->command->warn('No completed antrians found, skipping laporan kunjungan seeder.');
            return;
        }
        
        foreach ($antrians as $antrian) {
            // Skip if already has laporan
            if (LaporanKunjungan::where('antrian_id', $antrian->id)->exists()) {
                continue;
            }
            
            try {
                $checkInTime = $antrian->check_in_at ?? $antrian->created_at;
                $selesaiTime = $antrian->selesai_at ?? $antrian->created_at->copy()->addHours(rand(1, 3));
                
                if ($checkInTime && $selesaiTime) {
                    $lamaPelayanan = $checkInTime->diffInMinutes($selesaiTime);
                } else {
                    $lamaPelayanan = rand(30, 120);
                }
                
                $dokter_id = $antrian->jadwalDokter?->dokter_id;
                if (!$dokter_id) {
                    $dokter = \App\Models\Dokter::where('poli_id', $antrian->poli_id)->first();
                    $dokter_id = $dokter ? $dokter->user_id : \App\Models\User::where('role', 'dokter')->first()->id;
                }
                
                LaporanKunjungan::create([
                    'antrian_id' => $antrian->id,
                    'tanggal' => $antrian->tanggal,
                    'poli_id' => $antrian->poli_id,
                    'pasien_id' => $antrian->pasien_id,
                    'dokter_id' => $dokter_id,
                    'waktu_check_in' => $checkInTime,
                    'waktu_dipanggil' => $antrian->dipanggil_at,
                    'waktu_selesai' => $selesaiTime,
                    'lama_pelayanan' => $lamaPelayanan,
                    'status_pelayanan' => 'selesai',
                ]);
            } catch (\Exception $e) {
                $this->command->warn("Failed to create laporan for antrian {$antrian->id}: " . $e->getMessage());
                continue;
            }
        }
    }
}