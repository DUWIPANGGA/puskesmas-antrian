@extends('layouts.admin')

@section('title', 'Kelola Dokter - ' . $clinic->nama_poli)
@section('page-title', 'Kelola Dokter Poliklinik')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.clinics.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1 transition">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Kembali ke Daftar Klinik
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-[1.5rem] text-green-700 text-sm font-bold flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-[1.5rem] text-red-700 text-sm font-bold">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span>Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside ml-8 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    {{-- LEFT: CLINIC INFO --}}
    <div class="lg:col-span-4 flex flex-col gap-6">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-pink-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                <span class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">info</span>
                </span>
            </div>
            
            <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-2">
                Data Poliklinik
            </h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-2">Nama Poli</label>
                    <div class="bg-pink-50/50 rounded-2xl px-5 py-4 border border-pink-100 text-gray-900 font-bold">
                        {{ $clinic->nama_poli }}
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-2">Lokasi / Keterangan</label>
                    <div class="bg-gray-50 rounded-2xl px-5 py-4 border border-gray-100 text-gray-600 text-sm italic">
                        {{ $clinic->deskripsi ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-2">Status</label>
                        <div class="flex items-center gap-2 px-4 py-3 bg-green-50 text-green-700 rounded-full text-xs font-black border border-green-100">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            {{ $clinic->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-2">Verified</label>
                        <div class="flex items-center gap-2 px-4 py-3 bg-blue-50 text-blue-700 rounded-full text-xs font-black border border-blue-100">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            VERIFIED SLOT
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest">Kuota Harian</label>
                        <span class="bg-pink-500 text-white text-[10px] font-black px-3 py-1 rounded-full">{{ $clinic->kuota_harian_default }} Pasien</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-pink-500" style="width: 45%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-[9px] font-bold text-gray-400">MIN: 10</span>
                        <span class="text-[9px] font-bold text-gray-400">MAX: 500</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-3xl p-6 border border-teal-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-8xl text-teal-900">smart_toy</span>
            </div>
            <div class="flex gap-4 items-start relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-teal-500">auto_awesome</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-teal-900 mb-1">Smart Scheduling</h4>
                    <p class="text-[11px] text-teal-700 leading-relaxed">Sistem akan otomatis mengatur antrean berdasarkan kuota harian yang ditetapkan untuk setiap dokter.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: DOCTOR LIST & ASSIGNMENT --}}
    <div class="lg:col-span-8 flex flex-col gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-pink-50 flex flex-col overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">medical_information</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900">Daftar Dokter Bertugas</h2>
                        <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">Kelola tenaga medis di {{ $clinic->nama_poli }}</p>
                    </div>
                </div>
                <button type="button" onclick="openAssignModal()" 
                    class="bg-[#2d5a52] text-white px-6 py-3 rounded-2xl text-xs font-black shadow-lg hover:shadow-teal-900/20 hover:scale-[1.02] active:scale-95 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah ke Poli
                </button>
            </div>

            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-black text-pink-500 uppercase tracking-widest">{{ count($assignedDoctors) }} DOKTER TERPILIH</span>
                    </div>
                    <div class="bg-pink-50 text-pink-600 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tight">
                        Kapasitas Maksimal: 10
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($assignedDoctors as $dokter)
                        <div class="group relative bg-white border border-gray-100 rounded-[2rem] p-5 flex items-center gap-4 transition hover:border-teal-200 hover:shadow-xl hover:shadow-teal-500/5 hover:-translate-y-1">
                            {{-- Avatar --}}
                            <div class="relative">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-pink-50 border-2 border-white shadow-sm ring-1 ring-gray-100">
                                    <img src="{{ $dokter->user->photo ? asset('storage/' . $dokter->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($dokter->user->name).'&background=fdf2f8&color=db2777' }}" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -right-1 -bottom-1 w-6 h-6 rounded-full bg-teal-500 border-2 border-white flex items-center justify-center text-white shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                </div>
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-black text-gray-900 truncate uppercase mt-1">{{ $dokter->user->name }}</h4>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">{{ $dokter->keahlian ?? 'DOKTER UMUM' }}</p>
                                
                                <div class="flex flex-wrap gap-1 mt-3">
                                    @php $count = 0; @endphp
                                    @foreach($dokter->jadwal as $j)
                                        @if($j->poli_id == $clinic->id)
                                            <span class="px-2 py-0.5 bg-gray-50 text-gray-500 text-[8px] font-black rounded-md border border-gray-100 uppercase">{{ $j->hari }}</span>
                                            @php $count++; @endphp
                                        @endif
                                    @endforeach
                                    @if($count == 0)
                                        <span class="text-[8px] font-bold text-red-400 italic">Belum ada jadwal</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.clinics.remove-doctor', [$clinic->id, $dokter->id]) }}" method="POST" onsubmit="return confirm('Lepas dokter dari poli ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-20 bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-[2.5rem] flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center mb-4 text-gray-200 shadow-sm border border-gray-50">
                                <span class="material-symbols-outlined text-[40px]">person_add_disabled</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-400">Belum Ada Dokter Bertugas</h3>
                            <p class="text-xs font-bold text-gray-300 mt-1 uppercase tracking-widest">Klik "Tambah ke Poli" untuk menugaskan dokter</p>
                        </div>
                    @endforelse

                    {{-- Add More Card Placeholder --}}
                    @if(count($assignedDoctors) > 0)
                        <button onclick="openAssignModal()" class="group bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-[2.5rem] p-5 flex items-center justify-center gap-3 transition hover:border-pink-200 hover:bg-pink-50/30">
                            <div class="w-10 h-10 rounded-full bg-white border border-gray-100 text-gray-400 group-hover:text-pink-500 group-hover:border-pink-200 flex items-center justify-center transition shadow-sm">
                                <span class="material-symbols-outlined">add</span>
                            </div>
                            <span class="text-sm font-black text-gray-400 group-hover:text-pink-500 transition uppercase tracking-widest">Tambah Lagi</span>
                        </button>
                    @endif
                </div>

                <div class="mt-16 bg-gray-50/50 rounded-[2.5rem] p-8 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-pink-200 to-transparent"></div>
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-lg shadow-pink-500/10 text-pink-500 flex items-center justify-center mb-6 ring-4 ring-pink-50">
                        <span class="material-symbols-outlined">publish</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Konfirmasi Data</h3>
                    <p class="text-xs font-bold text-gray-500 max-w-xs leading-relaxed uppercase tracking-tight">Pastikan semua informasi poliklinik dan dokter sudah benar sebelum menyimpan.</p>
                    
                    <button type="button" onclick="window.location.reload()" class="mt-8 bg-[#b04b72] text-white px-12 py-4 rounded-full text-sm font-black shadow-xl shadow-pink-900/20 hover:bg-[#96375d] hover:scale-105 active:scale-95 transition">
                        Simpan Poliklinik
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ASSIGN MODAL --}}
<div id="assign-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeAssignModal()"></div>
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden transform transition-all">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-xl font-black text-gray-900">Tugaskan Dokter ke Poli</h3>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Atur jadwal praktek di {{ $clinic->nama_poli }}</p>
        </div>

        <form action="{{ route('admin.clinics.assign-doctor', $clinic->id) }}" method="POST" class="p-8">
            @csrf
            
            {{-- Select Doctor --}}
            <div class="mb-6">
                <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3">Pilih Dokter</label>
                <div class="grid grid-cols-1 gap-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($allDoctors as $d)
                        <label class="flex items-center gap-4 p-3 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-teal-200 transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50/30 @error('dokter_id') border-red-200 @enderror">
                            <input type="radio" name="dokter_id" value="{{ $d->id }}" {{ old('dokter_id') == $d->id ? 'checked' : '' }} required class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-50 border border-gray-200">
                                <img src="{{ $d->user->photo ? asset('storage/' . $d->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($d->user->name).'&background=fdf2f8&color=db2777' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-black text-gray-900">{{ $d->user->name }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">{{ $d->keahlian ?? 'Dokter Umum' }} @if($d->poli) <span class="text-orange-500">• (Poli {{ $d->poli->nama_poli }})</span> @endif</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Select Days --}}
            <div class="mb-6">
                <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3">Hari Praktek</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($days as $day)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="hari[]" value="{{ $day }}" {{ is_array(old('hari')) && in_array($day, old('hari')) ? 'checked' : '' }} class="hidden peer">
                            <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase text-gray-400 border-2 border-gray-100 cursor-pointer transition peer-checked:bg-pink-500 peer-checked:text-white peer-checked:border-pink-500">
                                {{ $day }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('hari') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase tracking-widest">Pilih minimal satu hari</p> @enderror
            </div>

            {{-- Time and Quota --}}
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="08:00" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-black focus:border-pink-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="14:00" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-black focus:border-pink-500 outline-none transition">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3">Kuota Pasien / Sesi</label>
                    <input type="number" name="kuota" value="20" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-black focus:border-pink-500 outline-none transition">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeAssignModal()" class="flex-1 py-4 rounded-2xl text-xs font-black text-gray-400 border-2 border-gray-100 hover:bg-gray-50 transition uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-4 rounded-2xl bg-pink-500 text-white text-xs font-black shadow-xl shadow-pink-500/20 hover:bg-pink-600 transition uppercase tracking-widest">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
</style>

<script>
    function openAssignModal() {
        const m = document.getElementById('assign-modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeAssignModal() {
        const m = document.getElementById('assign-modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
