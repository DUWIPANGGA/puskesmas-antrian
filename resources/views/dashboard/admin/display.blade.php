<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor {{ $type === 'loket' ? 'Registrasi' : 'Pemeriksaan' }} | Puskesmas Jagapura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #fdfafb; overflow: hidden; height: 100vh; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(216, 27, 96, 0.1); }
        .card-active { border-left: 8px solid #d81b60; transition: all 0.3s ease; }
        @keyframes pulse-custom { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.02); opacity: 0.9; } 100% { transform: scale(1); opacity: 1; } }
        .animate-new { animation: pulse-custom 1s ease-in-out infinite; }
    </style>
</head>
<body class="p-6">
    <div class="h-full flex flex-col gap-6">
        {{-- Header Display --}}
        <header class="glass rounded-[2rem] px-10 py-6 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-[#d81b60] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-pink-200">
                    <span class="material-symbols-outlined text-3xl">local_hospital</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#d81b60] tracking-tight">MONITOR {{ $type === 'loket' ? 'REGISTRASI' : 'PEMERIKSAAN' }}</h1>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.3em]">Puskesmas Jagapura</p>
                </div>
            </div>

            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p id="live-date" class="text-slate-500 font-bold text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p id="live-clock" class="text-4xl font-black text-slate-800 tracking-tighter" style="font-variant-numeric: tabular-nums;">{{ now()->format('H:i:s') }}</p>
                </div>
                <div class="h-12 w-px bg-slate-200"></div>
                <div class="flex gap-2">
                    <a href="?type={{ $type === 'loket' ? 'pemeriksaan' : 'loket' }}{{ $selectedPoliId ? '&poli_id='.$selectedPoliId : '' }}" class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 rounded-full transition font-bold text-slate-600">
                        <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                        Ke {{ $type === 'loket' ? 'Pemeriksaan' : 'Loket' }}
                    </a>
                    @if($selectedPoliId)
                        <a href="?type={{ $type }}" class="flex items-center gap-2 px-6 py-3 bg-pink-50 hover:bg-pink-100 rounded-full transition font-bold text-[#d81b60]">
                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                            Semua Poli
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- Content Area --}}
        <main id="display-container" class="flex-1 grid gap-6 {{ $selectedPoliId ? 'grid-cols-12' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3' }}">
            @if($selectedPoliId)
                {{-- Focus View --}}
                <div id="focus-poli" class="col-span-12 lg:col-span-8 glass rounded-[3rem] p-12 flex flex-col justify-center items-center text-center shadow-xl border-4 border-pink-500/10 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-pink-50 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-rose-50 rounded-full blur-3xl opacity-50"></div>
                    
                    <span id="focus-poli-name" class="text-[#d81b60] font-black text-2xl uppercase tracking-[0.4em] mb-4">MOHON TUNGGU</span>
                    
                    <div class="flex flex-col items-center">
                        <h2 class="text-slate-400 font-medium text-lg mb-2 uppercase tracking-widest">
                            {{ $type === 'loket' ? 'Antrian Loket Registrasi' : 'Antrian Pemeriksaan Dokter' }}
                        </h2>
                        <div id="focus-number-container" class="relative">
                            <h1 id="focus-number" class="text-[14rem] font-black leading-none text-slate-900 tracking-tighter drop-shadow-2xl">—</h1>
                        </div>
                        @if($type === 'pemeriksaan')
                            <h3 id="focus-pasien-name" class="text-4xl font-black text-[#d81b60] mt-4 uppercase tracking-wider">—</h3>
                        @endif
                    </div>
                    
                    <div class="mt-12 py-4 px-10 bg-[#2e1a28] text-white rounded-3xl shadow-2xl flex items-center gap-4">
                        <span class="material-symbols-outlined text-[#f48fb1]">
                            {{ $type === 'loket' ? 'person_pin' : 'medical_information' }}
                        </span>
                        <p id="focus-subtitle" class="text-xl font-bold tracking-tight">
                            {{ $type === 'loket' ? 'Silakan menunggu di loket' : 'Dokter sedang bersiap' }}
                        </p>
                    </div>
                </div>

                {{-- Sidebar: Next Queues --}}
                <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                    <div class="glass rounded-[3rem] p-10 border-4 border-pink-500/5 shadow-2xl h-full flex flex-col">
                        <h3 class="text-[#d81b60] font-black uppercase tracking-[0.2em] text-sm mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[24px]">list_alt</span>
                            Antrian Berikutnya
                        </h3>
                        <div id="next-queues-list" class="flex flex-col gap-4 flex-1 overflow-y-auto pr-2">
                        </div>
                    </div>
                </div>
            @endif
        </main>

        {{-- Footer --}}
        <footer class="h-14 shrink-0 bg-[#2e1a28] rounded-[1.5rem] overflow-hidden flex items-center shadow-inner">
            <div class="bg-[#d81b60] px-6 h-full flex items-center">
                <span class="text-white font-black text-sm uppercase tracking-widest">INFO</span>
            </div>
            <div class="flex-1 px-6 text-pink-100/50 font-bold overflow-hidden">
                <marquee scrollamount="5" class="pt-1">
                    Monitor Antrian {{ $type === 'loket' ? 'Registrasi (Loket)' : 'Pemeriksaan Dokter' }} • Puskesmas Jagapura Melayani Dengan Hati • Mohon Menunggu Antrian Anda Dipanggil
                </marquee>
            </div>
        </footer>
    </div>

    <script>
        const selectedPoliId = "{{ $selectedPoliId ?? '' }}";
        const displayType = "{{ $type }}"; // loket or pemeriksaan
        const apiUrl = "{{ route('admin.display.api') }}";
        
        let lastState = {};
        let lastAdminState = {};

        function speakQueue(nomorAntrian, poliName, pasienName = null, isDoctor = false) {
            if (!('speechSynthesis' in window)) return;
            // Only speak if the type matches
            if (isDoctor && displayType !== 'pemeriksaan') return;
            if (!isDoctor && displayType !== 'loket') return;

            const synth = window.speechSynthesis;
            synth.cancel();

            const formattedNomor = nomorAntrian.split('').join(' ');
            const message = isDoctor 
                ? `Nomor antrian... ${formattedNomor}... atas nama... ${pasienName}... silakan menuju... ${poliName}`
                : `Nomor antrian... ${formattedNomor}... silakan menuju... Loket Registrasi... ${poliName}`;

            const utter = new SpeechSynthesisUtterance(message);
            utter.lang = 'id-ID';
            utter.rate = 0.85;
            
            const setVoice = () => {
                const voices = synth.getVoices();
                let v = voices.find(x => x.name.toLowerCase().includes('google') && x.lang.toLowerCase().includes('id')) 
                        || voices.find(x => x.lang.toLowerCase().includes('id'));
                if (v) utter.voice = v;
                synth.speak(utter);
            };
            if (synth.getVoices().length > 0) setVoice();
            else synth.onvoiceschanged = setVoice;
        }

        function updateDisplay() {
            const url = selectedPoliId ? `${apiUrl}?poli_id=${selectedPoliId}` : apiUrl;
            fetch(url).then(res => res.json()).then(data => {
                document.getElementById('live-date').innerText = data.date;
                const container = document.getElementById('display-container');
                
                data.polis.forEach(poli => {
                    const pid = poli.id;
                    // Check Doctor Calls
                    if (poli.current_number !== '-') {
                        const pre = lastState[pid];
                        if (!pre || pre.number !== poli.current_number || pre.updated !== poli.current_update) {
                            speakQueue(poli.current_number, poli.name, poli.current_name, true);
                        }
                    }
                    lastState[pid] = { number: poli.current_number, updated: poli.current_update };

                    // Check Admin Calls
                    if (poli.admin_number !== '-') {
                        const pre = lastAdminState[pid];
                        if (!pre || pre.number !== poli.admin_number || pre.updated !== poli.admin_update) {
                            speakQueue(poli.admin_number, poli.name, null, false);
                        }
                    }
                    lastAdminState[pid] = { number: poli.admin_number, updated: poli.admin_update };
                });
                
                if (selectedPoliId) {
                    const p = data.polis.find(x => x.id == selectedPoliId);
                    if (p) {
                        document.getElementById('focus-poli-name').innerText = p.name;
                        const numEl = document.getElementById('focus-number');
                        const newNum = (displayType === 'loket') ? p.admin_number : p.current_number;
                        const finalNum = newNum === '-' ? '—' : newNum;
                        
                        if (numEl.innerText !== finalNum) {
                            numEl.innerText = finalNum;
                            if (finalNum !== '—') {
                                const box = document.getElementById('focus-number-container');
                                box.classList.add('animate-new');
                                setTimeout(() => box.classList.remove('animate-new'), 10000);
                            }
                        }

                        if (displayType === 'pemeriksaan') {
                            const nameEl = document.getElementById('focus-pasien-name');
                            if(nameEl) nameEl.innerText = p.current_name || '—';
                            document.getElementById('focus-subtitle').innerText = p.doctor;
                        }

                        const nextList = document.getElementById('next-queues-list');
                        if (nextList) {
                            let html = '';
                            if (p.next_queues.length > 0) {
                                p.next_queues.forEach(n => {
                                    html += `<div class="flex items-center justify-between p-5 bg-pink-50/50 rounded-2xl border border-pink-100">
                                        <span class="text-slate-400 font-bold text-xs uppercase italic">Nomor Berikutnya</span>
                                        <span class="text-3xl font-black text-[#d81b60]">${n}</span>
                                    </div>`;
                                });
                            } else { html = '<p class="text-slate-400 text-sm italic py-4 text-center">Belum ada antrian berikutnya</p>'; }
                            nextList.innerHTML = html;
                        }
                    }
                } else {
                    let html = '';
                    data.polis.forEach(p => {
                        const num = (displayType === 'loket') ? p.admin_number : p.current_number;
                        const isC = num !== '-';
                        html += `
                            <div onclick="window.location.href='?type=${displayType}&poli_id=${p.id}'" class="glass rounded-[2.5rem] p-8 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all cursor-pointer card-active border border-slate-100 group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-[#d81b60] font-black text-[10px] tracking-[0.3em] uppercase">${p.code || 'KLINIK'}</p>
                                        <h3 class="text-xl font-extrabold text-slate-800 group-hover:text-[#d81b60] transition">${p.name}</h3>
                                    </div>
                                    <div class="px-3 py-1 bg-pink-50 rounded-lg text-[#d81b60] text-[10px] font-bold">Sisa: ${p.remaining}</div>
                                </div>
                                <div class="flex-1 flex flex-col justify-center items-center py-6">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">PANGGILAN SEKARANG</p>
                                    <div class="text-7xl font-black text-slate-900 tracking-tighter">${num === '-' ? '—' : num}</div>
                                    ${displayType === 'pemeriksaan' ? `<p class="text-xs font-bold text-[#d81b60] mt-2 uppercase">${p.current_name || ''}</p>` : ''}
                                </div>
                                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <p class="text-xs font-bold text-slate-400">${isC ? 'Sedang Dilayani' : 'Menunggu Pasien'}</p>
                                    <p class="text-[10px] font-bold text-[#d81b60] italic">${displayType === 'loket' ? 'Loket Registrasi' : p.doctor}</p>
                                </div>
                            </div>`;
                    });
                    container.innerHTML = html;
                }
            });
        }

        updateDisplay();
        setInterval(updateDisplay, 3000);
        setInterval(() => {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0'), m = String(now.getMinutes()).padStart(2, '0'), s = String(now.getSeconds()).padStart(2, '0');
            const el = document.getElementById('live-clock');
            if (el) el.innerText = `${h}:${m}:${s}`;
        }, 1000);
    </script>
</body>
</html>
