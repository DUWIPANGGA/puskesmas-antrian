<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'dokter')
            ->with(['dokter.poli', 'jadwalDokter'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nik' => 'nullable|string|max:16|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
            'nik' => $request->nik ?? '',
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function show($id)
    {
        $doctor = User::where('role', 'dokter')->findOrFail($id);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit($id)
    {
        $doctor = User::with('jadwalDokter.poli')->where('role', 'dokter')->findOrFail($id);
        $polis = \App\Models\Poli::where('is_active', true)->get();
        return view('admin.doctors.edit', compact('doctor', 'polis'));
    }

    public function update(Request $request, $id)
    {
        $doctor = User::where('role', 'dokter')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($doctor->id)],
            'nik' => ['nullable', 'string', 'max:16', Rule::unique('users')->ignore($doctor->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik ?? '',
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $doctor->update($data);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil diupdate.');
    }

    public function destroy($id)
    {
        $doctor = User::where('role', 'dokter')->findOrFail($id);
        
        // Cek apakah dokter memiliki relasi data
        if ($doctor->jadwalDokter()->exists() || 
            $doctor->antrianSebagaiDokter()->exists() || 
            $doctor->resepSebagaiDokter()->exists()) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Dokter tidak dapat dihapus karena memiliki data terkait (jadwal, antrian, atau resep).');
        }
        
        $doctor->delete();
        
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }

    public function addSchedule(Request $request, $id)
    {
        $doctor = User::where('role', 'dokter')->findOrFail($id);
        $request->validate([
            'poli_id' => 'required|exists:polis,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'nullable|integer'
        ]);

        \App\Models\JadwalDokter::create([
            'dokter_id' => $doctor->id,
            'poli_id' => $request->poli_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota' => $request->kuota,
            'is_active' => true,
        ]);

        return back()->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }

    public function deleteSchedule($id, $scheduleId)
    {
        $schedule = \App\Models\JadwalDokter::where('dokter_id', $id)->findOrFail($scheduleId);
        $schedule->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}