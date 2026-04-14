@extends('layouts.admin')

@section('title', 'Manage Antrian')
@section('page-title', 'Manage Antrian Per Poli')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-red-500 text-[18px]">error</span>
        {{ session('error') }}
    </div>
@endif
@if(session('info'))
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-blue-500 text-[18px]">info</span>
        {{ session('info') }}
    </div>
@endif

{{-- Auto Refresh Badge --}}
<div class="flex items-center justify-between mb-6">
    <p class="text-xs text-gray-400 flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">sync</span>
        Halaman otomatis refresh tiap 30 detik
    </p>
    <button onclick="window.location.reload()" class="text-xs text-pink-600 font-bold flex items-center gap-1 hover:underline">
        <span class="material-symbols-outlined text-[14px]">refresh</span> Refresh Sekarang
    </button>
</div>

{{-- Grid per Poli --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($polis as $poli)
        @php
            $poliIndex = $loop->index;
            $hasActive = $poli->current_queue !== null;
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-pink-50 flex flex-col relative overflow-hidden transition-all hover:shadow-md
            {{ $hasActive ? 'ring-2 ring-pink-300' : '' }}">

            {{-- Top bar color --}}
            <div class="h-1.5 w-full {{ $hasActive ? 'bg-gradient-to-r from-pink-400 to-rose-500' : 'bg-gray-200' }}"></div>

            <div class="p-5 flex flex-col flex-1">

                {{-- Header --}}
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $hasActive ? 'bg-pink-100 text-[#d81b60]' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center shrink-0">
                            <i class="{{ $poli->icon ?? 'fa-solid fa-hospital' }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">{{ $poli->nama_poli }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $poli->kode_poli }}</p>
                        </div>
                    </div>
                    <div class="text-right flex flex-col gap-1">
                        <span class="inline-block px-2.5 py-1 bg-pink-50 text-pink-700 text-[11px] font-bold rounded-full">
                            {{ $poli->remaining_queue }} menunggu
                        </span>
                        <span class="text-[10px] text-gray-400">Total: {{ $poli->total_queue }}</span>
                    </div>
                </div>

                {{-- Display Number --}}
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-5 mb-4 flex flex-col items-center justify-center text-center min-h-[130px]">
                    <p class="text-[9px] uppercase font-black tracking-[0.2em] text-[#d81b60] mb-1">SEDANG DIPANGGIL</p>
                    <div class="text-6xl font-black {{ $hasActive ? 'text-gray-900' : 'text-gray-300' }} leading-none mb-2">
                        {{ $hasActive ? $poli->current_queue->nomor_antrian : '—' }}
                    </div>
                    @if($hasActive)
                        <p class="text-xs font-bold text-gray-500">{{ $poli->current_queue->pasien->name ?? 'Unknown' }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Dipanggil: {{ $poli->current_queue->dipanggil_at?->format('H:i') }}
                        </p>
                    @else
                        <p class="text-xs font-bold text-gray-400">Belum ada yang dipanggil</p>
                    @endif
                </div>

                {{-- Next Info --}}
                <div class="mb-4 rounded-lg bg-blue-50 p-2.5 border border-blue-100 text-center">
                    <p class="text-[11px] text-blue-800">
                        <span class="font-bold">Selanjutnya:</span>
                        {{ $poli->next_queue
                            ? $poli->next_queue->nomor_antrian.' – '.($poli->next_queue->pasien->name ?? '')
                            : 'Tidak ada antrian menunggu' }}
                    </p>
                </div>

                {{-- ── ACTION BUTTONS ── --}}
                <div class="mt-auto flex flex-col gap-2">

                    {{-- Row 1: Back | Next --}}
                    <div class="flex gap-2">
                        {{-- Mundur --}}
                        <form action="{{ route('admin.queue-control.update', $poli->id) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="go_back">
                            <button type="submit"
                                class="w-full border border-purple-200 bg-purple-50 text-purple-700 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 hover:bg-purple-100 transition
                                {{ !$hasActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$hasActive ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined text-[16px]">skip_previous</span> Mundur
                            </button>
                        </form>

                        {{-- Next / Panggil --}}
                        <form action="{{ route('admin.queue-control.update', $poli->id) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="call_next">
                            <button type="submit"
                                class="w-full bg-[#f06292] text-white py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 hover:bg-[#d81b60] transition shadow-sm
                                {{ (!$poli->next_queue && !$hasActive) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ (!$poli->next_queue && !$hasActive) ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined text-[16px]">{{ $hasActive ? 'skip_next' : 'campaign' }}</span>
                                {{ $hasActive ? 'Next' : 'Panggil' }}
                            </button>
                        </form>
                    </div>

                    {{-- Row 2: Selesai | Panggil Ulang | Pilih Antrian --}}
                    <div class="flex gap-2">
                        {{-- Selesai Administrasi (dengan popup konfirmasi) --}}
                        <button type="button"
                            onclick="confirmComplete({{ $poli->id }}, '{{ addslashes($poli->nama_poli) }}', '{{ $hasActive ? $poli->current_queue->nomor_antrian : '' }}')"
                            class="flex-1 border border-green-200 bg-green-50 text-green-700 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 hover:bg-green-100 transition
                            {{ !$hasActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$hasActive ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-[16px]">stethoscope</span> Admin Selesai
                        </button>

                        {{-- Panggil Ulang --}}
                        <form action="{{ route('admin.queue-control.update', $poli->id) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="recall">
                            <button type="submit"
                                class="w-full border border-blue-200 bg-blue-50 text-blue-700 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 hover:bg-blue-100 transition
                                {{ !$hasActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$hasActive ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined text-[16px]">replay</span> Ulang
                            </button>
                        </form>

                        {{-- Pilih Antrian Manual --}}
                        <button type="button"
                            onclick="openPickModal({{ $poliIndex }})"
                            class="flex-1 border border-orange-200 bg-orange-50 text-orange-700 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 hover:bg-orange-100 transition">
                            <span class="material-symbols-outlined text-[16px]">list_alt</span> Pilih
                        </button>
                    </div>

                </div>
            </div>

            {{-- Hidden form for complete --}}
            <form id="complete-form-{{ $poli->id }}"
                  action="{{ route('admin.queue-control.update', $poli->id) }}"
                  method="POST" class="hidden">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="complete">
            </form>

            {{-- Hidden form for call_specific --}}
            <form id="specific-form-{{ $poli->id }}"
                  action="{{ route('admin.queue-control.update', $poli->id) }}"
                  method="POST" class="hidden">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="call_specific">
                <input type="hidden" name="antrian_id" id="specific-antrian-id-{{ $poli->id }}">
            </form>

            {{-- Pick Modal Data (hidden JSON) --}}
            <script>
                window.poliQueues = window.poliQueues || [];
                window.poliQueues[{{ $poliIndex }}] = {
                    poliId: {{ $poli->id }},
                    namaP: @json($poli->nama_poli),
                    currentId: {{ $hasActive ? $poli->current_queue->id : 'null' }},
                    queues: @json($poli->all_queues)
                };
            </script>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">home_health</span>
            <h3 class="text-xl font-black text-gray-900 mb-2">Belum Ada Klinik Aktif</h3>
            <p class="text-sm text-gray-500">Silakan tambahkan klinik dan aktifkan statusnya terlebih dahulu.</p>
        </div>
    @endforelse
</div>

{{-- ═══ MODAL: Konfirmasi Selesai ══════════════════════════════════════════ --}}
<div id="modal-complete" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-green-600 text-4xl">stethoscope</span>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-1">Administrasi Selesai?</h3>
            <p class="text-sm text-gray-500 mb-1">Antrian <span id="modal-complete-nomor" class="font-black text-gray-900 text-xl"></span></p>
            <p class="text-xs text-gray-400 mb-6">di <span id="modal-complete-poli" class="font-bold"></span> akan dikirim ke <span class="text-green-600 font-bold">Antrian Dokter</span>.</p>
            <div class="flex gap-3">
                <button onclick="closeCompleteModal()"
                    class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button id="modal-complete-btn" onclick="submitComplete()"
                    class="flex-1 bg-green-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-green-700 transition">
                    Ya, Kirim ke Dokter
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL: Pilih Antrian ═══════════════════════════════════════════════ --}}
<div id="modal-pick" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl max-h-[80vh] flex flex-col">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-base font-black text-gray-900">Pilih Nomor Antrian</h3>
                <p id="modal-pick-poli" class="text-xs text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closePickModal()"
                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <span class="material-symbols-outlined text-gray-600 text-[18px]">close</span>
            </button>
        </div>

        {{-- Status legend --}}
        <div class="px-5 py-3 border-b border-gray-100 flex gap-3 text-[10px] font-bold flex-wrap">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-pink-500 inline-block"></span> Dipanggil</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span> Check-in</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> Selesai</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span> Menunggu</span>
        </div>

        {{-- List --}}
        <div id="modal-pick-list" class="overflow-y-auto flex-1 p-4 flex flex-col gap-2"></div>

        <div class="p-4 border-t border-gray-100">
            <button onclick="closePickModal()"
                class="w-full border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── Voice Announcement ──
    function speakQueue(nomorAntrian, poliName) {
        if ('speechSynthesis' in window) {
            const synth = window.speechSynthesis;
            synth.cancel(); 

            // Format nomor antrian for better reading (A-001 -> A 0 0 1)
            const formattedNomor = nomorAntrian.split('').join(' ');
            const message = `Nomor antrian... ${formattedNomor}... silakan menuju... ${poliName}`;
            
            const utter = new SpeechSynthesisUtterance(message);
            utter.lang = 'id-ID';
            utter.rate = 0.85;
            utter.pitch = 1;

            const setVoice = () => {
                const voices = synth.getVoices();
                let voice = voices.find(v => 
                    v.name.toLowerCase().includes('google') && v.lang.toLowerCase().includes('id')
                );
                if (!voice) voice = voices.find(v => v.lang.toLowerCase().includes('id'));
                if (voice) utter.voice = voice;
                synth.speak(utter);
            };

            if (synth.getVoices().length > 0) {
                setVoice();
            } else {
                synth.onvoiceschanged = setVoice;
            }
        }
    }

    // Trigger on load if session has data
    window.addEventListener('load', () => {
        @if(session('speak_nomor'))
            setTimeout(() => {
                speakQueue("{{ session('speak_nomor') }}", "{{ session('speak_poli') }}");
            }, 500);
        @endif
    });

    // ── Auto refresh 30 detik ──
    let refreshTimer = setTimeout(() => location.reload(), 30000);

    // ── Complete Modal ──
    let activeCompletePoliId = null;

    function confirmComplete(poliId, poliName, nomorAntrian) {
        activeCompletePoliId = poliId;
        document.getElementById('modal-complete-nomor').textContent = nomorAntrian;
        document.getElementById('modal-complete-poli').textContent  = poliName;
        const m = document.getElementById('modal-complete');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeCompleteModal() {
        document.getElementById('modal-complete').classList.add('hidden');
        document.getElementById('modal-complete').classList.remove('flex');
        activeCompletePoliId = null;
    }

    function submitComplete() {
        if (!activeCompletePoliId) return;
        document.getElementById('complete-form-' + activeCompletePoliId).submit();
    }

    document.getElementById('modal-complete').addEventListener('click', function(e) {
        if (e.target === this) closeCompleteModal();
    });

    // ── Pick Antrian Modal ──
    let activePickPoliId = null;

    const statusColor = {
        dipanggil: 'bg-pink-100 text-pink-700 border-pink-200',
        check_in:  'bg-blue-100  text-blue-700  border-blue-200',
        selesai:   'bg-green-100 text-green-700 border-green-200',
        menunggu:  'bg-gray-100  text-gray-600  border-gray-200',
    };
    const statusLabel = {
        dipanggil: 'Dipanggil',
        check_in:  'Check-in',
        selesai:   'Selesai',
        menunggu:  'Menunggu',
    };

    function openPickModal(index) {
        const data = window.poliQueues[index];
        if (!data) return;

        activePickPoliId = data.poliId;
        document.getElementById('modal-pick-poli').textContent = data.namaP;

        const list = document.getElementById('modal-pick-list');
        list.innerHTML = '';

        if (data.queues.length === 0) {
            list.innerHTML = `<div class="text-center py-8 text-gray-400 text-sm font-bold">Belum ada antrian hari ini</div>`;
        } else {
            data.queues.forEach(q => {
                const isCurrent = q.id === data.currentId;
                const colorClass = statusColor[q.status] || statusColor.menunggu;
                const label      = statusLabel[q.status] || q.status;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `flex items-center justify-between w-full p-3 rounded-xl border-2 text-left transition
                    ${isCurrent ? 'border-pink-400 bg-pink-50' : 'border-gray-100 hover:border-pink-200 hover:bg-pink-50'}`;

                btn.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="text-2xl font-black text-gray-800 w-12 text-center">${q.nomor_antrian}</div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">${q.pasien}</p>
                            <p class="text-[10px] text-gray-400">Urut ke-${q.nomor_urut}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        ${isCurrent ? '<span class="text-[10px] font-black text-pink-600 uppercase">Aktif</span>' : ''}
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border ${colorClass}">${label}</span>
                        ${!isCurrent ? '<span class="material-symbols-outlined text-gray-300 text-[18px]">chevron_right</span>' : ''}
                    </div>`;

                if (!isCurrent) {
                    btn.addEventListener('click', () => callSpecific(data.poliId, q.id, q.nomor_antrian));
                }
                list.appendChild(btn);
            });
        }

        const m = document.getElementById('modal-pick');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closePickModal() {
        document.getElementById('modal-pick').classList.add('hidden');
        document.getElementById('modal-pick').classList.remove('flex');
        activePickPoliId = null;
    }

    function callSpecific(poliId, antrianId, nomorAntrian) {
        if (!confirm(`Panggil antrian ${nomorAntrian}? Antrian yang sedang aktif akan dikembalikan ke check-in.`)) return;
        document.getElementById('specific-antrian-id-' + poliId).value = antrianId;
        document.getElementById('specific-form-' + poliId).submit();
        closePickModal();
    }

    document.getElementById('modal-pick').addEventListener('click', function(e) {
        if (e.target === this) closePickModal();
    });
</script>
@endpush
@endsection
