<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Poli;
use App\Models\KuotaHarianPoli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PasienDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Antrian pasien aktif (hari ini atau kedepannya)
        $antrianAktif = Antrian::with(['jadwalDokter.dokter', 'jadwalDokter.poli', 'poli'])
            ->where('pasien_id', $user->id)
            ->whereDate('tanggal', '>=', today())
            ->whereNotIn('status', ['selesai', 'batal'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('nomor_urut', 'asc')
            ->first();

        // Antrian yang sedang dilayani di poli dan tanggal yang sama
        $nowServing = null;
        $peopleAhead = null;
        if ($antrianAktif) {
            $nowServing = Antrian::where('poli_id', $antrianAktif->poli_id)
                ->whereDate('tanggal', $antrianAktif->tanggal)
                ->where('status', 'dipanggil')
                ->orderByDesc('nomor_urut')
                ->value('nomor_antrian');

            $peopleAhead = Antrian::where('poli_id', $antrianAktif->poli_id)
                ->whereDate('tanggal', $antrianAktif->tanggal)
                ->where('nomor_urut', '<', $antrianAktif->nomor_urut)
                ->whereNotIn('status', ['selesai', 'batal'])
                ->count();
        }

        // Upcoming appointments (antrian besok dan seterusnya)
        $upcomingAppointments = Antrian::with(['jadwalDokter.dokter', 'jadwalDokter.poli', 'poli'])
            ->where('pasien_id', $user->id)
            ->whereDate('tanggal', '>=', today())
            ->whereIn('status', ['menunggu', 'check_in', 'dipanggil'])
            ->orderBy('tanggal')
            ->orderBy('nomor_urut')
            ->take(3)
            ->get();

        // Fetch dynamic health tips from DB
        $healthTips = \App\Models\HealthTip::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($healthTips->isEmpty()) {
            $healthTips = [
                (object)['category' => 'Hydration Guide',   'icon' => 'water_drop',  'tip' => 'Drink 8 glasses of water daily for better focus.'],
                (object)['category' => 'Mental Wellness',   'icon' => 'self_improvement', 'tip' => 'Try 5 minutes of mindful breathing every morning.'],
                (object)['category' => 'Physical Activity', 'icon' => 'directions_run', 'tip' => 'A 30-minute walk can boost your immune system.'],
            ];
        }

        // Live status for all clinics to display on dashboard
        $allPolisLive = Poli::where('is_active', true)->get()->map(function($p) {
            $p->current_number = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->where('status', 'dipanggil')
                ->orderByDesc('updated_at')
                ->value('nomor_antrian') ?? '-';
            return $p;
        });

        $polis = $allPolisLive; // Reuse for the modal selection too

        // Latest Vital Signs from History
        $lastVisit = \App\Models\LaporanKunjungan::where('pasien_id', $user->id)
            ->where('status_pelayanan', 'selesai')
            ->orderByDesc('tanggal')
            ->first();

        return view('dashboard.pasien', compact(
            'antrianAktif',
            'nowServing',
            'peopleAhead',
            'upcomingAppointments',
            'healthTips',
            'polis',
            'allPolisLive',
            'lastVisit'
        ));
    }

    public function ambilTiket(Request $request)
    {
        $request->validate([
            'poli_id' => 'required|exists:polis,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'keluhan' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();
        $poli = Poli::findOrFail($request->poli_id);
        $tanggal = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d');

        // Cek apakah pasien sudah punya antrian di poli ini pada tanggal tersebut
        $hasTicket = Antrian::where('pasien_id', $user->id)
            ->where('poli_id', $poli->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', '!=', 'batal')
            ->exists();

        if ($hasTicket) {
            $dateText = $tanggal == today()->format('Y-m-d') ? 'hari ini' : 'pada tanggal ' . \Carbon\Carbon::parse($tanggal)->format('d M Y');
            return back()->with('popup_error', 'Anda sudah memiliki antrian aktif di ' . $poli->nama_poli . ' ' . $dateText . '.');
        }

        // Cek Kuota
        $kuota = KuotaHarianPoli::firstOrCreate(
            ['poli_id' => $poli->id, 'tanggal' => $tanggal],
            ['kuota' => $poli->kuota_harian_default ?? 20, 'terpakai' => 0]
        );

        if ($kuota->sisaKuota() <= 0) {
            $dateText = $tanggal == today()->format('Y-m-d') ? 'hari ini' : 'untuk tanggal ' . \Carbon\Carbon::parse($tanggal)->format('d M Y');
            return back()->with('popup_error', 'Maaf kuota poli ' . $poli->nama_poli . ' ' . $dateText . ' sudah habis, silahkan pilih tanggal lain dan datang kembali besok.');
        }

        // Generate Prefix based on Poli name or code
        $prefix = strtoupper(substr($poli->nama_poli, 0, 1));
        if ($poli->kode_poli) {
            $parts = explode('-', $poli->kode_poli);
            // Ambil karakter pertama dari bagian kedua kode_poli (misal: POL-KANDUNGAN -> K)
            $prefix = isset($parts[1]) ? substr($parts[1], 0, 1) : substr($poli->kode_poli, 0, 1);
            $prefix = strtoupper($prefix);
        }

        // Generate Nomor Antrian & Urut
        // Cari nomor urut terakhir UNTUK PREFIX YANG SAMA di tanggal yang sama
        // Hal ini dilakukan supaya tidak bentrok antar poli yang punya karakter depan sama (misal Kandungan & Kulit sama-sama 'K')
        $maxUrut = Antrian::whereDate('tanggal', $tanggal)
            ->where('nomor_antrian', 'like', $prefix . '-%')
            ->max('nomor_urut');
            
        $nomorUrut = $maxUrut ? $maxUrut + 1 : 1;
        $nomorAntrian = $prefix . '-' . $nomorUrut;

        Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'pasien_id' => $user->id,
            'poli_id' => $poli->id,
            'keluhan' => $request->keluhan,
            'tanggal' => $tanggal,
            'nomor_urut' => $nomorUrut,
            'status' => 'menunggu',
        ]);

        $kuota->incrementTerpakai();

        return back()->with('popup_success', 'Tiket antrian berhasil diambil! Nomor Antrian anda: ' . $nomorAntrian);
    }

    public function liveQueue()
    {
        $polis = Poli::with(['jadwalDokter.dokter'])->where('is_active', true)->get();

        foreach ($polis as $poli) {
            // Yang sedang dipanggil
            $poli->current_queue = Antrian::with('jadwalDokter.dokter')
                ->where('poli_id', $poli->id)
                ->whereDate('tanggal', today())
                ->where('status', 'dipanggil')
                ->orderByDesc('updated_at')
                ->first();

            // Sisa antrian yang sedang menunggu / sudah check in
            $poli->remaining_queue = Antrian::where('poli_id', $poli->id)
                ->whereDate('tanggal', today())
                ->whereIn('status', ['menunggu', 'check_in'])
                ->count();
        }

        return view('dashboard.live_queue', compact('polis'));
    }

    public function checkinPage(Request $request)
    {
        $user = Auth::user();
        
        $allTickets = Antrian::with(['poli', 'jadwalDokter.dokter'])
            ->where('pasien_id', $user->id)
            ->whereIn('status', ['menunggu', 'check_in'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $activeTicketId = $request->ticket_id;
        if ($activeTicketId) {
            $activeTicket = $allTickets->firstWhere('id', $activeTicketId);
        } else {
            // Priority: A waiting/checked-in ticket for today. Else just the first available ticket.
            $activeTicket = $allTickets->filter(function($t) {
                return \Carbon\Carbon::parse($t->tanggal)->isToday() && in_array($t->status, ['menunggu', 'check_in']);
            })->first() ?? $allTickets->first();
        }

        return view('dashboard.checkin', compact('allTickets', 'activeTicket'));
    }

    public function checkIn(Request $request)
    {
        $request->validate(['antrian_id' => 'required|exists:antrians,id']);

        $user = Auth::user();

        $antrian = Antrian::with('poli')
            ->where('pasien_id', $user->id)
            ->where('id', $request->antrian_id)
            ->whereDate('tanggal', today())
            ->where('status', 'menunggu')
            ->first();

        if (!$antrian) {
            return back()->with('popup_error', 'Tiket tidak ditemukan atau tidak bisa dicheck-in (mungkin sudah dicheck-in sebelumnya atau bukan untuk hari ini).');
        }

        $antrian->update([
            'status' => 'check_in',
            'check_in_at' => now(),
        ]);

        return back()->with('popup_success', 'Berhasil Check-in! Silahkan menuju ruang tunggu poli ' . $antrian->poli->nama_poli . '. Nomor antrian anda: ' . $antrian->nomor_antrian);
    }

    public function bookAppointment()
    {
        $polis = Poli::with(['jadwalDokter' => function($q) {
            $q->where('hari', now()->locale('id')->dayName)->orWhere('hari', now()->englishDayOfWeek);
        }, 'jadwalDokter.dokter'])->where('is_active', true)->get();

        foreach ($polis as $poli) {
            $poli->current_queue_count = Antrian::where('poli_id', $poli->id)
                ->whereDate('tanggal', today())
                ->whereIn('status', ['menunggu', 'check_in'])
                ->count();
        }

        return view('dashboard.book_appointment', compact('polis'));
    }

    public function medicalHistory()
    {
        $user = Auth::user();
        $histories = \App\Models\LaporanKunjungan::with(['dokter', 'poli', 'antrian.resep.detailResep'])
            ->where('pasien_id', $user->id)
            ->where('status_pelayanan', 'selesai')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('dashboard.medical_history', compact('histories'));
    }

    public function medicalHistoryDetail($id)
    {
        $user = Auth::user();
        $record = \App\Models\LaporanKunjungan::with(['dokter', 'poli', 'antrian.resep.detailResep'])
            ->where('pasien_id', $user->id)
            ->findOrFail($id);

        return view('dashboard.medical_history_detail', compact('record'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.settings', compact('user'));
    }

    public function settingsUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'nik'           => 'nullable|string|max:30|unique:users,nik,' . $user->id,
            'address'       => 'nullable|string|max:500',
            'birth_date'    => 'nullable|date',
            'golongan_darah'=> 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'gender'        => 'nullable|in:Laki-laki,Perempuan',
        ]);

        $user->update($request->only([
            'name', 'phone', 'nik', 'address', 'birth_date', 'golongan_darah', 'gender'
        ]));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo
        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('avatars', 'public');
        $user->update(['photo' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function getLiveStatus(Request $request)
    {
        $user = Auth::user();

        // 1. Get current calling for all polis today
        $allPolis = Poli::where('is_active', true)->get()->mapWithKeys(function($p) {
            $current = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->where('status', 'dipanggil')
                ->orderByDesc('updated_at')
                ->value('nomor_antrian') ?? '-';
            
            $remaining = Antrian::where('poli_id', $p->id)
                ->whereDate('tanggal', today())
                ->whereIn('status', ['menunggu', 'check_in'])
                ->count();

            return [$p->id => [
                'current_number' => $current,
                'remaining' => $remaining
            ]];
        });

        // 2. Get user's personal queue status for today
        // Prioritize tickets that are currently 'dipanggil'
        $personalStatus = null;
        $antrianAktif = Antrian::where('pasien_id', $user->id)
            ->whereDate('tanggal', today())
            ->whereNotIn('status', ['selesai', 'batal'])
            ->orderByRaw("CASE 
                WHEN status = 'dipanggil_dokter' THEN 0 
                WHEN status = 'dipanggil' THEN 1 
                ELSE 2 END")
            ->orderBy('id', 'asc')
            ->first();

        if ($antrianAktif) {
            $peopleAhead = Antrian::where('poli_id', $antrianAktif->poli_id)
                ->whereDate('tanggal', $antrianAktif->tanggal)
                ->where('nomor_urut', '<', $antrianAktif->nomor_urut)
                ->whereNotIn('status', ['selesai', 'batal', 'siap_pemeriksaan', 'dipanggil_dokter'])
                ->count();

            $personalStatus = [
                'poli_id' => $antrianAktif->poli_id,
                'nomor_antrian' => $antrianAktif->nomor_antrian,
                'people_ahead' => $peopleAhead,
                'is_called' => in_array($antrianAktif->status, ['dipanggil', 'dipanggil_dokter'])
            ];
        }

        return response()->json([
            'all_polis' => $allPolis,
            'personal' => $personalStatus
        ]);
    }
}
