<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nik', 'phone', 'address', 'birth_date', 'photo',
        'golongan_darah', 'gender',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'user_id');
    }

    // Relasi sebagai Dokter
    public function jadwalDokter()
    {
        return $this->hasMany(JadwalDokter::class, 'dokter_id');
    }

    public function antrianSebagaiDokter()
    {
        return $this->hasMany(Antrian::class, 'dokter_id');
    }

    public function resepSebagaiDokter()
    {
        return $this->hasMany(Resep::class, 'dokter_id');
    }

    // Relasi sebagai Pasien
    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'pasien_id');
    }

    public function resep()
    {
        return $this->hasMany(Resep::class, 'pasien_id');
    }

    // Relasi Notifikasi
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isApoteker()
    {
        return $this->role === 'apoteker';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }
}