<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nama',
        'nim',
        'telpon',
        'alamat'
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
