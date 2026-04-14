{{--
    Icon Picker for Poli/Clinic
    Usage: @include('admin.clinics._icon_picker', ['selected' => old('icon', $clinic->icon ?? 'fa-solid fa-hospital')])
--}}
@php
$poliIcons = [
    'Umum & Dasar' => [
        ['icon' => 'fa-solid fa-hospital',         'label' => 'Rumah Sakit'],
        ['icon' => 'fa-solid fa-house-medical',    'label' => 'Poli Umum'],
        ['icon' => 'fa-solid fa-stethoscope',      'label' => 'Stethoscope'],
        ['icon' => 'fa-solid fa-user-doctor',      'label' => 'Dokter'],
        ['icon' => 'fa-solid fa-kit-medical',      'label' => 'P3K'],
        ['icon' => 'fa-solid fa-heart-pulse',      'label' => 'Jantung'],
    ],
    'Gigi & Mulut' => [
        ['icon' => 'fa-solid fa-tooth',            'label' => 'Gigi'],
        ['icon' => 'fa-solid fa-teeth',            'label' => 'Gigi Lengkap'],
        ['icon' => 'fa-solid fa-teeth-open',       'label' => 'Mulut'],
    ],
    'Anak & Ibu' => [
        ['icon' => 'fa-solid fa-baby',             'label' => 'Bayi'],
        ['icon' => 'fa-solid fa-child',            'label' => 'Anak'],
        ['icon' => 'fa-solid fa-person-pregnant',  'label' => 'Ibu Hamil'],
        ['icon' => 'fa-solid fa-hand-holding-medical', 'label' => 'KIA'],
    ],
    'Spesialis' => [
        ['icon' => 'fa-solid fa-eye',              'label' => 'Mata'],
        ['icon' => 'fa-solid fa-ear-listen',       'label' => 'THT'],
        ['icon' => 'fa-solid fa-head-side-cough',  'label' => 'Paru'],
        ['icon' => 'fa-solid fa-brain',            'label' => 'Saraf'],
        ['icon' => 'fa-solid fa-bone',             'label' => 'Ortopedi'],
        ['icon' => 'fa-solid fa-kidneys',          'label' => 'Urologi'],
        ['icon' => 'fa-solid fa-syringe',          'label' => 'Imunisasi'],
    ],
    'Jiwa & Lainnya' => [
        ['icon' => 'fa-solid fa-brain',            'label' => 'Jiwa'],
        ['icon' => 'fa-solid fa-pills',            'label' => 'Farmasi'],
        ['icon' => 'fa-solid fa-vial',             'label' => 'Laboratorium'],
        ['icon' => 'fa-solid fa-x-ray',            'label' => 'Radiologi'],
        ['icon' => 'fa-solid fa-wheelchair',       'label' => 'Rehabilitasi'],
        ['icon' => 'fa-solid fa-dna',              'label' => 'Genetik'],
    ],
];
$selectedIcon = $selected ?? 'fa-solid fa-hospital';
@endphp

<div class="mb-6">
    <label class="block text-xs font-bold text-gray-600 mb-2">
        Ikon Poli <span class="text-gray-400 font-normal">(Font Awesome)</span>
    </label>

    {{-- Preview selected --}}
    <div class="flex items-center gap-3 mb-3 p-3 bg-pink-50 border border-pink-200 rounded-xl">
        <div class="w-12 h-12 rounded-xl bg-[#f06292] flex items-center justify-center">
            <i id="icon-preview" class="{{ $selectedIcon }} text-white text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-700">Preview Ikon</p>
            <p id="icon-name-preview" class="text-[11px] text-gray-500 font-mono">{{ $selectedIcon }}</p>
        </div>
    </div>

    <input type="hidden" name="icon" id="icon-value" value="{{ $selectedIcon }}">

    {{-- Icon grid by category --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        @foreach($poliIcons as $category => $icons)
            <div class="border-b border-gray-100 last:border-b-0">
                <p class="text-[10px] font-bold text-gray-500 uppercase px-3 py-2 bg-gray-50 tracking-widest">{{ $category }}</p>
                <div class="flex flex-wrap gap-2 p-3">
                    @foreach($icons as $item)
                        <button type="button"
                            onclick="selectPoliIcon('{{ $item['icon'] }}')"
                            title="{{ $item['label'] }}"
                            data-icon="{{ $item['icon'] }}"
                            class="icon-option w-10 h-10 rounded-lg border-2 flex items-center justify-center transition hover:bg-pink-50
                                {{ $selectedIcon === $item['icon'] ? 'border-[#f06292] bg-pink-50 text-[#d81b60]' : 'border-gray-200 text-gray-500' }}">
                            <i class="{{ $item['icon'] }}"></i>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <p class="text-xs text-gray-400 mt-1">Pilih ikon yang mewakili jenis poli/klinik ini</p>
</div>

<script>
function selectPoliIcon(iconClass) {
    // Update hidden input
    document.getElementById('icon-value').value = iconClass;
    // Update preview
    const preview = document.getElementById('icon-preview');
    preview.className = iconClass + ' text-white text-xl';
    document.getElementById('icon-name-preview').textContent = iconClass;
    // Update button states
    document.querySelectorAll('.icon-option').forEach(btn => {
        const isSelected = btn.dataset.icon === iconClass;
        btn.classList.toggle('border-[#f06292]', isSelected);
        btn.classList.toggle('bg-pink-50', isSelected);
        btn.classList.toggle('text-[#d81b60]', isSelected);
        btn.classList.toggle('border-gray-200', !isSelected);
        btn.classList.toggle('text-gray-500', !isSelected);
    });
}
</script>
