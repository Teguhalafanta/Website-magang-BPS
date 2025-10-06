<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;

    protected $table = 'pembimbings';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'jabatan',
        'instansi',
        'no_telp',
        'email',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke pelajar (opsional, jika ingin melihat pelajar yang dibimbing)
    public function pelajars()
    {
        return $this->hasMany(Pelajar::class);
    }
}
