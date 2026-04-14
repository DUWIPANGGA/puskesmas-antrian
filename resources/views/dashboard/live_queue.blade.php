@extends('layouts.pasien')

@section('title', 'Live Queue')

@section('content')
<div class="max-w-[1200px] mx-auto">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Live Queue (Antrian Berjalan)</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau antrian yang sedang berlangsung di seluruh poli saat ini.</p>
        </div>
        <div class="text-right">
            <p class="text-[11px] text-gray-400 flex items-center justify-end gap-1 mb-1">
                <span class="material-symbols-outlined text-[14px]">sync</span>
                Otomatis refresh tiap 30 detik
            </p>
            <button onclick="window.location.reload()" class="text-xs text-[#d81b60] font-bold hover:underline">
                Refresh Sekarang
            </button>
        </div>
    </div>

    {{-- Grid Poli --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($polis as $poli)
            @php
                $hasActive = $poli->current_queue !== null;
            @endphp
            <div class="bg-white rounded-3xl shadow-sm border border-pink-50 flex flex-col relative overflow-hidden transition-all hover:shadow-md
                {{ $hasActive ? 'ring-1 ring-pink-200' : '' }}">

                {{-- Header Poli --}}
                <div class="p-6 pb-4 border-b border-gray-50 flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $hasActive ? 'bg-[#fce4ec] text-[#d81b60]' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center shrink-0">
                            <i class="{{ $poli->icon ?? 'fa-solid fa-hospital' }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-900 leading-tight">{{ $poli->nama_poli }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $poli->kode_poli }}</p>
                        </div>
                    </div>
                </div>

                {{-- Display Angka Antrian --}}
                <div class="p-6 flex flex-col items-center justify-center text-center bg-gradient-to-b from-transparent to-gray-50/50 min-h-[160px]">
                    <p class="text-[10px] uppercase font-black tracking-[0.2em] {{ $hasActive ? 'text-[#d81b60]' : 'text-gray-400' }} mb-2">
                        SEKARANG MELAYANI
                    </p>
                    <div class="text-6xl font-black {{ $hasActive ? 'text-gray-900' : 'text-gray-200' }} tracking-tight mb-2 live-main-number" data-poli-id="{{ $poli->id }}">
                        {{ $hasActive ? $poli->current_queue->nomor_antrian : '—' }}
                    </div>
                    @if($hasActive)
                        <p class="text-[11px] text-gray-500 font-medium mb-1">
                            Dipanggil pada {{ $poli->current_queue->dipanggil_at?->format('H:i') ?? '-' }}
                        </p>
                        @if($poli->current_queue->jadwalDokter)
                            <div class="px-3 py-1 bg-blue-50 border border-blue-100 rounded-full mt-2 inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[12px] text-[#0288d1]">stethoscope</span>
                                <span class="text-[10px] font-black text-[#0288d1] tracking-wide">Dr. {{ $poli->current_queue->jadwalDokter->dokter->name }}</span>
                            </div>
                        @else
                            @php 
                                $todayJadwals = $poli->jadwalDokter->filter(function($j) {
                                    return $j->hari == now()->locale('id')->dayName || $j->hari == now()->englishDayOfWeek;
                                });
                            @endphp
                            @if($todayJadwals->count() > 0)
                                <div class="px-3 py-1 bg-blue-50 border border-blue-100 rounded-full mt-2 inline-flex items-center gap-1.5 shadow-sm">
                                    <span class="material-symbols-outlined text-[12px] text-[#0288d1]">stethoscope</span>
                                    <span class="text-[10px] font-black text-[#0288d1] tracking-wide">Dr. {{ $todayJadwals->first()->dokter->name }}</span>
                                </div>
                            @endif
                        @endif
                    @else
                        <p class="text-[11px] text-gray-400 font-medium mb-1">Belum ada antrian</p>
                        @php 
                            $todayJadwals = $poli->jadwalDokter->filter(function($j) {
                                return $j->hari == now()->locale('id')->dayName || $j->hari == now()->englishDayOfWeek;
                            });
                        @endphp
                        @if($todayJadwals->count() > 0)
                            <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full mt-2 inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[12px] text-gray-400">stethoscope</span>
                                <span class="text-[10px] font-black text-gray-500 tracking-wide">Dr. {{ $todayJadwals->first()->dokter->name }}</span>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Footer Info --}}
                <div class="bg-gray-50/80 p-4 px-6 flex justify-between items-center text-[11px] font-bold uppercase tracking-wider">
                    <span class="text-gray-500">Antrian Menunggu</span>
                    <span class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-full shadow-sm">
                        {{ $poli->remaining_queue }} orang
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border-2 border-dashed border-pink-100">
                <span class="material-symbols-outlined text-6xl text-pink-200 mb-4">home_health</span>
                <h3 class="text-xl font-black text-gray-900 mb-2">Belum Ada Klinik Aktif</h3>
                <p class="text-sm text-gray-500">Saat ini belum ada data poli yang tersedia.</p>
            </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
    // Fitur Auto-Refresh tiap 30 Detik
    setTimeout(() => {
        window.location.reload();
    }, 30000);
</script>
@endpush

@endsection
