<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrian_id', 'tanggal', 'poli_id', 'pasien_id', 'dokter_id',
        'waktu_check_in', 'waktu_dipanggil', 'waktu_selesai', 
        'lama_pelayanan', 'status_pelayanan', 'diagnosa', 'catatan',
        'detak_jantung', 'suhu_tubuh', 'tekanan_darah', 'saturasi_oksigen'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_check_in' => 'datetime',
        'waktu_dipanggil' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Scope for date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Scope by poli
    public function scopeByPoli($query, $poliId)
    {
        return $query->where('poli_id', $poliId);
    }
}