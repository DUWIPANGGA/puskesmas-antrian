@extends('layouts.dokter')

@section('title', 'History Pasien')

@section('content')
<div class="max-w-[1200px] mx-auto flex flex-col gap-6">

    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-[#1b4353]">History Pemeriksaan Pasien</h2>
            <p class="text-sm font-bold text-gray-500 mt-1">Daftar seluruh pasien yang pernah dilayani di poli ini</p>
        </div>
        
        <form action="{{ route('dokter.history') }}" method="GET" class="w-full md:w-96 relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pasien atau ID..." class="w-full bg-white border border-gray-200 rounded-full px-6 py-3.5 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#d81b60]/20 focus:border-[#d81b60] transition shadow-sm pr-12">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#d81b60]">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
    </div>

    {{-- History Table --}}
    <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-sm flex flex-col min-w-0">
        <div class="overflow-x-auto w-full custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-5 px-8 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Tanggal & Jam</th>
                        <th class="py-5 px-4 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Pasien</th>
                        <th class="py-5 px-4 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Dokter Pemeriksa</th>
                        <th class="py-5 px-4 text-[11px] font-black tracking-widest text-[#00897b] uppercase">Diagnosis</th>
                        <th class="py-5 px-8 text-[11px] font-black tracking-widest text-[#00897b] uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr class="group border-b border-gray-50 hover:bg-gray-50/30 transition cursor-pointer" onclick="document.getElementById('detail-history-{{ $item->id }}').classList.toggle('hidden')">
                        <td class="py-5 px-8 whitespace-nowrap">
                            <p class="text-[13px] font-black text-gray-900">{{ $item->tanggal->format('d M Y') }}</p>
                            <p class="text-[11px] font-bold text-gray-400">{{ $item->selesai_at ? $item->selesai_at->format('H:i') : '-' }}</p>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-pink-100 text-[#d81b60] flex items-center justify-center font-black text-xs shrink-0">
                                    {{ substr($item->pasien->name ?? 'U', 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-[14px] font-black text-gray-900">{{ $item->pasien->name ?? 'Pasien' }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold tracking-wider mt-0.5">PID: #{{ 90000 + $item->pasien_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <p class="text-[13px] font-bold text-[#1b4353]">Dr. {{ $item->laporanKunjungan->dokter->name ?? 'Dokter' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $item->poli->nama_poli ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-4">
                            <span class="bg-[#e1f5fe]/70 text-[#0288d1] text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full inline-block">
                                {{ $item->laporanKunjungan->diagnosa ?? 'General Checkup' }}
                            </span>
                        </td>
                        <td class="py-5 px-8 text-right">
                            <button class="text-[11px] font-black text-[#d81b60] hover:underline flex items-center gap-1 ml-auto">
                                Detail <span class="material-symbols-outlined text-[16px]">expand_more</span>
                            </button>
                        </td>
                    </tr>
                    
                    {{-- Detail Row --}}
                    <tr id="detail-history-{{ $item->id }}" class="hidden bg-gray-50/50">
                        <td colspan="5" class="px-8 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                                <div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-[#e0f2f1] text-[#00897b] flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">clinical_notes</span>
                                        </div>
                                        <h5 class="text-[11px] font-black tracking-widest text-[#00897b] uppercase">Hasil Pemeriksaan & Catatan</h5>
                                    </div>
                                    <p class="text-[13px] font-medium text-gray-700 whitespace-pre-line leading-relaxed bg-gray-50/50 p-4 rounded-xl">
                                        {{ $item->laporanKunjungan->catatan ?? 'Tidak ada catatan pemeriksaan fisik.' }}
                                    </p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-[#e1f5fe] text-[#0288d1] flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">prescriptions</span>
                                        </div>
                                        <h5 class="text-[11px] font-black tracking-widest text-[#0288d1] uppercase">Resep Obat yang Diberikan</h5>
                                    </div>
                                    @if($item->resep && $item->resep->obat)
                                        <div class="space-y-2">
                                            @foreach(explode("\n", $item->resep->obat) as $obatItem)
                                                @if(trim($obatItem))
                                                <div class="flex items-center gap-3 bg-teal-50/50 p-3 rounded-xl border border-teal-100/50">
                                                    <span class="material-symbols-outlined text-teal-600 text-[18px]">medication</span>
                                                    <span class="text-[13px] font-bold text-gray-800">{{ trim($obatItem) }}</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-[13px] font-medium text-gray-400 italic bg-gray-50/50 p-4 rounded-xl">Tidak ada resep obat diberikan untuk kunjungan ini.</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">person_search</span>
                                <h3 class="text-lg font-black text-gray-400">Data history tidak ditemukan</h3>
                                <p class="text-sm font-bold text-gray-300">Belum ada pasien yang dilayani atau keyword pencarian tidak cocok.</p>
                                @if($search)
                                    <a href="{{ route('dokter.history') }}" class="mt-4 text-[#d81b60] hover:underline font-bold text-sm">Reset Pencarian</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($history->hasPages())
        <div class="px-8 py-6 border-t border-gray-50 flex justify-between items-center">
            <p class="text-xs font-bold text-gray-400">Menampilkan {{ $history->firstItem() }} - {{ $history->lastItem() }} dari {{ $history->total() }} record</p>
            <div class="flex gap-2">
                {{ $history->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 99px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #bdbdbd; }
    
    /* Pagination Overrides */
    .pagination { display: flex; gap: 4px; }
    .pagination li span, .pagination li a { 
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; 
        border-radius: 50%; font-size: 13px; font-weight: 800; border: 1px solid #f1f1f1;
        transition: all 0.2s ease;
    }
    .active span { background: #d81b60; color: white; border-color: #d81b60; }
    .pagination li a:hover { border-color: #d81b60; color: #d81b60; }
</style>
@endsection
