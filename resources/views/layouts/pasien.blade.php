<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pasien Portal') | Puskesmas Jagapura</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":     "#d81b60", 
                        "primary-light": "#f8bbd9",
                        "primary-soft": "#fce4ec",
                        "on-primary":  "#ffffff",
                        "surface":     "#ffffff",
                        "on-surface":  "#2e1a28",
                        "muted":       "#78909c", 
                    },
                    fontFamily: { sans: ["DM Sans", "sans-serif"] },
                    borderRadius: { DEFAULT: "1rem", xl: "1.5rem", "2xl": "2rem", full: "9999px" },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle; display: inline-flex; align-items: center; justify-content: center;
        }
        html, body { height: 100%; margin: 0; font-family: 'DM Sans', sans-serif; background: #fdfafb; }
        .nav-item { 
            display: flex; align-items: center; gap: 14px; padding: 12px 18px; 
            border-radius: 50px; font-size: 14px; font-weight: 600; 
            color: #546e7a; cursor: pointer; transition: all 0.18s ease; text-decoration: none; 
            margin-bottom: 4px;
        }
        .nav-item:hover { background: #fce4ec; color: #d81b60; }
        .nav-item.active { background: #fce4ec; color: #d81b60; font-weight: 700; }
        .nav-item .material-symbols-outlined { font-size: 20px; }
        ::-webkit-scrollbar { width: 4px; } ::-webkit-scrollbar-track { background: transparent; } ::-webkit-scrollbar-thumb { background: #f48fb1; border-radius: 99px; }
    </style>
    @stack('styles')
</head>
<body>
<div class="flex h-screen overflow-hidden">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="pasienSidebar" class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col h-full bg-[#fdfafb] px-5 py-6 overflow-y-auto transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full shadow-xl lg:shadow-none">

        {{-- User Welcome --}}
        <div class="flex items-center gap-3 pt-2 pb-8 pl-2">
            <div class="w-12 h-12 rounded-full bg-primary-light flex items-center justify-center ring-2 ring-primary/20 overflow-hidden shrink-0">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover" alt="avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8bbd9&color=d81b60" class="w-full h-full object-cover" alt="avatar">
                @endif
            </div>
            <div>
                <p class="text-primary font-black text-sm leading-tight">Welcome back!</p>
                <p class="text-muted text-[11px] mt-0.5 font-medium">Stay healthy & joyful</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('pasien.dashboard') }}" class="nav-item {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="{{ route('pasien.live-queue') }}" class="nav-item {{ request()->routeIs('pasien.live-queue') ? 'active' : '' }}">
                <span class="material-symbols-outlined">assignment_add</span> Live Queue
            </a>
            <a href="{{ route('pasien.book-appointment') }}" class="nav-item {{ request()->routeIs('pasien.book-appointment') ? 'active' : '' }}">
                <span class="material-symbols-outlined">calendar_month</span> Book Appointment
            </a>
            <a href="{{ route('pasien.checkin.page') }}" class="nav-item {{ request()->routeIs('pasien.checkin.page') ? 'active' : '' }}">
                <span class="material-symbols-outlined">how_to_reg</span> Check-in
            </a>
            <a href="{{ route('pasien.medical-history') }}" class="nav-item {{ request()->routeIs('pasien.medical-history*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">history_edu</span> Medical History
            </a>
            <a href="{{ route('pasien.settings') }}" class="nav-item {{ request()->routeIs('pasien.settings*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">settings</span> Settings
            </a>
        </nav>

        {{-- Emergency Support --}}
        <div class="pt-6">
            <button class="w-full bg-[#9f2b4c] text-white text-sm font-bold py-3.5 px-4 rounded-full hover:bg-[[#872440]] transition-colors shadow-lg flex flex-col items-center justify-center gap-0.5">
                <span>Emergency</span>
                <span>Support</span>
            </button>
        </div>
    </aside>

    {{-- Sidebar overlay for mobile --}}
    <div id="pasienOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    {{-- ===================== MAIN AREA ===================== --}}
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden bg-white lg:rounded-l-[2.5rem] shadow-[-10px_0_30px_rgba(216,27,96,0.05)] lg:border-l border-pink-50">
        {{-- Top Header --}}
        <header class="bg-white border-b border-pink-50/50 px-4 lg:px-8 h-20 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <button id="pasienSidebarToggle" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-500">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <h1 class="text-[#d81b60] font-black text-lg lg:text-xl tracking-tight">Puskesmas Jagapura</h1>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-500 hover:text-[#d81b60]">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                </button>
                <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-500 hover:text-[#d81b60]">
                    <span class="material-symbols-outlined text-[20px]">help_outline</span>
                </button>
                <div class="relative group" id="profileDropdown">
                    <button class="w-10 h-10 rounded-full bg-[#f8bbd9] overflow-hidden ml-1 ring-2 ring-transparent hover:ring-[#d81b60] transition cursor-pointer flex items-center justify-center text-[#d81b60] font-bold shadow-sm"
                            onclick="toggleProfileDropdown()">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover" alt="avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div id="profileDropdownContent" class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-pink-50 py-2 hidden z-[60] animate-in fade-in slide-in-from-top-2 duration-200">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <p class="text-[13px] font-black text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('pasien.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-pink-50 hover:text-[#d81b60] transition">
                            <span class="material-symbols-outlined text-[18px]">person</span> Profile
                        </a>
                        <button onclick="document.getElementById('logout-form').submit()" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 transition">
                            <span class="material-symbols-outlined text-[18px]">logout</span> Log Out
                        </button>
                    </div>
                </div>

                {{-- Hidden Logout Form --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>
</div>

{{-- Notification Popup for "Your Turn" --}}
<div id="your-turn-popup" class="fixed bottom-4 lg:bottom-auto lg:top-8 left-1/2 -translate-x-1/2 z-[100] hidden items-center justify-between w-[92%] max-w-md bg-gradient-to-r from-[#d81b60] to-[#ad1457] text-white p-4 lg:p-5 rounded-2xl lg:rounded-[2rem] shadow-[0_20px_50px_rgba(216,27,96,0.3)] border-2 lg:border-4 border-white animate-in fade-in slide-in-from-bottom-4 duration-500">
    <div class="flex items-center gap-3 lg:gap-4">
        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-full flex items-center justify-center text-[#d81b60] shadow-sm shrink-0">
            <span class="material-symbols-outlined text-2xl lg:text-3xl font-black">campaign</span>
        </div>
        <div class="min-w-0">
            <h4 class="text-base lg:text-lg font-black leading-tight">IT'S YOUR TURN!</h4>
            <p class="text-[10px] lg:text-[11px] font-bold opacity-90 uppercase tracking-widest mt-0.5 truncate">Menuju ruang poli sekarang</p>
        </div>
    </div>
    <button onclick="hideYourTurnPopup()" class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition shrink-0 ml-2">
        <span class="material-symbols-outlined text-[18px] lg:text-[20px]">close</span>
    </button>
</div>

    <script>
        // Sidebar toggle for mobile
        const pToggle = document.getElementById('pasienSidebarToggle');
        const pSidebar = document.getElementById('pasienSidebar');
        const pOverlay = document.getElementById('pasienOverlay');

        function togglePasienSidebar() {
            pSidebar.classList.toggle('-translate-x-full');
            if (pOverlay) {
                if (pSidebar.classList.contains('-translate-x-full')) {
                    pOverlay.classList.add('opacity-0');
                    setTimeout(() => pOverlay.classList.add('hidden'), 300);
                } else {
                    pOverlay.classList.remove('hidden');
                    setTimeout(() => pOverlay.classList.remove('opacity-0'), 10);
                }
            }
        }

        if (pToggle && pSidebar) {
            pToggle.addEventListener('click', togglePasienSidebar);
        }

        if (pOverlay) {
            pOverlay.addEventListener('click', togglePasienSidebar);
        }

        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const content = document.getElementById('profileDropdownContent');
            content.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const content = document.getElementById('profileDropdownContent');
            if (dropdown && !dropdown.contains(event.target)) {
                content.classList.add('hidden');
            }
        });
    </script>
    <script>
        let yourTurnInterval = null;
        let lastIsCalled = false;

        function showYourTurnPopup() {
            const popup = document.getElementById('your-turn-popup');
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            
            // Optionally play a gentle ping sound
            // (Note: Audio might be blocked by browsers if no interaction)
        }

        function hideYourTurnPopup() {
            const popup = document.getElementById('your-turn-popup');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        function updateLiveQueue() {
            fetch("{{ route('pasien.api.live-status') }}")
                .then(response => response.json())
                .then(data => {
                    // 1. Update Dashboards (Now Serving & Feed)
                    const nowServingEl = document.getElementById('now-serving-number');
                    if (nowServingEl && data.personal) {
                        // Current calling in MY poli
                        const myPoliId = data.personal.poli_id;
                        const myPoliData = data.all_polis[myPoliId];
                        
                        if (myPoliData) {
                            const newNum = myPoliData.current_number === '-' ? '—' : myPoliData.current_number;
                            if (nowServingEl.innerText !== newNum) {
                                nowServingEl.innerText = newNum;
                            }
                        }

                        // Status Badge (Your Turn) & Logic for Popups
                        const statusContainer = document.getElementById('serving-status-container');
                        
                        if (data.personal.is_called) {
                            if (statusContainer) {
                                statusContainer.innerHTML = `
                                    <div class="mt-2 px-3 py-0.5 bg-white/20 backdrop-blur-md rounded-full shadow-sm animate-bounce">
                                        <p class="text-[9px] font-bold text-white uppercase">PROCEED TO ROOM</p>
                                    </div>
                                `;
                            }

                            // Handling the recurring popup every 10 seconds
                            if (!lastIsCalled) {
                                // First time called
                                showYourTurnPopup();
                                if (yourTurnInterval) clearInterval(yourTurnInterval);
                                yourTurnInterval = setInterval(showYourTurnPopup, 10000);
                            }
                            lastIsCalled = true;
                        } else {
                            if (statusContainer) {
                                statusContainer.innerHTML = `<p class="text-[10px] mt-2 font-bold opacity-70">Live Status</p>`;
                            }
                            // Stop the interval if no longer called
                            if (lastIsCalled) {
                                hideYourTurnPopup();
                                if (yourTurnInterval) clearInterval(yourTurnInterval);
                            }
                            lastIsCalled = false;
                        }

                        // Wait times & People ahead
                        const waitTimeEl = document.getElementById('wait-time-text');
                        if (waitTimeEl) waitTimeEl.innerText = data.personal.people_ahead * 5;
                        
                        const peopleAheadEl = document.getElementById('people-ahead-text');
                        if (peopleAheadEl) peopleAheadEl.innerText = data.personal.people_ahead;
                    }

                    // 2. Update Feed (Bottom cards)
                    const poliItems = document.querySelectorAll('.live-poli-item');
                    poliItems.forEach(item => {
                        const pid = item.getAttribute('data-poli-id');
                        if (data.all_polis[pid]) {
                            const numEl = item.querySelector('.live-poli-number');
                            if (numEl) {
                                const newNum = data.all_polis[pid].current_number === '-' ? '—' : data.all_polis[pid].current_number;
                                numEl.innerText = newNum;
                            }
                        }
                    });

                    // 3. Update Live Queue Page Cards
                    const liveMainNums = document.querySelectorAll('.live-main-number');
                    liveMainNums.forEach(numEl => {
                        const pid = numEl.getAttribute('data-poli-id');
                        if (data.all_polis[pid]) {
                            const newNum = data.all_polis[pid].current_number === '-' ? '—' : data.all_polis[pid].current_number;
                            numEl.innerText = newNum;
                        }
                    });
                })
                .catch(err => console.error('Polling error:', err));
        }

        // Start polling every 3 seconds
        updateLiveQueue();
        setInterval(updateLiveQueue, 3000);
    </script>
    @stack('scripts')
</body>
</html>
