@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
    {{-- Header --}}
    <div class="text-center mb-7">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-tertiary-container mb-4">
            <span class="material-symbols-outlined text-on-tertiary-container text-3xl" style="font-variation-settings:'FILL' 1;">lock_reset</span>
        </div>
        <h1 class="text-2xl font-black text-on-surface">Lupa Password?</h1>
        <p class="text-sm text-on-surface-variant mt-1 max-w-xs mx-auto">
            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk reset password.
        </p>
    </div>

    {{-- Status Message --}}
    @if(session('status'))
        <div class="mb-5 px-4 py-3 bg-secondary-container/60 border border-secondary-container rounded-xl text-sm text-on-secondary-container flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

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

    {{-- Forgot Password Form --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-on-surface mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-lg">mail</span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline-variant bg-white/50 text-sm text-on-surface transition"
                >
            </div>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 mt-2"
        >
            <span class="material-symbols-outlined text-lg">send</span>
            Kirim Link Reset Password
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
