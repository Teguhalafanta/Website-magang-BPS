<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;
use App\Models\Kegiatan;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelajar';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'nim', 
        'asal_univ',
        'jurusan',
        'prodi',
        'email',
        'password'
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
