@extends('layouts.pasien')
@section('title', 'Pengaturan Profil')

@section('content')
@php
    $golonganOptions = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
    $age = $user->birth_date ? $user->birth_date->diffInYears(now()) : null;
@endphp

<div class="max-w-3xl mx-auto flex flex-col gap-6">

    {{-- Page Title --}}
    <div>
        <h2 class="text-3xl font-black text-gray-900">Informasi Profil</h2>
        <p class="text-sm text-gray-400 font-medium mt-1">Kelola data diri dan informasi kesehatan Anda</p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-100 text-green-700 px-5 py-3.5 rounded-2xl text-sm font-bold">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 text-red-600 px-5 py-3.5 rounded-2xl text-sm font-bold">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Profile Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col md:flex-row items-center md:items-stretch gap-0">

            {{-- Left: Photo + Name + Info --}}
            <div class="flex-1 p-8 flex flex-col justify-center gap-5">
                {{-- Photo + Name --}}
                <div class="flex items-center gap-5">
                    {{-- Avatar with upload --}}
                    <form action="{{ route('pasien.settings.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <label class="relative cursor-pointer group">
                            <div class="w-24 h-24 rounded-full ring-4 ring-pink-200 overflow-hidden bg-pink-100 flex items-center justify-center">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover" id="photoPreview">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f8bbd9&color=d81b60&size=96" class="w-full h-full object-cover" id="photoPreview">
                                @endif
                            </div>
                            <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[20px]">photo_camera</span>
                            </div>
                            <input type="file" name="photo" accept="image/*" class="hidden" id="photoInput" onchange="document.getElementById('photoForm').submit()">
                        </label>
                    </form>

                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ $user->name }}</h3>
                        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 bg-pink-100 text-[#d81b60] rounded-full text-[10px] font-black">
                            <span class="material-symbols-outlined text-[12px]">verified</span> Verified Patient
                        </span>
                    </div>
                </div>

                {{-- Email + NIK --}}
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3 text-sm text-gray-600 font-medium">
                        <span class="material-symbols-outlined text-gray-300 text-[18px]">mail</span>
                        {{ $user->email }}
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600 font-medium">
                        <span class="material-symbols-outlined text-gray-300 text-[18px]">fingerprint</span>
                        NIK: {{ $user->nik ?? 'Belum diisi' }}
                    </div>
                </div>
            </div>

            {{-- Right: Blood type --}}
            @if($user->golongan_darah)
            <div class="w-full md:w-52 bg-teal-400 flex flex-col items-center justify-center py-8 md:py-0 gap-2">
                <span class="material-symbols-outlined text-white text-[28px]">water_drop</span>
                <p class="text-[10px] font-black text-teal-100 uppercase tracking-widest">GOLONGAN DARAH</p>
                <p class="text-5xl font-black text-white leading-none">{{ $user->golongan_darah }}</p>
            </div>
            @else
            <div class="w-full md:w-52 bg-gray-100 flex flex-col items-center justify-center py-8 md:py-0 gap-2">
                <span class="material-symbols-outlined text-gray-300 text-[28px]">water_drop</span>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">GOLONGAN DARAH</p>
                <p class="text-lg font-black text-gray-300">Belum diisi</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Birth Date + Address --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-teal-50 rounded-[2rem] p-7 border border-teal-100">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-teal-600 text-[20px]">cake</span>
                <p class="text-sm font-black text-gray-700">Tanggal Lahir</p>
            </div>
            <p class="text-2xl font-black text-gray-900 mb-2">
                {{ $user->birth_date ? $user->birth_date->translatedFormat('d F Y') : 'Belum diisi' }}
            </p>
            @if($age !== null)
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usia saat ini</p>
            <p class="text-base font-black text-gray-700">{{ $age }} Tahun</p>
            @endif
        </div>

        <div class="bg-white rounded-[2rem] p-7 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[#d81b60] text-[20px]">location_on</span>
                <p class="text-sm font-black text-gray-700">Alamat Lengkap</p>
            </div>
            <p class="text-sm font-medium text-gray-600 leading-relaxed">
                {{ $user->address ?? 'Alamat belum diisi' }}
            </p>
            {{-- Decorative --}}
            <span class="material-symbols-outlined absolute bottom-4 right-4 text-gray-100 text-[60px]">map</span>
        </div>
    </div>

    {{-- Edit Profile Form --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 pt-7 pb-2">
            <h3 class="text-base font-black text-gray-900">Edit Profil</h3>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Perbarui informasi pribadi dan kesehatan Anda</p>
        </div>

        <form action="{{ route('pasien.settings.update') }}" method="POST" class="p-8 pt-5 flex flex-col gap-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nama --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition" placeholder="08xxxxxxxxxx">
                </div>

                {{-- NIK --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">NIK (16 digit)</label>
                    <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="30"
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition" placeholder="3275xxxxxxxxxxxxxxx">
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition">
                </div>

                {{-- Golongan Darah --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Golongan Darah</label>
                    <select name="golongan_darah" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition">
                        <option value="">-- Pilih --</option>
                        @foreach($golonganOptions as $g)
                        <option value="{{ $g }}" {{ old('golongan_darah', $user->golongan_darah) === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Gender --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Jenis Kelamin</label>
                    <select name="gender" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition resize-none" placeholder="Masukkan alamat lengkap...">{{ old('address', $user->address) }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-between pt-2">
                <p class="text-[10px] font-medium text-gray-400">
                    Data terakhir diperbarui pada:
                    @if($user->updated_at)
                        <span class="font-bold text-gray-500">{{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    @else
                        <span class="font-bold text-gray-500">-</span>
                    @endif
                </p>
                <button type="submit"
                        class="flex items-center gap-2 bg-[#880e4f] text-white px-8 py-3.5 rounded-full font-black text-sm shadow-lg shadow-pink-200 hover:bg-[#6a0036] transition">
                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
