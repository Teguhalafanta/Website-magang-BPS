<?php
// app/Models/Mahasiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim'); // relasi ke User berdasarkan NIM
    }
}
