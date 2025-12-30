<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
use App\Models\JadwalPeriksa;
use App\Models\Periksa;

class DaftarPoli extends Model
{
    protected $table = 'daftar_poli';

    protected $fillable = [
        'id_pasien',
        'id_jadwal',
        'keluhan',
        'no_antrian',
    ];

    // 1. Relasi ke Pasien (Menggunakan Model User)
    public function pasien()
    {
        // Pasien dianggap sebagai User yang memiliki role 'pasien'
        return $this->belongsTo(User::class, 'id_pasien', 'id');
    }

    // 2. Relasi ke Jadwal 
    public function jadwalPeriksa()
    {
    
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal', 'id');
    }

    // 3. Relasi ke Periksa 
    public function periksas()
    {
        return $this->hasMany(Periksa::class, 'id_daftar_poli', 'id');
    }
    // 4. Relasi Tunggal (Untuk Riwayat Periksa di sisi Pasien)
    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli', 'id');
    }
}