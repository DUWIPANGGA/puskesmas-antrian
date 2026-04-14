@extends('layouts.app')

@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard Dokter')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30">
    <h2 class="font-bold text-on-surface mb-4">Selamat datang, Dr. {{ auth()->user()->name }}!</h2>
    <p class="text-sm text-on-surface-variant">Anda login sebagai <span class="font-bold text-secondary">Dokter</span>. Gunakan menu di samping untuk melihat antrian dan rekam medis pasien.</p>
</div>
@endsection
