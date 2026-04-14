<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Poli;
use Illuminate\Http\Request;

class QueueControlController extends Controller
{
    public function index()
    {
        $polis = Poli::where('is_active', true)->get()->map(function ($poli) {
            $antrianHariIni = Antrian::hariIni()->where('poli_id', $poli->id);

            $poli->total_queue     = (clone $antrianHariIni)->count();
            $poli->remaining_queue = (clone $antrianHariIni)->where('status', 'check_in')->count();

            // Yang sedang dipanggil
            $poli->current_queue = (clone $antrianHariIni)->where('status', 'dipanggil')->first();

            // Antrian berikutnya (check_in atau menunggu, urut terkecil)
            $poli->next_queue = (clone $antrianHariIni)
                ->where('status', 'check_in')
                ->orderBy('nomor_urut', 'asc')
                ->first();

            // Semua antrian hari ini (untuk modal pilih) — mapped ke plain array
            $poli->all_queues = (clone $antrianHariIni)
                ->where('status', '!=', 'menunggu')
                ->with('pasien')
                ->orderBy('nomor_urut', 'asc')
                ->get()
                ->map(function ($a) {
                    return [
                        'id'            => $a->id,
                        'nomor_antrian' => $a->nomor_antrian,
                        'nomor_urut'    => $a->nomor_urut,
                        'pasien'        => $a->pasien ? $a->pasien->name : 'Unknown',
                        'status'        => $a->status,
                    ];
                })
                ->values();

            return $poli;
        });

        return view('admin.queue_control.index', compact('polis'));
    }

    public function handleAction(Request $request, $poli_id)
    {
        $poli   = Poli::findOrFail($poli_id);
        $action = $request->input('action');

        $currentQueue = Antrian::hariIni()
            ->where('poli_id', $poli->id)
            ->where('status', 'dipanggil')
            ->first();

        // ── NEXT ── Selesaikan administrasi current, panggil antrian check_in urut terkecil berikutnya
        if ($action === 'call_next') {
            if ($currentQueue) {
                $currentQueue->siapPemeriksaan();
            }

            $nextQueue = Antrian::hariIni()
                ->where('poli_id', $poli->id)
                ->where('status', 'check_in')
                ->orderBy('nomor_urut', 'asc')
                ->first();

            if ($nextQueue) {
                $nextQueue->panggilAdmin();
                return back()->with([
                    'success' => "Memanggil antrian {$nextQueue->nomor_antrian} – Administrasi {$poli->nama_poli}",
                    'speak_nomor' => $nextQueue->nomor_antrian,
                    'speak_poli' => "Administrasi " . $poli->nama_poli
                ]);
            }

            return back()->with('error', 'Tidak ada antrian yang siap dipanggil selanjutnya.');
        }

        // ── BACK ── Kembalikan current ke check_in, panggil antrian sebelumnya
        if ($action === 'go_back') {
            if ($currentQueue) {
                // Kembalikan yang saat ini ke status check_in (mundur)
                $currentQueue->update(['status' => 'check_in', 'dipanggil_at' => null]);
            }

            // Ambil antrian terakhir yang diproses
            $urut = $currentQueue ? $currentQueue->nomor_urut : PHP_INT_MAX;

            $prevQueue = Antrian::hariIni()
                ->where('poli_id', $poli->id)
                ->where('nomor_urut', '<', $urut)
                ->whereIn('status', ['check_in', 'siap_pemeriksaan']) 
                ->orderBy('nomor_urut', 'desc')
                ->first();

            if ($prevQueue) {
                if ($prevQueue->status === 'siap_pemeriksaan') {
                    $prevQueue->update(['status' => 'check_in']);
                }
                $prevQueue->panggilAdmin();
                return back()->with([
                    'success' => "Mundur — memanggil antrian {$prevQueue->nomor_antrian}",
                    'speak_nomor' => $prevQueue->nomor_antrian,
                    'speak_poli' => "Administrasi " . $poli->nama_poli
                ]);
            }

            return back()->with('info', 'Tidak ada antrian sebelumnya untuk dipanggil.');
        }

        // ── COMPLETE ── Selesaikan administrasi, kirim ke antrian dokter
        if ($action === 'complete') {
            if ($currentQueue) {
                $currentQueue->siapPemeriksaan();
                return back()->with('success', "Antrian {$currentQueue->nomor_antrian} selesai administrasi & masuk antrian dokter.");
            }
            return back()->with('error', 'Tidak ada antrian yang sedang dipanggil.');
        }

        // ── RECALL ── Pangil ulang antrian yang sedang aktif
        if ($action === 'recall') {
            if ($currentQueue) {
                $currentQueue->update(['dipanggil_at' => now()]);
                return back()->with([
                    'success' => "Memanggil ulang antrian {$currentQueue->nomor_antrian}",
                    'speak_nomor' => $currentQueue->nomor_antrian,
                    'speak_poli' => "Administrasi " . $poli->nama_poli
                ]);
            }
            return back()->with('error', 'Tidak ada antrian yang sedang dipanggil.');
        }

        // ── CALL SPECIFIC ── Panggil nomor antrian tertentu (pilihan manual)
        if ($action === 'call_specific') {
            $antrianId = $request->input('antrian_id');
            $target    = Antrian::hariIni()
                ->where('poli_id', $poli->id)
                ->findOrFail($antrianId);

            if ($target->status === 'menunggu') {
                return back()->with('error', 'Pasien belum melakukan check-in dan tidak bisa dipanggil.');
            }

            // Selesaikan yang sedang dipanggil dulu
            if ($currentQueue && $currentQueue->id !== $target->id) {
                $currentQueue->update(['status' => 'check_in', 'dipanggil_at' => null]);
            }

            // Pastikan target bisa dipanggil
            if ($target->status === 'siap_pemeriksaan') {
                $target->update(['status' => 'check_in']);
            }

            $target->panggilAdmin();
            $pasienName = $target->pasien->name ?? '';
            return back()->with([
                'success' => "Memanggil antrian {$target->nomor_antrian} – {$pasienName}",
                'speak_nomor' => $target->nomor_antrian,
                'speak_poli' => "Administrasi " . $poli->nama_poli
            ]);
        }

        return back()->with('error', 'Aksi tidak diketahui.');
    }
}
