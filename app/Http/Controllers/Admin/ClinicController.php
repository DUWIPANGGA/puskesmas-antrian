<?php
// app/Http/Controllers/Admin/ClinicController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\KuotaHarianPoli;
use App\Models\User;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Poli::orderBy('created_at', 'desc')->get();
        $today = Carbon::today();
        
        // Load kuota hari ini untuk setiap poli
        foreach ($clinics as $clinic) {
            $kuotaHariIni = $clinic->kuotaHarian()->whereDate('tanggal', $today)->first();
            
            if (!$kuotaHariIni) {
                // Auto create jika belum ada
                $kuotaHariIni = KuotaHarianPoli::create([
                    'poli_id' => $clinic->id,
                    'tanggal' => $today,
                    'kuota' => $clinic->kuota_harian_default ?? 20,
                    'terpakai' => 0
                ]);
            }
            
            $clinic->kuota_today = $kuotaHariIni->kuota;
            $clinic->terpakai_today = $kuotaHariIni->terpakai;
            $clinic->sisa_today = $kuotaHariIni->sisaKuota();
            $clinic->kuota_id = $kuotaHariIni->id;
        }
        
        $totalPatientsToday = \App\Models\Antrian::hariIni()->count();
        $totalQuotaToday = KuotaHarianPoli::whereDate('tanggal', $today)->sum('kuota');
        $totalTerpakaiToday = KuotaHarianPoli::whereDate('tanggal', $today)->sum('terpakai');
        $quotaEfficiency = $totalQuotaToday > 0 ? round(($totalTerpakaiToday / $totalQuotaToday) * 100) : 0;
        $activeClinicsCount = $clinics->where('is_active', true)->count();
        
        return view('admin.clinics.index', compact('clinics', 'today', 'totalPatientsToday', 'quotaEfficiency', 'activeClinicsCount'));
    }

    public function create()
    {
        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:255|unique:polis',
            'kode_poli' => 'required|string|max:10|unique:polis',
            'deskripsi' => 'nullable|string',
            'kuota_harian_default' => 'required|integer|min:1|max:500',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $clinic = Poli::create([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => strtoupper($request->kode_poli),
            'deskripsi' => $request->deskripsi,
            'kuota_harian_default' => $request->kuota_harian_default,
            'icon' => $request->icon ?? 'fa-solid fa-hospital',
            'is_active' => $request->has('is_active'),
        ]);

        // Generate kuota untuk hari ini
        KuotaHarianPoli::generateKuota($clinic, Carbon::today());

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Klinik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $clinic = Poli::findOrFail($id);
        return view('admin.clinics.edit', compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = Poli::findOrFail($id);

        $request->validate([
            'nama_poli' => ['required', 'string', 'max:255', Rule::unique('polis')->ignore($clinic->id)],
            'kode_poli' => ['required', 'string', 'max:10', Rule::unique('polis')->ignore($clinic->id)],
            'deskripsi' => 'nullable|string',
            'kuota_harian_default' => 'required|integer|min:1|max:500',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $clinic->update([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => strtoupper($request->kode_poli),
            'deskripsi' => $request->deskripsi,
            'kuota_harian_default' => $request->kuota_harian_default,
            'icon' => $request->icon ?? 'fa-solid fa-hospital',
            'is_active' => $request->has('is_active'),
        ]);

        // Update kuota hari ini jika perlu
        $kuotaHariIni = $clinic->kuotaHarian()->whereDate('tanggal', Carbon::today())->first();
        if ($kuotaHariIni) {
            $kuotaHariIni->update(['kuota' => $request->kuota_harian_default]);
        }

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Klinik berhasil diupdate.');
    }

    public function destroy($id)
    {
        $clinic = Poli::findOrFail($id);
        
        // Cek apakah poli memiliki relasi data
        if ($clinic->jadwalDokter()->exists() || 
            $clinic->antrian()->exists() || 
            $clinic->laporanKunjungan()->exists()) {
            return redirect()->route('admin.clinics.index')
                ->with('error', 'Klinik tidak dapat dihapus karena memiliki data terkait (jadwal, antrian, atau laporan).');
        }
        
        // Hapus semua kuota harian
        $clinic->kuotaHarian()->delete();
        
        $clinic->delete();
        
        return redirect()->route('admin.clinics.index')
            ->with('success', 'Klinik berhasil dihapus.');
    }
    
    // RESET KUOTA - Fitur baru untuk admin reset manual
    public function resetQuota(Request $request, $id)
    {
        $clinic = Poli::findOrFail($id);
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : Carbon::today();
        
        // Reset kuota untuk poli ini
        KuotaHarianPoli::resetKuotaForPoli($clinic->id, $tanggal);
        
        $message = "Kuota untuk klinik {$clinic->nama_poli} tanggal " . $tanggal->format('d/m/Y') . " berhasil direset.";
        
        return redirect()->route('admin.clinics.index')
            ->with('success', $message);
    }
    
    // Reset semua kuota untuk hari ini
    public function resetAllQuota(Request $request)
    {
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : Carbon::today();
        
        $resetCount = KuotaHarianPoli::resetAllKuota($tanggal);
        
        return redirect()->route('admin.clinics.index')
            ->with('success', "Berhasil mereset kuota untuk {$resetCount} klinik pada tanggal " . $tanggal->format('d/m/Y'));
    }
    
    // Manajemen kuota harian
    public function manageQuota($id)
    {
        $clinic = Poli::findOrFail($id);
        
        // Get kuota untuk 7 hari ke depan
        $dates = [];
        for ($i = 0; $i <= 7; $i++) {
            $date = Carbon::now()->addDays($i);
            $kuota = KuotaHarianPoli::firstOrCreate(
                [
                    'poli_id' => $clinic->id,
                    'tanggal' => $date->format('Y-m-d')
                ],
                [
                    'kuota' => $clinic->kuota_harian_default,
                    'terpakai' => 0
                ]
            );
            
            $dates[] = [
                'date' => $date,
                'kuota' => $kuota,
                'sisa' => $kuota->sisaKuota(),
                'is_past' => $date->lt(Carbon::today())
            ];
        }
        
        return view('admin.clinics.quota', compact('clinic', 'dates'));
    }
    
    public function updateQuota(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuota' => 'required|integer|min:1|max:500',
        ]);
        
        $tanggal = Carbon::parse($request->tanggal);
        
        // Cek apakah tanggal sudah lewat
        if ($tanggal->lt(Carbon::today()) && $tanggal->format('Y-m-d') != Carbon::today()->format('Y-m-d')) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah kuota untuk tanggal yang sudah lewat.');
        }
        
        $kuota = KuotaHarianPoli::where('poli_id', $id)
            ->whereDate('tanggal', $request->tanggal)
            ->firstOrFail();
        
        // Cek jika kuota baru kurang dari yang sudah terpakai
        if ($request->kuota < $kuota->terpakai) {
            return redirect()->back()->with('error', 'Kuota baru tidak boleh kurang dari jumlah yang sudah terpakai (' . $kuota->terpakai . ')');
        }
        
        $kuota->update(['kuota' => $request->kuota]);
        
        return redirect()->route('admin.clinics.quota', $id)
            ->with('success', 'Kuota berhasil diupdate untuk tanggal ' . $tanggal->format('d/m/Y'));
    }
    
    // Reset untuk tanggal tertentu
    public function resetQuotaByDate(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);
        
        $clinic = Poli::findOrFail($id);
        $tanggal = Carbon::parse($request->tanggal);
        
        KuotaHarianPoli::resetKuotaForPoli($clinic->id, $tanggal);
        
        return redirect()->route('admin.clinics.quota', $id)
            ->with('success', 'Kuota berhasil direset untuk tanggal ' . $tanggal->format('d/m/Y'));
    }

    public function manageDoctors($id)
    {
        $clinic = Poli::findOrFail($id);
        
        // Dokter yang sudah ada di poli ini
        $assignedDoctors = Dokter::with(['user', 'jadwal' => function($q) use ($id) {
            $q->where('poli_id', $id);
        }])->where('poli_id', $id)->get();
        
        // Semua dokter untuk dropdown (bisa pindah poli)
        $allDoctors = Dokter::with('user')->get();
        
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        return view('admin.clinics.manage_doctors', compact('clinic', 'assignedDoctors', 'allDoctors', 'days'));
    }

    public function assignDoctor(Request $request, $id)
    {
        try {
            $request->validate([
                'dokter_id' => 'required|exists:dokters,id',
                'hari' => 'required|array',
                'hari.*' => 'string|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'kuota' => 'required|integer|min:1',
            ]);

            $dokter = Dokter::findOrFail($request->dokter_id);
            $clinic = Poli::findOrFail($id);
            
            // Update poli_id dokter
            $dokter->update(['poli_id' => $id]);

            // Tambah/Update jadwal
            foreach ($request->hari as $hari) {
                JadwalDokter::updateOrCreate(
                    [
                        'dokter_id' => $dokter->user_id,
                        'hari' => $hari,
                        'poli_id' => $id
                    ],
                    [
                        'jam_mulai' => $request->jam_mulai,
                        'jam_selesai' => $request->jam_selesai,
                        'kuota' => $request->kuota,
                        'is_active' => true
                    ]
                );
            }

            return redirect()->route('admin.clinics.doctors', $id)
                ->with('success', "Dr. {$dokter->user->name} berhasil ditugaskan ke {$clinic->nama_poli}.");
        } catch (\Exception $e) {
            \Log::error('Assign Doctor Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan dokter: ' . $e->getMessage()])->withInput();
        }
    }

    public function removeDoctor(Request $request, $id, $doctorId)
    {
        $dokter = Dokter::findOrFail($doctorId);
        $clinic = Poli::findOrFail($id);
        
        // Lepas dari poli
        $dokter->update(['poli_id' => null]);
        
        // Hapus jadwal di poli ini
        JadwalDokter::where('dokter_id', $dokter->user_id)
            ->where('poli_id', $id)
            ->delete();

        return redirect()->route('admin.clinics.doctors', $id)
            ->with('success', "Dr. {$dokter->user->name} berhasil dilepas dari {$clinic->nama_poli}.");
    }
}