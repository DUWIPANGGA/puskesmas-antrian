<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_antrian', 'pasien_id', 'poli_id', 'keluhan', 'jadwal_dokter_id',
        'tanggal', 'nomor_urut', 'status', 'check_in_at', 
        'dipanggil_at', 'selesai_at'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'check_in_at' => 'datetime',
        'dipanggil_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function jadwalDokter()
    {
        return $this->belongsTo(JadwalDokter::class);
    }

    public function resep()
    {
        return $this->hasOne(Resep::class);
    }

    public function laporanKunjungan()
    {
        return $this->hasOne(LaporanKunjungan::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Scope untuk antrian hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper methods
    public function checkIn()
    {
        $this->update([
            'status' => 'check_in',
            'check_in_at' => now()
        ]);
    }

    public function panggilAdmin()
    {
        $this->update([
            'status' => 'dipanggil',
            'dipanggil_at' => now()
        ]);
    }

    public function siapPemeriksaan()
    {
        $this->update([
            'status' => 'siap_pemeriksaan',
            'updated_at' => now()
        ]);
    }

    public function panggilDokter()
    {
        $this->update([
            'status' => 'dipanggil_dokter',
            'dipanggil_at' => now()
        ]);
    }

    public function panggil() // Alias for backward compatibility (defaults to Admin)
    {
        $this->panggilAdmin();
    }

    public function selesai()
    {
        $this->update([
            'status' => 'selesai',
            'selesai_at' => now()
        ]);
    }
}