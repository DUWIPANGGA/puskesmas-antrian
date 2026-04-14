@extends('layouts.dokter')

@section('title', 'Dokter Dashboard')

@section('content')
<div class="max-w-[1200px] mx-auto flex flex-col gap-6">

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Total Patients Today --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#fce4ec] text-[#d81b60] flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">people</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Total Pasien Hari Ini</p>
                <div class="text-3xl font-black text-gray-900 leading-none">{{ $totalPatientsToday }}</div>
            </div>
        </div>

        {{-- Queue Waiting --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#fff3e0] text-[#ef6c00] flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">queue</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Antrian Menunggu</p>
                <div class="text-3xl font-black text-gray-900 leading-none">{{ $queueCount ?? 0 }}</div>
            </div>
        </div>

        {{-- Avg Consultation --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#f3e8f8] text-[#714bca] flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">timer</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Rata-rata Konsultasi</p>
                <div class="text-3xl font-black text-gray-900 leading-none">{{ $avgConsultation }} <span class="text-sm">mnt</span></div>
            </div>
        </div>

        {{-- Finished Patients --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.2rem] bg-[#e1f5fe] text-[#0288d1] flex items-center justify-center shrink-0">
                 <span class="material-symbols-outlined text-3xl">check_circle</span>
            </div>
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-0.5">Selesai Dilayani</p>
                <div class="text-3xl font-black text-gray-900 leading-none">{{ $finishedPatients }}</div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="flex flex-col lg:flex-row gap-6 mt-2">
        
        {{-- Left Column: Now Serving & Upcoming --}}
        <div class="w-full lg:w-[380px] shrink-0 flex flex-col gap-6">
            
            {{-- Now Serving Card --}}
            <div class="bg-white rounded-[2.5rem] p-8 pb-10 border border-gray-100 shadow-[0_10px_40px_rgba(216,27,96,0.06)] relative overflow-hidden flex flex-col items-center min-h-[460px]">
                
                {{-- Poli Info --}}
                <div class="absolute top-6 right-6">
                    <span class="bg-[#fce4ec] text-[#d81b60] text-[10px] font-black px-3 py-1.5 rounded-full">
                        {{ $poli->nama_poli ?? 'Poli' }}
                    </span>
                </div>

                <div class="w-full flex justify-center mb-4">
                    <span class="bg-[#fce4ec] text-[#d81b60] text-[10px] uppercase font-black tracking-widest px-4 py-1.5 rounded-full">
                        {{ $nowServing ? 'SEDANG DILAYANI' : 'STATUS STANDBY' }}
                    </span>
                </div>

                @if($nowServing)
                    <h1 class="text-7xl font-black text-[#d81b60] tracking-tight mb-2" style="text-shadow: 0 4px 20px rgba(216,27,96,0.15)">
                        {{ $nowServing->nomor_antrian }}
                    </h1>
                    
                    <div class="text-center w-full mb-8">
                        <h2 class="text-xl font-black text-gray-900 mb-1 truncate px-4">{{ $nowServing->pasien->name ?? 'Pasien' }}</h2>
                        <p class="text-sm font-bold text-gray-400">Antrian ke-{{ $nowServing->nomor_urut }} • {{ $nowServing->pasien->phone ?? '-' }}</p>
                        
                        @if($nowServing->keluhan)
                        <div class="mt-4 px-4 py-3 bg-[#fff9fb] border border-pink-100 rounded-2xl text-left">
                            <p class="text-[10px] font-black text-[#d81b60] uppercase tracking-widest mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">medical_information</span> Keluhan Pasien
                            </p>
                            <p class="text-xs text-gray-600 font-medium leading-relaxed italic line-clamp-2">"{{ $nowServing->keluhan }}"</p>
                        </div>
                        @endif
                        
                        <div class="mt-6 flex flex-col gap-2 px-4">
                            <a href="{{ route('dokter.my-patients') }}" class="w-full inline-flex items-center justify-center gap-2 bg-[#d81b60] text-white px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-[#c2185b] transition shadow-lg shadow-pink-500/20">
                                <span class="material-symbols-outlined text-[20px]">medical_services</span> Periksa Pasien Sekarang
                            </a>
                            <p class="text-[10px] text-gray-400 font-bold italic mt-1">* Isi form pemeriksaan untuk menyelesaikan antrian ini</p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-3 px-2">
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="recallPatient()" class="bg-white border border-gray-200 hover:border-[#d81b60] hover:text-[#d81b60] text-gray-700 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                <span class="material-symbols-outlined text-base">campaign</span> Panggil Ulang
                            </button>
                            <button onclick="callPrevPatient()" class="bg-white border border-gray-200 hover:border-[#d81b60] hover:text-[#d81b60] text-gray-700 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                <span class="material-symbols-outlined text-base">arrow_back</span> Pasien Lewat
                            </button>
                        </div>
                        
                        <div class="h-px bg-gray-100 my-1"></div>
                        
                        <button onclick="callNextPatient()" id="callNextBtn" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-400 py-4 rounded-2xl font-black text-sm flex items-center justify-center gap-2 transition cursor-not-allowed group relative" disabled>
                            <span class="material-symbols-outlined text-lg">skip_next</span> Panggil Berikutnya
                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] py-1 px-3 rounded hidden group-hover:block whitespace-nowrap">
                                Selesaikan pemeriksaan terlebih dulu cuy!
                            </div>
                        </button>
                        
                        <button onclick="skipCurrentPatient()" class="text-gray-400 hover:text-red-500 text-[10px] font-bold uppercase tracking-widest transition">
                            Batalkan Panggilan Ini (Kembalikan ke Antrian)
                        </button>
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4 mt-6">
                        <span class="material-symbols-outlined text-5xl text-gray-300">person_off</span>
                    </div>
                    <h1 class="text-4xl font-black text-gray-300 tracking-tight mb-2">Tidak Ada Pasien</h1>
                    <div class="text-center w-full mb-10 px-6">
                        <h2 class="text-lg font-black text-gray-400 mb-1 leading-tight">Belum ada pasien yang dipanggil</h2>
                        <p class="text-sm font-semibold text-gray-300">Klik tombol di bawah untuk memanggil antrian pertama.</p>
                    </div>
                    <div class="w-full flex gap-3 flex-col px-4">
                        <button onclick="callNextPatient()" id="callNextBtnEmpty" 
                                class="w-full bg-[#d81b60] hover:bg-[#c2185b] text-white py-5 rounded-[1.5rem] font-black text-base shadow-xl shadow-pink-500/30 flex items-center justify-center gap-3 transition transform active:scale-95
                                {{ $queueCount == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $queueCount == 0 ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-2xl">campaign</span> 
                            {{ $queueCount == 0 ? 'Tidak Ada Antrian' : 'Panggil Pasien Sekarang' }}
                        </button>
                    </div>
                @endif
            </div>

            {{-- Upcoming Today --}}
            <div class="bg-[#fcf7fa] rounded-[2rem] p-6 border border-pink-50/50">
                <h3 class="text-sm font-black text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#d81b60] text-lg">schedule</span> Antrian Selanjutnya
                </h3>
                
                <div class="flex flex-col gap-3" id="upcomingList">
                    @forelse($upcomingToday as $upcoming)
                    <div class="bg-white rounded-2xl p-4 flex items-center justify-between border border-white shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="bg-pink-50 text-[#d81b60] text-[11px] font-black px-3 py-1 rounded-full">
                                {{ $upcoming->nomor_antrian }}
                            </span>
                            <span class="text-sm font-black text-gray-900">{{ $upcoming->pasien->name ?? 'Pasien' }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400">
                            {{ $upcoming->status == 'check_in' ? 'Sudah Check-in' : 'Menunggu' }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center text-xs text-gray-400 py-4 font-bold">Tidak ada antrian berikutnya.</div>
                    @endforelse
                </div>
                
                @if($queueCount > 2)
                <div class="mt-4 text-center">
                    <p class="text-xs font-bold text-gray-400">+{{ $queueCount - 2 }} antrian lainnya</p>
                </div>
                @endif
            </div>

        </div>

        {{-- Right Column: Today's Patients List --}}
        <div class="flex-1 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm flex flex-col h-[680px]">
            <div class="p-8 pb-4 flex justify-between items-center border-b border-gray-50">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Daftar Pasien Hari Ini</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $poli->nama_poli ?? 'Poli' }} • {{ now()->format('d F Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="refreshData()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-500 transition">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white/90 backdrop-blur border-b border-gray-100/50 z-10">
                        <tr>
                            <th class="py-4 px-8 text-[10px] font-black tracking-widest text-gray-400 uppercase w-32">No. Antrian</th>
                            <th class="py-4 px-4 text-[10px] font-black tracking-widest text-gray-400 uppercase">Nama Pasien</th>
                            <th class="py-4 px-4 text-[10px] font-black tracking-widest text-gray-400 uppercase">No. Urut</th>
                            <th class="py-4 px-8 text-[10px] font-black tracking-widest text-gray-400 uppercase text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody id="patientsTableBody">
                        @forelse($todaysPatients as $patient)
                        <tr class="group hover:bg-pink-50/30 transition border-b border-gray-50/50 last:border-0" data-id="{{ $patient->id }}">
                            <td class="py-4 px-8 text-[14px] font-black text-[#d81b60]">{{ $patient->nomor_antrian }}</td>
                            <td class="py-4 px-4 text-[14px] font-black text-gray-900">{{ $patient->pasien->name ?? 'Unknown' }}</td>
                            <td class="py-4 px-4 text-[13px] font-medium text-gray-500">{{ $patient->nomor_urut }}</td>
                            <td class="py-4 px-8 text-right">
                                 @if($patient->status == 'dipanggil_dokter')
                                     <span class="bg-[#e1f5fe] text-[#0288d1] text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Sedang Dilayani</span>
                                 @elseif($patient->status == 'selesai')
                                     <div class="flex items-center justify-end gap-2">
                                         <span class="bg-[#e8f5e9] text-[#2e7d32] text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Selesai</span>
                                         <button onclick="reExamine({{ $patient->id }})" class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-blue-100 transition" title="Buka kembali untuk edit obat/diagnosa">
                                             Ubah
                                         </button>
                                     </div>
                                 @elseif($patient->status == 'siap_pemeriksaan')
                                     <div class="flex items-center justify-end gap-2">
                                         <span class="bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full mr-2 border border-green-100 italic">Siap Periksa</span>
                                         <button onclick="callSpecific({{ $patient->id }})" class="bg-[#d81b60] text-white border border-[#d81b60] hover:bg-[#c2185b] px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-wider transition shadow-sm">
                                             Panggil
                                         </button>
                                     </div>
                                 @else
                                     <div class="flex items-center justify-end gap-2">
                                         @if($patient->status == 'check_in')
                                             <span class="bg-yellow-50 text-yellow-600 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full mr-2">Sudah Check-in</span>
                                         @else
                                             <span class="bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full mr-2">Menunggu Admin</span>
                                         @endif
                                         <button disabled class="bg-gray-50 border border-gray-100 text-gray-300 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider cursor-not-allowed">
                                             Panggil
                                         </button>
                                     </div>
                                 @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-sm font-bold text-gray-400">Tidak ada pasien untuk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    let autoRefreshInterval = null;
    const POLI_NAME = "{{ $poli->nama_poli ?? 'Poli' }}";

    function speakQueue(nomorAntrian) {
    if (!('speechSynthesis' in window)) return;

    const synth = window.speechSynthesis;
    synth.cancel();

    const formattedNomor = nomorAntrian.split('').join(' ');
    const message = `Nomor antrian... ${formattedNomor}... silakan menuju... ${POLI_NAME}`;

    const utter = new SpeechSynthesisUtterance(message);
    utter.lang = 'id-ID';
    utter.rate = 0.85;
    utter.pitch = 1;

    const setVoice = () => {
        const voices = synth.getVoices();

        console.log("Available voices:", voices);

        // Prioritas 1: Google Bahasa Indonesia
        let voice = voices.find(v => 
            v.name.toLowerCase().includes('google') && v.lang.toLowerCase().includes('id')
        );

        // Prioritas 2: Semua voice Indonesia
        if (!voice) {
            voice = voices.find(v => v.lang.toLowerCase().includes('id'));
        }

        // Prioritas 3: fallback English (kalau tidak ada Indo)
        if (!voice) {
            voice = voices.find(v => v.lang.toLowerCase().includes('en'));
        }

        if (voice) {
            utter.voice = voice;
        }
speechSynthesis.getVoices().forEach(v => {
    console.log(v.name, v.lang);
});
        synth.speak(utter);
    };

    if (synth.getVoices().length > 0) {
        setVoice();
    } else {
        synth.onvoiceschanged = setVoice;
    }
}

    // Auto-trigger voice after reload if query param exists
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const speakParam = urlParams.get('speak');
        if (speakParam) {
            // Hilangkan param dari URL tanpa reload agar tidak dipanggil lagi kalau direfresh
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
            
            // Beri jeda dikit biar halaman ready
            setTimeout(() => {
                speakQueue(speakParam);
            }, 500);
        }
    });
    
    function callNextPatient() {
        const btn = document.getElementById('callNextBtn') || document.getElementById('callNextBtnEmpty');
        if (!btn || btn.disabled) return;

        btn.disabled = true;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">hourglass_empty</span> Memproses...';
        
        fetch('{{ route("dokter.call-next") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.patient) {
                window.location.href = window.location.pathname + '?speak=' + encodeURIComponent(data.patient.nomor_antrian);
            } else {
                alert(data.error || 'Terjadi kesalahan');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }

    function recallPatient() {
        fetch('{{ route("dokter.recall") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                speakQueue(data.speak_nomor);
                showToast("Memanggil ulang antrian " + data.speak_nomor);
            }
        });
    }

    function callSpecific(id) {
        if (!confirm('Panggil antrian ini sekarang?')) return;
        
        // Disable all buttons to prevent double click
        document.querySelectorAll('button').forEach(b => b.disabled = true);

        fetch('{{ route("dokter.call-specific") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.patient) {
                window.location.href = window.location.pathname + '?speak=' + encodeURIComponent(data.patient.nomor_antrian);
            } else {
                alert(data.error || 'Gagal memanggil antrian');
                location.reload();
            }
        })
        .catch(() => location.reload());
    }

    function reExamine(id) {
        if (!confirm('Buka kembali sesi pemeriksaan ini?')) return;
        
        document.querySelectorAll('button').forEach(b => b.disabled = true);

        fetch('{{ route("dokter.re-examine") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    location.reload();
                }
            } else {
                alert(data.error || 'Gagal membuka kembali sesi');
                location.reload();
            }
        })
        .catch(() => location.reload());
    }

    function callPrevPatient() {
        if (!confirm('Panggil kembali antrian sebelumnya?')) return;
        fetch('{{ route("dokter.call-prev") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = window.location.pathname + '?speak=' + encodeURIComponent(data.speak_nomor);
            } else {
                alert(data.error || 'Gagal memanggil antrian sebelumnya');
            }
        });
    }

    function skipCurrentPatient() {
        if (!confirm('Kembalikan pasien ini ke antrian dan batalkan panggilan?')) return;
        fetch('{{ route("dokter.skip-patient") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
        });
    }
    
    function refreshData() {
        location.reload();
    }
    
    function showToast(message, type = 'success') {
        console.log(message);
    }
    
    if (autoRefreshInterval) clearInterval(autoRefreshInterval);
    autoRefreshInterval = setInterval(() => {
        fetch('{{ route("dokter.dashboard") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const newNowServing = newDoc.querySelector('.now-serving-number');
            const currentNowServing = document.querySelector('.now-serving-number');
            
            if (newNowServing && currentNowServing && 
                newNowServing.textContent !== currentNowServing.textContent) {
                location.reload();
            }
        })
        .catch(() => {});
    }, 30000);
</script>
@endpush

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 99px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #bdbdbd; }
    
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush
@endsection