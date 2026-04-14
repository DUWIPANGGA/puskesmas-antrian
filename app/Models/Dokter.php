<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $fillable = [
        'user_id',
        'poli_id',
        'nip',
        'keahlian',
        'bio',
        'alumni',
        'pengalaman_tahun',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    // Karena user punya relasi ke jadwal, resep, antrian melalui 'dokter_id',
    // untuk sementara biarkan relasi itu ada di User, tapi perlahan kita 
    // bisa update ini. Di sini kita sediakan relasinya. Apabila kita mengubah foreignKey 
    // di tabel lain ke `dokter_id` (merujuk ke tabel dokters).
    public function jadwal()
    {
        return $this->hasMany(JadwalDokter::class, 'dokter_id', 'user_id');
    }
}
