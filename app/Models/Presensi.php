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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pelajar (jika berbeda dari User)
    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class);
    }
}
