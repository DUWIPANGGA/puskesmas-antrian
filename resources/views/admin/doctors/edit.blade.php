@extends('layouts.admin')

@section('title', 'Edit Dokter')
@section('page-title', 'Edit Data Dokter')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.doctors.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Dokter
            </a>
        </div>
        
        <h2 class="text-xl font-black text-gray-900 mb-1">Edit Dokter</h2>
        <p class="text-xs font-medium text-gray-500 mb-6">Update data dokter</p>
        
        <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Avatar Upload -->
            <div class="mb-6 text-center">
                <div class="relative inline-block">
                    <div id="avatarPreview" class="w-32 h-32 rounded-full bg-pink-100 flex items-center justify-center mx-auto mb-3 overflow-hidden">
                        @if($doctor->avatar)
                            <img src="{{ Storage::url($doctor->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-5xl text-[#d81b60]">person</span>
                        @endif
                    </div>
                    <label for="avatar" class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-pink-600">camera_alt</span>
                    </label>
                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                </div>
                <p class="text-xs text-gray-400 mt-2">Upload foto profil (opsional, maks 2MB)</p>
                @if($doctor->avatar)
                    <p class="text-xs text-gray-400 mt-1">Foto saat ini: {{ basename($doctor->avatar) }}</p>
                @endif
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $doctor->name) }}" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email', $doctor->email) }}" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Password
                </label>
                <input type="password" 
                       name="password" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition"
                       placeholder="Kosongkan jika tidak ingin mengubah password">
                <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    No. Telepon
                </label>
                <input type="text" 
                       name="phone" 
                       value="{{ old('phone', $doctor->phone) }}" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    NIK
                </label>
                <input type="text" 
                       name="nik" 
                       value="{{ old('nik', $doctor->nik) }}" 
                       maxlength="16" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('nik') border-red-500 @enderror">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Alamat
                </label>
                <textarea name="address" 
                          rows="3" 
                          class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition"
                          placeholder="Jl. Contoh No. 123">{{ old('address', $doctor->address) }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Tanggal Lahir
                </label>
                <input type="date" 
                       name="birth_date" 
                       value="{{ old('birth_date', $doctor->birth_date ? $doctor->birth_date->format('Y-m-d') : '') }}" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-[#f06292] text-white py-2 rounded-full text-sm font-bold hover:bg-[#d81b60] transition shadow-sm">
                    Update Dokter
                </button>
                <a href="{{ route('admin.doctors.index') }}" 
                   class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-full text-sm font-bold text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Jadwal Praktik Section --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50 mt-6">
        <h2 class="text-xl font-black text-gray-900 mb-1">Jadwal Praktik</h2>
        <p class="text-xs font-medium text-gray-500 mb-6">Atur hari, jam kerja, dan koneksi Poli</p>

        {{-- Existing Schedules --}}
        <div class="mb-6 overflow-hidden border border-gray-100 rounded-xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 font-bold text-gray-600">Poli</th>
                        <th class="px-4 py-3 font-bold text-gray-600">Hari</th>
                        <th class="px-4 py-3 font-bold text-gray-600">Jam</th>
                        <th class="px-4 py-3 font-bold text-gray-600 w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($doctor->jadwalDokter as $jadwal)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-medium">{{ $jadwal->poli->nama_poli }}</td>
                        <td class="px-4 py-3">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-pink-50 text-pink-600 px-2 py-1 rounded text-xs font-bold">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.doctors.delete-schedule', [$doctor->id, $jadwal->id]) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 p-1">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs italic">Belum ada jadwal praktik terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Add New Schedule Form --}}
        <form action="{{ route('admin.doctors.add-schedule', $doctor->id) }}" method="POST" class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
            @csrf
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3">Tambah Jadwal Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Poli <span class="text-red-500">*</span></label>
                    <select name="poli_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-pink-300">
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $poli)
                        <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Hari <span class="text-red-500">*</span></label>
                    <select name="hari" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-pink-300">
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-pink-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-pink-300">
                </div>
            </div>
            <button type="submit" class="w-full bg-[#1b4353] text-white py-2 rounded-full text-xs font-bold hover:bg-[#122c36] transition shadow-sm">
                + Tambah Jadwal Praktik
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection