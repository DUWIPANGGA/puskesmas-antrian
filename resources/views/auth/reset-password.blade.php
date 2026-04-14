@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    {{-- Header --}}
    <div class="text-center mb-7">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-container mb-4">
            <span class="material-symbols-outlined text-on-primary-container text-3xl" style="font-variation-settings:'FILL' 1;">shield_lock</span>
        </div>
        <h1 class="text-2xl font-black text-on-surface">Buat Password Baru</h1>
        <p class="text-sm text-on-surface-variant mt-1">Masukkan password baru Anda di bawah ini</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-5 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-sm text-error flex items-start gap-2">
            <span class="material-symbols-outlined text-base mt-0.5">error</span>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Reset Password Form --}}
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email (readonly context) --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-on-surface mb-1.5">Email</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-lg">mail</span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline-variant bg-white/50 text-sm text-on-surface transition"
                >
            </div>
        </div>

        {{-- Password Baru --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-on-surface mb-1.5">Password Baru</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-lg">key</span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 karakter"
                    class="w-full pl-10 pr-12 py-3 rounded-xl border border-outline-variant bg-white/50 text-sm text-on-surface transition"
                >
                <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                </button>
            </div>
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-on-surface mb-1.5">Konfirmasi Password Baru</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-lg">key</span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                    class="w-full pl-10 pr-12 py-3 rounded-xl border border-outline-variant bg-white/50 text-sm text-on-surface transition"
                >
                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                </button>
            </div>
        </div>

        {{-- Password Strength Hint --}}
        <div id="strengthIndicator" class="hidden">
            <div class="flex gap-1 mt-1">
                <div id="bar1" class="h-1 flex-1 rounded-full bg-outline-variant transition-colors duration-300"></div>
                <div id="bar2" class="h-1 flex-1 rounded-full bg-outline-variant transition-colors duration-300"></div>
                <div id="bar3" class="h-1 flex-1 rounded-full bg-outline-variant transition-colors duration-300"></div>
                <div id="bar4" class="h-1 flex-1 rounded-full bg-outline-variant transition-colors duration-300"></div>
            </div>
            <p id="strengthText" class="text-xs mt-1 text-on-surface-variant"></p>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 mt-2"
        >
            <span class="material-symbols-outlined text-lg">done_all</span>
            Simpan Password Baru
        </button>
    </form>

    {{-- Back to login --}}
    <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary font-medium transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali ke halaman masuk
        </a>
    </div>
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

    // Password strength indicator
    const pwField = document.getElementById('password');
    const indicator = document.getElementById('strengthIndicator');
    const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
    const strengthText = document.getElementById('strengthText');
    const levels = [
        { color: 'bg-error', label: 'Lemah' },
        { color: 'bg-yellow-400', label: 'Cukup' },
        { color: 'bg-blue-400', label: 'Baik' },
        { color: 'bg-secondary', label: 'Kuat' },
    ];

    pwField.addEventListener('input', () => {
        const val = pwField.value;
        indicator.classList.toggle('hidden', val.length === 0);
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' + (i < score ? levels[score - 1].color : 'bg-outline-variant');
        });
        strengthText.textContent = score > 0 ? 'Kekuatan: ' + levels[score - 1].label : '';
    });
</script>
@endpush
