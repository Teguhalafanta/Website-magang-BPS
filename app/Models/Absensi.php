<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'nama_mahasiswa',
        'status',
        'jam_masuk',
        'jam_keluar',
        'tanggal',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
