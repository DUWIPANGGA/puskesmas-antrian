@extends('layouts.admin')

@section('title', 'Patient Check-ins')
@section('page-title', 'Patient Check-ins')

@section('content')
<div class="flex flex-col gap-8">
    {{-- Search & Header --}}
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-pink-50 flex flex-wrap items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Patient Arrival Control</h2>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Konfirmasi kedatangan pasien di Puskesmas</p>
        </div>
        
        <form action="{{ route('admin.patient-checkins.index') }}" method="GET" class="relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-pink-500 transition-colors">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau nomor antrean..." 
                   class="pl-12 pr-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl text-xs font-bold focus:ring-0 focus:border-pink-300 outline-none w-80 transition-all">
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in">
        <span class="material-symbols-outlined">verified</span>
        <span class="text-xs font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Section: Pending Check-in --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-orange-50 overflow-hidden flex flex-col h-full">
            <div class="p-8 border-b border-orange-50 bg-orange-50/20 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Belum Check-in</h3>
                    <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mt-0.5">{{ $pending->count() }} Pasien Menunggu</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">hourglass_empty</span>
                </div>
            </div>

            <div class="flex-1 overflow-x-auto min-h-[400px]">
                <table class="w-full text-left">
                    <tbody class="text-sm">
                        @forelse($pending as $p)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 font-black text-xs group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                        {{ $p->nomor_antrian }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 uppercase leading-none">{{ $p->pasien->name ?? 'Unknown' }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">{{ $p->poli->nama_poli }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.patient-checkins.update', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-500 hover:scale-105 transition shadow-lg shadow-gray-200">
                                        Check-In
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="px-8 py-20 text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">check_circle</span>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Semua pasien sudah<br>check-in hari ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section: Already Checked-in --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-teal-50 overflow-hidden flex flex-col h-full">
            <div class="p-8 border-b border-teal-50 bg-teal-50/20 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Sudah Check-in</h3>
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">{{ $checkedIn->count() }} Pasien Aktif</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                </div>
            </div>

            <div class="flex-1 overflow-x-auto min-h-[400px]">
                <table class="w-full text-left">
                    <tbody class="text-sm">
                        @forelse($checkedIn as $p)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xs">
                                        {{ $p->nomor_antrian }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 uppercase leading-none">{{ $p->pasien->name ?? 'Unknown' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $p->poli->nama_poli }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                            <span class="text-[9px] font-black text-teal-500 uppercase tracking-tighter">{{ $p->check_in_at ? $p->check_in_at->format('H:i') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($p->status == 'dipanggil')
                                    <span class="px-3 py-1 bg-pink-100 text-pink-600 rounded-lg text-[9px] font-black uppercase tracking-widest">DI PERIKSA</span>
                                @elseif($p->status == 'selesai')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest">SELESAI</span>
                                @else
                                    <span class="px-3 py-1 bg-teal-100 text-teal-600 rounded-lg text-[9px] font-black uppercase tracking-widest">DI ANTREAN</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="px-8 py-20 text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">person_off</span>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Belum ada pasien<br>yang check-in</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
</style>
@endsection
