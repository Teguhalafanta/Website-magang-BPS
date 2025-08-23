<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'deskripsi',
        'jam_mulai',
        'jam_selesai',
    ];

    /**
     * Relasi ke tabel mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class);
    }
}
