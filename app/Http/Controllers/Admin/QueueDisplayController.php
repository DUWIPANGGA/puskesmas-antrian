<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Poli;
use Illuminate\Http\Request;

class QueueDisplayController extends Controller
{
    public function index(Request $request)
    {
        $polis = Poli::where('is_active', true)->get();
        $selectedPoliId = $request->poli_id;
        $type = 'loket';
        
        return view('dashboard.admin.display', compact('polis', 'selectedPoliId', 'type'));
    }

    public function displayPemeriksaan(Request $request)
    {
        $polis = Poli::where('is_active', true)->get();
        $selectedPoliId = $request->poli_id;
        $type = 'pemeriksaan';
        
        return view('dashboard.admin.display_pemeriksaan', compact('polis', 'selectedPoliId', 'type'));
    }

    public function getStatus(Request $request)
    {
        $polis = Poli::where('is_active', true)->get()->map(function($p) {
            // Yang sedang dipanggil oleh ADMIN (Loket Registrasi)
            $admin = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->where('status', 'dipanggil')
                ->orderByDesc('dipanggil_at')
                ->first();

            // Yang sedang dipanggil oleh DOKTER (Pemeriksaan)
            $doctor = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->where('status', 'dipanggil_dokter')
                ->orderByDesc('dipanggil_at')
                ->first();

            // Sisa antrian (yang belum masuk dokter)
            $remaining = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->whereIn('status', ['menunggu', 'check_in', 'siap_pemeriksaan'])
                ->count();

            // 5 Antrian berikutnya (yang sudah di-check-in admin, siap ke dokter)
            $nextQueues = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->where('status', 'siap_pemeriksaan')
                ->orderBy('nomor_urut', 'asc')
                ->limit(5)
                ->pluck('nomor_antrian');

            return [
                'id' => $p->id,
                'name' => $p->nama_poli,
                'code' => $p->kode_poli,
                'current_number' => $doctor->nomor_antrian ?? '-',
                'current_name' => $doctor ? ($doctor->pasien->name ?? 'Pasien') : null,
                'current_update' => $doctor ? $doctor->dipanggil_at->format('H:i:s') : null,
                'current_status' => $doctor ? 'doctor' : 'idle',
                
                'admin_number' => $admin->nomor_antrian ?? '-',
                'admin_update' => $admin ? $admin->dipanggil_at->format('H:i:s') : null,
                'admin_status' => $admin ? 'admin' : 'idle',

                'remaining' => $remaining,
                'next_queues' => $nextQueues,
                'doctor' => $doctor && $doctor->jadwalDokter ? $doctor->jadwalDokter->dokter->name : 'Mohon Menunggu',
            ];
        });

        return response()->json([
            'polis' => $polis,
            'time' => now()->format('H:i'),
            'date' => now()->translatedFormat('l, d F Y')
        ]);
    }
}
