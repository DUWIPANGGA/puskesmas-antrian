<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_resep', 'antrian_id', 'dokter_id', 'pasien_id', 'apoteker_id', 'poli_id',
        'diagnosa', 'catatan', 'catatan_apoteker', 'status', 
        'diproses_at', 'selesai_at', 'diambil_at', 'obat'
    ];

    protected $casts = [
        'diproses_at' => 'datetime',
        'selesai_at'  => 'datetime',
        'diambil_at'  => 'datetime',
    ];

    // ---- Relationships ----
    public function antrian()    { return $this->belongsTo(Antrian::class); }
    public function dokter()     { return $this->belongsTo(User::class, 'dokter_id'); }
    public function pasien()     { return $this->belongsTo(User::class, 'pasien_id'); }
    public function apoteker()   { return $this->belongsTo(User::class, 'apoteker_id'); }
    public function poli()       { return $this->belongsTo(Poli::class); }
    public function detailResep(){ return $this->hasMany(DetailResep::class); }

    // ---- Status helpers ----
    public function proses()
    {
        $this->update(['status' => 'diproses', 'diproses_at' => now()]);
    }

    public function selesaikan()
    {
        $this->update(['status' => 'siap_ambil', 'selesai_at' => now()]);
    }

    public function diambil()
    {
        $this->update(['status' => 'diambil', 'diambil_at' => now()]);
    }

    // ---- Progress ----
    public function progressPersen(): int
    {
        $total = $this->detailResep->count();
        if ($total === 0) return 0;
        $done = $this->detailResep->where('is_checked', true)->count();
        return (int) round(($done / $total) * 100);
    }

    // ---- Scopes ----
    public function scopeIncoming($query) 
    { 
        return $query->where('status', 'pending'); 
    }

    public function scopeInProcess($query) 
    { 
        return $query->where('status', 'diproses'); 
    }

    public function scopeCompleted($query) 
    { 
        return $query->whereIn('status', ['siap_ambil', 'diambil']); 
    }

    // ---- Generate nomor resep ----
    public static function generateNomor(): string
    {
        $prefix = 'RX-' . date('Y') . '-';
        $last = self::where('nomor_resep', 'like', $prefix . '%')
            ->orderByDesc('nomor_resep')->first();
        $num = $last ? (int) substr($last->nomor_resep, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}