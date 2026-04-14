<?php
// app/Console/Commands/ResetDailyQuota.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KuotaHarianPoli;
use App\Models\Poli;
use Carbon\Carbon;

class ResetDailyQuota extends Command
{
    protected $signature = 'quota:reset';
    protected $description = 'Reset kuota harian poli setiap jam 5 pagi';

    public function handle()
    {
        $today = Carbon::today();
        
        $this->info("Starting quota reset for date: " . $today->format('Y-m-d'));
        
        // Get all active polis
        $polis = Poli::where('is_active', true)->get();
        
        $resetCount = 0;
        $createdCount = 0;
        
        foreach ($polis as $poli) {
            $kuota = KuotaHarianPoli::where('poli_id', $poli->id)
                ->whereDate('tanggal', $today)
                ->first();
            
            if ($kuota) {
                // Reset existing quota
                $oldKuota = $kuota->kuota;
                $oldTerpakai = $kuota->terpakai;
                
                $kuota->update([
                    'kuota' => $poli->kuota_harian_default ?? 20,
                    'terpakai' => 0
                ]);
                
                $resetCount++;
                $this->line("Reset quota for {$poli->nama_poli}: {$oldKuota}/{$oldTerpakai} -> {$kuota->kuota}/0");
            } else {
                // Create new quota for today
                KuotaHarianPoli::create([
                    'poli_id' => $poli->id,
                    'tanggal' => $today,
                    'kuota' => $poli->kuota_harian_default ?? 20,
                    'terpakai' => 0
                ]);
                
                $createdCount++;
                $this->line("Created new quota for {$poli->nama_poli}: {$poli->kuota_harian_default} kuota");
            }
        }
        
        $this->info("Quota reset completed! Reset: {$resetCount}, Created: {$createdCount}");
        
        // Log ke file
        \Illuminate\Support\Facades\Log::info('Daily quota reset executed', [
            'date' => $today->format('Y-m-d'),
            'reset_count' => $resetCount,
            'created_count' => $createdCount,
            'executed_at' => Carbon::now()
        ]);
        
        return Command::SUCCESS;
    }
}