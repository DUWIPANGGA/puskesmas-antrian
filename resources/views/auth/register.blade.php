@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('header')
<div class="mb-8 text-center lg:text-left shadow-none">
    <h2 class="text-2xl lg:text-3xl font-black text-on-surface tracking-tight mb-2">Buat Akun Baru</h2>
    <p class="text-on-surface-variant font-medium">Daftarkan diri sebagai pasien dan mulai layanan kesehatan.</p>
</div>
@endsection

@section('tabs')
{{-- Tab Switcher --}}
<div class="flex border-b border-surface-container-highest">
    <div class="flex-1 py-4 text-center text-sm font-bold text-primary border-b-4 border-primary bg-primary/5 cursor-default">
        Register
    </div>
    <a href="{{ route('login') }}" class="flex-1 py-4 text-center text-sm font-bold text-secondary hover:text-primary transition-colors decoration-transparent">
        Login
    </a>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    {{-- Nama Lengkap --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="name">Nama Lengkap</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">person</span>
            <input
                class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Nama lengkap Anda"
            />
        </div>
    </div>

    {{-- NIK --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="nik">NIK</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">badge</span>
            <input
                class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="nik"
                name="nik"
                type="text"
                value="{{ old('nik') }}"
                required
                maxlength="16"
                placeholder="16 digit NIK KTP"
            />
        </div>
    </div>

    {{-- Tanggal Lahir --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="tanggal_lahir">Tanggal Lahir</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">cake</span>
            <input
                class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="tanggal_lahir"
                name="tanggal_lahir"
                type="date"
                value="{{ old('tanggal_lahir') }}"
                required
            />
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="email">Alamat Email</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">mail</span>
            <input
                class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="nama@email.com"
            />
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="password">Password</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
            <input
                class="w-full pl-12 pr-12 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Min. 8 karakter"
            />
            <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition">
                <span class="material-symbols-outlined text-lg">visibility</span>
            </button>
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="password_confirmation">Konfirmasi Password</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
            <input
                class="w-full pl-12 pr-12 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Ulangi password Anda"
            />
            <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition">
                <span class="material-symbols-outlined text-lg">visibility</span>
            </button>
        </div>
    </div>

    <div class="pt-2">
        <button class="w-full py-4 bg-primary text-white font-black text-lg rounded-full shadow-[0_8px_16px_rgba(224,64,160,0.3)] hover:scale-[1.03] transition-transform active:scale-95 flex items-center justify-center gap-2" type="submit">
            Daftar Sekarang
        </button>
    </div>
</form>
@endsection

@section('footer_text')
<p class="text-sm font-medium text-on-surface-variant">
    Sudah punya akun? <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Masuk di sini</a>
</p>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('.material-symbols-outlined');
        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            field.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush
