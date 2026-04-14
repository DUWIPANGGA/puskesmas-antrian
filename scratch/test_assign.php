<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Poli;

try {
    $poli = Poli::first();
    $dokter = Dokter::first();
    
    if (!$poli || !$dokter) {
        echo "No poli or dokter found\n";
        exit;
    }
    
    echo "Assigning Dr. {$dokter->user->name} to Poli {$poli->nama_poli}...\n";
    
    $dokter->update(['poli_id' => $poli->id]);
    
    JadwalDokter::updateOrCreate(
        [
            'dokter_id' => $dokter->user_id,
            'hari' => 'senin',
            'poli_id' => $poli->id
        ],
        [
            'jam_mulai' => '08:00',
            'jam_selesai' => '14:00',
            'kuota' => 20,
            'is_active' => true
        ]
    );
    
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
