<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPeriksa; 
use App\Models\DaftarPoli;    

class Periksa extends Model
{
    use HasFactory;

    protected $table = 'periksa';
    protected $primaryKey = 'id';
    protected $guarded = [];

    // Relasi ke Detail Periksa 
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa', 'id');
    }

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa', 'id');
    }

    // Relasi balik ke Daftar Poli
    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli', 'id');
    }
}