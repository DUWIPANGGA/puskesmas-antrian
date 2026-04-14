@extends('layouts.apoteker')
@section('title', 'In Process')

@section('content')
<div class="flex gap-6 h-full">
    {{-- LEFT: PROCESSING LIST --}}
    <div class="w-[400px] shrink-0 flex flex-col gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Resep Sedang Diproses</h2>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Monitoring dan penyelesaian penyiapan obat.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-600 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>{{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col gap-3 overflow-y-auto flex-1 pr-1">
            @forelse($processing as $r)
            @php
                $isSelected = $selectedResep && $selectedResep->id === $r->id;
                $progress = $r->progressPersen();
                $stage = $progress < 50 ? ['label' => 'Menyiapkan Obat', 'color' => 'text-pink-600 bg-pink-50'] 
                       : ($progress < 100 ? ['label' => 'Pengecekan Akhir', 'color' => 'text-teal-600 bg-teal-50'] 
                       : ['label' => 'Siap Selesai', 'color' => 'text-green-600 bg-green-50']);
            @endphp
            <a href="{{ route('apoteker.in-process', ['selected' => $r->id]) }}"
               class="p-5 bg-white rounded-2xl border {{ $isSelected ? 'border-pink-300 shadow-lg shadow-pink-100' : 'border-gray-100' }} hover:border-pink-200 transition block">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="bg-[#880e4f] text-white text-[9px] font-black px-2 py-1 rounded-lg">{{ $r->nomor_resep ?? 'REC-' . str_pad($r->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-[10px] text-gray-400 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">schedule</span>
                            Start: {{ $r->diproses_at?->format('H:i A') ?? '-' }}
                        </span>
                    </div>
                    <span class="px-2 py-1 {{ $stage['color'] }} rounded-lg text-[9px] font-black">{{ $stage['label'] }}</span>
                </div>
                <p class="font-black text-gray-900 text-base">{{ $r->pasien->name ?? 'Unknown' }}</p>
                <p class="text-[10px] text-gray-400 font-medium mb-3">{{ $r->dokter->name ?? '-' }}</p>

                {{-- Progress bar --}}
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[9px] font-bold text-gray-400">Progress</span>
                    <span id="list-progress-label-{{ $r->id }}" class="text-[9px] font-black text-[#d81b60]">{{ $progress }}%</span>
                </div>
                <div class="h-2 w-full bg-pink-50 rounded-full overflow-hidden">
                    <div id="list-progress-bar-{{ $r->id }}" class="h-full bg-gradient-to-r from-[#d81b60] to-[#f06292] rounded-full transition-all duration-700" style="width: {{ $progress }}%"></div>
                </div>

                <div class="flex justify-end mt-3">
                    <form action="{{ route('apoteker.resep.finish', $r->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-[#d81b60] text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-[#880e4f] transition">
                            Selesai
                        </button>
                    </form>
                </div>
            </a>
            @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">inventory_2</span>
                <p class="text-sm font-bold text-gray-400">Tidak ada resep yang diproses</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: DETAIL PENYIAPAN --}}
    <div class="flex-1 animate-fadein">
        @if($selectedResep)
        @php
            $progress = $selectedResep->progressPersen();
            $checkedCount = $selectedResep->detailResep->where('is_checked', true)->count();
            $totalCount   = $selectedResep->detailResep->count();
        @endphp
        <div class="bg-[#880e4f] rounded-t-[2rem] p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-black uppercase tracking-widest text-pink-200">DETAIL PENYIAPAN</p>
                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">medication</span>
                </div>
            </div>
            <h3 class="text-2xl font-black">{{ $selectedResep->pasien->name ?? 'Unknown' }}</h3>
            <div class="flex items-center gap-3 mt-3">
                <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-black">{{ $selectedResep->antrian->poli->nama_poli ?? 'General' }}</span>
                <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-black">
                    Antrian: {{ $selectedResep->antrian->nomor_antrian }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-b-[2rem] border border-t-0 border-gray-100 flex flex-col overflow-hidden">
            {{-- Medicine List --}}
            <div class="p-6 border-b border-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">list</span>
                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Daftar Obat & Racikan</p>
                </div>
                <div class="flex flex-col gap-3" id="medicine-list-{{ $selectedResep->id }}">
                    @foreach($selectedResep->detailResep as $item)
                    @php
                        $stokStatus = match($item->stok_tersedia <=> null) {
                            0       => ['label' => '', 'cls' => ''],
                            default => ($item->stok_tersedia >= $item->jumlah 
                                        ? ['label' => 'STOK OK', 'cls' => 'text-teal-600'] 
                                        : ['label' => 'PROSES', 'cls' => 'text-orange-500'])
                        };
                        $jenis = strtoupper($item->jenis ?? 'obat');
                    @endphp
                    <div class="flex items-start gap-4 p-4 rounded-2xl border {{ $item->is_checked ? 'border-teal-100 bg-teal-50/30' : 'border-gray-100 bg-white' }} transition-all item-row" data-id="{{ $item->id }}">
                        {{-- Checkbox --}}
                        <button type="button"
                                onclick="toggleItem({{ $selectedResep->id }}, {{ $item->id }}, this)"
                                class="w-7 h-7 rounded-full border-2 {{ $item->is_checked ? 'bg-teal-500 border-teal-500' : 'border-gray-200' }} flex items-center justify-center shrink-0 transition hover:scale-110">
                            @if($item->is_checked)
                            <span class="material-symbols-outlined text-white text-[15px]">check</span>
                            @endif
                        </button>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-gray-900 text-sm">{{ $item->nama_obat }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                {{ $item->aturan_pakai }} ({{ $item->jumlah }} {{ $item->dosis ?? 'unit' }})
                                @if($item->keterangan) &bull; {{ $item->keterangan }}@endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($jenis === 'RACIKAN')
                            <span class="px-2 py-0.5 bg-orange-50 text-orange-500 text-[9px] font-black rounded-full border border-orange-100">RACIKAN</span>
                            @endif
                            @if($stokStatus['label'])
                            <span class="text-[10px] font-black {{ $stokStatus['cls'] }}">{{ $stokStatus['label'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Progress Penyiapan</p>
                    <div class="flex items-center gap-3">
                        <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div id="progress-bar-{{ $selectedResep->id }}" class="h-full bg-[#d81b60] rounded-full transition-all duration-700" style="width: {{ $progress }}%"></div>
                        </div>
                        <span id="progress-label-{{ $selectedResep->id }}" class="text-xs font-black text-[#d81b60]">{{ $progress }}% Complete</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('apoteker.resep.etiket', $selectedResep->id) }}" target="_blank"
                       class="border-2 border-gray-200 text-gray-600 py-2.5 px-5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:border-gray-300 flex items-center gap-2 transition">
                        <span class="material-symbols-outlined text-[16px]">print</span> Cetak Etiket
                    </a>
                    <form action="{{ route('apoteker.resep.finish', $selectedResep->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-[#880e4f] text-white py-2.5 px-5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#6a0036] shadow-lg shadow-pink-200 transition">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="h-full flex flex-col items-center justify-center text-center bg-white rounded-[2rem] border border-dashed border-pink-100">
            <span class="material-symbols-outlined text-6xl text-pink-100 mb-4">hourglass_top</span>
            <p class="text-sm font-black text-gray-400">Pilih resep untuk memproses</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function toggleItem(resepId, itemId, btn) {
    try {
        const res = await fetch(`/dashboard/apoteker/resep/${resepId}/item/${itemId}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await res.json();

        // Update button visual
        if (data.checked) {
            btn.classList.add('bg-teal-500', 'border-teal-500');
            btn.classList.remove('border-gray-200');
            btn.innerHTML = '<span class="material-symbols-outlined text-white text-[15px]">check</span>';
            btn.closest('.item-row').classList.add('border-teal-100', 'bg-teal-50/30');
            btn.closest('.item-row').classList.remove('border-gray-100');
        } else {
            btn.classList.remove('bg-teal-500', 'border-teal-500');
            btn.classList.add('border-gray-200');
            btn.innerHTML = '';
            btn.closest('.item-row').classList.remove('border-teal-100', 'bg-teal-50/30');
            btn.closest('.item-row').classList.add('border-gray-100');
        }

        // Update progress in BOTH Detail (right) and List (left)
        const bar       = document.getElementById(`progress-bar-${resepId}`);
        const label     = document.getElementById(`progress-label-${resepId}`);
        const listBar   = document.getElementById(`list-progress-bar-${resepId}`);
        const listLabel = document.getElementById(`list-progress-label-${resepId}`);

        if (bar) bar.style.width = data.progress + '%';
        if (label) label.textContent = data.progress + '% Complete';
        if (listBar) listBar.style.width = data.progress + '%';
        if (listLabel) listLabel.textContent = data.progress + '%';
    } catch(e) {
        alert('Gagal update item: ' + e.message);
    }
}
</script>
@endpush
