<?php
// app/Models/Mahasiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;
use App\Models\Kegiatan;

class Mahasiswa extends Model
{
    protected $fillable = ['nama', 'nim', 'telepon', 'alamat'];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
