<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Admin') | Puskesmas Jagapura</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":     "#d81b60", // Adjusted to match the image pink
                        "primary-light": "#f8bbd9",
                        "primary-soft": "#fce4ec",
                        "on-primary":  "#ffffff",
                        "surface":     "#ffffff",
                        "surface-card":"#ffffff",
                        "on-surface":  "#2e1a28",
                        "muted":       "#78909c", // Slate/Blue gray for inactive icons
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
        html, body { height: 100%; margin: 0; font-family: 'DM Sans', sans-serif; background: #fafafa; }
        .nav-item { 
            display: flex; align-items: center; gap: 14px; padding: 12px 18px; 
            border-radius: 50px; font-size: 15px; font-weight: 600; 
            color: #78909c; cursor: pointer; transition: all 0.18s ease; text-decoration: none; 
            margin-bottom: 8px;
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
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col h-full bg-surface border-r border-gray-100 px-5 py-6 overflow-y-auto transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full shadow-xl lg:shadow-none">

        {{-- User Welcome --}}
        <div class="flex flex-col items-center text-center pt-2 pb-8">
            <div class="w-16 h-16 rounded-full bg-primary-light flex items-center justify-center mb-3 ring-2 ring-primary/20 overflow-hidden">
                @if(auth()->user()->avatar ?? false)
                    <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover" alt="avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8bbd9&color=d81b60" class="w-full h-full object-cover" alt="avatar">
                @endif
            </div>
            <p class="text-primary font-black text-sm leading-tight">Welcome back!</p>
            <p class="text-muted text-xs mt-0.5 font-medium">Stay healthy & joyful</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('admin.clinic-quotas.index') }}" class="nav-item {{ request()->routeIs('admin.clinic-quotas.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">chair_alt</span> Clinic Quotas
            </a>
            <a href="{{ route('admin.patient-checkins.index') }}" class="nav-item {{ request()->routeIs('admin.patient-checkins.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">how_to_reg</span> Patient Check-ins
            </a>
            <a href="{{ route('admin.queue-control.index') }}" class="nav-item {{ request()->routeIs('admin.queue-control.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">campaign</span> Queue Control
            </a>
            <a href="{{ route('admin.display') }}" target="_blank" class="nav-item {{ request()->routeIs('admin.display') && !request()->routeIs('*.pemeriksaan') ? 'active' : '' }}">
                <span class="material-symbols-outlined">tv</span> Display Loket
            </a>
            <a href="{{ route('admin.display.pemeriksaan') }}" target="_blank" class="nav-item {{ request()->routeIs('admin.display.pemeriksaan') ? 'active' : '' }}">
                <span class="material-symbols-outlined">stethoscope</span> Display Poli
            </a>
            <a href="{{ route('admin.reset-queues.index') }}" class="nav-item {{ request()->routeIs('admin.reset-queues.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">history</span> Reset Queues
            </a>
            <a href="{{ route('admin.visit-reports.index') }}" class="nav-item {{ request()->routeIs('admin.visit-reports.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">bar_chart</span> Visit Reports
            </a>
            <a href="{{ route('admin.health-tips.index') }}" class="nav-item {{ request()->routeIs('admin.health-tips.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">lightbulb</span> Health Tips
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">medical_information</span> Doctors
            </a>
            <a href="{{ route('admin.clinics.index') }}" class="nav-item {{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">local_hospital</span> Clinics
            </a>
        </nav>

        {{-- Emergency Support & Footer Profile --}}
        <div class="pt-6 relative">
            <div class="absolute top-0 left-0 right-0 h-px bg-gray-100"></div>
            
            <button class="w-full bg-[#df3d8b] text-white text-sm font-bold py-3 px-4 rounded-full hover:bg-primary transition-colors shadow flex items-center justify-center gap-2 mb-6">
                <span class="material-symbols-outlined text-lg">podcasts</span>
                Emergency Alert
            </button>

            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=eceff1&color=374151" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-black text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-muted uppercase font-bold tracking-wider mt-0.5">Admin</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Sidebar overlay for mobile --}}
    <div id="adminOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    {{-- ===================== MAIN AREA ===================== --}}
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden bg-[#fafafa]">
        {{-- Top Header --}}
        <header class="bg-white border-b border-pink-50 px-4 lg:px-8 h-16 flex items-center justify-between shrink-0 shadow-sm gap-3">
            <div class="flex items-center gap-3">
                <button id="adminSidebarToggle" class="lg:hidden w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition border border-gray-100 shadow-sm text-gray-500">
                    <span class="material-symbols-outlined text-[20px]">menu</span>
                </button>
                <h1 class="text-[#d81b60] font-black text-lg lg:text-xl tracking-tight flex items-center gap-2">Puskesmas Jagapura</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition">
                    <span class="material-symbols-outlined text-gray-400 text-xl">notifications</span>
                </button>
                <button class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-pink-50 transition">
                    <span class="material-symbols-outlined text-gray-400 text-xl">help_outline</span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#f8bbd9] overflow-hidden ml-2 ring-2 ring-transparent hover:ring-[#d81b60] transition cursor-pointer flex items-center justify-center text-[#d81b60] font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                {{-- Hidden Logout Form triggered by avatar --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>
                <button onclick="document.getElementById('logout-form').submit()" class="text-xs text-gray-400 hover:text-[#d81b60] font-medium ml-1" title="Keluar">
                    <span class="material-symbols-outlined text-base">logout</span>
                </button>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-8 mt-5 px-4 py-3 bg-green-50 text-green-700 rounded-xl text-sm font-medium flex items-center gap-2 border border-green-100">
                <span class="material-symbols-outlined text-base">check_circle</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-8 mt-5 px-4 py-3 bg-red-50 text-red-600 rounded-xl text-sm font-medium flex items-center gap-2 border border-red-100">
                <span class="material-symbols-outlined text-base">error</span> {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>
<script>
    // Sidebar toggle for mobile
    const aToggle = document.getElementById('adminSidebarToggle');
    const aSidebar = document.getElementById('adminSidebar');
    const aOverlay = document.getElementById('adminOverlay');

    function toggleAdminSidebar() {
        aSidebar.classList.toggle('-translate-x-full');
        if (aOverlay) {
            if (aSidebar.classList.contains('-translate-x-full')) {
                aOverlay.classList.add('opacity-0');
                setTimeout(() => aOverlay.classList.add('hidden'), 300);
            } else {
                aOverlay.classList.remove('hidden');
                setTimeout(() => aOverlay.classList.remove('opacity-0'), 10);
            }
        }
    }

    if (aToggle && aSidebar) {
        aToggle.addEventListener('click', toggleAdminSidebar);
    }

    if (aOverlay) {
        aOverlay.addEventListener('click', toggleAdminSidebar);
    }
</script>
@stack('scripts')
</body>
</html>
