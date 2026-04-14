@extends('layouts.pasien')

@section('title', 'Book Appointment')

@section('content')
<div class="max-w-[1000px] mx-auto pb-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-gray-900 leading-tight">Pilih Poli Tujuan</h2>
        <p class="text-[15px] text-[#7c51a1] font-medium mt-3">Silahkan pilih poliklinik yang ingin Anda tuju untuk pengambilan tiket antrian.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
        @foreach($polis as $index => $poli)
            <div class="bg-white rounded-[2.5rem] p-8 border-2 border-pink-50 hover:border-pink-200 transition-all duration-300 shadow-sm hover:shadow-xl group">
                
                <div class="flex justify-between items-start mb-8">
                    <div class="w-16 h-16 rounded-2xl {{ $index % 2 == 0 ? 'bg-[#fce4ec] text-[#d81b60]' : 'bg-[#e1f5fe]/70 text-[#0288d1]' }} flex items-center justify-center shrink-0 shadow-inner">
                        <i class="{{ $poli->icon ?? 'fa-solid fa-hospital' }} text-3xl"></i>
                    </div>
                    <span class="bg-pink-50 text-[#d81b60] text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-pink-100">
                        {{ $poli->is_active ? 'TERSEDIA' : 'TUTUP' }}
                    </span>
                </div>

                <h3 class="text-2xl font-black text-gray-900 mb-6">{{ $poli->nama_poli }}</h3>

                <div class="flex flex-col gap-4 mb-8">
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Antrian Aktif</span>
                        <span class="text-[#d81b60] font-black bg-pink-50 px-3 py-1 rounded-lg border border-pink-100">{{ $poli->current_queue_count }} Pasien</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Sisa Kuota Hari Ini</span>
                        @php
                            $kuota = $poli->kuotaHariIni();
                            $sisa = $kuota ? $kuota->sisaKuota() : ($poli->kuota_harian_default ?? 0);
                        @endphp
                        <span class="text-[#0288d1] font-black bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">{{ $sisa }} Slot</span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="mt-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kapasitas Poli</span>
                            <span class="text-[10px] font-black text-gray-400">{{ round(($poli->current_queue_count / max(1, ($kuota->kuota ?? 20))) * 100) }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            @php
                                $percentage = $kuota ? min(100, ($poli->current_queue_count / max(1, $kuota->kuota)) * 100) : 10;
                            @endphp
                            <div class="h-full {{ $index % 2 == 0 ? 'bg-[#d81b60]' : 'bg-[#0288d1]' }} rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <button onclick="openBookingModal('{{ $poli->id }}', '{{ addslashes($poli->nama_poli) }}')" class="w-full bg-[#d81b60] text-white py-4 rounded-full font-black text-sm hover:bg-[#c2185b] transition shadow-lg shadow-pink-500/20 active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest">
                    Pilih Poli <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </div>
        @endforeach
    </div>
</div>

{{-- SINGLE BOOKING MODAL --}}
<div id="modal-booking" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden relative transform transition-all">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 text-[#d81b60] border border-pink-100 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">local_hospital</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900" id="modal-poli-name">Booking Poli</h3>
                    <p class="text-xs font-medium text-gray-500">Pilih tanggal kunjungan Anda</p>
                </div>
            </div>
            <button type="button" onclick="closeBookingModal()" class="w-10 h-10 rounded-full bg-white hover:bg-rose-50 flex items-center justify-center transition border border-gray-100 text-gray-400 hover:text-rose-500 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('pasien.ambil-tiket') }}" method="POST" class="p-8 flex flex-col gap-6">
            @csrf
            <input type="hidden" name="poli_id" id="modal-poli-id">
            
            {{-- Select Date --}}
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Tanggal Kunjungan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">calendar_month</span>
                    <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" 
                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-12 pr-4 py-4 focus:outline-none focus:ring-4 focus:ring-[#d81b60]/10 focus:border-[#d81b60] text-gray-800 font-bold text-sm transition-all appearance-none cursor-pointer">
                </div>
                <p class="text-[10px] text-gray-400 mt-2 italic font-medium">Layanan buka mulai pukul 08:00 - 15:00 WIB.</p>
            </div>

            <button type="submit" class="w-full mt-2 bg-[#d81b60] text-white py-4.5 rounded-full font-black text-sm hover:bg-[#c2185b] transition shadow-xl shadow-pink-500/30 flex items-center justify-center gap-2 uppercase tracking-[0.15em]">
                Ambil Tiket Antrian <span class="material-symbols-outlined text-xl">confirmation_number</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openBookingModal(poliId, poliName) {
        document.getElementById('modal-poli-id').value = poliId;
        document.getElementById('modal-poli-name').innerText = 'Booking ' + poliName;
        const modal = document.getElementById('modal-booking');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBookingModal() {
        const modal = document.getElementById('modal-booking');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close on overlay click
    document.getElementById('modal-booking').addEventListener('click', function(e) {
        if (e.target === this) closeBookingModal();
    });
</script>
@endpush
@endsection
