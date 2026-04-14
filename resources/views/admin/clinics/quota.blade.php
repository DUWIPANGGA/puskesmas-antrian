@extends('layouts.admin')

@section('title', 'Manajemen Kuota - ' . $clinic->nama_poli)
@section('page-title', 'Manajemen Kuota')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-pink-50">
        <div class="mb-6">
            <a href="{{ route('admin.clinics.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Klinik
            </a>
        </div>
        
        <div class="flex justify-between items-start mb-6 flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-black text-gray-900 mb-1">Manajemen Kuota</h2>
                <p class="text-xs font-medium text-gray-500">
                    Klinik: <span class="text-gray-900 font-bold">{{ $clinic->nama_poli }}</span> 
                    ({{ $clinic->kode_poli }})
                </p>
            </div>
            <div class="flex gap-2">
                <button type="button" 
                        onclick="resetAllQuota()"
                        class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-yellow-600 transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    Reset Semua
                </button>
            </div>
        </div>
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Hari</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Kuota</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Terpakai</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Sisa</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="text-left py-3 px-3 text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dates as $item)
                        @php
                            $date = $item['date'];
                            $kuota = $item['kuota'];
                            $sisa = $item['sisa'];
                            $isPast = $item['is_past'];
                            $persentase = $kuota->kuota > 0 ? ($kuota->terpakai / $kuota->kuota) * 100 : 0;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition {{ $isPast ? 'bg-gray-50 opacity-75' : '' }}">
                            <td class="py-3 px-3">
                                <span class="text-sm font-medium text-gray-900">{{ $date->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="text-xs text-gray-500">
                                    {{ $date->translatedFormat('l') }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                @if(!$isPast)
                                    <div class="flex items-center gap-2">
                                        <span id="kuota-value-{{ $date->format('Ymd') }}" class="text-sm font-bold text-blue-600">{{ $kuota->kuota }}</span>
                                        <button type="button" 
                                                onclick="editKuota('{{ $date->format('Y-m-d') }}', {{ $kuota->kuota }})"
                                                class="text-blue-600 hover:text-blue-800">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">{{ $kuota->kuota }}</span>
                                @endif
                             </td>
                            <td class="py-3 px-3">
                                <span class="text-sm {{ $kuota->terpakai > 0 ? 'text-orange-600 font-bold' : 'text-gray-600' }}">
                                    {{ $kuota->terpakai }}
                                </span>
                             </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold {{ $sisa > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $sisa }}
                                    </span>
                                    <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-pink-500 rounded-full h-1.5" style="width: {{ $persentase }}%"></div>
                                    </div>
                                </div>
                             </td>
                            <td class="py-3 px-3">
                                @if($isPast)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-xs">Lewat</span>
                                @elseif($sisa > 0)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Tersedia</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs">Penuh</span>
                                @endif
                             </td>
                            <td class="py-3 px-3">
                                @if(!$isPast)
                                    <button type="button" 
                                            onclick="resetQuota('{{ $date->format('Y-m-d') }}')"
                                            class="text-yellow-600 hover:text-yellow-800 p-1"
                                            title="Reset kuota ke default">
                                        <span class="material-symbols-outlined text-[16px]">refresh</span>
                                    </button>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                             </td>
                        </tr>
                    @endforeach
                </tbody>
             </table>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-2 items-start">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">info</span>
                    <div class="text-xs text-blue-700">
                        <p class="font-bold mb-1">Informasi:</p>
                        <p>- Kuota dapat diubah untuk tanggal tertentu sesuai kebutuhan</p>
                        <p>- Reset kuota akan mengembalikan ke nilai default klinik ({{ $clinic->kuota_harian_default }})</p>
                        <p>- Kuota untuk tanggal yang sudah lewat tidak dapat diubah</p>
                        <p>- Perubahan kuota akan langsung berlaku untuk pendaftaran antrian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kuota -->
<div id="editQuotaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-blue-600 text-3xl">numbers</span>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-2">Edit Kuota Harian</h3>
            <p class="text-sm text-gray-600">
                Tanggal: <span id="editTanggal" class="font-bold text-gray-900"></span>
            </p>
        </div>
        
        <form id="editKuotaForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 mb-2">
                    Jumlah Kuota
                </label>
                <input type="number" 
                       name="kuota" 
                       id="editKuotaValue" 
                       required 
                       min="1" 
                       max="500"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            </div>
            
            <input type="hidden" name="tanggal" id="editTanggalInput">
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-2 rounded-full text-sm font-bold hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
                <button type="button" 
                        onclick="closeEditModal()"
                        class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-full text-sm font-bold hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reset Kuota -->
<div id="resetQuotaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-yellow-600 text-3xl">refresh</span>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-2">Reset Kuota</h3>
            <p class="text-sm text-gray-600 mb-4">
                Apakah Anda yakin ingin mereset kuota untuk tanggal <span id="resetTanggalText" class="font-bold text-gray-900"></span>?
                <br>Kuota akan dikembalikan ke nilai default: <span id="defaultKuota" class="font-bold text-blue-600">{{ $clinic->kuota_harian_default }}</span>
            </p>
            <form id="resetQuotaForm" method="POST">
                @csrf
                <input type="hidden" name="tanggal" id="resetTanggalInput">
                <button type="submit" 
                        class="w-full bg-yellow-500 text-white py-2 rounded-xl font-bold text-sm hover:bg-yellow-600 transition">
                    Ya, Reset Kuota
                </button>
                <button type="button" 
                        onclick="closeResetModal()"
                        class="w-full mt-2 border border-gray-300 text-gray-700 py-2 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                    Batal
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editKuota(tanggal, currentKuota) {
        document.getElementById('editTanggal').innerText = tanggal;
        document.getElementById('editTanggalInput').value = tanggal;
        document.getElementById('editKuotaValue').value = currentKuota;
        document.getElementById('editKuotaForm').action = `/dashboard/admin/clinics/{{ $clinic->id }}/update-quota`;
        document.getElementById('editQuotaModal').classList.remove('hidden');
        document.getElementById('editQuotaModal').classList.add('flex');
    }
    
    function closeEditModal() {
        document.getElementById('editQuotaModal').classList.add('hidden');
        document.getElementById('editQuotaModal').classList.remove('flex');
    }
    
    function resetQuota(tanggal) {
        document.getElementById('resetTanggalText').innerText = tanggal;
        document.getElementById('resetTanggalInput').value = tanggal;
        document.getElementById('resetQuotaForm').action = `/dashboard/admin/clinics/{{ $clinic->id }}/reset-quota-by-date`;
        document.getElementById('resetQuotaModal').classList.remove('hidden');
        document.getElementById('resetQuotaModal').classList.add('flex');
    }
    
    function closeResetModal() {
        document.getElementById('resetQuotaModal').classList.add('hidden');
        document.getElementById('resetQuotaModal').classList.remove('flex');
    }
    
    function resetAllQuota() {
        if(confirm('Reset semua kuota untuk 7 hari ke depan ke nilai default ({{ $clinic->kuota_harian_default }})?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dashboard/admin/clinics/{{ $clinic->id }}/reset-quota`;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('editQuotaModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
    
    document.getElementById('resetQuotaModal').addEventListener('click', function(e) {
        if (e.target === this) closeResetModal();
    });
</script>
@endpush
@endsection