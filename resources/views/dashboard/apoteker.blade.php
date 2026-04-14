@extends('layouts.app')

@section('title', 'Dashboard Apoteker')
@section('page-title', 'Dashboard Apoteker')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30">
    <h2 class="font-bold text-on-surface mb-4">Selamat datang, {{ auth()->user()->name }}!</h2>
    <p class="text-sm text-on-surface-variant">Anda login sebagai <span class="font-bold text-tertiary">Apoteker</span>. Gunakan menu di samping untuk mengelola resep dan stok obat.</p>
</div>
@endsection
