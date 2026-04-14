@extends('layouts.admin')

@section('title', 'Clinic Quotas')
@section('page-title', 'Clinic Quotas')

@section('content')
<div class="flex flex-col gap-8">
    {{-- Header & Logic --}}
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-pink-50 flex flex-wrap items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Queue & Quota Control</h2>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Atur kapasitas harian unit layanan kesehatan</p>
        </div>
        
        <form action="{{ route('admin.clinic-quotas.index') }}" method="GET" class="flex items-center gap-3 bg-gray-50 p-2 rounded-2xl border border-gray-100">
            <span class="material-symbols-outlined text-gray-400 ml-2">calendar_month</span>
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" 
                   class="bg-transparent border-none text-xs font-black text-gray-700 focus:ring-0 outline-none cursor-pointer">
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in">
        <span class="material-symbols-outlined">check_circle</span>
        <span class="text-xs font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Quota Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($quotas as $q)
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 relative group flex flex-col h-full hover:border-pink-200 transition-all duration-500">
            {{-- Status Badge --}}
            <div class="absolute top-8 right-8">
                @if($q['is_custom'])
                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-100">Customized</span>
                @else
                    <span class="bg-gray-50 text-gray-400 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-100">Default</span>
                @endif
            </div>

            {{-- Icon & Info --}}
            <div class="w-14 h-14 rounded-3xl bg-gray-50 text-gray-400 flex items-center justify-center mb-6 group-hover:bg-pink-50 group-hover:text-pink-500 transition-colors duration-500">
                <i class="{{ $q['icon'] ?? 'fa-solid fa-hospital' }} text-xl"></i>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-black text-gray-900 mb-1">{{ $q['nama'] }}</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $q['kode'] }}</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Default</p>
                    <p class="text-lg font-black text-gray-900">{{ $q['default_quota'] }}</p>
                </div>
                <div class="bg-pink-50 rounded-2xl p-4 border border-pink-100">
                    <p class="text-[9px] font-black text-pink-500 uppercase mb-1">Today</p>
                    <p class="text-lg font-black text-pink-600">{{ $q['current_quota'] }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-auto pt-6 border-t border-gray-50 flex items-center gap-3">
                <button onclick="openQuotaModal('{{ $q['id'] }}', '{{ $q['nama'] }}', '{{ $q['current_quota'] }}')" 
                        class="flex-1 bg-gray-900 text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition shadow-lg shadow-gray-200">
                    Update Quota
                </button>
                @if($q['is_custom'])
                <form action="{{ route('admin.clinic-quotas.reset', $q['id']) }}" method="POST" onsubmit="return confirm('Kembalikan ke kuota default?')">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="w-12 h-12 rounded-2xl border-2 border-gray-100 flex items-center justify-center text-gray-400 hover:border-pink-200 hover:text-pink-500 transition-all">
                        <span class="material-symbols-outlined text-[20px]">history</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Update Modal --}}
    <div id="quota-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-[3rem] w-full max-w-md overflow-hidden shadow-2xl animate-pop-in">
            <div class="bg-gray-900 p-8 text-white relative">
                <button onclick="closeQuotaModal()" class="absolute top-6 right-6 text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <h3 class="text-xl font-black mb-1">Update Daily Quota</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest" id="modal-clinic-name">Poliklinik</p>
            </div>
            
            <form id="quota-form" method="POST" action="" class="p-8">
                @csrf
                @method('PUT')
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="mb-8">
                    <label class="text-[10px] font-black text-pink-500 uppercase tracking-widest mb-3 block">Jumlah Kuota Pasien</label>
                    <div class="relative">
                        <input type="number" name="kuota" id="modal-input-quota" required min="1"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-lg font-black text-gray-900 focus:border-pink-500 focus:ring-0 transition outline-none">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">Pasien</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-[#d81b60] text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-pink-200 hover:scale-[1.02] transition">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeQuotaModal()" class="w-full py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openQuotaModal(id, name, current) {
        const modal = document.getElementById('quota-modal');
        const form = document.getElementById('quota-form');
        const clinicName = document.getElementById('modal-clinic-name');
        const inputQuota = document.getElementById('modal-input-quota');
        
        form.action = `/dashboard/admin/clinic-quotas/${id}`;
        clinicName.innerText = name;
        inputQuota.value = current;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQuotaModal() {
        const modal = document.getElementById('quota-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pop-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    .animate-pop-in { animation: pop-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
</style>
@endsection
