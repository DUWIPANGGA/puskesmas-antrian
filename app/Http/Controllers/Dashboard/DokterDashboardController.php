<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\LaporanKunjungan;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;

class DokterDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get doctor's schedule for today
        $today = Carbon::today();
        $hariIni = $today->locale('id')->dayName;
        
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        $jadwalHariIni = JadwalDokter::where('dokter_id', $user->id)
            ->where('hari', $hariIni)
            ->where('is_active', true)
            ->first();

        if (!$poli_id) {
            return view('dokter.dashboard', [
                'totalPatientsToday' => 0,
                'finishedPatients' => 0,
                'avgConsultation' => 0,
                'nowServing' => null,
                'todaysPatients' => collect(),
                'upcomingToday' => collect(),
                'poli' => null,
                'jadwal' => null,
                'queueCount' => 0  // ← Added this
            ]);
        }

        $poli = \App\Models\Poli::find($poli_id);

        // Statistics - Only patients ready for or finished with the doctor
        $doctorStatuses = ['siap_pemeriksaan', 'dipanggil_dokter', 'selesai'];

        $totalPatientsToday = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->whereIn('status', $doctorStatuses)
            ->count();
            
        $finishedPatients = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->count();
            
        $avgConsultation = $this->calculateAvgConsultation($poli_id, $today);

        // Now Serving (Doctor specific call)
        $nowServing = Antrian::with('pasien')
            ->where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'dipanggil_dokter')
            ->first();

        // Today's Patients (Only those who passed Admin)
        $todaysPatients = Antrian::with('pasien')
            ->where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->whereIn('status', $doctorStatuses)
            ->orderByRaw("CASE 
                WHEN status = 'dipanggil_dokter' THEN 1
                WHEN status = 'siap_pemeriksaan' THEN 2
                WHEN status = 'selesai' THEN 3
                ELSE 4 END")
            ->orderBy('nomor_urut', 'asc')
            ->get();

        // Upcoming (waiting for doctor - siap_pemeriksaan)
        $upcomingToday = Antrian::with('pasien')
            ->where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'siap_pemeriksaan')
            ->orderBy('nomor_urut', 'asc')
            ->take(2)
            ->get();

        // Queue count for doctor (siap_pemeriksaan)
        $queueCount = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'siap_pemeriksaan')
            ->count();

        return view('dokter.dashboard', compact(
            'totalPatientsToday', 
            'finishedPatients', 
            'avgConsultation',
            'nowServing', 
            'todaysPatients', 
            'upcomingToday',
            'poli',
            'jadwalHariIni',
            'queueCount'
        ));
    }

    private function calculateAvgConsultation($poli_id, $date)
    {
        $completedAntrian = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $date)
            ->whereNotNull('dipanggil_at')
            ->whereNotNull('selesai_at')
            ->get();
            
        if ($completedAntrian->count() === 0) {
            return 0;
        }
        
        $totalMinutes = 0;
        foreach ($completedAntrian as $a) {
            $totalMinutes += $a->dipanggil_at->diffInMinutes($a->selesai_at);
        }
        
        return round($totalMinutes / $completedAntrian->count());
    }

    public function myPatients(Request $request)
    {
        $user = Auth::user();
        
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        $jadwalHariIni = JadwalDokter::where('dokter_id', $user->id)
            ->where('hari', Carbon::today()->locale('id')->dayName)
            ->where('is_active', true)
            ->first();

        $selectedDateString = $request->query('date', Carbon::today()->format('Y-m-d'));
        try {
            $selectedDate = Carbon::parse($selectedDateString);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        $totalPatientsMonth = 0;
        $currentPatient = null;
        $riwayatMedis = collect();
        $lastTinggi = '';
        $lastBerat = '';
        $calendarDays = [];
        
        if ($poli_id) {
            $totalPatientsMonth = Antrian::where('poli_id', $poli_id)
                ->whereMonth('tanggal', today()->month)
                ->whereYear('tanggal', today()->year)
                ->where('status', 'selesai')
                ->count();
                
            $currentPatient = Antrian::with(['pasien', 'laporanKunjungan'])
                ->where('poli_id', $poli_id)
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'dipanggil_dokter')
                ->first();
                
            if ($currentPatient) {
                // Get all previous medical histories for this patient, excluding current one if saved
                $riwayatMedis = LaporanKunjungan::with('antrian.resep')
                    ->where('pasien_id', $currentPatient->pasien_id)
                    ->whereDate('tanggal', '<', Carbon::today())
                    ->orderBy('tanggal', 'desc')
                    ->get();
                    
                if ($riwayatMedis->isNotEmpty()) {
                    $lastLaporan = $riwayatMedis->first();
                    // Attempt to extract TB and BB from catatan
                    if ($lastLaporan->catatan && preg_match('/TB: (\d+)/', $lastLaporan->catatan, $matches)) {
                        $lastTinggi = $matches[1];
                    }
                    if ($lastLaporan->catatan && preg_match('/BB: (\d+)/', $lastLaporan->catatan, $matches)) {
                        $lastBerat = $matches[1];
                    }
                }
            }
        }
            
        $mostPrescribed = "Amoxicillin";
        $avgDailyVisits = 0;
        $visits = collect();

        if ($poli_id) {
            // Generate calendar days
            for ($i = 0; $i < 7; $i++) {
                $loopDate = Carbon::today()->subDays($i); // Or anchor to selectedDate
                $patientsCount = Antrian::where('poli_id', $poli_id)
                    ->whereDate('tanggal', $loopDate)
                    ->where('status', 'selesai')
                    ->count();
                
                $calendarDays[] = [
                    'date' => $loopDate,
                    'count' => $patientsCount,
                    'is_selected' => $loopDate->isSameDay($selectedDate)
                ];
            }

            $visits = Antrian::with(['pasien', 'resep.detailResep', 'laporanKunjungan'])
                ->where('poli_id', $poli_id)
                ->where('status', 'selesai')
                ->whereDate('tanggal', $selectedDate)
                ->orderByDesc('selesai_at')
                ->paginate(10)
                ->withQueryString();
                
            $avgDailyVisits = round($totalPatientsMonth / max(1, today()->day), 1);
            
            // Calculate most prescribed algorithmically
            $allReseps = \App\Models\Resep::whereHas('antrian', function($q) use ($poli_id) {
                    $q->where('poli_id', $poli_id)
                      ->whereMonth('tanggal', today()->month)
                      ->whereYear('tanggal', today()->year);
                })->get();
            
            $drugCounts = [];
            foreach ($allReseps as $r) {
                if ($r->obat) {
                    $lines = explode("\n", $r->obat);
                    foreach ($lines as $line) {
                        $parts = explode(" - ", trim($line));
                        if(isset($parts[0]) && strlen(trim($parts[0])) > 0) {
                            $drugName = trim($parts[0]);
                            $drugCounts[$drugName] = ($drugCounts[$drugName] ?? 0) + 1;
                        }
                    }
                }
            }
            
            if (!empty($drugCounts)) {
                arsort($drugCounts);
                $mostPrescribed = array_key_first($drugCounts);
            } else {
                $mostPrescribed = "-";
            }
        }

        return view('dokter.my_patients', compact(
            'totalPatientsMonth', 'mostPrescribed', 'avgDailyVisits', 'visits', 'poli_id', 'currentPatient', 'riwayatMedis', 'lastTinggi', 'lastBerat', 'calendarDays', 'selectedDate'
        ));
    }

    // Call Next Patient
    public function callNext(Request $request)
    {
        $user = Auth::user();
        
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        $jadwalHariIni = JadwalDokter::where('dokter_id', $user->id)
            ->where('hari', Carbon::today()->locale('id')->dayName)
            ->where('is_active', true)
            ->first();
            
        if (!$poli_id) {
            return response()->json(['error' => 'Anda tidak terdaftar di poli manapun'], 403);
        }
        $today = Carbon::today();
        
        // Get current serving patient at DOCTOR
        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'dipanggil_dokter')
            ->first();
        
        // If there's a current patient, they MUST be finished first before calling next
        if ($currentServing) {
            return response()->json(['error' => 'Selesaikan atau lewati pasien saat ini terlebih dahulu'], 400);
        }
        
        // Get next patient (siap_pemeriksaan)
        $nextPatient = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', $today)
            ->where('status', 'siap_pemeriksaan')
            ->orderBy('nomor_urut', 'asc')
            ->first();
        
        if (!$nextPatient) {
            return response()->json(['error' => 'Tidak ada antrian yang siap pemeriksaan'], 404);
        }
        
        // Call the patient for Doctor
        $nextPatient->panggilDokter();
        $nextPatient->update(['jadwal_dokter_id' => $jadwalHariIni ? $jadwalHariIni->id : null]);
        
        $pasienName = $nextPatient->pasien->name ?? 'Pasien';

        return response()->json([
            'success' => true,
            'message' => "Memanggil antrian {$nextPatient->nomor_antrian} - {$pasienName}",
            'patient' => [
                'id' => $nextPatient->id,
                'nomor_antrian' => $nextPatient->nomor_antrian,
                'nama_pasien' => $pasienName,
                'status' => $nextPatient->status
            ],
            // Data for TTS in Display (if the dashboard triggers it? usually display polls, but this is for doctor dashboard feedback)
            'speak_nomor' => $nextPatient->nomor_antrian,
            'speak_nama' => $pasienName,
            'speak_poli' => $poli->nama_poli,
            
            'queue_count' => Antrian::where('poli_id', $poli_id)
                ->whereDate('tanggal', $today)
                ->where('status', 'siap_pemeriksaan')
                ->count()
        ]);
    }
    
    // Finish current patient (with form submission support)
    public function finishCurrent(Request $request)
    {
        $user = Auth::user();
        
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        $jadwalHariIni = JadwalDokter::where('dokter_id', $user->id)
            ->where('hari', Carbon::today()->locale('id')->dayName)
            ->where('is_active', true)
            ->first();
            
        if (!$poli_id) {
            $msg = 'Anda tidak terdaftar di poli manapun';
            return $request->wantsJson() ? response()->json(['error' => $msg], 403) : back()->with('error', $msg);
        }
        
        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();
        
        if (!$currentServing) {
            $msg = 'Tidak ada pasien yang sedang dilayani';
            return $request->wantsJson() ? response()->json(['error' => $msg], 404) : back()->with('error', $msg);
        }
        
        // Save Medical Record (LaporanKunjungan) if data is provided
        if ($request->has('diagnosa') || $request->has('catatan') || $request->has('tinggi_badan') || $request->has('obat')) {
            $catatan = $request->catatan;
            
            // Append TB/BB/gula darah to catatan (non-dedicated columns)
            $vitalsInfo = [];
            if ($request->tinggi_badan) $vitalsInfo[] = "TB: " . $request->tinggi_badan . " cm";
            if ($request->berat_badan)  $vitalsInfo[] = "BB: " . $request->berat_badan . " kg";
            if ($request->kadar_gula)   $vitalsInfo[] = "Gula Darah: " . $request->kadar_gula;
            if (count($vitalsInfo) > 0) {
                $catatan = "Tanda Vital: " . implode(", ", $vitalsInfo) . "\n\n" . $catatan;
            }

            LaporanKunjungan::updateOrCreate(
                ['antrian_id' => $currentServing->id],
                [
                    'tanggal'          => $currentServing->tanggal,
                    'poli_id'          => $poli_id,
                    'pasien_id'        => $currentServing->pasien_id,
                    'dokter_id'        => $user->id,
                    'waktu_check_in'   => $currentServing->check_in_at ?? $currentServing->created_at,
                    'waktu_dipanggil'  => $currentServing->dipanggil_at,
                    'waktu_selesai'    => now(),
                    'lama_pelayanan'   => $currentServing->dipanggil_at ? (int) abs(now()->diffInMinutes($currentServing->dipanggil_at)) : 0,
                    'status_pelayanan' => 'selesai',
                    'diagnosa'         => $request->diagnosa ?? 'Selesai',
                    'catatan'          => $catatan,
                    'detak_jantung'    => $request->detak_jantung ?: null,
                    'suhu_tubuh'       => $request->suhu_tubuh ?: null,
                    'tekanan_darah'    => $request->tekanan_darah ?: null,
                    'saturasi_oksigen' => $request->saturasi_oksigen ?: null,
                ]
            );

            // Save Prescription if drugs are given
            if ($request->has('obat') && count($request->obat) > 0) {
                // Formatting prescription as text for simplicity
                $obatList = [];
                for ($i = 0; $i < count($request->obat); $i++) {
                    if (!empty($request->obat[$i])) {
                        $obatList[] = $request->obat[$i] . " - " . ($request->dosis[$i] ?? '') . " - " . ($request->instruksi[$i] ?? '');
                    }
                }
                
                if (count($obatList) > 0) {
                    $resepData = [
                        'status' => 'pending',
                        'dokter_id' => $user->id,
                        'pasien_id' => $currentServing->pasien_id,
                        'poli_id' => $poli_id,
                        'diagnosa' => $request->diagnosa ?? 'Tanpa diagnosa',
                        'catatan' => $catatan,
                        'obat' => implode("\n", $obatList)
                    ];

                    $resep = \App\Models\Resep::where('antrian_id', $currentServing->id)->first();
                    
                    if (!$resep) {
                        $resepData['antrian_id'] = $currentServing->id;
                        $resepData['nomor_resep'] = \App\Models\Resep::generateNomor();
                        $resep = \App\Models\Resep::create($resepData);
                    } else {
                        $resep->update($resepData);
                    }

                    // Sync detailed medicines for pharmacist list
                    $resep->detailResep()->delete();
                    for ($i = 0; $i < count($request->obat); $i++) {
                        if (!empty($request->obat[$i])) {
                            \App\Models\DetailResep::create([
                                'resep_id' => $resep->id,
                                'nama_obat' => $request->obat[$i],
                                'dosis' => $request->dosis[$i] ?? '',
                                'aturan_pakai' => $request->instruksi[$i] ?? '',
                                'jumlah' => $request->jumlah[$i] ?? 1,
                            ]);
                        }
                    }
                }
            }
        } else {
            // Generate basic laporan if form was submitted empty or from dashboard ajax
             LaporanKunjungan::updateOrCreate(
                ['antrian_id' => $currentServing->id],
                [
                    'tanggal' => $currentServing->tanggal,
                    'poli_id' => $poli_id,
                    'pasien_id' => $currentServing->pasien_id,
                    'dokter_id' => $user->id,
                    'waktu_check_in' => $currentServing->check_in_at ?? $currentServing->created_at,
                    'waktu_dipanggil' => $currentServing->dipanggil_at,
                    'waktu_selesai' => now(),
                    'status_pelayanan' => 'selesai',
                    'diagnosa' => 'Selesai tanpa catatan detail'
                ]
            );
        }
        
        $currentServing->update([
            'status' => 'selesai',
            'selesai_at' => now()
        ]);
        
        $msg = "Pasien dengan antrian {$currentServing->nomor_antrian} telah selesai dilayani";
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg
            ]);
        }
        
        return back()->with('success', $msg);
    }
    // Skip current patient
    public function skipPatient(Request $request)
    {
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) return response()->json(['error' => 'Poli tidak ditemukan'], 403);

        // Find current and return to siap_pemeriksaan (undo doctor call)
        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();

        if ($currentServing) {
            $currentServing->update([
                'status' => 'siap_pemeriksaan',
                'dipanggil_at' => null
            ]);
        }

        // To skip, we just finish this call and the NEXT callNext() will handle picking a new one
        return response()->json([
            'success' => true,
            'message' => "Antrian dilewati / dikembalikan ke daftar tunggu"
        ]);
    }

    // Recall current patient
    public function recall(Request $request)
    {
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) return response()->json(['error' => 'Poli tidak ditemukan'], 403);

        $nowServing = Antrian::with('pasien')
            ->where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();

        if (!$nowServing) {
            return response()->json(['error' => 'Tidak ada pasien yang sedang dipanggil'], 404);
        }

        $nowServing->update(['dipanggil_at' => now()]);

        return response()->json([
            'success' => true,
            'speak_nomor' => $nowServing->nomor_antrian,
            'speak_nama' => $nowServing->pasien->name ?? 'Pasien'
        ]);
    }

    // Call Previous Patient (Mundur)
    public function callPrev(Request $request)
    {
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) return response()->json(['error' => 'Poli tidak ditemukan'], 403);

        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();

        $referenceUrut = $currentServing ? $currentServing->nomor_urut : 1000;

        // Return current to siap_pemeriksaan if any
        if ($currentServing) {
            $currentServing->update(['status' => 'siap_pemeriksaan', 'dipanggil_at' => null]);
        }

        // Find the patient with smaller nomor_urut that is either 'selesai' (to recall them) or 'siap_pemeriksaan'
        $prevPatient = Antrian::with('pasien')
            ->where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('nomor_urut', '<', $referenceUrut)
            ->whereIn('status', ['siap_pemeriksaan', 'selesai'])
            ->orderBy('nomor_urut', 'desc')
            ->first();

        if (!$prevPatient) {
            return response()->json(['error' => 'Tidak ada antrian sebelumnya'], 404);
        }

        $prevPatient->update(['status' => 'dipanggil_dokter', 'dipanggil_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => "Mundur ke antrian {$prevPatient->nomor_antrian}",
            'speak_nomor' => $prevPatient->nomor_antrian,
            'speak_nama' => $prevPatient->pasien->name ?? 'Pasien'
        ]);
    }

    public function callSpecific(Request $request)
    {
        $user = Auth::user();
        $antrianId = $request->id; // Using 'id' from JS request
        
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        $jadwalHariIni = JadwalDokter::where('dokter_id', $user->id)
            ->where('hari', Carbon::today()->locale('id')->dayName)
            ->where('is_active', true)
            ->first();
            
        if (!$poli_id) {
            return response()->json(['error' => 'Anda tidak terdaftar di poli manapun'], 403);
        }
        
        $patient = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->findOrFail($antrianId);
            
        if ($patient->status == 'dipanggil_dokter') {
            return response()->json(['error' => 'Pasien sudah sedang dilayani'], 400);
        }

        // Only allow calling if patient is ready for doctor (siap_pemeriksaan)
        if ($patient->status != 'siap_pemeriksaan') {
             // Forcing call from other status? Usually not allowed, but lets check
             return response()->json(['error' => 'Pasien ini belum selesai urusan administrasi.'], 400);
        }

        // Handle current patient if any (return to siap_pemeriksaan or they must finish)
        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();

        if ($currentServing) {
            return response()->json(['error' => 'Selesaikan atau lewati pasien saat ini terlebih dahulu'], 400);
        }

        // Call the patient
        $patient->panggilDokter();
        $patient->update(['jadwal_dokter_id' => $jadwalHariIni ? $jadwalHariIni->id : null]);

        return response()->json([
            'success' => true,
            'message' => "Memanggil antrian {$patient->nomor_antrian}",
            'patient' => [
                'id' => $patient->id,
                'nomor_antrian' => $patient->nomor_antrian,
                'status' => $patient->status
            ],
            'speak_nomor' => $patient->nomor_antrian,
            'speak_nama' => $patient->pasien->name ?? 'Pasien',
            'speak_poli' => $patient->poli->nama_poli
        ]);
    }

    public function exportReport(Request $request)
    {
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) {
            return redirect()->back()->with('error', 'Poli tidak ditemukan');
        }

        $dateStr = $request->query('date', Carbon::today()->format('Y-m-d'));
        try {
            $date = Carbon::parse($dateStr);
        } catch (\Exception $e) {
            $date = Carbon::today();
        }

        $visits = Antrian::with(['pasien', 'laporanKunjungan', 'resep'])
            ->where('poli_id', $poli_id)
            ->where('status', 'selesai')
            ->whereDate('tanggal', $date)
            ->orderBy('selesai_at')
            ->get();

        $fileName = 'Visit_Report_' . $date->format('Y-m-d') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Waktu Selesai', 'Nama Pasien', 'No. RM', 'Diagnosa', 'Tanda Vital/Catatan', 'Resep Obat');

        $callback = function() use($visits, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($visits as $index => $visit) {
                fputcsv($file, array(
                    $index + 1,
                    $visit->selesai_at ? $visit->selesai_at->format('H:i') : '-',
                    $visit->pasien->name ?? '-',
                    '#'.(90000 + $visit->pasien_id),
                    $visit->laporanKunjungan->diagnosa ?? '-',
                    $visit->laporanKunjungan->catatan ?? '-',
                    str_replace("\n", "; ", $visit->resep->obat ?? '-')
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) {
            return redirect()->back()->with('error', 'Poli tidak ditemukan');
        }

        $search = $request->query('search');

        $history = Antrian::with(['pasien', 'laporanKunjungan.dokter', 'resep', 'poli'])
            ->where('poli_id', $poli_id)
            ->where('status', 'selesai')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    // Cari berdasarkan Nama Pasien atau ID
                    $q->whereHas('pasien', function($pq) use ($search) {
                        $pq->where('name', 'like', "%$search%")
                           ->orWhere('id', 'like', "%".str_replace('PID: #', '', $search)."%");
                    })
                    // Cari berdasarkan Nama Dokter yang memeriksa
                    ->orWhereHas('laporanKunjungan.dokter', function($dq) use ($search) {
                        $dq->where('name', 'like', "%$search%");
                    })
                    // Cari berdasarkan Diagnosis/Penyakit
                    ->orWhereHas('laporanKunjungan', function($lq) use ($search) {
                        $lq->where('diagnosa', 'like', "%$search%");
                    });
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('selesai_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dokter.history', compact('history', 'search', 'poli_id'));
    }

    public function settings()
    {
        $user = Auth::user();
        $dokter = \App\Models\Dokter::with('poli')->where('user_id', $user->id)->first();
        return view('dokter.settings', compact('user', 'dokter'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $dokter = \App\Models\Dokter::where('user_id', $user->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nip' => 'nullable|string|max:30',
            'keahlian' => 'nullable|string|max:255',
            'alumni' => 'nullable|string|max:255',
            'pengalaman_tahun' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $file = $request->file('photo');
            $path = $file->store('doctors/photos', 'public');
            $userData['photo'] = $path;
        }

        $user->update($userData);

        if ($dokter) {
            $dokter->update([
                'nip' => $request->nip,
                'keahlian' => $request->keahlian,
                'alumni' => $request->alumni,
                'pengalaman_tahun' => $request->pengalaman_tahun,
                'bio' => $request->bio,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function reExamine(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        $dokterData = \App\Models\Dokter::where('user_id', $user->id)->first();
        $poli_id = $dokterData ? $dokterData->poli_id : null;

        if (!$poli_id) return response()->json(['error' => 'Poli tidak ditemukan'], 403);

        $currentServing = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'dipanggil_dokter')
            ->first();

        if ($currentServing) {
            return response()->json(['error' => 'Selesaikan pasien aktif dulu cuy!'], 400);
        }

        $antrian = Antrian::where('poli_id', $poli_id)
            ->whereDate('tanggal', Carbon::today())
            ->findOrFail($id);

        if ($antrian->status !== 'selesai') {
            return response()->json(['error' => 'Pasien belum selesai.'], 400);
        }

        // Set back to dipanggil_dokter
        $antrian->update([
            'status' => 'dipanggil_dokter',
            'selesai_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesi pemeriksaan dibuka kembali',
            'redirect' => route('dokter.my-patients')
        ]);
    }

    public function downloadVisitPdf($id)
    {
        $visit = \App\Models\Antrian::with(['pasien', 'laporanKunjungan', 'resep', 'dokter.dokter'])->findOrFail($id);
        
        // Pastikan hanya dokter yang bersangkutan (atau admin) yang bisa akses
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $visit->dokter_id) {
             abort(403);
        }

        return view('dokter.visit_pdf', compact('visit'));
    }
}