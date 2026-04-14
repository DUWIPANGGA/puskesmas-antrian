@extends('layouts.apoteker')
@section('title', 'Pharmacy Reports')

@section('content')
<div class="flex flex-col gap-6">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">Pharmacy Insights</h2>
            <p class="text-xs text-gray-400 font-medium mt-1">Laporan aktivitas resep & performa pelayanan farmasi.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Period Toggle --}}
            <div class="flex items-center gap-1 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm">
                @foreach(['daily' => 'Harian', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
                <a href="{{ route('apoteker.reports', ['period' => $key]) }}"
                   class="px-4 py-2 text-[10px] font-black rounded-xl transition {{ $period === $key ? 'bg-[#d81b60] text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
            <button onclick="window.print()" 
                    class="flex items-center gap-2 px-4 py-2.5 bg-[#2d7a6e] text-white rounded-2xl text-xs font-black shadow-lg shadow-teal-100 hover:bg-[#205a50] transition">
                <span class="material-symbols-outlined text-[16px]">download_for_offline</span> Export
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-[1.75rem] p-6 shadow-sm border border-gray-50 relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-pink-50 text-[#d81b60] flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[20px]">receipt_long</span>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Resep</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalResep }}</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-pink-300 to-[#d81b60]"></div>
        </div>

        <div class="bg-white rounded-[1.75rem] p-6 shadow-sm border border-gray-50 relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-teal-50 text-[#2d7a6e] flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[20px]">task_alt</span>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Selesai Dilayani</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalSelesai }}</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-200 to-[#2d7a6e]"></div>
        </div>

        <div class="bg-white rounded-[1.75rem] p-6 shadow-sm border border-gray-50 relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[20px]">timer</span>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Rata-rata Proses</p>
            <p class="text-3xl font-black text-gray-900">{{ $avgMin }}<span class="text-sm font-bold text-gray-400 ml-1">mnt</span></p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-200 to-blue-600"></div>
        </div>

        <div class="bg-white rounded-[1.75rem] p-6 shadow-sm border border-gray-50 relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[20px]">percent</span>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Completion Rate</p>
            <p class="text-3xl font-black text-gray-900">{{ $completionRate }}<span class="text-sm font-bold text-gray-400 ml-0.5">%</span></p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-200 to-purple-600"></div>
        </div>
    </div>

    {{-- Chart + Obat Terbanyak --}}
    <div class="grid grid-cols-5 gap-5">
        {{-- Bar Chart 7 Hari --}}
        <div class="col-span-3 bg-white rounded-[2rem] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-black text-gray-900">Volume Resep 7 Hari</h3>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">Jumlah resep masuk vs selesai per hari</p>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#d81b60]"></span> Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-teal-400"></span> Selesai</span>
                </div>
            </div>

            @php $maxVal = $chartData->max('value') ?: 1; @endphp
            <div class="flex items-end justify-between gap-2" style="height: 140px;">
                @foreach($chartData as $day)
                @php
                    $incomingH = max(4, round(($day['value'] / $maxVal) * 120));
                    $doneH     = $day['value'] > 0 ? max(4, round(($day['done'] / $day['value']) * $incomingH)) : 0;
                @endphp
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <p class="text-[9px] font-black text-gray-400">{{ $day['value'] ?: '' }}</p>
                    <div class="w-full flex flex-col justify-end gap-0.5 flex-1">
                        {{-- Selesai bar (on top of incoming) --}}
                        <div class="w-full rounded-t-lg {{ $day['isToday'] ? 'bg-[#d81b60]' : 'bg-pink-100' }} transition-all duration-700 relative"
                             style="height: {{ $incomingH }}px;">
                            @if($doneH > 0)
                            <div class="absolute bottom-0 left-0 right-0 rounded-t-lg bg-teal-400 opacity-80"
                                 style="height: {{ $doneH }}px;"></div>
                            @endif
                        </div>
                    </div>
                    <p class="text-[9px] font-black {{ $day['isToday'] ? 'text-[#d81b60]' : 'text-gray-400' }} uppercase leading-none">{{ $day['label'] }}</p>
                    <p class="text-[8px] text-gray-300">{{ $day['date'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Obat Terbanyak --}}
        <div class="col-span-2 bg-white rounded-[2rem] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-[#d81b60] text-[18px]">medication</span>
                <h3 class="text-base font-black text-gray-900">Obat Terbanyak Diresepkan</h3>
            </div>
            @php $maxQty = $topMedicines->max('total_qty') ?: 1; @endphp
            <div class="flex flex-col gap-4">
                @forelse($topMedicines as $med)
                @php $pct = round(($med->total_qty / $maxQty) * 100); @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-black text-gray-900 truncate max-w-[70%]">{{ $med->nama_obat }}</p>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[9px] text-gray-400 font-bold">{{ $med->total_rx }} Rx</span>
                            <span class="text-[9px] font-black text-[#d81b60]">{{ $med->total_qty }} unit</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-pink-400 to-[#d81b60] rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-6">Belum ada data resep</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Breakdown Poli + Activity Log --}}
    <div class="grid grid-cols-5 gap-5">
        {{-- Poli Breakdown --}}
        <div class="col-span-2 bg-white rounded-[2rem] p-6 shadow-sm border border-gray-50">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-[#2d7a6e] text-[18px]">local_hospital</span>
                <h3 class="text-base font-black text-gray-900">Resep per Poliklinik</h3>
            </div>
            @if($poliBreakdown->isEmpty())
                <div class="flex flex-col items-center py-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-gray-200 mb-2">bar_chart</span>
                    <p class="text-xs font-bold text-gray-400">Tidak ada data pada periode ini</p>
                </div>
            @else
            @php $maxPoli = $poliBreakdown->max('total') ?: 1; @endphp
            <div class="flex flex-col gap-4">
                @foreach($poliBreakdown as $poli)
                @php $pct = round(($poli->total / $maxPoli) * 100); @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-xs font-black text-gray-800 truncate max-w-[75%]">{{ $poli->nama_poli }}</p>
                        <span class="text-[10px] font-black text-teal-600">{{ $poli->total }}</span>
                    </div>
                    <div class="h-2 w-full bg-teal-50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-teal-300 to-[#2d7a6e] rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Activity Log --}}
        <div class="col-span-3 bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden flex flex-col">
            <div class="px-7 py-5 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-gray-900">Log Aktivitas Resep</h3>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">Riwayat resep pada periode terpilih</p>
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/40">
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">No. Resep</th>
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Pasien</th>
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Dokter / Poli</th>
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-7 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLog as $r)
                        @php
                            $statusMap = [
                                'pending'    => ['label' => 'Pending',     'cls' => 'bg-orange-50 text-orange-500'],
                                'diproses'   => ['label' => 'Diproses',    'cls' => 'bg-blue-50 text-blue-500'],
                                'siap_ambil' => ['label' => 'Siap Ambil',  'cls' => 'bg-teal-50 text-teal-600'],
                                'diambil'    => ['label' => 'Diambil',     'cls' => 'bg-gray-100 text-gray-500'],
                                'batal'      => ['label' => 'Batal',       'cls' => 'bg-red-50 text-red-500'],
                            ];
                            $st = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'cls' => 'bg-gray-100 text-gray-400'];
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition">
                            <td class="px-7 py-4">
                                <span class="text-[10px] font-black text-[#d81b60]">#{{ $r->nomor_resep ?? str_pad($r->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-7 py-4 font-black text-gray-900 uppercase text-xs">{{ $r->pasien->name ?? '-' }}</td>
                            <td class="px-7 py-4">
                                <p class="text-[10px] font-bold text-gray-700">{{ $r->dokter->name ?? '-' }}</p>
                                <p class="text-[9px] text-gray-400">{{ $r->antrian->poli->nama_poli ?? '-' }}</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="px-2 py-1 {{ $st['cls'] }} rounded-full text-[9px] font-black">{{ $st['label'] }}</span>
                            </td>
                            <td class="px-7 py-4 text-[10px] font-bold text-gray-400">{{ $r->created_at->format('d/m H:i') }}</td>
                            <td class="px-7 py-4">
                                <button onclick="showResepDetail({{ json_encode($r->load(['detailResep', 'pasien', 'dokter', 'antrian.poli'])) }})" 
                                        class="w-8 h-8 rounded-lg bg-pink-50 text-[#d81b60] flex items-center justify-center hover:bg-[#d81b60] hover:text-white transition">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-7 py-16 text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">receipt_long</span>
                                <p class="text-xs font-bold text-gray-400">Tidak ada aktivitas resep pada periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($activityLog->hasPages())
            <div class="px-7 py-4 border-t border-gray-50 flex items-center justify-between">
                <p class="text-[10px] font-bold text-gray-400">{{ $activityLog->total() }} resep total</p>
                <div class="flex gap-2">
                    @if(!$activityLog->onFirstPage())
                    <a href="{{ $activityLog->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 transition">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>
                    @endif
                    @foreach($activityLog->getUrlRange(1, $activityLog->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-full text-[10px] font-black {{ $page == $activityLog->currentPage() ? 'bg-[#d81b60] text-white' : 'text-gray-400 hover:bg-gray-100' }} transition">{{ $page }}</a>
                    @endforeach
                    @if($activityLog->hasMorePages())
                    <a href="{{ $activityLog->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 transition">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
{{-- Detail Resep Modal --}}
<div id="modal-resep-detail" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl animate-in fade-in zoom-in duration-200 my-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-pink-50/50">
            <div>
                <h3 class="font-black text-gray-900">Detail Resep Obat</h3>
                <p id="modal-rx-id" class="text-[10px] text-[#d81b60] font-black mt-1 uppercase tracking-widest"></p>
            </div>
            <button onclick="closeRxModal()" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-gray-600 shadow-sm transition">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">PASIEN</p>
                    <p id="modal-pasien-name" class="text-sm font-black text-gray-900 uppercase"></p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">DOKTER / POLI</p>
                    <p id="modal-dokter-info" class="text-sm font-black text-gray-900 uppercase"></p>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[#d81b60] text-[18px]">medication</span>
                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Daftar Obat</p>
                </div>
                <div id="modal-medicines-list" class="flex flex-col gap-2">
                    {{-- JS populated items --}}
                </div>
            </div>

            <button onclick="closeRxModal()" class="w-full bg-[#d81b60] text-white py-4 rounded-2xl font-black shadow-lg shadow-pink-500/20 hover:scale-[1.02] transition">
                Tutup Detail
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showResepDetail(resep) {
        const modal = document.getElementById('modal-resep-detail');
        const list = document.getElementById('modal-medicines-list');
        
        document.getElementById('modal-rx-id').innerText = 'ID: #' + (resep.nomor_resep || ('RX-' + String(resep.id).padStart(5, '0')));
        document.getElementById('modal-pasien-name').innerText = resep.pasien ? resep.pasien.name : 'Unknown';
        document.getElementById('modal-dokter-info').innerText = (resep.dokter ? resep.dokter.name : '-') + ' / ' + (resep.antrian && resep.antrian.poli ? resep.antrian.poli.nama_poli : '-');
        
        list.innerHTML = '';
        
        if (resep.detail_resep && resep.detail_resep.length > 0) {
            resep.detail_resep.forEach(item => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100';
                div.innerHTML = `
                    <div>
                        <p class="text-sm font-black text-[#d81b60] uppercase">${item.nama_obat}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">${item.dosis} &bull; ${item.aturan_pakai}</p>
                    </div>
                    <span class="text-[10px] font-black text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">QTY: ${item.jumlah}</span>
                `;
                list.appendChild(div);
            });
        } else {
            // Legacy data fallback
            const div = document.createElement('div');
            div.className = 'p-4 bg-orange-50 rounded-xl border border-orange-100';
            div.innerHTML = `
                <p class="text-[10px] font-black text-orange-600 uppercase mb-2">Legacy Data (Raw Text):</p>
                <p class="text-xs text-orange-800 font-bold leading-relaxed whitespace-pre-line">${resep.obat || 'Tidak ada data obat'}</p>
            `;
            list.appendChild(div);
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRxModal() {
        const modal = document.getElementById('modal-resep-detail');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection
