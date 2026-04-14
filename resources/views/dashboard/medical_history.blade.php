@extends('layouts.pasien')
@section('title', 'Riwayat Medis')

@section('content')
<div class="flex flex-col gap-6">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-black text-gray-900">Medical History</h2>
        <p class="text-xs text-gray-400 font-medium mt-1">Riwayat seluruh pemeriksaan & kunjungan ke Puskesmas Jagapura.</p>
    </div>

    @if($histories->isEmpty())
    <div class="bg-white rounded-[2rem] p-16 text-center border border-dashed border-pink-100 shadow-sm">
        <span class="material-symbols-outlined text-6xl text-pink-100 block mb-4">history_edu</span>
        <h3 class="text-base font-black text-gray-700 mb-1">Belum ada riwayat medis</h3>
        <p class="text-xs text-gray-400 font-medium">Riwayat pemeriksaan akan tampil di sini setelah kamu selesai diperiksa dokter.</p>
    </div>
    @else
    <div class="flex flex-col gap-4">
        @foreach($histories as $h)
        @php
            $vitals = array_filter([
                'detak_jantung' => $h->detak_jantung,
                'suhu_tubuh'    => $h->suhu_tubuh,
                'tekanan_darah' => $h->tekanan_darah,
            ]);
        @endphp
        <a href="{{ route('pasien.medical-history.detail', $h->id) }}"
           class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-pink-200 hover:shadow-md transition-all duration-300 block group">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                {{-- Date & Clinic --}}
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-pink-50 flex flex-col items-center justify-center shrink-0 group-hover:bg-pink-100 transition-colors">
                        <p class="text-lg font-black text-[#d81b60] leading-none">{{ $h->tanggal->format('d') }}</p>
                        <p class="text-[9px] font-black text-[#d81b60] uppercase tracking-widest">{{ $h->tanggal->format('M Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">{{ $h->diagnosa ?? 'Pemeriksaan Umum' }}</h3>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span class="flex items-center gap-1 text-[10px] font-bold text-gray-500">
                                <span class="material-symbols-outlined text-[13px]">local_hospital</span>
                                {{ $h->poli->nama_poli ?? '-' }}
                            </span>
                            <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-gray-500">
                                <span class="material-symbols-outlined text-[13px]">stethoscope</span>
                                Dr. {{ $h->dokter->name ?? '-' }}
                            </span>
                            <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-gray-500">
                                <span class="material-symbols-outlined text-[13px]">schedule</span>
                                {{ $h->waktu_check_in ? \Carbon\Carbon::parse($h->waktu_check_in)->format('H:i') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Vitals & Status --}}
                <div class="flex items-center gap-4 flex-wrap">
                    @if($h->detak_jantung)
                    <div class="flex items-center gap-1.5 bg-red-50 px-3 py-2 rounded-2xl border border-red-100">
                        <span class="material-symbols-outlined text-red-500 text-[15px]" style="font-variation-settings:'FILL' 1">favorite</span>
                        <div>
                            <p class="text-sm font-black text-gray-900 leading-none">{{ number_format($h->detak_jantung, 0) }} <span class="text-[9px] font-bold text-gray-400">bpm</span></p>
                            <p class="text-[8px] text-gray-400 font-bold uppercase">Heart Rate</p>
                        </div>
                    </div>
                    @endif
                    @if($h->suhu_tubuh)
                    <div class="flex items-center gap-1.5 bg-orange-50 px-3 py-2 rounded-2xl border border-orange-100">
                        <span class="material-symbols-outlined text-orange-500 text-[15px]">thermostat</span>
                        <div>
                            <p class="text-sm font-black text-gray-900 leading-none">{{ number_format($h->suhu_tubuh, 1) }}<span class="text-[9px] font-bold text-gray-400">°C</span></p>
                            <p class="text-[8px] text-gray-400 font-bold uppercase">Temperature</p>
                        </div>
                    </div>
                    @endif
                    @if($h->tekanan_darah)
                    <div class="flex items-center gap-1.5 bg-teal-50 px-3 py-2 rounded-2xl border border-teal-100">
                        <span class="material-symbols-outlined text-teal-500 text-[15px]">monitor_heart</span>
                        <div>
                            <p class="text-sm font-black text-gray-900 leading-none">{{ $h->tekanan_darah }}</p>
                            <p class="text-[8px] text-gray-400 font-bold uppercase">Blood Pressure</p>
                        </div>
                    </div>
                    @endif

                    <span class="px-3 py-1.5 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-100">
                        Completed
                    </span>
                    <span class="material-symbols-outlined text-gray-200 group-hover:text-pink-400 transition text-[22px]">chevron_right</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($histories->hasPages())
    <div class="flex justify-center gap-2">
        @if(!$histories->onFirstPage())
        <a href="{{ $histories->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-500 hover:bg-pink-50 hover:text-[#d81b60] transition">
            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
        </a>
        @endif
        @foreach($histories->getUrlRange(1, $histories->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-black {{ $page == $histories->currentPage() ? 'bg-[#d81b60] text-white shadow-lg shadow-pink-200' : 'bg-white border border-gray-100 text-gray-500 hover:bg-pink-50' }} transition">{{ $page }}</a>
        @endforeach
        @if($histories->hasMorePages())
        <a href="{{ $histories->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-500 hover:bg-pink-50 hover:text-[#d81b60] transition">
            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
        </a>
        @endif
    </div>
    @endif
    @endif
</div>
@endsection
