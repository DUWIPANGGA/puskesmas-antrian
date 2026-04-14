@extends('layouts.apoteker')
@section('title', 'Completed Orders')

@section('content')
<div class="flex flex-col gap-6 h-full">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <span class="inline-block px-3 py-1 bg-pink-50 text-[#d81b60] text-[9px] font-black uppercase tracking-widest rounded-full border border-pink-100 mb-3">PHARMACY LOGS</span>
            <h2 class="text-3xl font-black text-gray-900">Completed Orders</h2>
            <p class="text-xs text-gray-400 font-medium mt-1">Manage and track prescriptions ready for pickup or already collected.</p>
        </div>
        {{-- Filter Tabs --}}
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm">
            <a href="{{ route('apoteker.completed', ['filter' => 'today', 'search' => $search]) }}"
               class="px-4 py-2 text-xs font-black rounded-xl transition {{ $filter === 'today' ? 'bg-[#d81b60] text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">Today</a>
            <a href="{{ route('apoteker.completed', ['filter' => 'week', 'search' => $search]) }}"
               class="px-4 py-2 text-xs font-black rounded-xl transition {{ $filter === 'week' ? 'bg-[#d81b60] text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">Weekly</a>
            <div class="flex items-center gap-1.5 px-3 py-2 text-xs font-black text-gray-500 cursor-pointer hover:bg-gray-50 rounded-xl">
                <span class="material-symbols-outlined text-[15px]">tune</span> Filters
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-5 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
        <span class="material-symbols-outlined text-[16px]">check_circle</span>{{ session('success') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-teal-50 rounded-2xl p-5 border border-teal-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest">Ready for Pickup</p>
                <p class="text-3xl font-black text-gray-900 leading-none mt-1">{{ $readyCount }}</p>
            </div>
        </div>
        <div class="bg-[#d6faf4] rounded-2xl p-5 border border-teal-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-white text-[#2d7a6e] flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[20px]">task_alt</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#2d7a6e] uppercase tracking-widest">Collected Today</p>
                <p class="text-3xl font-black text-gray-900 leading-none mt-1">{{ $takenCount }}</p>
            </div>
        </div>
        <div class="bg-pink-50 rounded-2xl p-5 border border-pink-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-white text-[#d81b60] flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[20px]">timer</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#d81b60] uppercase tracking-widest">Avg. Processing Time</p>
                <p class="text-3xl font-black text-gray-900 leading-none mt-1">{{ $avgMinutes }}<span class="text-base font-bold text-gray-400 ml-1">min</span></p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden flex-1 flex flex-col">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[9px] font-black text-[#d81b60] uppercase tracking-widest">Prescription ID</th>
                        <th class="px-8 py-4 text-[9px] font-black text-[#d81b60] uppercase tracking-widest">Patient Name</th>
                        <th class="px-8 py-4 text-[9px] font-black text-[#d81b60] uppercase tracking-widest">Time Completed</th>
                        <th class="px-8 py-4 text-[9px] font-black text-[#d81b60] uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[9px] font-black text-[#d81b60] uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completed as $r)
                    @php
                        $initials = strtoupper(substr($r->pasien->name ?? 'P', 0, 2));
                        $colors   = ['pink', 'teal', 'blue', 'purple', 'emerald'];
                        $c        = $colors[$loop->index % 5];
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-[#d81b60]">#{{ $r->nomor_resep ?? 'RX-' . str_pad($r->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-{{ $c }}-100 text-{{ $c }}-600 font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $initials }}
                                </div>
                                <span class="font-black text-gray-900 uppercase text-sm">{{ $r->pasien->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-gray-500">
                                {{ $r->selesai_at?->isToday() ? $r->selesai_at->format('h:i A') : $r->selesai_at?->format('d M, H:i') ?? '-' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            @if($r->status === 'siap_ambil')
                            <span class="px-3 py-1.5 bg-teal-50 text-teal-600 rounded-full text-[9px] font-black border border-teal-100 flex items-center gap-1.5 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span> Ready for Pickup
                            </span>
                            @else
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded-full text-[9px] font-black flex items-center gap-1.5 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Collected
                            </span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                @if($r->status === 'siap_ambil')
                                <form action="{{ route('apoteker.resep.pickup', $r->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-[#d81b60] text-white rounded-xl text-[9px] font-black hover:bg-[#880e4f] transition">
                                        Konfirmasi Ambil
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('apoteker.resep.etiket', $r->id) }}" target="_blank"
                                   class="px-3 py-1.5 border border-gray-200 text-gray-500 rounded-xl text-[9px] font-black hover:bg-gray-50 transition flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">print</span> Etiket
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-3">receipt_long</span>
                            <p class="text-sm font-bold text-gray-400">Tidak ada resep selesai pada periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($completed->hasPages())
        <div class="px-8 py-4 border-t border-gray-50 flex items-center justify-between">
            <p class="text-[10px] font-bold text-gray-400">Showing {{ $completed->firstItem() }}–{{ $completed->lastItem() }} of {{ $completed->total() }} completed prescriptions</p>
            <div class="flex items-center gap-2">
                @if($completed->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-gray-200"><span class="material-symbols-outlined text-[18px]">chevron_left</span></span>
                @else
                <a href="{{ $completed->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 transition"><span class="material-symbols-outlined text-[18px]">chevron_left</span></a>
                @endif

                @foreach($completed->getUrlRange(1, $completed->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-full text-xs font-black {{ $page == $completed->currentPage() ? 'bg-[#d81b60] text-white shadow' : 'text-gray-400 hover:bg-gray-100' }} transition">{{ $page }}</a>
                @endforeach

                @if($completed->hasMorePages())
                <a href="{{ $completed->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 transition"><span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-gray-200"><span class="material-symbols-outlined text-[18px]">chevron_right</span></span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
