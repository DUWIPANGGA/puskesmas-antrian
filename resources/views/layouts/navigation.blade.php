<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 glass-nav z-50 flex flex-col transition-transform duration-300 md:translate-x-0 -translate-x-full shadow-xl">

    {{-- Logo --}}
    <div class="flex items-center gap-2 px-5 h-16 border-b border-outline-variant/30">
        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings:'FILL' 1;">favorite</span>
        <span class="text-lg font-black bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Puskesmas Jagapura</span>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- ===== ADMIN MENU ===== --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest px-3 pt-2 pb-1">Admin Panel</p>

            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">dashboard</span> Dashboard
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">medical_information</span> Manajemen Dokter
            </a>
            <a href="{{ route('admin.clinics.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">medical_services</span> Manajemen Poli
            </a>
            <a href="{{ route('admin.clinic-quotas.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.clinic-quotas.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">calendar_today</span> Kuota Harian
            </a>
            <a href="{{ route('admin.health-tips.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.health-tips.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">lightbulb</span> Health Tips
            </a>
            <a href="{{ route('admin.visit-reports.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface {{ request()->routeIs('admin.visit-reports.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-xl sidebar-icon">bar_chart</span> Laporan Kunjungan
            </a>
        @endif

        {{-- ===== DOKTER MENU ===== --}}
        @if(auth()->check() && auth()->user()->role === 'dokter')
            <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest px-3 pt-2 pb-1">Dokter</p>

            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">dashboard</span> Dashboard
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">queue</span> Antrian Hari Ini
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">description</span> Rekam Medis
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">medication</span> Resep Obat
            </a>
        @endif

        {{-- ===== PERAWAT MENU ===== --}}
        @if(auth()->check() && auth()->user()->role === 'perawat')
            <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest px-3 pt-2 pb-1">Perawat</p>

            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">dashboard</span> Dashboard
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">queue</span> Kelola Antrian
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">vital_signs</span> Pemeriksaan Awal
            </a>
        @endif

        {{-- ===== APOTEKER MENU ===== --}}
        @if(auth()->check() && auth()->user()->role === 'apoteker')
            <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest px-3 pt-2 pb-1">Apotek</p>

            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">dashboard</span> Dashboard
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">medication</span> Kelola Resep
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">inventory</span> Stok Obat
            </a>
        @endif

        {{-- ===== PASIEN MENU ===== --}}
        @if(auth()->check() && auth()->user()->role === 'pasien')
            <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest px-3 pt-2 pb-1">Pasien</p>

            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">dashboard</span> Dashboard
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">add_circle</span> Daftar Antrian
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">pending</span> Status Antrian
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-on-surface">
                <span class="material-symbols-outlined text-xl sidebar-icon">history</span> Riwayat Kunjungan
            </a>
        @endif

    </nav>

    {{-- Logout --}}
    <div class="p-3 border-t border-outline-variant/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-error hover:bg-error/10 transition">
                <span class="material-symbols-outlined text-xl">logout</span> Keluar
            </button>
        </form>
    </div>
</aside>
