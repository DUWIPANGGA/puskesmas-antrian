@extends('layouts.pasien')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 max-w-[1200px] mx-auto">
    
    {{-- ===================== LEFT COLUMN ===================== --}}
    <div class="flex-1 flex flex-col gap-6 min-w-0">

        {{-- 1. Hero / Live Queue Card --}}
        <div class="bg-gradient-to-br from-[#fdf6f9] to-white rounded-[2rem] p-8 border border-pink-100 shadow-sm relative overflow-hidden flex flex-col sm:flex-row justify-between items-center sm:items-start gap-6">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-pink-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>

            <div class="z-10 w-full sm:w-auto text-center sm:text-left flex-1">
                <div class="flex items-center justify-center sm:justify-start gap-2 mb-4">
                    <span class="bg-[#fce4ec] text-[#d81b60] font-bold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider animate-pulse">
                        LIVE MONITOR
                    </span>
                    @if($antrianAktif && $antrianAktif->status == 'dipanggil')
                        <span class="bg-green-100 text-green-700 font-bold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider">
                            IT'S YOUR TURN!
                        </span>
                    @endif
                </div>
                
                @if($antrianAktif)
                    <div class="mb-6">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1">Your Ticket Status</p>
                        <h2 class="text-3xl font-black text-gray-900 leading-tight">
                            {{ $antrianAktif->nomor_antrian }}
                            <span class="text-xs font-bold text-[#d81b60] bg-pink-50 px-2 py-0.5 rounded-md ml-1">{{ $antrianAktif->poli->nama_poli }}</span>
                        </h2>
                        <div class="flex items-center justify-center sm:justify-start gap-4 mt-2">
                             <div class="flex items-center gap-1 text-[11px] font-bold text-gray-500">
                                <span class="material-symbols-outlined text-[14px] text-teal-500">schedule</span>
                                <span id="wait-time-text">{{ $peopleAhead * 5 }}</span> mins wait
                            </div>
                            <div class="flex items-center gap-1 text-[11px] font-bold text-gray-500">
                                <span class="material-symbols-outlined text-[14px] text-blue-500">groups</span>
                                <span id="people-ahead-text">{{ $peopleAhead }}</span> people ahead
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-6">
                        <h2 class="text-2xl font-black text-gray-900 leading-tight">Welcome to Live Queue</h2>
                        <p class="text-sm text-gray-400 mt-1 font-medium">Monitor real-time patient calls from all clinics.</p>
                    </div>
                @endif

                <div id="polis-live-feed" class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-4">
                    @foreach($allPolisLive->take(3) as $lp)
                        <div class="px-3 py-1.5 bg-white border border-gray-100 rounded-xl shadow-sm flex items-center gap-2 live-poli-item" data-poli-id="{{ $lp->id }}">
                            <span class="text-[9px] font-black text-gray-400 uppercase">{{ $lp->kode_poli }}</span>
                            <span class="text-xs font-black text-[#d81b60] live-poli-number">{{ $lp->current_number }}</span>
                        </div>
                    @endforeach
                    <a href="{{ route('pasien.live-queue') }}" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-[#fce4ec] hover:text-[#d81b60] transition">
                        <span class="material-symbols-outlined text-lg">more_horiz</span>
                    </a>
                </div>
            </div>

            {{-- Right Circle: Now Serving Focus --}}
            <div class="z-10 shrink-0 group relative cursor-pointer" onclick="window.location.href='{{ route('pasien.live-queue') }}'">
                <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-tr from-[#ce1c64] via-[#f05a96] to-[#ff80ab] flex flex-col items-center justify-center text-white ring-[15px] ring-[#fce4ec]/50 shadow-[0_20px_40px_rgba(216,27,96,0.3)] border border-white/20 relative transition-transform hover:scale-105 duration-300">
                    <div class="absolute -top-2 -right-2 bg-white text-[#d81b60] px-3 py-1 rounded-full text-[9px] font-black shadow-md border border-pink-100 uppercase tracking-tighter">
                        @if($antrianAktif) {{ $antrianAktif->poli->kode_poli }} Clinic @else Public @endif
                    </div>
                    
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-1 opacity-80">NOW SERVING</p>
                    <p id="now-serving-number" class="text-6xl font-black leading-none drop-shadow-lg">{{ $nowServing ?? '-' }}</p>
                    
                    <div id="serving-status-container">
                        @if($nowServing && $antrianAktif && $nowServing == $antrianAktif->nomor_antrian)
                            <div class="mt-2 px-3 py-0.5 bg-white/20 backdrop-blur-md rounded-full">
                                <p class="text-[9px] font-bold">PROCEED TO ROOM</p>
                            </div>
                        @else
                            <p class="text-[10px] mt-2 font-bold opacity-70">Live Status</p>
                        @endif
                    </div>
                </div>
                
                {{-- Decorative pulse --}}
                <div class="absolute inset-0 rounded-full bg-pink-400 blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
            </div>
        </div>

        {{-- 2. Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <button onclick="document.getElementById('modal-ambil-tiket').classList.remove('hidden')" class="bg-white border border-pink-50 rounded-[1.5rem] p-5 flex items-center gap-4 hover:shadow-md transition shadow-sm group text-left w-full cursor-pointer focus:outline-none">
                <div class="w-12 h-12 rounded-full bg-[#fce4ec] text-[#d81b60] flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-[0_4px_10px_rgba(216,27,96,0.15)]">
                    <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[15px] font-black text-gray-900 truncate">Ambil Nomor Antrian</h3>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">Get a new queue ticket</p>
                </div>
                <span class="material-symbols-outlined text-[#d81b60] bg-pink-50 p-1.5 rounded-full group-hover:bg-[#d81b60] group-hover:text-white transition">arrow_forward</span>
            </button>

            <a href="{{ route('pasien.checkin.page') }}" class="bg-white border border-blue-50 rounded-[1.5rem] p-5 flex items-center gap-4 hover:shadow-md transition shadow-sm group text-left w-full cursor-pointer focus:outline-none block w-full">
                <div class="w-12 h-12 rounded-full bg-[#e1f5fe] text-[#0288d1] flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-[0_4px_10px_rgba(2,136,209,0.15)]">
                    <span class="material-symbols-outlined text-2xl">where_to_vote</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[15px] font-black text-gray-900 truncate">Check-in</h3>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">Sudah tiba di puskesmas?</p>
                </div>
                <span class="material-symbols-outlined text-[#0288d1] bg-blue-50 p-1.5 rounded-full group-hover:bg-[#0288d1] group-hover:text-white transition">check_circle</span>
            </a>
        </div>

        {{-- 3. Tiket Antrian Anda --}}
        <div class="mt-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-gray-900">Tiket Antrian Anda</h3>
                <a href="#" class="text-xs font-bold text-[#d81b60] hover:underline">Lihat Semua</a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($upcomingAppointments as $apt)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-5 hover:shadow-md transition relative overflow-hidden">
                    {{-- Side color indicator --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $apt->status == 'dipanggil' ? 'bg-[#d81b60]' : 'bg-gray-200' }}"></div>
                    
                    {{-- Date --}}
                    <div class="w-16 h-16 rounded-xl {{ $apt->status == 'dipanggil' ? 'bg-pink-50' : 'bg-[#f3e8f8]' }} flex flex-col items-center justify-center shrink-0 ml-2">
                        <span class="text-[10px] font-bold {{ $apt->status == 'dipanggil' ? 'text-[#d81b60]' : 'text-[#7c51a1]' }} uppercase mb-0.5">
                            {{ \Carbon\Carbon::parse($apt->tanggal)->format('M') }}
                        </span>
                        <span class="text-xl font-black {{ $apt->status == 'dipanggil' ? 'text-gray-900' : 'text-[#5e3881]' }} leading-none">
                            {{ \Carbon\Carbon::parse($apt->tanggal)->format('d') }}
                        </span>
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1 min-w-0 text-center sm:text-left">
                        <h4 class="text-[15px] font-black {{ $apt->status == 'dipanggil' ? 'text-[#d81b60]' : 'text-gray-900' }} truncate mb-1">
                            Nomor: {{ $apt->nomor_antrian }}
                        </h4>
                        <div class="flex items-center justify-center sm:justify-start gap-1 text-xs text-gray-500">
                            <span class="material-symbols-outlined text-[14px]">local_hospital</span>
                            <span class="truncate font-bold">{{ $apt->poli->nama_poli ?? 'Poli Klinik' }}</span>
                        </div>
                    </div>

                    {{-- Actions / Status --}}
                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end mt-2 sm:mt-0">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-widest border
                            {{ $apt->status == 'dipanggil' ? 'bg-[#fce4ec] text-[#d81b60] border-pink-200' : 
                              ($apt->status == 'check_in' ? 'bg-blue-50 text-[#0288d1] border-blue-100' : 'bg-gray-50 text-gray-500 border-gray-200') }}">
                            @if($apt->status == 'dipanggil')
                                <span class="material-symbols-outlined text-[14px]">campaign</span> Sedang Dipanggil
                            @elseif($apt->status == 'check_in')
                                <span class="material-symbols-outlined text-[14px]">where_to_vote</span> Sudah Check-in
                            @else
                                <span class="material-symbols-outlined text-[14px]">schedule</span> Menunggu
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-8 border border-dashed border-gray-200 text-center">
                    <p class="text-sm font-bold text-gray-400">Belum ada tiket antrian untuk mendatang.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ===================== RIGHT COLUMN ===================== --}}
    <div class="w-full lg:w-80 shrink-0 flex flex-col gap-6">
        
        {{-- Health Tips (Purple) --}}
        <div class="bg-gradient-to-b from-[#76569e] to-[#634289] rounded-[2rem] p-6 text-white shadow-md relative overflow-hidden">
            <div class="absolute -bottom-8 -right-8 opacity-10">
                <span class="material-symbols-outlined text-9xl">medical_services</span>
            </div>

            <h3 class="text-[15px] font-black flex items-center gap-2 mb-5 relative z-10">
                <span class="material-symbols-outlined">lightbulb</span> Recent Health Tips
            </h3>

            <div class="flex flex-col gap-3 relative z-10">
                @foreach($healthTips as $tip)
                <div onclick="showTipDetail({{ json_encode($tip) }})" class="bg-white/10 p-4 rounded-2xl hover:bg-white/20 transition cursor-pointer border border-white/5 group">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-purple-200 mb-1 flex items-center gap-1">
                        {{ $tip->category }}
                    </p>
                    <p class="text-xs leading-snug font-medium mb-2">{{ $tip->tip }}</p>
                    <div class="text-[9px] flex items-center gap-1 text-purple-100 group-hover:text-white font-bold opacity-80">
                        Read more <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>



        {{-- Quick Vitals (Now: Last Health History) --}}
        <div class="bg-white rounded-[2rem] p-6 border border-pink-50 shadow-sm relative">
            <h3 class="text-sm font-black text-gray-900 mb-1 flex items-center justify-between">
                {{ $lastVisit ? 'Riwayat Kesehatan Terakhir' : 'Quick Vitals' }}
                <span class="material-symbols-outlined text-[#0288d1] text-lg bg-blue-50 p-1 rounded-full">equalizer</span>
            </h3>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                {{ $lastVisit ? 'Pemeriksaan pada ' . $lastVisit->tanggal->format('d M Y') : 'Data kesehatan umum pasen' }}
            </p>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#f0fbfa] rounded-2xl p-4 flex flex-col justify-center items-center text-center">
                    <p class="text-[9px] font-bold text-[#00897b] uppercase tracking-wider mb-1">Heart Rate</p>
                    <p class="text-2xl font-black text-gray-900 flex items-baseline gap-1">
                        {{ $lastVisit->detak_jantung ?? '—' }} <span class="text-[10px] text-gray-500 font-bold">bpm</span>
                    </p>
                </div>
                <div class="bg-[#fff1f2] rounded-2xl p-4 flex flex-col justify-center items-center text-center">
                    <p class="text-[9px] font-bold text-[#e91e63] uppercase tracking-wider mb-1">Weight</p>
                    <p class="text-2xl font-black text-gray-900 flex items-baseline gap-1">
                        {{ $lastVisit->berat_badan ?? '—' }} <span class="text-[10px] text-gray-500 font-bold">kg</span>
                    </p>
                </div>
                <div class="bg-[#f5f3fa] rounded-2xl p-4 flex flex-col justify-center items-center text-center">
                    <p class="text-[9px] font-bold text-[#673ab7] uppercase tracking-wider mb-1">Suhu Tubuh</p>
                    <p class="text-2xl font-black text-gray-900 flex items-baseline gap-1">
                        {{ $lastVisit->suhu_tubuh ?? '—' }} <span class="text-[10px] text-gray-500 font-bold">°C</span>
                    </p>
                </div>
                <div class="bg-[#e1f5fe] rounded-2xl p-4 flex flex-col justify-center items-center text-center">
                    <p class="text-[9px] font-bold text-[#0288d1] uppercase tracking-wider mb-1">Tensi Darah</p>
                    <p class="text-[15px] font-black text-gray-900 flex items-baseline gap-1">
                        {{ $lastVisit->tekanan_darah ?? '—' }}
                    </p>
                </div>
            </div>
            
            @if(!$lastVisit)
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <p class="text-[10px] text-gray-400 font-medium italic text-center">Belum ada riwayat pemeriksaan medis sebelumnya.</p>
            </div>
            @endif
        </div>

        {{-- Upload Lab Results --}}
        <div class="border-2 border-dashed border-pink-200 bg-[#fff5f8] rounded-[2rem] p-6 text-center hover:bg-pink-50 transition cursor-pointer relative group overflow-hidden">
            <div class="w-14 h-14 bg-[#f48fb1] text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-[0_4px_15px_rgba(244,143,177,0.4)] group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-2xl">add_a_photo</span>
            </div>
            <h3 class="text-sm font-black text-gray-900 mb-1">Upload Lab Results</h3>
            <p class="text-xs text-gray-500 font-medium mb-4">Got a PDF or image of your tests?</p>
            <button class="bg-[#df3d8b] text-white text-[11px] font-bold py-2.5 px-6 rounded-full w-full hover:bg-[#c2185b] transition shadow-md">
                Browse Files
            </button>
        </div>

    </div>
</div>

{{-- ===================== MODALS ===================== --}}
{{-- 1. Modal Pilih Poli --}}
<div id="modal-ambil-tiket" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden scale-95 transition-transform duration-300">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-pink-50/50 to-white">
            <div>
                <h3 class="text-xl font-black text-gray-900">Ambil Nomor Antrian</h3>
                <p class="text-xs text-gray-500 mt-1">Pilih klinik/poli tujuan anda hari ini</p>
            </div>
            <button type="button" onclick="closeModal('modal-ambil-tiket')" class="w-10 h-10 rounded-full bg-white border border-gray-100 hover:bg-pink-50 flex items-center justify-center transition focus:outline-none">
                <span class="material-symbols-outlined text-gray-600 text-xl">close</span>
            </button>
        </div>
        
        <form action="{{ route('pasien.ambil-tiket') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Tanggal Kunjungan</label>
                <div class="relative">
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                           class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-pink-300 focus:ring-0 outline-none text-gray-700 font-medium transition cursor-pointer">
                    <span class="material-symbols-outlined absolute right-4 top-3 text-gray-400 pointer-events-none">calendar_month</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">* Wajib Diisi</span></label>
                <div class="relative">
                    <textarea name="keluhan" rows="2" required minlength="10" placeholder="Minimal 10 karakter. Contoh: Sakit kepala dan demam sejak 2 hari yang lalu..."
                              class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-pink-300 focus:ring-0 outline-none text-gray-700 font-medium transition resize-none"></textarea>
                    <span class="material-symbols-outlined absolute right-4 top-3 text-gray-400 pointer-events-none">medical_information</span>
                </div>
            </div>

            <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Klinik / Poli</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 max-h-[35vh] overflow-y-auto pr-2" id="poli-selection-container">
                @foreach($polis as $poli)
                <label class="cursor-pointer group relative">
                    <input type="radio" name="poli_id" value="{{ $poli->id }}" class="peer sr-only" required>
                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-[#d81b60] peer-checked:bg-[#fce4ec] hover:border-pink-200 hover:bg-pink-50 transition w-full text-left">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-3 text-[#d81b60]">
                            <i class="{{ $poli->icon ?? 'fa-solid fa-hospital' }} text-xl"></i>
                        </div>
                        <h4 class="font-black text-gray-900 text-[14px] leading-tight mb-1 group-hover:text-[#d81b60] peer-checked:text-[#d81b60]">{{ $poli->nama_poli }}</h4>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">{{ $poli->kode_poli }}</p>
                        
                        <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 border-gray-200 peer-checked:border-[#d81b60] peer-checked:bg-[#d81b60] flex items-center justify-center transition">
                            <span class="material-symbols-outlined text-white text-[12px] opacity-0 peer-checked:opacity-100">check</span>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            
            <button type="submit" class="w-full bg-[#d81b60] text-white py-3.5 rounded-full font-bold text-sm hover:bg-[#c2185b] transition shadow-lg shadow-pink-500/30 flex justify-center items-center gap-2">
                Ambil Tiket Sekarang <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>
        </form>
    </div>
</div>

{{-- 2. Modal Error (Popup Maaf Kuota Habis dll) --}}
@if(session('popup_error'))
<div id="modal-error" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl text-center">
        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-5 relative">
            <div class="absolute inset-0 rounded-full bg-red-100 animate-ping opacity-50"></div>
            <span class="material-symbols-outlined text-red-500 text-5xl relative z-10">sentiment_dissatisfied</span>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Mohon Maaf</h3>
        <p class="text-[14px] text-gray-600 mb-8 leading-relaxed">{{ session('popup_error') }}</p>
        <button onclick="document.getElementById('modal-error').style.display='none'" class="w-full bg-gray-100 text-gray-800 py-3 rounded-full font-bold text-[13px] hover:bg-gray-200 transition">
            Tutup & Mengerti
        </button>
    </div>
</div>
@endif

{{-- 3. Modal Success --}}
@if(session('popup_success'))
<div id="modal-success" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl text-center">
        <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-5 relative">
            <div class="absolute inset-0 rounded-full bg-green-100 animate-ping opacity-20"></div>
            <span class="material-symbols-outlined text-green-500 text-5xl relative z-10">check_circle</span>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Berhasil!</h3>
        <p class="text-[14px] text-gray-600 mb-8 leading-relaxed">{{ session('popup_success') }}</p>
        <button onclick="document.getElementById('modal-success').style.display='none'" class="w-full bg-[#d81b60] text-white py-3 rounded-full font-bold text-[13px] hover:bg-[#c2185b] transition shadow-md shadow-pink-500/20">
            Tutup
        </button>
    </div>
</div>
@endif



{{-- Tip Detail Modal --}}
<div id="modal-tip-detail" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-purple-50">
            <span id="tip-detail-category" class="bg-purple-100 text-[#634289] text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider"></span>
            <button onclick="closeModal('modal-tip-detail')" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        <div class="p-8">
            <h3 id="tip-detail-title" class="text-xl font-black text-gray-900 leading-tight mb-4"></h3>
            <div class="w-12 h-1 bg-purple-200 rounded-full mb-6"></div>
            <p id="tip-detail-content" class="text-gray-600 text-sm leading-relaxed mb-8"></p>
            <button onclick="closeModal('modal-tip-detail')" class="w-full bg-[#634289] text-white py-4 rounded-2xl font-black shadow-lg shadow-purple-500/20 hover:scale-[1.02] transition">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.activePoli_id = "{{ $antrianAktif->poli_id ?? '' }}";
    window.userTicket = "{{ $antrianAktif->nomor_antrian ?? '' }}";

    function showTipDetail(tip) {
        document.getElementById('tip-detail-category').innerText = tip.category;
        document.getElementById('tip-detail-title').innerText = tip.tip;
        document.getElementById('tip-detail-content').innerText = tip.content || 'Informasi detail belum tersedia.';
        
        const modal = document.getElementById('modal-tip-detail');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Lock Scroll
        const main = document.querySelector('main');
        if(main) main.style.overflowY = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        
        const modalContent = modal.querySelector('div');
        
        if (modalContent) {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modal.style.pointerEvents = 'none';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.style.pointerEvents = '';
            
            // Unlock Scroll
            const main = document.querySelector('main');
            if(main) main.style.overflowY = 'auto';
        }, 300);
    }

    // Custom Open logic with animation for forms
    function setupModalTrigger(modalId) {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if(e.target === this) closeModal(modalId);
        });

        const orgClick = document.querySelector(`[onclick="document.getElementById('${modalId}').classList.remove('hidden')"]`);
        if (orgClick) {
            orgClick.onclick = function(e) {
                e.preventDefault();
                const modal = document.getElementById(modalId);
                const modalContent = modal.querySelector('div.scale-95');
                
                // Lock Scroll
                const main = document.querySelector('main');
                if(main) main.style.overflowY = 'hidden';

                modal.classList.remove('hidden');
                
                // Trigger reflow
                void modal.offsetWidth;
                
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                if(modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            };
        }
    }

    setupModalTrigger('modal-ambil-tiket');
</script>
@endpush

@endsection
