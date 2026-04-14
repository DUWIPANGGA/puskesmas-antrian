{{-- resources/views/admin/doctors/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Doctor Management')
@section('page-title', 'Doctor Management')

@section('content')
<div class="flex flex-col gap-8 pb-12">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Doctor Management</h1>
            <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">Manage and monitor {{ $doctors->count() }} active practitioners in your facility.</p>
        </div>
        <a href="{{ route('admin.doctors.create') }}" 
           class="bg-[#d81b60] text-white px-10 py-4 rounded-full text-base font-black hover:bg-[#c2185b] transition shadow-xl shadow-pink-500/20 flex items-center gap-3 active:scale-95">
            <span class="material-symbols-outlined text-[24px]">person_add</span>
            Add New Doctor
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Staff --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-pink-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="w-16 h-16 rounded-3xl bg-pink-50 text-[#d81b60] flex items-center justify-center shrink-0 relative z-10">
                 <span class="material-symbols-outlined text-3xl">stethoscope</span>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">TOTAL STAFF</p>
                <div class="text-4xl font-black text-gray-900 leading-none">{{ $doctors->count() }}</div>
            </div>
        </div>

        {{-- On-Duty --}}
        @php
            $onDuty = $doctors->filter(function($d) {
                return $d->jadwalDokter->contains('hari', now()->locale('id')->dayName);
            })->count();
        @endphp
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 relative z-10">
                 <span class="material-symbols-outlined text-3xl">verified</span>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">ON-DUTY</p>
                <div class="text-4xl font-black text-gray-900 leading-none">{{ $onDuty }}</div>
            </div>
        </div>

        {{-- Avg Rating --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="w-16 h-16 rounded-3xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 relative z-10">
                 <span class="material-symbols-outlined text-3xl">assignment_turned_in</span>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">AVG. RATING</p>
                <div class="text-4xl font-black text-gray-900 leading-none">4.9</div>
            </div>
        </div>
    </div>

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-[1.5rem] text-green-700 text-sm font-bold flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-[1.5rem] text-red-700 text-sm font-bold flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Cards Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($doctors as $doctor)
        @php
            $spec = $doctor->dokter->keahlian ?? ($doctor->dokter->poli->nama_poli ?? 'General Medicine');
            $role = $doctor->dokter->alumni ?? 'General Practitioner';
        @endphp
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 relative group hover:border-pink-200 transition-all flex flex-col hover:shadow-xl hover:shadow-pink-500/5">
            {{-- Header info: Rating & Badge --}}
            <div class="flex justify-between items-start mb-6 w-full">
                <span class="bg-pink-100 text-[#d81b60] text-[10px] font-black uppercase tracking-[0.15em] px-4 py-1.5 rounded-full border border-pink-200">
                    {{ $spec }}
                </span>
                <div class="flex items-center gap-1.5 text-purple-600 bg-purple-50 px-3 py-1.5 rounded-full border border-purple-100">
                    <span class="material-symbols-outlined text-sm fill-1">star</span>
                    <span class="text-[11px] font-black">4.{{ rand(7,9) }}</span>
                </div>
            </div>

            {{-- Doctor Profile --}}
            <div class="flex items-center gap-5 mb-8">
                <div class="w-20 h-20 rounded-3xl overflow-hidden bg-gray-50 border-4 border-white shadow-md relative group-hover:scale-105 transition-transform">
                    @if($doctor->photo)
                        <img src="{{ asset('storage/' . $doctor->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-50 to-white text-[#d81b60] font-black text-2xl uppercase">
                             {{ substr($doctor->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ $doctor->name }}</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1 opacity-70">{{ $role }}</p>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="space-y-4 mb-10">
                <div class="flex items-center gap-4 text-gray-600">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">mail</span>
                    </div>
                    <span class="text-xs font-bold leading-none truncate" title="{{ $doctor->email }}">{{ $doctor->email }}</span>
                </div>
                <div class="flex items-center gap-4 text-gray-600">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">phone_iphone</span>
                    </div>
                    <span class="text-xs font-bold leading-none">{{ $doctor->phone ?? '+62 000-000-000' }}</span>
                </div>
                <div class="flex items-center gap-4 text-gray-600">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                    <span class="text-xs font-bold leading-none">
                        @if($doctor->jadwalDokter->count() > 0)
                            @php
                                $jadwal = $doctor->jadwalDokter->first();
                            @endphp
                            {{ $jadwal->hari }} • {{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}
                        @else
                            No Active Schedule
                        @endif
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-auto flex gap-4 pt-2">
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" 
                   class="flex-1 bg-gray-100 hover:bg-[#1b4353] hover:text-white text-gray-800 py-4 rounded-3xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                </a>
                <button onclick="confirmDelete({{ $doctor->id }}, '{{ $doctor->name }}')" 
                        class="w-14 h-14 rounded-3xl border border-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-inner active:scale-95">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
            </div>
        </div>
        @endforeach

        {{-- Add Member Placeholder Card --}}
        <a href="{{ route('admin.doctors.create') }}" 
           class="bg-[#fff9fc] rounded-[2.5rem] p-8 border-2 border-dashed border-[#f8bbd0]/50 flex flex-col items-center justify-center text-center group hover:bg-white hover:border-[#f06292] transition-all min-h-[400px]">
            <div class="w-20 h-20 rounded-full bg-[#fce4ec] text-[#d81b60] flex items-center justify-center mb-8 group-hover:scale-110 transition-transform shadow-inner border border-white">
                <span class="material-symbols-outlined text-4xl">add</span>
            </div>
            <h4 class="text-2xl font-black text-gray-900 leading-tight">Add Member</h4>
            <p class="text-[13px] font-bold text-gray-400 mt-2">Register a new specialist</p>
        </a>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-[2.5rem] p-10 max-w-md w-full mx-4 shadow-2xl transform transition-all scale-90 opacity-0" id="deleteModalContent">
        <div class="text-center">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 border-8 border-red-50">
                <span class="material-symbols-outlined text-red-600 text-5xl">warning</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Hapus Dokter?</h3>
            <p class="text-sm font-bold text-gray-500 mb-10 leading-relaxed px-6">
                Apakah Anda yakin ingin menghapus <span id="doctorName" class="text-red-600"></span>? Data jadwal dan riwayat akan ikut terdampak.
            </p>
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-4 border border-gray-200 rounded-3xl text-gray-500 font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition active:scale-95">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-4 bg-red-600 text-white rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition active:scale-95 shadow-xl shadow-red-200">
                        Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id, name) {
        document.getElementById('doctorName').innerText = name;
        document.getElementById('deleteForm').action = `/dashboard/admin/doctors/${id}`;
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-90', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        content.classList.add('scale-90', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection