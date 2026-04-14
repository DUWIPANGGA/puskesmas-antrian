<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Case Report - {{ $visit->nomor_antrian }}</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: white; color: #111; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .header h1 { margin: 0; font-size: 20pt; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 2px 0; font-size: 9pt; color: #555; }
        
        .section-title { font-size: 10pt; font-weight: bold; text-transform: uppercase; background: #f8f9fa; padding: 8px 12px; margin: 25px 0 12px; border-left: 5px solid #d81b60; color: #333; }
        
        .grid { width: 100%; margin-bottom: 5px; }
        .grid-item { display: inline-block; width: 48%; vertical-align: top; margin-bottom: 15px; }
        .label { font-size: 8.5pt; color: #777; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .value { font-size: 10.5pt; font-weight: bold; color: #111; }
        .clearfix::after { content: ""; clear: both; display: table; }

        .vitals-grid { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .vitals-grid td { border: 1px solid #e9ecef; padding: 10px 15px; width: 25%; }
        .vitals-label { font-size: 7.5pt; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; }
        .vitals-val { font-size: 11pt; font-weight: bold; color: #d81b60; }

        .content-box { border: 1px solid #f1f3f5; padding: 15px; border-radius: 8px; font-size: 10.5pt; min-height: 60px; background-color: #fcfcfc; }
        .prescription-list { list-style: none; padding: 0; margin: 0; }
        .prescription-item { border-bottom: 1px dashed #dee2e6; padding: 10px 0; font-weight: 500; font-size: 10.5pt; }
        .prescription-item:last-child { border: none; }
        
        .footer { margin-top: 60px; text-align: right; }
        .signature-box { display: inline-block; width: 220px; text-align: center; }
        .signature-line { border-top: 1.5px solid #333; margin-top: 70px; padding-top: 8px; font-weight: bold; font-size: 11pt; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .section-title { -webkit-print-color-adjust: exact; }
        }
        
        .watermark { position: absolute; top: 40%; left: 15%; font-size: 70pt; color: #f0f0f0; transform: rotate(-45deg); z-index: -1; font-weight: black; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="watermark">MEDICAL RECORD</div>

    <div class="header">
        <h1>Puskesmas Jagapura</h1>
        <p>Jl. Raya Kesehatan No. 7, Jagapura, Jawa Barat</p>
        <p>Telp: (021) 1234-5678 | Email: contact@puskesmas-jagapura.go.id</p>
    </div>

    <div style="text-align: right; font-size: 8.5pt; color: #999; margin-bottom: 10px;">
        <strong>Reference:</strong> {{ $visit->nomor_antrian }} / {{ $visit->tanggal->format('YMD') }} / {{ $visit->id }}
    </div>

    <div class="section-title">Patient Information</div>
    <div class="grid clearfix">
        <div class="grid-item">
            <div class="label">Patient Name</div>
            <div class="value">{{ $visit->pasien->name ?? '-' }}</div>
        </div>
        <div class="grid-item">
            <div class="label">Age / Gender</div>
            <div class="value">{{ \Carbon\Carbon::parse($visit->pasien->birth_date)->age }} Years / {{ $visit->pasien->gender ?? 'N/A' }}</div>
        </div>
        <div class="grid-item">
            <div class="label">Patient Identifier</div>
            <div class="value">PID-#{{ 90000 + $visit->pasien_id }}</div>
        </div>
        <div class="grid-item">
            <div class="label">Clinic Department</div>
            <div class="value">{{ $visit->antrian->poli->nama_poli ?? 'General Clinic' }}</div>
        </div>
    </div>

    <div class="section-title">Vitals and Physical Examination</div>
    <table class="vitals-grid">
        <tr>
            <td>
                <div class="vitals-label">Height / Weight</div>
                <div class="vitals-val">{{ $visit->laporanKunjungan->tinggi_badan ?? '-' }} cm / {{ $visit->laporanKunjungan->berat_badan ?? '-' }} kg</div>
            </td>
            <td>
                <div class="vitals-label">Blood Pressure</div>
                <div class="vitals-val">{{ $visit->laporanKunjungan->tekanan_darah ?? '-' }} mmHg</div>
            </td>
            <td>
                <div class="vitals-label">Heart Rate</div>
                <div class="vitals-val">{{ $visit->laporanKunjungan->detak_jantung ?? '-' }} BPM</div>
            </td>
            <td>
                <div class="vitals-label">Body Temp</div>
                <div class="vitals-val">{{ $visit->laporanKunjungan->suhu_tubuh ?? '-' }} °C</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Clinical Findings & Diagnosis</div>
    <div style="margin-bottom: 15px;">
        <div class="label">Chief Complaint:</div>
        <div style="font-style: italic; color: #495057; font-size: 10pt; margin-top: 5px;">"{{ $visit->keluhan }}"</div>
    </div>

    <div style="margin-bottom: 20px;">
        <div class="label">Primary Diagnosis:</div>
        <div class="value" style="color: #d81b60; font-size: 13pt; margin-top: 5px;">{{ $visit->laporanKunjungan->diagnosa ?? 'General Checkup / No Specific Diagnosis' }}</div>
    </div>

    <div>
        <div class="label">Doctor's Clinical Notes:</div>
        <div class="content-box">
            {!! nl2br(e($visit->laporanKunjungan->catatan ?? 'No additional clinical findings documented.')) !!}
        </div>
    </div>

    <div class="section-title">Digital Prescription List</div>
    @if($visit->resep && $visit->resep->obat)
        <div class="content-box">
            <ul class="prescription-list">
                @foreach(explode("\n", $visit->resep->obat) as $item)
                    @if(trim($item))
                        <li class="prescription-item">{{ $item }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="content-box" style="text-align: center; color: #adb5bd; font-style: italic;">No medications prescribed during this visit.</div>
    @endif

    <div class="footer">
        <div class="signature-box">
            <p style="font-size: 8.5pt; color: #666; margin-bottom: 5px;">Date: {{ now()->format('d/m/Y H:i') }}</p>
            <div class="signature-line">
                Dr. {{ $visit->dokter->name ?? '-' }}<br>
                <div style="font-size: 8pt; font-weight: normal; margin-top: 3px;">NIP/SIP: {{ $visit->dokter->dokter->nip ?? '........................' }}</div>
            </div>
        </div>
    </div>

    <div class="no-print" style="position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%);">
        <button onclick="window.print()" style="background: #1b4353; color: white; border: none; padding: 12px 30px; border-radius: 99px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; box-shadow: 0 10px 20px rgba(27,67,83,0.3); transition: all 0.3s hover:scale-105 active:scale-95;">
            Print / Save as PDF
        </button>
    </div>

    <script>
        window.onload = function() {
            // Auto start print dialog
            // window.print();
        };
    </script>
</body>
</html>
