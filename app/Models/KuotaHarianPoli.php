<?php
// app/Models/KuotaHarianPoli.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KuotaHarianPoli extends Model
{
    use HasFactory;

    protected $table = 'kuota_harian_polis';
    
    protected $fillable = [
        'poli_id', 'tanggal', 'kuota', 'terpakai'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    // Cek sisa kuota
    public function sisaKuota()
    {
        return $this->kuota - $this->terpakai;
    }

    // Increment terpakai
    public function incrementTerpakai()
    {
        $this->increment('terpakai');
    }
    
    // Decrement terpakai (jika batal)
    public function decrementTerpakai()
    {
        if ($this->terpakai > 0) {
            $this->decrement('terpakai');
        }
    }
    
    // Reset kuota untuk poli tertentu
    public static function resetKuotaForPoli($poliId, $tanggal = null)
    {
        $tanggal = $tanggal ?? Carbon::today();
        $poli = Poli::find($poliId);
        
        if (!$poli) {
            return false;
        }
        
        $kuota = self::where('poli_id', $poliId)
            ->whereDate('tanggal', $tanggal)
            ->first();
        
        if ($kuota) {
            $kuota->update([
                'kuota' => $poli->kuota_harian_default ?? 20,
                'terpakai' => 0
            ]);
        } else {
            self::create([
                'poli_id' => $poliId,
                'tanggal' => $tanggal,
                'kuota' => $poli->kuota_harian_default ?? 20,
                'terpakai' => 0
            ]);
        }
        
        return true;
    }
    
    // Reset semua poli untuk tanggal tertentu
    public static function resetAllKuota($tanggal = null)
    {
        $tanggal = $tanggal ?? Carbon::today();
        $polis = Poli::where('is_active', true)->get();
        
        $resetCount = 0;
        foreach ($polis as $poli) {
            self::resetKuotaForPoli($poli->id, $tanggal);
            $resetCount++;
        }
        
        return $resetCount;
    }
    
    // Generate kuota untuk poli tertentu di tanggal tertentu
    public static function generateKuota(Poli $poli, $tanggal)
    {
        return self::updateOrCreate(
            [
                'poli_id' => $poli->id,
                'tanggal' => $tanggal
            ],
            [
                'kuota' => $poli->kuota_harian_default ?? 20,
                'terpakai' => 0
            ]
        );
    }
}