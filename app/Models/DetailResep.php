<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;

    protected $fillable = [
        'resep_id', 'nama_obat', 'dosis', 'jumlah', 'aturan_pakai', 
        'keterangan', 'is_checked', 'jenis', 'stok_tersedia'
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class);
    }

    public function getStokStatusAttribute(): string
    {
        if ($this->stok_tersedia === null) return 'unknown';
        if ($this->stok_tersedia <= 0) return 'habis';
        if ($this->stok_tersedia < $this->jumlah) return 'kurang';
        return 'ok';
    }
}