@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Stat Card --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings:'FILL' 1;">people</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-medium">Total Pengguna</p>
            <p class="text-2xl font-black text-on-surface">—</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings:'FILL' 1;">queue</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-medium">Antrian Hari Ini</p>
            <p class="text-2xl font-black text-on-surface">—</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-tertiary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings:'FILL' 1;">medical_services</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-medium">Total Poli</p>
            <p class="text-2xl font-black text-on-surface">—</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-error text-2xl" style="font-variation-settings:'FILL' 1;">bar_chart</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-medium">Kunjungan Bulan Ini</p>
            <p class="text-2xl font-black text-on-surface">—</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30">
    <h2 class="font-bold text-on-surface mb-4">Selamat datang, {{ auth()->user()->name }}!</h2>
    <p class="text-sm text-on-surface-variant">Anda login sebagai <span class="font-bold text-primary">Admin</span>. Gunakan menu di samping untuk mengelola sistem.</p>
</div>
@endsection
