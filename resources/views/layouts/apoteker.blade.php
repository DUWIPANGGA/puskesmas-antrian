<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apoteker Portal') | Puskesmas Jagapura</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":      "#d81b60",
                        "primary-deep": "#880e4f",
                        "primary-soft": "#fce4ec",
                        "apo-pink":     "#f06292",
                        "apo-teal":     "#2d7a6e",
                    },
                    fontFamily: { sans: ["DM Sans", "sans-serif"] },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        html, body { height: 100%; margin: 0; font-family: 'DM Sans', sans-serif; background: #fdf8fb; }
        .nav-item {
            display: flex; align-items: center; gap: 14px; padding: 12px 18px;
            border-radius: 50px; font-size: 13px; font-weight: 700;
            color: #78716c; cursor: pointer; transition: all 0.18s ease; text-decoration: none;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: #fce4ec; color: #d81b60; }
        .nav-item.active { background: #fce4ec; color: #d81b60; }
        .nav-item .material-symbols-outlined { font-size: 20px; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #f48fb1; border-radius: 99px; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadein { animation: fadeSlideIn 0.35s ease forwards; }
    </style>
    @stack('styles')
</head>
<body>
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside id="apoSidebar" class="fixed inset-y-0 left-0 z-50 w-60 flex flex-col h-full bg-white px-4 py-6 overflow-y-auto border-r border-pink-50 transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full shadow-xl lg:shadow-none">

        {{-- Welcome --}}
        <div class="pt-2 pb-8 px-2">
            <p class="text-[#d81b60] font-black text-base leading-tight">Welcome Back</p>
            <p class="text-[#d81b60] font-black text-base leading-tight">Apothecary!</p>
            <p class="text-gray-400 text-[10px] mt-1 font-black uppercase tracking-widest">Apothecary Portal</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex flex-col gap-0.5 flex-1">
            <a href="{{ route('apoteker.incoming') }}" class="nav-item {{ request()->routeIs('apoteker.incoming') || request()->routeIs('apoteker.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">pill</span> Incoming Prescriptions
            </a>
            <a href="{{ route('apoteker.in-process') }}" class="nav-item {{ request()->routeIs('apoteker.in-process') ? 'active' : '' }}">
                <span class="material-symbols-outlined">hourglass_top</span> In Process
            </a>
            <a href="{{ route('apoteker.completed') }}" class="nav-item {{ request()->routeIs('apoteker.completed') ? 'active' : '' }}">
                <span class="material-symbols-outlined">task_alt</span> Completed
            </a>
            <a href="{{ route('apoteker.reports') }}" class="nav-item {{ request()->routeIs('apoteker.reports') ? 'active' : '' }}">
                <span class="material-symbols-outlined">bar_chart</span> Reports
            </a>
        </nav>

        {{-- Bottom: New Prescription Button + Profile --}}
        <div class="pt-6 border-t border-gray-100 flex flex-col gap-4">
            <div class="flex items-center justify-between gap-2 px-1">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-9 h-9 rounded-full overflow-hidden shrink-0">
                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'APO').'&background=fce4ec&color=d81b60' }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Apoteker</p>
                    </div>
                </div>
                <button onclick="document.getElementById('logout-form-apo').submit()"
                        class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition shrink-0" title="Logout">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-apo" class="hidden">@csrf</form>
        </div>
    </aside>

    {{-- Sidebar overlay for mobile --}}
    <div id="apoOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden bg-[#fdf8fb]">
        {{-- Top Header --}}
        <header class="bg-transparent px-4 lg:px-8 pt-6 pb-3 flex items-center justify-between shrink-0 gap-3">
            <div class="flex items-center gap-3">
                <button id="apoSidebarToggle" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-400">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <h1 class="text-[#d81b60] font-black text-lg lg:text-xl tracking-tight">Puskesmas Jagapura</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition text-gray-400">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                </button>
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition text-gray-400">
                    <span class="material-symbols-outlined text-[20px]">help_outline</span>
                </button>
                <div class="w-9 h-9 rounded-full overflow-hidden ml-2">
                    <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'APO').'&background=fce4ec&color=d81b60' }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-8 py-2 pb-8">
            @yield('content')
        </main>
    </div>
</div>
<script>
    // Sidebar toggle for mobile
    const apoToggle = document.getElementById('apoSidebarToggle');
    const apoSidebar = document.getElementById('apoSidebar');
    const apoOverlay = document.getElementById('apoOverlay');

    function toggleApoSidebar() {
        apoSidebar.classList.toggle('-translate-x-full');
        if (apoOverlay) {
            if (apoSidebar.classList.contains('-translate-x-full')) {
                apoOverlay.classList.add('opacity-0');
                setTimeout(() => apoOverlay.classList.add('hidden'), 300);
            } else {
                apoOverlay.classList.remove('hidden');
                setTimeout(() => apoOverlay.classList.remove('opacity-0'), 10);
            }
        }
    }

    if (apoToggle && apoSidebar) {
        apoToggle.addEventListener('click', toggleApoSidebar);
    }

    if (apoOverlay) {
        apoOverlay.addEventListener('click', toggleApoSidebar);
    }
</script>
@stack('scripts')
</body>
</html>
