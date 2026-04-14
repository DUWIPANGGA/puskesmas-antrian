@extends('layouts.pasien')
@section('title', 'Visit Details')

@section('content')
@php
    $resep    = $record->antrian->resep ?? null;
    $obatList = $resep?->detailResep ?? collect();
    $recordId = '#SC-' . now()->year . '-' . str_pad($record->id, 4, '0', STR_PAD_LEFT);

    // Parse catatan to extract symptoms and notes
    $catatanRaw = $record->catatan ?? '';
    $catatanClean = preg_replace('/^Tanda Vital:.*?\n\n/s', '', $catatanRaw);
    $lines = array_filter(array_map('trim', explode("\n", $catatanClean)));
    
    // Divide into Symptoms and Notes (simple heuristic: lines with bullet points or first few lines)
    $symptomLines = array_slice($lines, 0, 3);
    $doctorNotes = implode(" ", array_slice($lines, 3));
    if(empty($doctorNotes)) {
        $doctorNotes = $catatanClean;
        $symptomLines = []; // Fallback if not easily splittable
    }
@endphp

<div class="flex flex-col gap-6 max-w-6xl mx-auto pb-12">

    {{-- Header Row --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('pasien.medical-history') }}" class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-[#d81b60] hover:bg-pink-100 transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900">Visit Details</h1>
                <p class="text-[11px] font-bold text-gray-400">Record ID: <span class="text-gray-900">{{ $recordId }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-6 py-2.5 bg-white border-2 border-teal-600 text-teal-700 rounded-full text-xs font-black hover:bg-teal-50 transition">
                <span class="material-symbols-outlined text-[18px]">download</span> Download PDF
            </button>
            <button class="flex items-center gap-2 px-6 py-2.5 bg-[#880e4f] text-white rounded-full text-xs font-black shadow-lg shadow-pink-100">
                <span class="material-symbols-outlined text-[18px]">share</span> Share Record
            </button>
        </div>
    </div>

    {{-- Top Hero Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Hero Card Left --}}
        <div class="lg:col-span-8 bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 relative overflow-hidden flex flex-col justify-center">
            {{-- Decorative Background --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-pink-50/50 rounded-full -translate-y-1/2 translate-x-1/4 -z-0"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 text-xs font-bold text-[#d81b60] mb-4">
                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                    {{ $record->tanggal->translatedFormat('F d, Y') }} • {{ \Carbon\Carbon::parse($record->waktu_check_in)->format('H:i A') }}
                </div>
                
                <h2 class="text-4xl font-black text-gray-900 leading-tight mb-8 max-w-md">
                    {{ $record->diagnosa ?? 'General Health Checkup' }}
                </h2>

                <div class="flex items-center justify-between gap-6 flex-wrap">
                    {{-- Doctor Badge --}}
                    <div class="bg-gray-50/80 rounded-full pl-2 pr-6 py-2 flex items-center gap-3 border border-gray-100">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border-2 border-white">
                            @if($record->dokter?->photo)
                                <img src="{{ asset('storage/' . $record->dokter->photo) }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($record->dokter->name ?? 'D') }}&background=E0F2F1&color=00897B" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Attending Doctor</p>
                            <p class="text-xs font-black text-gray-900">Dr. {{ $record->dokter->name ?? '-' }}</p>
                            <p class="text-[8px] font-bold text-[#d81b60] uppercase mt-0.5">{{ $record->poli->nama_poli ?? 'General' }} Specialist</p>
                        </div>
                    </div>

                    {{-- Category Badges --}}
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-2 bg-teal-50 text-teal-700 rounded-full text-[10px] font-black border border-teal-100 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">local_hospital</span> Internal Medicine
                        </span>
                        <span class="px-4 py-2 bg-cyan-100 text-cyan-800 rounded-full text-[10px] font-black border border-cyan-200 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> Completed
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vitals Cards Right (2x2 Grid) --}}
        <div class="lg:col-span-4 grid grid-cols-2 gap-4">
            {{-- Heart Rate --}}
            <div class="bg-cyan-50 rounded-[2rem] p-6 flex flex-col items-start gap-2 border border-cyan-100">
                <span class="material-symbols-outlined text-teal-600 bg-white w-8 h-8 rounded-lg flex items-center justify-center text-[18px]">favorite</span>
                <div class="mt-auto">
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $record->detak_jantung ? round($record->detak_jantung) : '--' }} <span class="text-xs font-bold text-gray-400">bpm</span></p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Heart Rate</p>
                </div>
            </div>
            {{-- Temperature --}}
            <div class="bg-teal-50 rounded-[2rem] p-6 flex flex-col items-start gap-2 border border-teal-100">
                <span class="material-symbols-outlined text-teal-600 bg-white w-8 h-8 rounded-lg flex items-center justify-center text-[18px]">thermostat</span>
                <div class="mt-auto">
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $record->suhu_tubuh ? number_format($record->suhu_tubuh, 1) : '--' }} <span class="text-xs font-bold text-gray-400">°C</span></p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Temperature</p>
                </div>
            </div>
            {{-- Blood Pressure --}}
            <div class="bg-pink-50 rounded-[2rem] p-6 flex flex-col items-start gap-2 border border-pink-100">
                <span class="material-symbols-outlined text-pink-600 bg-white w-8 h-8 rounded-lg flex items-center justify-center text-[18px]">monitor_heart</span>
                <div class="mt-auto">
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $record->tekanan_darah ?? '--' }}</p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Blood Pressure</p>
                </div>
            </div>
            {{-- SpO2 --}}
            <div class="bg-gray-50 rounded-[2rem] p-6 flex flex-col items-start gap-2 border border-gray-200">
                <span class="material-symbols-outlined text-gray-600 bg-white w-8 h-8 rounded-lg flex items-center justify-center text-[18px]">air</span>
                <div class="mt-auto">
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $record->saturasi_oksigen ? round($record->saturasi_oksigen) : '--' }} <span class="text-xs font-bold text-gray-400">%</span></p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">SpO₂</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Symptoms & Notes Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Symptoms --}}
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                </div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Reported Symptoms</h3>
            </div>

            @if(!empty($symptomLines))
                <div class="space-y-4">
                    @foreach($symptomLines as $line)
                        <div class="flex items-start gap-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-teal-600 mt-2 shrink-0"></div>
                            <div>
                                <p class="text-sm font-black text-gray-900">{{ $line }}</p>
                                <p class="text-[11px] text-gray-400 font-medium leading-relaxed mt-0.5">Reported by patient during clinical observation.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">No specific symptoms documented.</p>
            @endif
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">clinical_notes</span>
                </div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Doctor's Diagnosis & Notes</h3>
            </div>

            <div class="bg-pink-50/50 rounded-3xl p-6 mb-6 border border-pink-100 relative overflow-hidden">
                <p class="text-[9px] font-black text-[#d81b60] uppercase tracking-widest mb-1.5">PRIMARY DIAGNOSIS</p>
                <p class="text-sm font-black text-gray-900 leading-tight">
                    {{ $record->diagnosa ?? 'Post-Viral Fatigue Syndrome' }}
                </p>
            </div>

            <p class="text-sm text-gray-600 font-medium leading-relaxed flex-1">
                {{ $doctorNotes ?: 'Patient reports symptoms consistent with recovery fatigue. Physical examination reveals stable vital signs and no acute abnormalities.' }}
            </p>

            <div class="mt-6 pt-6 border-t border-gray-50">
                <p class="text-[11px] text-gray-400 font-bold"><span class="text-gray-900 font-black">Recommendations:</span> Regular rest, hydration, and follow-up in 4 weeks.</p>
            </div>
        </div>
    </div>

    {{-- Digital Prescription --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-10 py-6 border-b border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-pink-50 text-[#d81b60] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">medication</span>
                </div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Digital Prescription</h3>
            </div>
            @if($resep)
                <span class="px-4 py-1.5 bg-gray-50 text-gray-400 rounded-full text-[10px] font-black border border-gray-100 uppercase tracking-widest">
                    Rx #{{ $resep->nomor_resep ?? str_pad($resep->id, 4, '0', STR_PAD_LEFT) }}
                </span>
            @endif
        </div>

        <div class="p-10">
            @if($obatList->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($obatList as $obat)
                        @php
                            $tag = 'DAILY';
                            $cls = 'bg-gray-100 text-gray-600';
                            if(str_contains(strtolower($obat->aturan_pakai), 'pagi')) { $tag = 'MORNING'; $cls='bg-orange-100 text-orange-700'; }
                            if(str_contains(strtolower($obat->aturan_pakai), 'malam')) { $tag = 'EVENING'; $cls='bg-indigo-100 text-indigo-700'; }
                            if(str_contains(strtolower($obat->aturan_pakai), 'sesuai kebutuhan')) { $tag = 'AS NEEDED'; $cls='bg-cyan-100 text-cyan-700'; }
                        @endphp
                        <div class="bg-white rounded-[2rem] p-6 border-2 border-gray-100 relative group hover:border-[#d81b60]/20 transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-base font-black text-gray-900">{{ $obat->nama_obat }}</h4>
                                <span class="px-2 py-0.5 {{ $cls }} rounded text-[8px] font-black uppercase tracking-tighter">{{ $tag }}</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-start gap-2 text-xs text-gray-500 font-bold">
                                    <span class="material-symbols-outlined text-[16px] text-gray-300">pill</span>
                                    <span>Dosage: <span class="text-gray-900">{{ $obat->dosis }} mg</span></span>
                                </div>
                                <div class="flex items-start gap-2 text-xs text-gray-500 font-bold">
                                    <span class="material-symbols-outlined text-[16px] text-gray-300">schedule</span>
                                    <span>Instructions: <span class="text-gray-900">{{ $obat->aturan_pakai }}</span></span>
                                </div>
                                <div class="flex items-start gap-2 text-xs text-gray-500 font-bold">
                                    <span class="material-symbols-outlined text-[16px] text-gray-300">timer</span>
                                    <span>Duration: <span class="text-gray-900">{{ $obat->jumlah }} Days</span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400 py-12">No prescription data available.</p>
            @endif
        </div>

        <div class="bg-teal-50 px-10 py-5 flex items-center justify-between border-t border-teal-100">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-teal-600 bg-white w-8 h-8 rounded-lg flex items-center justify-center text-[18px]">storefront</span>
                <p class="text-xs font-bold text-gray-500">Sent to: <span class="text-teal-700 font-black">Apotek Poliklinik Puskesmas</span></p>
            </div>
            <button class="text-[10px] font-black text-teal-700 uppercase tracking-widest hover:underline transition">Change Pharmacy</button>
        </div>
    </div>
</div>
@endsection
