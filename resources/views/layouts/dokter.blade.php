<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doctor Portal') | Puskesmas Jagapura</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":     "#d81b60", 
                        "primary-light": "#f8bbd9",
                        "primary-soft": "#fce4ec",
                    },
                    fontFamily: { sans: ["DM Sans", "sans-serif"] },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
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

    {{-- SIDEBAR --}}
    <aside id="dokterSidebar" class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col h-full bg-[#fdfafb] px-5 py-6 overflow-y-auto transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full shadow-xl lg:shadow-none">

        {{-- Welcome --}}
        <div class="pt-2 pb-8 pl-2">
            <p class="text-[#714bca] font-black text-base leading-tight">Welcome Back Doctor!</p>
            <p class="text-gray-400 text-xs mt-0.5 font-bold">Doctor Portal</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('dokter.dashboard') }}" class="nav-item {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="{{ route('dokter.my-patients') }}" class="nav-item {{ request()->routeIs('dokter.my-patients') ? 'active' : '' }}">
                <span class="material-symbols-outlined">groups</span> My Patients
            </a>
            <a href="{{ route('dokter.history') }}" class="nav-item {{ request()->routeIs('dokter.history') ? 'active' : '' }}">
                <span class="material-symbols-outlined">history</span> History
            </a>
            <a href="{{ route('dokter.settings') }}" class="nav-item {{ request()->routeIs('dokter.settings') ? 'active' : '' }}">
                <span class="material-symbols-outlined">settings</span> Settings
            </a>
        </nav>

        {{-- Profile / Action --}}
        <div class="pt-6 border-t border-gray-100 flex flex-col gap-4">
            <button class="w-full bg-[#df3d8b] text-white text-[13px] font-bold py-3.5 px-4 rounded-full hover:bg-[#c2185b] transition shadow flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">add</span>
                New Appointment
            </button>
            
            <div class="flex items-center justify-between gap-1 px-1">
                <a href="{{ route('dokter.settings') }}" class="flex items-center gap-3 px-2 py-2 cursor-pointer hover:bg-white/60 rounded-2xl transition flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center overflow-hidden shrink-0 ring-2 ring-transparent transition">
                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Doctor').'&background=e0f2f1&color=00897b' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5 truncate">{{ auth()->user()->dokter->poli->nama_poli ?? 'POLI UMUM' }}</p>
                    </div>
                </a>
                <button onclick="document.getElementById('logout-form').submit()" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition shrink-0" title="Keluar">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                </button>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
        </div>
    </aside>

    {{-- Sidebar overlay for mobile --}}
    <div id="dokterOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden bg-[#faf8fb] lg:rounded-l-[2rem] lg:border-l border-pink-50">
        {{-- Top Header --}}
        <header class="bg-transparent px-4 lg:px-8 pt-6 lg:pt-8 pb-4 flex items-center justify-between shrink-0 gap-3">
            <div class="flex items-center gap-3">
                <button id="dokterSidebarToggle" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-500">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <h1 class="text-[#d81b60] font-black text-lg lg:text-xl tracking-tight">Puskesmas Jagapura</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition text-gray-500">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                </button>
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition text-gray-500">
                    <span class="material-symbols-outlined text-[20px]">help_outline</span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#f4b6a4] text-white flex items-center justify-center font-bold text-xs ml-2 cursor-pointer shadow-sm overflow-hidden">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}
                    @endif
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-8 py-4">
            @yield('content')
        </main>
    </div>
</div>
<script>
    // Sidebar toggle for mobile
    const dToggle = document.getElementById('dokterSidebarToggle');
    const dSidebar = document.getElementById('dokterSidebar');
    const dOverlay = document.getElementById('dokterOverlay');

    function toggleDokterSidebar() {
        dSidebar.classList.toggle('-translate-x-full');
        if (dOverlay) {
            if (dSidebar.classList.contains('-translate-x-full')) {
                dOverlay.classList.add('opacity-0');
                setTimeout(() => dOverlay.classList.add('hidden'), 300);
            } else {
                dOverlay.classList.remove('hidden');
                setTimeout(() => dOverlay.classList.remove('opacity-0'), 10);
            }
        }
    }

    if (dToggle && dSidebar) {
        dToggle.addEventListener('click', toggleDokterSidebar);
    }

    if (dOverlay) {
        dOverlay.addEventListener('click', toggleDokterSidebar);
    }
</script>
@stack('scripts')
</body>
</html>
