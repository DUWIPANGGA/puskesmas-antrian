@extends('layouts.dokter')

@section('title', 'My Patients')

@section('content')
<div class="max-w-[1200px] mx-auto flex flex-col gap-6">

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Patients Month --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5 border-2 hover:border-[#fce4ec] transition">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#fce4ec] text-[#d81b60] flex items-center justify-center shrink-0 shadow-inner">
                 <span class="material-symbols-outlined text-3xl">groups</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Total Patients (Month)</p>
                <div class="text-3xl font-black text-[#d81b60] leading-none">{{ number_format($totalPatientsMonth) }}</div>
            </div>
        </div>

        {{-- Most Prescribed --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5 border-2 border-teal-100 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-[1.2rem] bg-teal-300 text-teal-800 flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">medication</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Most Prescribed</p>
                <div class="text-2xl font-black text-teal-600 leading-none">{{ $mostPrescribed }}</div>
            </div>
        </div>

        {{-- Avg Daily Visits --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5 border-2 hover:border-[#e0f2f1] transition">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#e0f2f1] text-[#00897b] flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">trending_up</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Avg Daily Visits</p>
                <div class="text-3xl font-black text-gray-900 leading-none">{{ $avgDailyVisits }}</div>
            </div>
        </div>
    </div>

    {{-- Active Consultation Dashboard --}}
    @if($currentPatient)
    <div class="bg-gradient-to-r from-pink-50 to-white rounded-[2rem] border border-[#fce4ec] p-8 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-[#1b4353]">Anda sedang memeriksa...</h2>
                <p class="text-sm font-bold text-gray-500 mt-1">Sesi dimulai: {{ $currentPatient->dipanggil_at ? $currentPatient->dipanggil_at->format('H:i') : 'Sekarang' }}</p>
            </div>
            <div class="flex gap-3">
                <button class="px-6 py-3 rounded-full font-bold text-sm bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">Simpan Draft</button>
                <form action="{{ route('dokter.finish-current') }}" method="POST" class="m-0" id="finishConsultationForm">
                    @csrf
                    <button type="submit" class="px-6 py-3 rounded-full font-bold text-sm bg-[#d81b60] text-white hover:bg-[#c2185b] shadow-md transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">done_all</span> Selesaikan Sesi
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Sidebar: Patient Info --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                {{-- Profile Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-50 rounded-bl-full -z-0"></div>
                    <div class="w-24 h-24 rounded-full bg-gray-200 border-4 border-white shadow-md relative z-10 mb-4 overflow-hidden flex items-center justify-center text-[#d81b60] font-black text-3xl">
                        {{ substr($currentPatient->pasien->name ?? 'A', 0, 1) }}
                        <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 z-10">{{ $currentPatient->pasien->name ?? 'Nama Pasien' }}</h3>
                    <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-widest z-10">
                        ID: #{{ 90000 + ($currentPatient->pasien_id ?? 1) }} &bull; 
                        {{ \Carbon\Carbon::parse($currentPatient->pasien->birth_date ?? '1990-01-01')->age }} Tahun
                    </p>
                    
                    <div class="flex gap-2 mt-4 z-10">
                        <span class="px-3 py-1 bg-pink-100 text-[#d81b60] rounded-full text-[10px] font-black uppercase tracking-wider">BPJS Aktif</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-wider">Gol. Darah: O</span>
                    </div>

                    <div class="w-full mt-6 bg-gray-50 rounded-xl p-4 text-left border border-gray-100 h-full">
                        <p class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-2">Keluhan Utama</p>
                        <p class="text-sm font-medium text-gray-600 italic">"{{ $currentPatient->keluhan ?: 'Pasien tidak menulis keluhan spesifik saat pendaftaran.' }}"</p>
                    </div>
                </div>

                {{-- Medical History --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-black text-gray-900">Riwayat Medis</h4>
                        <a href="#" class="text-[11px] font-bold text-[#d81b60] hover:underline">Lihat Semua</a>
                    </div>
                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar space-y-4">
                        @forelse($riwayatMedis as $rm)
                        <div class="relative pl-4 border-l-2 border-gray-100">
                            <span class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-[#d81b60]"></span>
                            <p class="text-[10px] font-black tracking-wider text-gray-400 uppercase">{{ $rm->tanggal->format('d M Y') }}</p>
                            <p class="text-xs font-bold text-gray-800 mt-0.5">{{ $rm->diagnosa ?? 'Pemeriksaan Umum' }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5" title="{{ $rm->catatan }}">{{ Str::limit($rm->catatan, 50) }}</p>
                            @if($rm->antrian && $rm->antrian->resep && $rm->antrian->resep->obat)
                            <p class="text-[10px] text-[#0288d1] font-bold mt-1 inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">prescriptions</span> Resep diberikan
                            </p>
                            @endif
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 font-bold opacity-70">Belum ada riwayat pemeriksaan sebelumnya.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Area: Medical Inputs --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                {{-- Vitals Input --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-pink-200 transition">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                             <span class="material-symbols-outlined text-[18px]">monitor_heart</span>
                        </div>
                        <h4 class="text-sm font-black text-gray-900">Tanda Vital & Pemeriksaan Fisik</h4>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-1.5 block">Tinggi (cm)</label>
                            <input type="number" form="finishConsultationForm" name="tinggi_badan" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60]" placeholder="170" value="{{ $lastTinggi ?? '' }}">
                        </div>
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-1.5 block">Berat (kg)</label>
                            <input type="number" form="finishConsultationForm" name="berat_badan" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60]" placeholder="65" value="{{ $lastBerat ?? '' }}">
                        </div>
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-1.5 block">Tensi (mmHg)</label>
                            <input type="text" form="finishConsultationForm" name="tekanan_darah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60]" placeholder="120/80">
                        </div>
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-[#e53935] uppercase mb-1.5 block">Detak Jantung</label>
                            <div class="relative">
                                <input type="number" form="finishConsultationForm" name="detak_jantung" class="w-full bg-red-50 border border-red-100 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" placeholder="72">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-red-400">bpm</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-orange-600 uppercase mb-1.5 block">Suhu Tubuh</label>
                            <div class="relative">
                                <input type="number" step="0.1" form="finishConsultationForm" name="suhu_tubuh" class="w-full bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400" placeholder="36.7">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-orange-400">°C</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-1.5 block">Gula Darah</label>
                            <input type="number" form="finishConsultationForm" name="kadar_gula" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60]" placeholder="90">
                        </div>
                    </div>
                </div>

                {{-- Consultation Notes & Digital Prescription Wrapper --}}
                <div class="grid grid-cols-1 gap-6 flex-1">
                    {{-- Consultation Notes --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col hover:border-pink-200 transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-[#fce4ec] text-[#d81b60] flex items-center justify-center shrink-0">
                                 <span class="material-symbols-outlined text-[18px]">edit_note</span>
                            </div>
                            <h4 class="text-sm font-black text-gray-900">Catatan Pemeriksaan</h4>
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase mb-1.5 block">Diagnosis Utama</label>
                            <input type="text" form="finishConsultationForm" name="diagnosa" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60] transition" placeholder="Mulai ketik diagnosis utama (wajib)...">
                        </div>

                        <div class="flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[10px] font-black tracking-widest text-[#00897b] uppercase block">Catatan Dokter</label>
                                <div class="flex gap-2 text-gray-400">
                                    <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-gray-800">format_bold</span>
                                    <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-gray-800">format_list_bulleted</span>
                                </div>
                            </div>
                            <textarea form="finishConsultationForm" name="catatan" class="w-full h-24 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60] resize-none transition" placeholder="Dokumentasikan gejala, tindakan, keluhan tambahan pasien..."></textarea>
                        </div>
                    </div>

                    {{-- Digital Prescription --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border-l-[6px] border-l-[#0288d1] border-y border-r border-gray-100 flex flex-col relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-[#e1f5fe] text-[#0288d1] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">prescriptions</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-black text-gray-900 leading-tight">Digital Prescription</h4>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">MANAGE PATIENT MEDICATIONS</p>
                                </div>
                            </div>
                            <button type="button" id="btnAddMedicine" class="bg-[#00bcd4] hover:bg-[#00acc1] text-white px-5 py-2.5 rounded-full font-bold text-xs flex items-center gap-2 shadow-md hover:shadow-lg transition">
                                <span class="material-symbols-outlined text-[16px]">add</span> Add Medicine
                            </button>
                        </div>
                        
                        <div id="prescriptionList">
                            {{-- Prescribed Medicine Item --}}
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 flex items-center justify-between mb-4 hover:border-teal-100 transition medicine-item">
                                <div class="w-[30%]">
                                    <p class="text-[9px] font-black tracking-widest text-[#00897b] uppercase mb-1">DRUG NAME</p>
                                    <input type="text" form="finishConsultationForm" name="obat[]" class="text-sm font-black text-gray-900 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#00897b]" placeholder="Nama obat" value="Amoxicillin">
                                </div>
                                <div class="w-[15%]">
                                    <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">DOSAGE</p>
                                    <input type="text" form="finishConsultationForm" name="dosis[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="Dosis" value="500mg">
                                </div>
                                <div class="w-[30%]">
                                    <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">INSTRUCTIONS</p>
                                    <input type="text" form="finishConsultationForm" name="instruksi[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="Aturan pakai" value="3x sehari setelah makan">
                                </div>
                                <div class="w-[10%]">
                                    <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">QTY</p>
                                    <input type="number" form="finishConsultationForm" name="jumlah[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="0" value="10">
                                </div>
                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 rounded-full hover:bg-red-50 transition remove-medicine">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        </div>

                        {{-- Add more placeholder --}}
                        <div id="addMorePlaceholder" class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center cursor-pointer hover:border-[#00bcd4] hover:bg-[#e1f5fe]/20 sm:col-span-3 transition flex items-center justify-center gap-2 text-gray-400">
                            <span class="material-symbols-outlined text-[20px]">medication</span>
                            <span class="text-xs font-bold tracking-wide">Search and add more drugs to the list</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="flex flex-col lg:flex-row gap-6 mt-2 h-[750px]">
        
        {{-- Left Column: Calendar List --}}
        <div class="w-full lg:w-[320px] shrink-0 flex flex-col bg-white rounded-[2.5rem] shadow-sm border border-pink-50/50 p-6">
            
            <div class="flex justify-between items-center mb-6 px-2">
                <h3 class="text-[15px] font-black text-gray-900">{{ today()->format('F Y') }}</h3>
                <div class="flex gap-2">
                    <button class="text-gray-400 hover:text-[#d81b60]"><span class="material-symbols-outlined text-lg">chevron_left</span></button>
                    <button class="text-gray-400 hover:text-[#d81b60]"><span class="material-symbols-outlined text-lg">chevron_right</span></button>
                </div>
            </div>

            <div class="flex flex-col gap-3 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                
                @foreach($calendarDays as $dayInfo)
                    @php 
                        $date = $dayInfo['date']; 
                        $count = $dayInfo['count'];
                        $isSelected = $dayInfo['is_selected'];
                    @endphp

                    <a href="{{ route('dokter.my-patients', ['date' => $date->format('Y-m-d')]) }}" class="block">
                        @if($isSelected)
                        {{-- Active Item --}}
                        <div class="bg-gradient-to-br from-[#ac4471] to-[#802a50] rounded-2xl p-5 text-white shadow-lg shadow-[#ac4471]/30 cursor-pointer flex justify-between items-center relative overflow-hidden transition transform hover:scale-[1.02]">
                            <div class="z-10 relative">
                                <p class="text-[10px] font-bold tracking-widest uppercase mb-1 opacity-80">
                                    {{ $date->isToday() ? 'TODAY' : ($date->isYesterday() ? 'YESTERDAY' : $date->format('l')) }}
                                </p>
                                <p class="text-xl font-black">{{ $date->format('M d') }}</p>
                            </div>
                            <div class="text-right z-10 relative">
                                <p class="text-3xl font-black">{{ $count }}</p>
                                <p class="text-[9px] uppercase tracking-widest font-bold opacity-80">PATIENTS</p>
                            </div>
                            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[80px] opacity-10">calendar_today</span>
                        </div>
                        @else
                        {{-- Inactive Days --}}
                        <div class="bg-pink-50/30 rounded-2xl p-5 text-gray-600 hover:bg-pink-50 cursor-pointer flex justify-between items-center border border-transparent hover:border-pink-100 transition">
                            <div>
                                <p class="text-[10px] font-bold tracking-widest uppercase mb-1 text-gray-400">
                                    {{ $date->isToday() ? 'TODAY' : ($date->isYesterday() ? 'YESTERDAY' : $date->format('l')) }}
                                </p>
                                <p class="text-lg font-black text-gray-800">{{ $date->format('M d') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-[#d81b60]">{{ $count }}</p>
                                <p class="text-[9px] uppercase tracking-widest font-bold text-gray-400">PATIENTS</p>
                            </div>
                        </div>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="relative mt-4">
                <input type="date" id="fullCalendarInput" class="absolute inset-0 opacity-0 cursor-pointer pointer-events-none" onchange="window.location.href='{{ route('dokter.my-patients') }}?date=' + this.value">
                <button type="button" onclick="document.getElementById('fullCalendarInput').showPicker ? document.getElementById('fullCalendarInput').showPicker() : document.getElementById('fullCalendarInput').focus()" class="w-full bg-white border-2 border-dashed border-pink-200 text-[#d81b60] py-3.5 rounded-full font-black text-[13px] hover:bg-pink-50 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span> View Full Calendar
                </button>
            </div>

        </div>

        {{-- Right Column: Visit Log Table --}}
        <div class="flex-1 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm flex flex-col min-w-0">
            <div class="p-8 pb-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30 rounded-t-[2.5rem]">
                <div>
                    <h3 class="text-xl font-black text-[#1b4353]">Visit Log: {{ $selectedDate->format('F d, Y') }}</h3>
                    <p class="text-xs font-medium text-gray-500 mt-1">Detailed report of patient interactions</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dokter.export-report', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-[#1b4353] hover:bg-[#122c36] text-white px-5 py-2.5 rounded-full font-bold text-xs flex items-center gap-2 shadow-md transition">
                        <span class="material-symbols-outlined text-sm">download</span> Export Report
                    </a>
                    <button class="w-10 h-10 rounded-full bg-[#e1f5fe] text-[#0288d1] flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto w-full custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white border-b border-gray-100 z-10">
                        <tr>
                            <th class="py-4 px-8 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Patient</th>
                            <th class="py-4 px-4 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Time</th>
                            <th class="py-4 px-4 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Diagnosis Summary</th>
                            <th class="py-4 px-8 text-[11px] font-black tracking-widest text-[#00897b] uppercase text-right">Digital Prescription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                        <tr class="group border-b border-gray-50 hover:bg-gray-50/50 transition cursor-pointer" onclick="document.getElementById('detail-{{ $visit->id }}').classList.toggle('hidden')">
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-pink-100 text-[#d81b60] flex items-center justify-center font-black text-sm shrink-0">
                                        {{ substr($visit->pasien->name ?? 'U', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-black text-gray-900">{{ $visit->pasien->name ?? 'Patient Name' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold tracking-wider mt-0.5">PID: #{{ 90000 + $visit->pasien_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                <p class="text-[13px] font-black text-gray-900">{{ $visit->selesai_at ? $visit->selesai_at->format('H:i') : '10:30' }}</p>
                                <p class="text-[11px] font-medium text-gray-400 font-bold italic mt-0.5">{{ rand(0,1) ? 'Regular Follow-up' : 'Urgent' }}</p>
                            </td>
                            <td class="py-5 px-4">
                                <span class="bg-[#e1f5fe]/70 text-[#0288d1] text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full inline-block">
                                    {{ $visit->laporanKunjungan->diagnosa ?? 'General Checkup' }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <div class="inline-flex flex-col items-end gap-2">
                                    @if($visit->resep && $visit->resep->obat)
                                    <span class="bg-teal-50 text-teal-600 border border-teal-100 text-[11px] font-black px-3 py-1.5 rounded-full shadow-sm">
                                        {{ explode(" - ", explode("\n", $visit->resep->obat)[0])[0] ?? 'Obat' }}
                                    </span>
                                    @endif
                                    <button class="text-[10px] bg-white border border-gray-200 text-gray-500 rounded-full px-3 py-1.5 font-bold hover:bg-gray-50 hover:text-[#d81b60] hover:border-pink-200 transition flex items-center gap-1 shadow-sm mt-1">
                                        Lihat Detail <span class="material-symbols-outlined text-[14px]">expand_more</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr id="detail-{{ $visit->id }}" class="hidden bg-gradient-to-r from-gray-50/80 to-white">
                            <td colspan="4" class="px-8 py-6 border-b border-gray-100 shadow-inner">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                    <div class="space-y-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="material-symbols-outlined text-[#d81b60] text-[16px]">medical_information</span>
                                                <h5 class="text-[10px] font-black tracking-widest text-[#d81b60] uppercase">Keluhan Utama</h5>
                                            </div>
                                            <p class="text-[13px] font-medium text-gray-600 italic leading-relaxed">"{{ $visit->keluhan ?: 'Pasien tidak menulis keluhan spesifik saat pendaftaran.' }}"</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="material-symbols-outlined text-[#00897b] text-[16px]">clinical_notes</span>
                                                <h5 class="text-[10px] font-black tracking-widest text-[#00897b] uppercase">Catatan & Pemeriksaan Fisik</h5>
                                            </div>
                                            <p class="text-[13px] font-medium text-gray-700 whitespace-pre-line leading-relaxed">{{ $visit->laporanKunjungan->catatan ?? 'Tidak ada catatan.' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="material-symbols-outlined text-[#0288d1] text-[16px]">prescriptions</span>
                                            <h5 class="text-[10px] font-black tracking-widest text-[#0288d1] uppercase">Resep Obat</h5>
                                        </div>
                                        @if($visit->resep && $visit->resep->obat)
                                            <ul class="text-[13px] font-medium text-gray-700 leading-relaxed list-disc pl-4 space-y-1">
                                                @foreach(explode("\n", $visit->resep->obat) as $obatItem)
                                                    @if(trim($obatItem))
                                                        <li>{{ trim($obatItem) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-[13px] font-medium text-gray-400 italic">Tidak ada resep diberikan.</p>
                                        @endif

                                        <div class="mt-8 flex justify-end">
                                            <a href="{{ route('dokter.visit.pdf', $visit->id) }}" target="_blank" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Cetak Laporan Medis
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">history</span>
                                <p class="text-sm font-bold text-gray-400">Belum ada riwayat kunjungan pasien.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-8 py-4 border-t border-gray-50 flex justify-between items-center text-xs text-gray-500 font-bold">
                <span>Showing {{ $visits->firstItem() ?? 0 }}-{{ $visits->lastItem() ?? 0 }} of {{ $visits->total() }} patients</span>
                <div class="flex gap-1">
                    {{-- Previous Page Link --}}
                    @if ($visits->onFirstPage())
                        <button disabled class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-300"><span class="material-symbols-outlined text-[16px]">chevron_left</span></button>
                    @else
                        <a href="{{ $visits->previousPageUrl() }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-[#d81b60] hover:text-[#d81b60] transition"><span class="material-symbols-outlined text-[16px]">chevron_left</span></a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($visits->getUrlRange(max(1, $visits->currentPage() - 1), min($visits->lastPage(), $visits->currentPage() + 1)) as $page => $url)
                        @if ($page == $visits->currentPage())
                            <button class="w-8 h-8 rounded-full bg-[#d81b60] border border-[#d81b60] text-white flex items-center justify-center transition">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-[#d81b60] hover:text-[#d81b60] transition text-gray-600">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($visits->hasMorePages())
                        <a href="{{ $visits->nextPageUrl() }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-[#d81b60] hover:text-[#d81b60] transition"><span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
                    @else
                        <button disabled class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-300"><span class="material-symbols-outlined text-[16px]">chevron_right</span></button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAddMedicine = document.getElementById('btnAddMedicine');
        const prescriptionList = document.getElementById('prescriptionList');

        if (btnAddMedicine && prescriptionList) {
            // Auto-save logic
            const patientId = "{{ $currentPatient->id ?? '' }}";
            const storageKey = `consult_autosave_${patientId}`;

            function saveToLocal() {
                if (!patientId) return;
                const formData = {
                    vitals: {
                        tinggi: document.querySelector('input[name="tinggi_badan"]')?.value,
                        berat: document.querySelector('input[name="berat_badan"]')?.value,
                        tensi: document.querySelector('input[name="tekanan_darah"]')?.value,
                        jantung: document.querySelector('input[name="detak_jantung"]')?.value,
                        suhu: document.querySelector('input[name="suhu_tubuh"]')?.value,
                        gula: document.querySelector('input[name="kadar_gula"]')?.value,
                    },
                    notes: {
                        diagnosa: document.querySelector('input[name="diagnosa"]')?.value,
                        catatan: document.querySelector('textarea[name="catatan"]')?.value,
                    },
                    medicines: []
                };

                document.querySelectorAll('.medicine-item').forEach(item => {
                    formData.medicines.push({
                        obat: item.querySelector('input[name="obat[]"]')?.value,
                        dosis: item.querySelector('input[name="dosis[]"]')?.value,
                        instruksi: item.querySelector('input[name="instruksi[]"]')?.value,
                        jumlah: item.querySelector('input[name="jumlah[]"]')?.value,
                    });
                });

                localStorage.setItem(storageKey, JSON.stringify(formData));
            }

            function loadFromLocal() {
                if (!patientId) return;
                const saved = localStorage.getItem(storageKey);
                if (!saved) return;

                const data = JSON.parse(saved);
                
                // Load Vitals
                if (data.vitals) {
                    if (data.vitals.tinggi) document.querySelector('input[name="tinggi_badan"]').value = data.vitals.tinggi;
                    if (data.vitals.berat) document.querySelector('input[name="berat_badan"]').value = data.vitals.berat;
                    if (data.vitals.tensi) document.querySelector('input[name="tekanan_darah"]').value = data.vitals.tensi;
                    if (data.vitals.jantung) document.querySelector('input[name="detak_jantung"]').value = data.vitals.jantung;
                    if (data.vitals.suhu) document.querySelector('input[name="suhu_tubuh"]').value = data.vitals.suhu;
                    if (data.vitals.gula) document.querySelector('input[name="kadar_gula"]').value = data.vitals.gula;
                }

                // Load Notes
                if (data.notes) {
                    if (data.notes.diagnosa) document.querySelector('input[name="diagnosa"]').value = data.notes.diagnosa;
                    if (data.notes.catatan) document.querySelector('textarea[name="catatan"]').value = data.notes.catatan;
                }

                // Load Medicines
                if (data.medicines && data.medicines.length > 0) {
                    // Clear default list and rebuild
                    prescriptionList.innerHTML = '';
                    data.medicines.forEach(m => addMedicineItem(m));
                }
            }

            // Universal listener for changes
            document.addEventListener('input', function(e) {
                if (e.target.closest('#finishConsultationForm') || 
                    e.target.closest('.lg:col-span-8') || 
                    e.target.closest('.medicine-item')) {
                    saveToLocal();
                }
            });

            // Clear local storage on form submit
            const mainForm = document.getElementById('finishConsultationForm');
            if (mainForm) {
                mainForm.addEventListener('submit', () => {
                    localStorage.removeItem(storageKey);
                });
            }

            function addMedicineItem(data = null) {
                const newItem = document.createElement('div');
                newItem.className = 'bg-gray-50/50 rounded-2xl p-4 border border-gray-100 flex items-center justify-between mb-4 hover:border-teal-100 transition medicine-item';
                newItem.innerHTML = `
                    <div class="w-[30%]">
                        <p class="text-[9px] font-black tracking-widest text-[#00897b] uppercase mb-1">DRUG NAME</p>
                        <input type="text" form="finishConsultationForm" name="obat[]" class="text-sm font-black text-gray-900 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#00897b]" placeholder="Nama obat" value="${data ? (data.obat || '') : ''}">
                    </div>
                    <div class="w-[15%]">
                        <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">DOSAGE</p>
                        <input type="text" form="finishConsultationForm" name="dosis[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="Dosis" value="${data ? (data.dosis || '') : ''}">
                    </div>
                    <div class="w-[30%]">
                        <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">INSTRUCTIONS</p>
                        <input type="text" form="finishConsultationForm" name="instruksi[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="Aturan pakai" value="${data ? (data.instruksi || '') : ''}">
                    </div>
                    <div class="w-[10%]">
                        <p class="text-[9px] font-black tracking-widest text-[#0288d1] uppercase mb-1">QTY</p>
                        <input type="number" form="finishConsultationForm" name="jumlah[]" class="text-sm font-bold text-gray-800 bg-transparent outline-none w-full border-b border-dashed border-gray-300 focus:border-[#0288d1]" placeholder="0" value="${data ? (data.jumlah || '') : ''}">
                    </div>
                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 rounded-full hover:bg-red-50 transition remove-medicine">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                `;
                prescriptionList.appendChild(newItem);
                attachRemoveEvent(newItem.querySelector('.remove-medicine'));
                if (!data) saveToLocal(); // Save if manually added
            }

            btnAddMedicine.addEventListener('click', function() {
                addMedicineItem();
            });

            function attachRemoveEvent(button) {
                button.addEventListener('click', function(e) {
                    const items = document.querySelectorAll('.medicine-item');
                    if (items.length > 1) {
                        this.closest('.medicine-item').remove();
                    } else {
                        const inputs = this.closest('.medicine-item').querySelectorAll('input');
                        inputs.forEach(input => input.value = '');
                    }
                    saveToLocal();
                });
            }

            // Initialization
            loadFromLocal();
            document.querySelectorAll('.remove-medicine').forEach(attachRemoveEvent);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 99px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #bdbdbd; }
</style>
@endpush
@endsection
