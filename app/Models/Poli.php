<?php
// app/Models/Poli.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'polis';
    
    protected $fillable = [
        'nama_poli', 'kode_poli', 'deskripsi', 'is_active', 'kuota_harian_default', 'icon'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kuota_harian_default' => 'integer',
    ];

    public function jadwalDokter()
    {
        return $this->hasMany(JadwalDokter::class);
    }

    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'poli_id');
    }

    public function kuotaHarian()
    {
        return $this->hasMany(KuotaHarianPoli::class);
    }

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function laporanKunjungan()
    {
        return $this->hasMany(LaporanKunjungan::class);
    }

    // Get active jadwal
    public function jadwalAktif()
    {
        return $this->jadwalDokter()->where('is_active', true);
    }
    
    // Get kuota hari ini
    public function kuotaHariIni()
    {
        return $this->kuotaHarian()
            ->whereDate('tanggal', today())
            ->first();
    }
    
    // Cek sisa kuota hari ini
    public function sisaKuotaHariIni()
    {
        $kuota = $this->kuotaHariIni();
        
        if (!$kuota) {
            return $this->kuota_harian_default ?? 0;
        }
        
        return $kuota->sisaKuota();
    }
    
    // Cek apakah masih ada kuota
    public function hasKuotaHariIni()
    {
        return $this->sisaKuotaHariIni() > 0;
    }
}