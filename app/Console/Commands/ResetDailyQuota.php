<?php
// app/Console/Commands/ResetDailyQuota.php - OPSIONAL (bisa dihapus atau dinonaktifkan)

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KuotaHarianPoli;
use App\Models\Poli;
use Carbon\Carbon;

class ResetDailyQuota extends Command
{
    protected $signature = 'quota:reset {--date= : Tanggal yang akan direset (default: hari ini)}';
    protected $description = 'Reset kuota harian poli (manual by admin)';

    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
        
        $this->info("Starting quota reset for date: " . $date->format('Y-m-d'));
        
        $resetCount = KuotaHarianPoli::resetAllKuota($date);
        
        $this->info("Quota reset completed! Reset: {$resetCount} poli");
        
        \Illuminate\Support\Facades\Log::info('Manual quota reset executed', [
            'date' => $date->format('Y-m-d'),
            'reset_count' => $resetCount,
            'executed_by' => auth()->user()->name ?? 'console',
            'executed_at' => Carbon::now()
        ]);
        
        return Command::SUCCESS;
    }
}