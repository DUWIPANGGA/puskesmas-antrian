<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $poliId = $request->get('poli_id');

        // Stats
        $totalVisits = \App\Models\Antrian::whereDate('tanggal', $date)
            ->when($poliId, fn($q) => $q->where('poli_id', $poliId))
            ->count();
            
        $completedAntrian = \App\Models\Antrian::whereDate('tanggal', $date)
            ->when($poliId, fn($q) => $q->where('poli_id', $poliId))
            ->whereNotNull('check_in_at')
            ->whereNotNull('dipanggil_at')
            ->get();
            
        $totalWaitMinutes = 0;
        foreach($completedAntrian as $a) {
            $totalWaitMinutes += $a->check_in_at->diffInMinutes($a->dipanggil_at);
        }
        $avgWaitTime = $completedAntrian->count() > 0 ? round($totalWaitMinutes / $completedAntrian->count()) : 0;

        // Queue Efficiency (Completed / Total)
        $totalStarted = \App\Models\Antrian::whereDate('tanggal', $date)
            ->when($poliId, fn($q) => $q->where('poli_id', $poliId))
            ->whereIn('status', ['dipanggil', 'selesai'])
            ->count();
        $efficiency = $totalVisits > 0 ? round(($totalStarted / $totalVisits) * 100, 1) : 0;

        // Clinic Stats
        $polis = \App\Models\Poli::all();
        $clinicStats = $polis->map(function($poli) use ($date) {
            $kuotaEntry = \App\Models\KuotaHarianPoli::where('poli_id', $poli->id)
                ->whereDate('tanggal', $date)
                ->first();
            
            $quota = $kuotaEntry ? $kuotaEntry->kuota : ($poli->kuota_harian_default ?: 50);
            $filled = \App\Models\Antrian::where('poli_id', $poli->id)
                ->whereDate('tanggal', $date)
                ->count();
                
            return [
                'poli' => $poli,
                'quota' => $quota,
                'filled' => $filled,
                'is_full' => $filled >= $quota
            ];
        });

        // Visit logs (Paginated)
        $visitLogs = \App\Models\Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal', $date)
            ->when($poliId, fn($q) => $q->where('poli_id', $poliId))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.visit_reports.index', compact(
            'totalVisits', 
            'avgWaitTime', 
            'efficiency', 
            'clinicStats', 
            'visitLogs', 
            'date', 
            'poliId', 
            'polis'
        ));
    }
}
