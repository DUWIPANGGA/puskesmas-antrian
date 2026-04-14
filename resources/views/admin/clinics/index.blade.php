{{-- resources/views/admin/clinics/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Klinik')
@section('page-title', 'Manajemen Klinik')

@section('content')
<div class="px-2">
    {{-- Header section --}}
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Clinic & Quota</h1>
            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wider">Manage daily operations and patient limits across all departments.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-[#e9e3f7] text-[#6b4fa3] px-6 py-2.5 rounded-2xl text-xs font-black flex items-center gap-2 hover:bg-[#dcd1f0] transition">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            <a href="{{ route('admin.clinics.create') }}" class="bg-[#df3d8b] text-white px-6 py-2.5 rounded-2xl text-xs font-black flex items-center gap-2 shadow-lg shadow-pink-200 hover:bg-[#c2185b] transition">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Add New Clinic
            </a>
        </div>
    </div>

    {{-- Clinics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @forelse($clinics as $index => $clinic)
            @php
                $percentage = $clinic->kuota_today > 0 ? ($clinic->terpakai_today / $clinic->kuota_today) * 100 : 0;
                $isFull = $percentage >= 100;
                $isCardiology = $index === 0; // For demo, make the first one the "Featured" large card
            @endphp

            @if($isCardiology && $loop->first)
                {{-- Featured Large Card --}}
                <div class="col-span-1 md:col-span-2 bg-white rounded-[2.5rem] p-10 shadow-sm border border-pink-50 flex flex-col md:flex-row gap-10 items-center relative overflow-hidden group">
                    {{-- Small decorative icon --}}
                    <div class="absolute top-8 left-8">
                        <div class="w-12 h-12 rounded-2xl bg-pink-100/50 text-pink-500 flex items-center justify-center">
                            <i class="{{ $clinic->icon ?? 'fa-solid fa-heart-pulse' }} text-xl"></i>
                        </div>
                    </div>

                    <div class="flex-1 w-full mt-12 md:mt-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-pink-50 text-pink-600 text-[10px] font-black rounded-full uppercase tracking-widest">Priority Hub</span>
                        </div>
                        <h2 class="text-4xl font-black text-gray-900 leading-tight mb-4">{{ $clinic->nama_poli }}</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">{{ $clinic->deskripsi ?? 'Specialized Medical Center' }}</p>
                        
                        <div class="flex gap-8 items-center">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Doctors</span>
                                <span class="text-2xl font-black text-gray-900">08</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Avg. Wait Time</span>
                                <span class="text-2xl font-black text-gray-900">14<span class="text-sm"> m</span></span>
                            </div>
                        </div>

                        <div class="mt-8 w-full max-w-xs">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[10px] font-black text-gray-900 uppercase">Current Load</span>
                                <span class="text-[10px] font-black text-gray-900 uppercase">{{ round($percentage) }}%</span>
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pink-400 to-purple-600 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-48">
                        <a href="{{ route('admin.clinics.doctors', $clinic->id) }}" class="w-full border-2 border-pink-200 text-pink-600 py-4 rounded-2xl text-xs font-black text-center hover:bg-pink-50 transition uppercase tracking-widest">Details</a>
                        <a href="{{ route('admin.clinics.edit', $clinic->id) }}" class="w-full bg-[#df3d8b] text-white py-4 rounded-2xl text-xs font-black text-center shadow-lg shadow-pink-200 hover:bg-[#c2185b] transition uppercase tracking-widest">Settings</a>
                    </div>
                </div>
            @else
                {{-- Regular Clinic Card --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl {{ $isFull ? 'bg-red-50 text-red-500' : 'bg-pink-50 text-pink-500' }} flex items-center justify-center transition-colors">
                            <i class="{{ $clinic->icon ?? 'fa-solid fa-hospital' }} text-2xl"></i>
                        </div>
                        <a href="{{ route('admin.clinics.edit', $clinic->id) }}" class="text-gray-300 hover:text-gray-600 transition">
                            <span class="material-symbols-outlined text-[20px]">edit_square</span>
                        </a>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 mb-1">{{ $clinic->nama_poli }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">location_on</span> Building A • Level {{ $loop->iteration }}
                    </p>

                    <div class="mt-auto">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black text-gray-900 uppercase tracking-tight">Daily Quota Usage</span>
                            <span class="text-[10px] font-black {{ $isFull ? 'text-red-500' : 'text-gray-400' }} uppercase">
                                <span class="text-gray-900">{{ $clinic->terpakai_today }}</span> / {{ $clinic->kuota_today }}
                            </span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden mb-4">
                            <div class="h-full {{ $isFull ? 'bg-red-500' : 'bg-pink-500' }} rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                        </div>

                        @if($isFull)
                            <div class="flex items-center gap-1 text-red-500 text-[9px] font-black uppercase mb-6">
                                <span class="material-symbols-outlined text-[14px]">warning</span> Quota Full
                            </div>
                        @endif

                        <div class="pt-6 border-t border-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-black text-gray-400 uppercase">Quick Update Quota</span>
                                <a href="{{ route('admin.clinics.quota', $clinic->id) }}" class="w-8 h-8 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center text-[10px] font-black hover:bg-pink-100 transition">
                                    {{ $clinic->kuota_today }}
                                </a>
                            </div>
                            <div class="mt-2 h-1 bg-gray-100 rounded-full relative">
                                <div class="absolute top-1/2 left-{{ floor(($clinic->kuota_today / 500) * 100) }}% -translate-y-1/2 w-3 h-3 bg-white border-2 border-pink-500 rounded-full shadow-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-span-full py-20 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 flex flex-col items-center">
                 <span class="material-symbols-outlined text-gray-300 text-6xl">home_health</span>
                 <p class="text-gray-400 font-bold mt-4">Belum ada klinik terdaftar</p>
                 <a href="{{ route('admin.clinics.create') }}" class="text-pink-500 text-sm font-black mt-2 hover:underline">Add Your First Clinic</a>
            </div>
        @endforelse

        {{-- Add Department Card --}}
        <a href="{{ route('admin.clinics.create') }}" class="group bg-gray-50/50 rounded-[2rem] border-2 border-dashed border-gray-100 p-8 flex flex-col items-center justify-center text-center hover:border-pink-200 hover:bg-pink-50/30 transition-all duration-300 min-h-[300px]">
            <div class="w-14 h-14 rounded-full bg-white border border-gray-100 text-gray-300 group-hover:text-pink-500 group-hover:border-pink-200 flex items-center justify-center mb-6 transition shadow-sm">
                <span class="material-symbols-outlined text-4xl">add</span>
            </div>
            <h3 class="text-lg font-black text-gray-400 group-hover:text-pink-500 transition">Add Department</h3>
            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1">Configure new clinic quotas</p>
        </a>
    </div>

    {{-- Live System Pulse --}}
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50">
        <h2 class="text-2xl font-black text-gray-900 mb-8">Live System Pulse</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Stat 1 --}}
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">trending_up</span>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($totalPatientsToday) }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Daily Patients</div>
                </div>
            </div>

            {{-- Stat 2 --}}
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">history</span>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ $quotaEfficiency }}%</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Quota Efficiency</div>
                </div>
            </div>

            {{-- Stat 3 --}}
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">speed</span>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ $activeClinicsCount }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Active Clinics</div>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-10 border-t border-gray-50">
            <button class="bg-gray-900 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-black transition shadow-xl shadow-gray-200">
                Generate Daily Report
            </button>
        </div>
    </div>
</div>
@endsection