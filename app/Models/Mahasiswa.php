<?php
// app/Models/Mahasiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // pastikan sesuai dengan nama tabel di database
    protected $table = 'mahasiswas';

    protected $fillable = [
        'nama',
        'nim',
        'telepon',  // konsisten: di migration juga harus 'telepon', bukan 'telpon'
        'alamat',
    ];

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
        return $this->belongsTo(User::class, 'nim', 'nim');
    }
}
