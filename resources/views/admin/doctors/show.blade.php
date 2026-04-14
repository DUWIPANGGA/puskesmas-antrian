@extends('layouts.admin')

@section('title', 'Detail Dokter')
@section('page-title', 'Detail Dokter')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.doctors.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Dokter
            </a>
        </div>
        
        <div class="text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-pink-100 flex items-center justify-center text-[#d81b60] font-black text-2xl mx-auto mb-3 border-2 border-white shadow-sm overflow-hidden">
                @if($doctor->avatar)
                    <img src="{{ $doctor->avatar }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                @endif
            </div>
            <h2 class="text-xl font-black text-gray-900">{{ $doctor->name }}</h2>
            <p class="text-sm text-gray-500">{{ $doctor->email }}</p>
        </div>
        
        <div class="border-t border-gray-100 pt-6">
            <div class="space-y-4">
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Nama Lengkap</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->name }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Email</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->email }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">No. Telepon</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->phone ?? '-' }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">NIK</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->nik ?? '-' }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Alamat</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->address ?? '-' }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Tanggal Lahir</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->birth_date ? $doctor->birth_date->format('d/m/Y') : '-' }}</div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Role</div>
                    <div class="flex-1">
                        <span class="inline-block px-2 py-1 bg-pink-100 text-[#d81b60] rounded-lg text-xs font-bold">
                            Dokter
                        </span>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="w-32 text-xs font-bold text-gray-500">Tanggal Registrasi</div>
                    <div class="flex-1 text-sm text-gray-900">{{ $doctor->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" 
               class="flex-1 bg-green-600 text-white py-2 rounded-full text-sm font-bold text-center hover:bg-green-700 transition">
                Edit Dokter
            </a>
            <a href="{{ route('admin.doctors.index') }}" 
               class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-full text-sm font-bold text-center hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection