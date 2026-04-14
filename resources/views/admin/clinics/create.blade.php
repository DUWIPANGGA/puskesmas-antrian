@extends('layouts.admin')

@section('title', 'Tambah Klinik')
@section('page-title', 'Tambah Klinik Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.clinics.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Klinik
            </a>
        </div>
        
        <h2 class="text-xl font-black text-gray-900 mb-1">Form Tambah Klinik</h2>
        <p class="text-xs font-medium text-gray-500 mb-6">Isi data klinik/poli dengan lengkap</p>
        
        <form action="{{ route('admin.clinics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Kode Klinik <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="kode_poli" 
                       value="{{ old('kode_poli') }}" 
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
                       value="{{ old('nama_poli') }}" 
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
                          placeholder="Deskripsi lengkap tentang klinik ini...">{{ old('deskripsi') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Informasi tambahan tentang klinik (opsional)</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Kuota Harian Default <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" 
                           name="kuota_harian_default" 
                           value="{{ old('kuota_harian_default', 20) }}" 
                           required 
                           min="1" 
                           max="500"
                           class="w-32 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('kuota_harian_default') border-red-500 @enderror">
                    <span class="text-sm text-gray-500">pasien per hari</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Jumlah maksimal pasien yang bisa dilayani per hari</p>
                @error('kuota_harian_default')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @include('admin.clinics._icon_picker', ['selected' => old('icon', 'fa-solid fa-hospital')])

            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                    <span class="text-sm font-bold text-gray-700">Aktifkan Klinik</span>
                </label>
                <p class="text-xs text-gray-400 mt-1 ml-7">Jika nonaktif, klinik tidak akan muncul dalam pendaftaran antrian</p>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex gap-2 items-start">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">info</span>
                    <div class="text-xs text-blue-700">
                        <p class="font-bold mb-1">Informasi Kuota Harian:</p>
                        <p>- Kuota akan otomatis dibuat untuk hari ini saat klinik ditambahkan</p>
                        <p>- Anda bisa mengatur kuota khusus untuk tanggal tertentu di halaman manajemen kuota</p>
                        <p>- Reset kuota hanya bisa dilakukan manual oleh admin</p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-[#f06292] text-white py-2 rounded-full text-sm font-bold hover:bg-[#d81b60] transition shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    Simpan Klinik
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