<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = [
        'pelajar_id',
        'pembimbing_id',
        'nilai',
        'keterangan',
    ];

    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }
}
