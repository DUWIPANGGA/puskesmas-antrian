@extends('layouts.dokter')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-[1000px] mx-auto flex flex-col gap-8">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-[#1b4353]">Pengaturan Profil & Profesional</h2>
            <p class="text-sm font-bold text-gray-500 mt-1">Kelola informasi pribadi dan profil publik Anda</p>
        </div>
        @if(session('success'))
            <div class="bg-teal-50 text-teal-600 px-4 py-2 rounded-xl text-xs font-black flex items-center gap-2 border border-teal-100 animate-bounce">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <form action="{{ route('dokter.settings.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        {{-- Left Column: Basic Info --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 flex flex-col items-center">
                <div class="w-32 h-32 rounded-full ring-4 ring-pink-50 p-1 mb-4 relative group overflow-hidden">
                    <img id="avatarPreview" src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=d81b60&color=fff&size=256' }}" class="w-full h-full rounded-full object-cover">
                    <label for="photoInput" class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                         <span class="material-symbols-outlined text-white">photo_camera</span>
                         <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                <h3 class="text-lg font-black text-gray-900">{{ $user->name }}</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $dokter->poli->nama_poli ?? 'POLI' }}</p>
                
                <div class="w-full border-t border-dashed border-gray-100 mt-6 pt-6 flex flex-col gap-3">
                    <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest">
                        <span class="text-gray-400">STATUS AKUN</span>
                        <span class="text-teal-600">AKTIF</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest">
                        <span class="text-gray-400">BERGABUNG</span>
                        <span class="text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#ac4471] to-[#802a50] rounded-[2.5rem] p-7 text-white shadow-lg shadow-[#ac4471]/20">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                    <h4 class="font-black text-sm">Professional Badge</h4>
                </div>
                <p class="text-[11px] leading-relaxed opacity-80 font-medium">Informasi profesional Anda ditampilkan pada tiket pasien dan laporan kunjungan sistem jagapura.</p>
            </div>
        </div>

        {{-- Right Column: Forms --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            {{-- Personal Information --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-pink-50 text-[#d81b60] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </div>
                    <h4 class="text-base font-black text-gray-900">Informasi Pribadi</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                        @error('name') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                        @error('email') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Professional Info --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">medical_information</span>
                    </div>
                    <h4 class="text-base font-black text-gray-900">Kredensial Profesional</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nip" value="{{ old('nip', $dokter->nip) }}" placeholder="Contoh: 1980...XXXX" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Bidang Keahlian</label>
                        <input type="text" name="keahlian" value="{{ old('keahlian', $dokter->keahlian) }}" placeholder="Contoh: Spesialis Anak" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Alumni Universitas</label>
                        <input type="text" name="alumni" value="{{ old('alumni', $dokter->alumni) }}" placeholder="Contoh: Universitas Indonesia" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Lama Pengalaman (Tahun)</label>
                        <input type="number" name="pengalaman_tahun" value="{{ old('pengalaman_tahun', $dokter->pengalaman_tahun) }}" class="bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-black tracking-widest text-[#00897b] uppercase ml-1">Biodata Profesional</label>
                    <textarea name="bio" rows="4" placeholder="Tuliskan deskripsi singkat mengenai latar belakang medis and visi pelayanan Anda..." class="bg-gray-50 border border-gray-100 rounded-[1.5rem] px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/10 focus:border-[#d81b60] transition resize-none">{{ old('bio', $dokter->bio) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-[#d81b60] hover:bg-[#c2185b] text-white px-10 py-4 rounded-full font-black text-sm shadow-lg shadow-pink-500/30 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan Profil
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
