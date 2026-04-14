@extends('layouts.guest')

@section('title', 'Masuk')

@section('header')
<div class="mb-8 text-center lg:text-left shadow-none">
    <h2 class="text-2xl lg:text-3xl font-black text-on-surface tracking-tight mb-2">Selamat Datang Kembali!</h2>
    <p class="text-on-surface-variant font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>
</div>
@endsection

@section('tabs')
<!-- Tab Switcher -->
<div class="flex border-b border-surface-container-highest">
    <a href="{{ route('register') }}" class="flex-1 py-4 text-center text-sm font-bold text-secondary hover:text-primary transition-colors decoration-transparent">
        Register
    </a>
    <div class="flex-1 py-4 text-center text-sm font-bold text-primary border-b-4 border-primary bg-primary/5 cursor-default">
        Login
    </div>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

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
                autofocus
                placeholder="nama@email.com" 
            />
        </div>
    </div>
    
    <div>
        <label class="block text-sm font-bold text-on-surface mb-2" for="password">
            <div class="flex justify-between items-center">
                <span>Password</span>
                @if(Route::has('password.request'))
                    <a class="text-xs text-primary hover:underline font-bold" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>
        </label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
            <input 
                class="w-full pl-12 pr-12 py-3 bg-surface-container-low border-transparent rounded-full focus:ring-2 focus:ring-primary focus:bg-white transition-all outline-none text-on-surface" 
                id="password" 
                name="password"
                type="password" 
                required
                placeholder="••••••••" 
            />
            <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition">
                <span class="material-symbols-outlined text-lg">visibility</span>
            </button>
        </div>
    </div>

    <!-- Remember Me -->
    <div class="flex items-center gap-2 px-1">
        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded text-primary focus:ring-primary bg-surface-container-low border-transparent">
        <label for="remember_me" class="text-sm font-bold text-on-surface-variant cursor-pointer">Ingat saya</label>
    </div>

    <div class="pt-2">
        <button class="w-full py-4 bg-primary text-white font-black text-lg rounded-full shadow-[0_8px_16px_rgba(224,64,160,0.3)] hover:scale-[1.03] transition-transform active:scale-95 flex items-center justify-center gap-2" type="submit">
            Masuk Sekarang
        </button>
    </div>
</form>
@endsection

@section('footer_text')
<p class="text-sm font-medium text-on-surface-variant">
    Belum punya akun? <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">Daftar di sini</a>
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
