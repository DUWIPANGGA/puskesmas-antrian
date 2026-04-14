<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientCheckinController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $today = now()->toDateString();
        
        $query = \App\Models\Antrian::with(['pasien', 'poli'])
            ->whereDate('tanggal', $today)
            ->when($search, function($q) use ($search) {
                $q->whereHas('pasien', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhere('nomor_antrian', 'like', "%{$search}%");
            });

        $pending = (clone $query)->where('status', 'menunggu')->orderBy('created_at', 'asc')->get();
        $checkedIn = (clone $query)->whereIn('status', ['check_in', 'dipanggil', 'selesai'])->orderBy('check_in_at', 'desc')->get();

        return view('admin.patient_checkins.index', compact('pending', 'checkedIn', 'search'));
    }

    public function update(Request $request, $id)
    {
        $antrian = \App\Models\Antrian::findOrFail($id);
        
        if ($antrian->status === 'menunggu') {
            $antrian->update([
                'status' => 'check_in',
                'check_in_at' => now()
            ]);
            return redirect()->back()->with('success', "Pasien {$antrian->pasien->name} berhasil check-in.");
        }
        
        return redirect()->back()->with('error', 'Pasien sudah check-in sebelumnya.');
    }
}
