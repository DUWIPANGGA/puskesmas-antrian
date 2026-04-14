<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Etiket Obat - {{ $resep->nomor_resep }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; background: white; }
        .etiket { width: 9cm; padding: 0.6cm; border: 2px solid #000; margin: 0.5cm auto; }
        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 6px; margin-bottom: 6px; }
        .header h1 { font-size: 11pt; font-weight: bold; }
        .header p { font-size: 8pt; }
        .rx-no { font-size: 8pt; color: #555; text-align: right; }
        .patient { font-size: 9pt; margin: 6px 0; }
        .patient strong { font-size: 10pt; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .obat { margin: 4px 0; }
        .obat .nama { font-size: 10pt; font-weight: bold; }
        .obat .detail { font-size: 8pt; color: #333; }
        .instruksi { font-size: 9pt; font-weight: bold; background: #f0f0f0; padding: 4px 6px; margin: 4px 0; border-radius: 3px; }
        .footer { font-size: 7pt; color: #555; text-align: center; margin-top: 8px; border-top: 1px solid #000; padding-top: 6px; }
        @media print {
            body { background: white; }
            .etiket { margin: 0; border: 2px solid #000; }
        }
    </style>
</head>
<body>
    @forelse($resep->detailResep as $item)
    <div class="etiket">
        <div class="header">
            <h1>PUSKESMAS JAGAPURA</h1>
            <p>Jl. Kesehatan No. 1 | Telp. (0xx) xxx-xxxx</p>
        </div>
        <div class="rx-no">Rx: {{ $resep->nomor_resep ?? '#' . $resep->id }} | {{ now()->format('d/m/Y') }}</div>

        <div class="patient">
            <strong>{{ $resep->pasien->name ?? 'Pasien' }}</strong><br>
            Dr. {{ $resep->dokter->name ?? '-' }} | {{ $resep->antrian->poli->nama_poli ?? '' }}
        </div>

        <div class="divider"></div>

        <div class="obat">
            <div class="nama">{{ $item->nama_obat }}</div>
            <div class="detail">{{ $item->dosis }} — Qty: {{ $item->jumlah }}</div>
        </div>

        <div class="instruksi">{{ $item->aturan_pakai }}</div>

        @if($item->keterangan)
        <div class="detail" style="font-size:8pt; color:#555;">Ket: {{ $item->keterangan }}</div>
        @endif

        @if($resep->catatan_apoteker)
        <div class="divider"></div>
        <div class="detail" style="font-size:8pt;">Catatan: {{ $resep->catatan_apoteker }}</div>
        @endif

        <div class="footer">
            Simpan di tempat sejuk & kering. Habiskan sesuai anjuran dokter.
        </div>
    </div>
    @empty
    {{-- Fallback for legacy data --}}
    <div class="etiket">
        <div class="header">
            <h1>PUSKESMAS JAGAPURA</h1>
            <p>Jl. Kesehatan No. 1 | Telp. (0xx) xxx-xxxx</p>
        </div>
        <div class="rx-no">Rx: {{ $resep->nomor_resep ?? '#' . $resep->id }} | {{ now()->format('d/m/Y') }}</div>

        <div class="patient">
            <strong>{{ $resep->pasien->name ?? 'Pasien' }}</strong><br>
            Dr. {{ $resep->dokter->name ?? '-' }} | {{ $resep->antrian->poli->nama_poli ?? '' }}
        </div>

        <div class="divider"></div>

        <div class="obat">
            <div class="nama">Daftar Obat (Legacy Data):</div>
            <div class="detail" style="white-space: pre-line; margin-top: 5px;">{{ $resep->obat }}</div>
        </div>

        @if($resep->catatan_apoteker)
        <div class="divider"></div>
        <div class="detail" style="font-size:8pt;">Catatan: {{ $resep->catatan_apoteker }}</div>
        @endif

        <div class="footer">
            Simpan di tempat sejuk & kering. Habiskan sesuai anjuran dokter.
        </div>
    </div>
    @endforelse

    <script>window.onload = function() { window.print(); };</script>
</body>
</html>
