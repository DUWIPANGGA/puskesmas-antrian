@extends('layouts.admin')

@section('title', 'Visit Reports')
@section('page-title', 'Visit Reports')

@section('content')
<div class="flex flex-col gap-8">
    {{-- Filter Row --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50 flex flex-wrap items-center gap-6">
        <form action="{{ route('admin.visit-reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full md:w-auto">
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black text-pink-500 uppercase tracking-widest px-1">Pilih Tanggal</label>
                <div class="relative">
                    <input type="date" name="date" value="{{ $date }}" 
                           onchange="this.form.submit()"
                           class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-black text-pink-500 uppercase tracking-widest px-1">Poli / Unit</label>
                <select name="poli_id" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition cursor-pointer">
                    <option value="">Semua Poli</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id }}" {{ $poliId == $p->id ? 'selected' : '' }}>{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end h-full">
                <a href="{{ route('admin.visit-reports.index') }}" class="bg-gray-100 p-2.5 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition" title="Reset Filter">
                    <span class="material-symbols-outlined text-[18px] leading-none">refresh</span>
                </a>
            </div>
        </form>

        <div class="ml-auto flex items-center gap-3">
            <button class="bg-[#2d5a52] text-white px-6 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-teal-900/10 hover:scale-[1.02] transition">
                <span class="material-symbols-outlined text-[18px]">download_for_offline</span>
                Export Report
            </button>
        </div>
    </div>

    {{-- Top Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Stat 1 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-[#d81b60]">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Kunjungan</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900">{{ number_format($totalVisits) }}</span>
                <span class="text-xs font-bold text-gray-400 uppercase">Pasien</span>
            </div>
        </div>
        {{-- Stat 2 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-purple-600">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rata-rata Tunggu</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900">{{ $avgWaitTime }}</span>
                <span class="text-xs font-bold text-gray-400 uppercase">Menit</span>
            </div>
        </div>
        {{-- Stat 3 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-blue-500">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Efisiensi Antrean</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900">{{ $efficiency }}</span>
                <span class="text-xs font-bold text-gray-400 uppercase">%</span>
            </div>
        </div>
        {{-- Stat 4 --}}
        <div class="bg-gradient-to-br from-[#d81b60] to-[#ad1457] rounded-2xl p-6 shadow-sm text-white relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <span class="material-symbols-outlined text-8xl">analytics</span>
            </div>
            <p class="text-[10px] font-black text-pink-200 uppercase tracking-widest mb-2">Health Pulse</p>
            <h3 class="text-xl font-black leading-tight">System Status<br>Balanced</h3>
        </div>
    </div>

    {{-- Clinic Quotas Section --}}
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-black text-gray-900">Kapasitas Poliklinik</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Status ketersediaan kuota unit pada {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($clinicStats as $stat)
            @php
                $percentage = $stat['quota'] > 0 ? min(100, round(($stat['filled'] / $stat['quota']) * 100)) : 0;
                $isFull = $stat['is_full'];
            @endphp
            <div class="bg-white rounded-[1.5rem] p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:border-pink-200 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-gray-50 text-gray-400 group-hover:bg-pink-50 group-hover:text-pink-500 flex items-center justify-center transition-colors">
                        <i class="{{ $stat['poli']->icon ?? 'fa-solid fa-hospital' }} text-sm"></i>
                    </div>
                    <div class="px-2 py-1 rounded-lg text-[9px] font-black {{ $isFull ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }} uppercase tracking-widest">
                        {{ $isFull ? 'FULL' : 'READY' }}
                    </div>
                </div>
                <h3 class="text-sm font-black text-gray-900 mb-1">{{ $stat['poli']->nama_poli }}</h3>
                <p class="text-[10px] font-bold text-gray-400 mb-6 uppercase tracking-tight">{{ $stat['poli']->kode_poli }}</p>
                
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kunjungan</span>
                    <span class="text-xs font-black {{ $isFull ? 'text-red-500' : 'text-gray-900' }}">{{ $stat['filled'] }} / {{ $stat['quota'] }}</span>
                </div>
                <div class="h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                    <div class="h-full {{ $isFull ? 'bg-red-500' : 'bg-pink-500' }} rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Visit Log & Performance --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-pink-50 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <div>
                <h2 class="text-lg font-black text-gray-900">Log Aktivitas Kunjungan</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Detail setiap interaksi pasien di poliklinik</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Jam</th>
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Nomor</th>
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Pasien</th>
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Poliklinik</th>
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Durasi</th>
                        <th class="px-8 py-4 text-[10px] uppercase font-black text-gray-500 tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($visitLogs as $log)
                    @php
                        $duration = '--';
                        if($log->check_in_at && ($log->dipanggil_at || $log->selesai_at)) {
                            $end = $log->selesai_at ?? now();
                            $duration = $log->check_in_at->diffInMinutes($end) . 'm';
                        }
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-black text-gray-400 group-hover:text-pink-500 transition-colors">{{ $log->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-mono font-bold bg-gray-100 text-gray-700 px-2 py-1 rounded-lg">{{ $log->nomor_antrian }}</span>
                        </td>
                        <td class="px-8 py-5 text-gray-900 font-black uppercase">{{ $log->pasien->name ?? 'Unknown' }}</td>
                        <td class="px-8 py-5">
                            <span class="inline-block px-3 py-1 bg-pink-50 text-pink-600 text-[10px] font-black rounded-full uppercase tracking-tighter">{{ $log->poli->nama_poli ?? 'General' }}</span>
                        </td>
                        <td class="px-8 py-5 font-bold text-gray-600">{{ $duration }}</td>
                        <td class="px-8 py-5">
                            @if($log->status == 'selesai')
                                <span class="bg-green-50 text-green-600 px-3 py-1.5 rounded-full text-[9px] font-black inline-flex items-center gap-1.5 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Selesai
                                </span>
                            @elseif($log->status == 'batal')
                                <span class="bg-red-50 text-red-600 px-3 py-1.5 rounded-full text-[9px] font-black inline-flex items-center gap-1.5 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Batal
                                </span>
                            @elseif($log->status == 'dipanggil')
                                <span class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full text-[9px] font-black inline-flex items-center gap-1.5 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Sedang Diperiksa
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-400 px-3 py-1.5 rounded-full text-[9px] font-black inline-flex items-center gap-1.5 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Menunggu
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Tidak ada data kunjungan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visitLogs->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $visitLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
