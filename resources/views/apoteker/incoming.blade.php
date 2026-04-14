@extends('layouts.apoteker')
@section('title', 'Incoming Prescriptions')

@section('content')
<div class="flex gap-6 h-full">
    {{-- LEFT: LIST --}}
    <div class="w-[380px] shrink-0 flex flex-col gap-4">
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-black text-gray-900">Incoming Prescriptions</h2>
                <p class="text-xs text-gray-400 font-medium mt-0.5">Manage and process active pharmaceutical requests.</p>
            </div>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('apoteker.incoming') }}" class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-[18px]">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search Patient..."
                   class="w-full pl-11 pr-4 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-pink-200 focus:border-pink-300 outline-none shadow-sm">
        </form>

        @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-600 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>{{ session('success') }}
        </div>
        @endif

        {{-- Incoming List --}}
        <div class="flex flex-col gap-2 overflow-y-auto flex-1 pr-1">
            @forelse($incoming as $r)
            @php
                $isSelected = $selectedResep && $selectedResep->id === $r->id;
                $initials = strtoupper(substr($r->pasien->name ?? 'P', 0, 2));
                $statusColor = match($r->status) {
                    'pending'    => 'bg-orange-100 text-orange-600',
                    'diproses'   => 'bg-blue-100 text-blue-600',
                    default      => 'bg-green-100 text-green-600'
                };
                $statusLabel = match($r->status) {
                    'pending'    => 'PENDING',
                    'diproses'   => 'IN PROGRESS',
                    default      => 'READY'
                };
            @endphp
            <a href="{{ route('apoteker.incoming', ['selected' => $r->id, 'search' => $search]) }}"
               class="flex items-center gap-4 p-4 bg-white rounded-2xl border {{ $isSelected ? 'border-pink-300 shadow-lg shadow-pink-100' : 'border-gray-100' }} hover:border-pink-200 transition group cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 font-black text-sm flex items-center justify-center shrink-0">
                    {{ $initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-900 text-sm truncate">{{ $r->pasien->name ?? 'Unknown' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium truncate">{{ $r->dokter->name ?? '-' }} &bull; {{ $r->antrian->poli->nama_poli ?? 'General' }}</p>
                </div>
                <div class="flex flex-col items-end gap-2 shrink-0">
                    <span class="text-[10px] font-bold text-gray-400">{{ $r->created_at->format('h:i A') }}</span>
                    <span class="px-2 py-0.5 {{ $statusColor }} rounded-full text-[9px] font-black uppercase tracking-widest">{{ $statusLabel }}</span>
                </div>
                <span class="material-symbols-outlined text-gray-200 group-hover:text-pink-400 transition text-[20px]">chevron_right</span>
            </a>
            @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">inbox</span>
                <p class="text-sm font-bold text-gray-400">Tidak ada resep masuk</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: DETAIL PANEL --}}
    <div class="flex-1 animate-fadein">
        @if($selectedResep)
        @php
            $patient = $selectedResep->pasien;
            $initials = strtoupper(substr($patient->name ?? 'P', 0, 2));
        @endphp
        <div class="bg-white rounded-[2rem] shadow-sm border border-pink-50 h-full flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900">Prescription Details</h3>
                <span class="bg-pink-50 text-[#d81b60] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-pink-100">
                    ID: #{{ $selectedResep->nomor_resep ?? 'DRAFT-' . $selectedResep->id }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">

                {{-- Patient Card --}}
                <div class="flex items-center gap-4 p-4 bg-pink-50/40 rounded-2xl border border-pink-100">
                    <div class="w-12 h-12 rounded-full bg-[#d81b60] text-white font-black text-lg flex items-center justify-center shrink-0">
                        {{ $initials }}
                    </div>
                    <div>
                        <p class="font-black text-gray-900 text-base">{{ $patient->name ?? 'Unknown' }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">
                            Poli: {{ $selectedResep->antrian->poli->nama_poli ?? '-' }}
                            &bull; Dr. {{ $selectedResep->dokter->name ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- Diagnosa --}}
                @if($selectedResep->diagnosa)
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Diagnosa</p>
                    <p class="text-sm text-gray-700 font-medium bg-gray-50 rounded-xl px-4 py-3">{{ $selectedResep->diagnosa }}</p>
                </div>
                @endif

                {{-- Medicines --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-[#d81b60] text-[18px]">medication</span>
                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Medicines</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        @foreach($selectedResep->detailResep as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-sm font-black text-[#d81b60]">{{ $item->nama_obat }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">
                                    Instruksi: {{ $item->aturan_pakai }}
                                    @if($item->keterangan) &bull; {{ $item->keterangan }}@endif
                                </p>
                            </div>
                            <span class="text-[10px] font-black text-gray-500 bg-white px-2 py-1 rounded-lg border">Qty: {{ $item->jumlah }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Catatan Apoteker --}}
                <form action="{{ route('apoteker.resep.catatan', $selectedResep->id) }}" method="POST">
                    @csrf
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-[18px] text-gray-400">edit_note</span>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Apoteker</p>
                    </div>
                    <textarea name="catatan_apoteker" rows="3" placeholder="Tambahkan catatan untuk asisten atau pasien..."
                              class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-pink-200 focus:border-pink-300 outline-none resize-none">{{ $selectedResep->catatan_apoteker }}</textarea>
                    <button type="submit" class="mt-2 text-[10px] font-black text-[#d81b60] hover:underline">Simpan Catatan</button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="p-6 border-t border-gray-50 flex gap-3">
                @if($selectedResep->status === 'pending')
                <form action="{{ route('apoteker.resep.hold', $selectedResep->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex-1 border-2 border-gray-200 text-gray-600 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest hover:border-gray-300 transition">
                        Hold Ticket
                    </button>
                </form>
                <form action="{{ route('apoteker.resep.start', $selectedResep->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-[#d81b60] text-white py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-pink-200 hover:bg-[#880e4f] transition">
                        Mulai Proses
                    </button>
                </form>
                @elseif($selectedResep->status === 'diproses')
                <a href="{{ route('apoteker.in-process', ['selected' => $selectedResep->id]) }}"
                   class="flex-1 bg-blue-500 text-white py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest text-center hover:bg-blue-600 transition">
                    Lihat In Process
                </a>
                @endif
            </div>
        </div>
        @else
        <div class="h-full flex flex-col items-center justify-center text-center bg-white rounded-[2rem] border border-dashed border-pink-100">
            <span class="material-symbols-outlined text-6xl text-pink-100 mb-4">prescriptions</span>
            <p class="text-sm font-black text-gray-400">Pilih resep untuk lihat detail</p>
        </div>
        @endif
    </div>
</div>
@endsection
