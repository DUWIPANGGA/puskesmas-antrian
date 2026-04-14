<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClinicQuotaController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $polis = \App\Models\Poli::all();

        $quotas = $polis->map(function($poli) use ($date) {
            $daily = \App\Models\KuotaHarianPoli::where('poli_id', $poli->id)
                ->whereDate('tanggal', $date)
                ->first();
            
            return [
                'id' => $poli->id,
                'nama' => $poli->nama_poli,
                'kode' => $poli->kode_poli,
                'icon' => $poli->icon,
                'default_quota' => $poli->kuota_harian_default ?: 50,
                'current_quota' => $daily ? $daily->kuota : ($poli->kuota_harian_default ?: 50),
                'is_custom' => $daily !== null,
                'has_antrian' => \App\Models\Antrian::where('poli_id', $poli->id)->whereDate('tanggal', $date)->exists()
            ];
        });

        return view('admin.clinic_quotas.index', compact('quotas', 'date'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'kuota' => 'required|integer|min:1',
        ]);

        \App\Models\KuotaHarianPoli::updateOrCreate(
            [
                'poli_id' => $id,
                'tanggal' => $request->date,
            ],
            [
                'kuota' => $request->kuota,
            ]
        );

        return redirect()->back()->with('success', 'Kuota harian berhasil diperbarui.');
    }

    public function reset(Request $request, $id)
    {
        $request->validate(['date' => 'required|date']);

        \App\Models\KuotaHarianPoli::where('poli_id', $id)
            ->whereDate('tanggal', $request->date)
            ->delete();

        return redirect()->back()->with('success', 'Kuota dikembalikan ke default.');
    }
}
