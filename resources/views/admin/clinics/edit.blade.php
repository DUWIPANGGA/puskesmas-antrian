@extends('layouts.admin')

@section('title', 'Edit Klinik')
@section('page-title', 'Edit Data Klinik')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.clinics.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Klinik
            </a>
        </div>
        
        <h2 class="text-xl font-black text-gray-900 mb-1">Edit Klinik</h2>
        <p class="text-xs font-medium text-gray-500 mb-6">Update data klinik/poli</p>
        
        <form action="{{ route('admin.clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Kode Klinik <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="kode_poli" 
                       value="{{ old('kode_poli', $clinic->kode_poli) }}" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition uppercase @error('kode_poli') border-red-500 @enderror"
                       placeholder="Contoh: POLI-UMUM, POLI-GIGI">
                <p class="text-xs text-gray-400 mt-1">Kode unik untuk identifikasi klinik (maksimal 10 karakter)</p>
                @error('kode_poli')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Nama Klinik <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_poli" 
                       value="{{ old('nama_poli', $clinic->nama_poli) }}" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('nama_poli') border-red-500 @enderror"
                       placeholder="Contoh: Poli Umum, Poli Gigi, Poli KIA">
                @error('nama_poli')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Deskripsi Klinik
                </label>
                <textarea name="deskripsi" 
                          rows="4" 
                          class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition"
                          placeholder="Deskripsi lengkap tentang klinik ini...">{{ old('deskripsi', $clinic->deskripsi) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Informasi tambahan tentang klinik (opsional)</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Kuota Harian Default <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" 
                           name="kuota_harian_default" 
                           value="{{ old('kuota_harian_default', $clinic->kuota_harian_default) }}" 
                           required 
                           min="1" 
                           max="500"
                           class="w-32 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('kuota_harian_default') border-red-500 @enderror">
                    <span class="text-sm text-gray-500">pasien per hari</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    Jumlah maksimal pasien yang bisa dilayani per hari. 
                    <br>Perubahan akan langsung mempengaruhi kuota hari ini.
                </p>
                @error('kuota_harian_default')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @include('admin.clinics._icon_picker', ['selected' => old('icon', $clinic->icon ?? 'fa-solid fa-hospital')])

            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $clinic->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                    <span class="text-sm font-bold text-gray-700">Aktifkan Klinik</span>
                </label>
                <p class="text-xs text-gray-400 mt-1 ml-7">Jika nonaktif, klinik tidak akan muncul dalam pendaftaran antrian</p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="flex gap-2 items-start">
                    <span class="material-symbols-outlined text-yellow-500 text-[18px]">warning</span>
                    <div class="text-xs text-yellow-700">
                        <p class="font-bold mb-1">Perhatian:</p>
                        <p>- Perubahan kuota default akan mempengaruhi kuota hari ini</p>
                        <p>- Anda bisa mengatur kuota khusus untuk tanggal tertentu di <a href="{{ route('admin.clinics.quota', $clinic->id) }}" class="underline font-bold">halaman manajemen kuota</a></p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-[#f06292] text-white py-2 rounded-full text-sm font-bold hover:bg-[#d81b60] transition shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">update</span>
                    Update Klinik
                </button>
                <a href="{{ route('admin.clinics.index') }}" 
                   class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-full text-sm font-bold text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection