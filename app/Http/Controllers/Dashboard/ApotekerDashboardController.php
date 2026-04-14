<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Antrian;
use Illuminate\Http\Request;

class ApotekerDashboardController extends Controller
{
    // ====================== INCOMING PRESCRIPTIONS ======================
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $selected = $request->get('selected');

        $incoming = Resep::with(['pasien', 'dokter', 'antrian.poli', 'detailResep'])
            ->incoming()
            ->when($search, function($q) use ($search) {
                $q->whereHas('pasien', fn($s) => $s->where('name', 'like', "%$search%"))
                  ->orWhere('nomor_resep', 'like', "%$search%");
            })
            ->latest()
            ->get();

        $selectedResep = $selected
            ? Resep::with(['pasien', 'dokter', 'antrian.poli', 'detailResep'])->find($selected)
            : $incoming->first();

        return view('apoteker.incoming', compact('incoming', 'selectedResep', 'search'));
    }

    // ====================== IN PROCESS ======================
    public function inProcess(Request $request)
    {
        $selected = $request->get('selected');

        $processing = Resep::with(['pasien', 'dokter', 'antrian.poli', 'detailResep'])
            ->inProcess()
            ->latest('diproses_at')
            ->get();

        $selectedResep = $selected
            ? Resep::with(['pasien', 'dokter', 'antrian.poli', 'detailResep'])->find($selected)
            : $processing->first();

        return view('apoteker.in_process', compact('processing', 'selectedResep'));
    }

    // ====================== COMPLETED ======================
    public function completed(Request $request)
    {
        $filter = $request->get('filter', 'today');
        $search = $request->get('search');

        $query = Resep::with(['pasien', 'dokter', 'antrian.poli'])
            ->completed()
            ->when($search, function($q) use ($search) {
                $q->whereHas('pasien', fn($s) => $s->where('name', 'like', "%$search%"))
                  ->orWhere('nomor_resep', 'like', "%$search%");
            });

        if ($filter === 'today') {
            $query->whereDate('selesai_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('selesai_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $completed  = $query->latest('selesai_at')->paginate(15);
        $readyCount = Resep::where('status', 'siap_ambil')->whereDate('selesai_at', today())->count();
        $takenCount = Resep::where('status', 'diambil')->whereDate('diambil_at', today())->count();
        $avgMinutes = $this->getAvgProcessingMinutes();

        return view('apoteker.completed', compact('completed', 'filter', 'search', 'readyCount', 'takenCount', 'avgMinutes'));
    }

    // ====================== REPORTS ======================
    public function reports(Request $request)
    {
        $period = $request->get('period', 'daily');
        $today  = today();

        // Date range based on period
        [$dateFrom, $dateTo] = match($period) {
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly'  => [now()->startOfYear(), now()->endOfYear()],
            default   => [$today->copy()->startOfDay(), $today->copy()->endOfDay()], // daily
        };

        // Header stats
        $totalResep    = Resep::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $totalSelesai  = Resep::whereBetween('created_at', [$dateFrom, $dateTo])->whereIn('status', ['siap_ambil', 'diambil'])->count();
        $totalDiambil  = Resep::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'diambil')->count();
        $avgMin        = $this->getAvgProcessingMinutes();
        $completionRate = $totalResep > 0 ? round(($totalSelesai / $totalResep) * 100, 1) : 0;

        // Most prescribed medicine
        $topObat = DetailResep::select('nama_obat', \DB::raw('COUNT(*) as total'))
            ->groupBy('nama_obat')
            ->orderByDesc('total')
            ->first();

        // Daily volume chart (last 7 days always)
        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            return [
                'label' => $date->format('D'),
                'date'  => $date->format('d/m'),
                'value' => Resep::whereDate('created_at', $date)->count(),
                'done'  => Resep::whereDate('created_at', $date)->whereIn('status', ['siap_ambil', 'diambil'])->count(),
                'isToday' => $date->isToday(),
            ];
        });

        // Top 5 most prescribed medicines in period
        $topMedicines = DetailResep::select('nama_obat', \DB::raw('SUM(jumlah) as total_qty'), \DB::raw('COUNT(*) as total_rx'))
            ->groupBy('nama_obat')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Breakdown by poli
        $poliBreakdown = Resep::whereBetween('reseps.created_at', [$dateFrom, $dateTo])
            ->join('antrians', 'reseps.antrian_id', '=', 'antrians.id')
            ->join('polis', 'antrians.poli_id', '=', 'polis.id')
            ->select('polis.nama_poli', \DB::raw('COUNT(reseps.id) as total'))
            ->groupBy('polis.nama_poli')
            ->orderByDesc('total')
            ->get();

        // Recent activity log (paginated)
        $activityLog = Resep::with(['pasien', 'dokter', 'antrian.poli', 'detailResep'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->paginate(10);

        return view('apoteker.reports', compact(
            'totalResep', 'totalSelesai', 'totalDiambil',
            'avgMin', 'completionRate',
            'topObat', 'chartData', 'topMedicines',
            'poliBreakdown', 'activityLog', 'period'
        ));
    }


    // ====================== ACTIONS ======================

    // Mulai proses resep
    public function startProcess(Request $request, $id)
    {
        $resep = Resep::findOrFail($id);
        if ($resep->status !== 'pending') {
            return redirect()->back()->with('error', 'Resep sudah diproses.');
        }
        if (!$resep->nomor_resep) {
            $resep->update(['nomor_resep' => Resep::generateNomor()]);
        }
        $resep->update([
            'status'      => 'diproses',
            'apoteker_id' => auth()->id(),
            'diproses_at' => now(),
        ]);
        return redirect()->route('apoteker.in-process', ['selected' => $resep->id])
            ->with('success', 'Resep mulai diproses.');
    }

    // Toggle centang item obat
    public function toggleItem(Request $request, $resepId, $itemId)
    {
        $item = DetailResep::where('resep_id', $resepId)->findOrFail($itemId);
        $item->update(['is_checked' => !$item->is_checked]);
        return response()->json([
            'checked'  => $item->is_checked,
            'progress' => $item->resep->progressPersen(),
        ]);
    }

    // Update catatan apoteker
    public function updateCatatan(Request $request, $id)
    {
        Resep::findOrFail($id)->update(['catatan_apoteker' => $request->catatan_apoteker]);
        return redirect()->back()->with('success', 'Catatan disimpan.');
    }

    // Selesaikan (siap diambil)
    public function finishProcess(Request $request, $id)
    {
        $resep = Resep::findOrFail($id);
        $resep->selesaikan();
        return redirect()->route('apoteker.completed')
            ->with('success', "Resep #{$resep->nomor_resep} siap diambil pasien.");
    }

    // Konfirmasi diambil
    public function confirmPickup(Request $request, $id)
    {
        Resep::findOrFail($id)->diambil();
        return redirect()->back()->with('success', 'Obat berhasil dikonfirmasi diambil pasien.');
    }

    // Tahan (hold) resep
    public function holdResep(Request $request, $id)
    {
        Resep::findOrFail($id)->update(['status' => 'pending']);
        return redirect()->route('apoteker.incoming')
            ->with('info', 'Resep dikembalikan ke antrian masuk.');
    }

    // Cetak etiket (return HTML untuk print)
    public function cetakEtiket($id)
    {
        $resep = Resep::with(['pasien', 'dokter', 'detailResep', 'antrian.poli'])->findOrFail($id);
        return view('apoteker.etiket', compact('resep'));
    }

    // ---- Helper ----
    private function getAvgProcessingMinutes(): int
    {
        $reseps = Resep::whereNotNull('diproses_at')
            ->whereNotNull('selesai_at')
            ->whereDate('selesai_at', today())
            ->get();
        if ($reseps->isEmpty()) return 0;
        $total = $reseps->sum(fn($r) => $r->diproses_at->diffInMinutes($r->selesai_at));
        return (int) round($total / $reseps->count());
    }
}
