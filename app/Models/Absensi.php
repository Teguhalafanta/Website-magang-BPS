<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelajar_id',
        'tanggal',
        'status',
        'keterangan',
        'nama_pelajar'
    ];

    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id', 'id_pelajar');
    }
}
