<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'pelajar_id',
        'tanggal',
        'status',
        'keterangan',
        'shift',
    ];

    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id', 'id');
=======
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
>>>>>>> a36833fb672c39bf3ab77ca99d1e51fea78edddd
    }
}
