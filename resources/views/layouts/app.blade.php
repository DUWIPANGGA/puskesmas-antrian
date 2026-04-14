<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Puskesmas') | CandyClinic</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#974169",
                        "primary-container": "#ffa7cb",
                        "on-primary": "#fff7f8",
                        "on-primary-container": "#6c1e46",
                        "secondary": "#396765",
                        "secondary-container": "#c9faf8",
                        "on-secondary-container": "#346260",
                        "tertiary": "#006b65",
                        "tertiary-container": "#6bf2e6",
                        "on-tertiary-container": "#005853",
                        "background": "#fff8f8",
                        "surface": "#fff8f8",
                        "surface-container": "#ffe8ee",
                        "surface-container-low": "#fff0f3",
                        "surface-container-high": "#ffe0ea",
                        "surface-variant": "#ffd9e5",
                        "on-surface": "#4f2438",
                        "on-surface-variant": "#835065",
                        "outline": "#a26b81",
                        "outline-variant": "#dea1b8",
                        "error": "#ac3149",
                    },
                    fontFamily: {
                        "sans": ["DM Sans", "sans-serif"],
                    },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                }
            },
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; scroll-behavior: smooth; overflow-x: hidden; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; display: inline-flex; align-items: center; justify-content: center; }
        .glass-nav { background: rgba(255, 248, 248, 0.85); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border-right: 1px solid rgba(224,64,160,0.1); }
        .nav-link { transition: all 0.2s ease; }
        .nav-link:hover, .nav-link.active { background: rgba(151,65,105,0.1); color: #974169; }
        .sidebar-icon { transition: transform 0.2s ease; }
        .nav-link:hover .sidebar-icon { transform: translateX(3px); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.35s ease forwards; }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface min-h-screen flex">

    {{-- Sidebar Navigation --}}
    @include('layouts.navigation')
    
    {{-- Sidebar overlay for mobile --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-h-screen md:ml-64">

        {{-- Top Bar --}}
        <header class="sticky top-0 z-40 h-16 glass-nav flex items-center justify-between px-6 shadow-sm">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="md:hidden w-9 h-9 rounded-full flex items-center justify-center hover:bg-surface-container transition">
                    <span class="material-symbols-outlined text-on-surface-variant">menu</span>
                </button>
                <h1 class="text-lg font-bold text-on-surface">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notifications --}}
                <button class="relative w-9 h-9 rounded-full flex items-center justify-center hover:bg-surface-container transition">
                    <span class="material-symbols-outlined text-on-surface-variant">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                </button>
                {{-- Profile --}}
                <div class="flex items-center gap-2 pl-2 border-l border-outline-variant">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-on-surface-variant">{{ ucfirst(auth()->user()->role ?? 'user') }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 fade-in">
            @if(session('success'))
                <div class="mb-4 px-5 py-3 bg-secondary-container text-on-secondary-container rounded-xl flex items-center gap-2 text-sm font-medium shadow-sm">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-5 py-3 bg-error/10 text-error rounded-xl flex items-center gap-2 text-sm font-medium shadow-sm border border-error/20">
                    <span class="material-symbols-outlined text-base">error</span>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-3 text-center text-xs text-on-surface-variant border-t border-outline-variant/30">
            © {{ date('Y') }} CandyClinic — Puskesmas Management System
        </footer>
    </div>

    <script>
        // Sidebar toggle for mobile
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            if (overlay) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                } else {
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                }
            }
        }

        if (toggle && sidebar) {
            toggle.addEventListener('click', toggleSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
    </script>
    @stack('scripts')
</body>
</html>
