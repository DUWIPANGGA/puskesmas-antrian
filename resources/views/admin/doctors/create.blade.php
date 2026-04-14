@extends('layouts.admin')

@section('title', 'Tambah Dokter')
@section('page-title', 'Tambah Dokter Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.doctors.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Dokter
            </a>
        </div>
        
        <h2 class="text-xl font-black text-gray-900 mb-1">Form Tambah Dokter</h2>
        <p class="text-xs font-medium text-gray-500 mb-6">Isi data dokter dengan lengkap</p>
        
        <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Avatar Upload -->
            <div class="mb-6 text-center">
                <div class="relative inline-block">
                    <div id="avatarPreview" class="w-32 h-32 rounded-full bg-pink-100 flex items-center justify-center mx-auto mb-3 overflow-hidden">
                        <span class="material-symbols-outlined text-5xl text-[#d81b60]">person</span>
                    </div>
                    <label for="avatar" class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-pink-600">camera_alt</span>
                    </label>
                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                </div>
                <p class="text-xs text-gray-400 mt-2">Upload foto profil (opsional, maks 2MB)</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
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
                       value="{{ old('email') }}" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       required 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('password') border-red-500 @enderror"
                       placeholder="Minimal 8 karakter">
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
                       value="{{ old('phone') }}" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition"
                       placeholder="08123456789">
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    NIK
                </label>
                <input type="text" 
                       name="nik" 
                       value="{{ old('nik') }}" 
                       maxlength="16" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition @error('nik') border-red-500 @enderror"
                       placeholder="16 Digit NIK">
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
                          placeholder="Jl. Contoh No. 123">{{ old('address') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Tanggal Lahir
                </label>
                <input type="date" 
                       name="birth_date" 
                       value="{{ old('birth_date') }}" 
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-[#f06292] text-white py-2 rounded-full text-sm font-bold hover:bg-[#d81b60] transition shadow-sm">
                    Simpan Dokter
                </button>
                <a href="{{ route('admin.doctors.index') }}" 
                   class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-full text-sm font-bold text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
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