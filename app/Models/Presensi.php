<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pelajar_id',
        'tanggal',
        'waktu_datang',
        'waktu_pulang',
        'status',
    ];
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pelajar
    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'user_id', 'user_id');
    }
}
