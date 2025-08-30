<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajar extends Model
{
    use HasFactory;

    protected $table = 'pelajars';   // tabel di database
    protected $primaryKey = 'id_pelajar'; // primary key

    protected $fillable = [
        'id_user',
        'nama',
        'jenis_kelamin',
        'tempat_tanggal_lahir',
        'alamat',
        'telepon',
        'email',
        'nim_nisn',
        'asal_institusi',
        'fakultas',
        'jurusan',
        'rencana_mulai',
        'rencana_selesai',
    ];

    // relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
