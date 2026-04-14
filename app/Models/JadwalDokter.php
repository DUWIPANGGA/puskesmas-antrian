<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokter_id', 'poli_id', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    // Cek sisa kuota
    public function sisaKuota($tanggal)
    {
        $terpakai = Antrian::where('jadwal_dokter_id', $this->id)
            ->whereDate('tanggal', $tanggal)
            ->count();
        
        return $this->kuota - $terpakai;
    }
}