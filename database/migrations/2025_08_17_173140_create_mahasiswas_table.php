<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // Jangan set $table kalau kamu pakai default Laravel
    protected $fillable = ['nama', 'nim', 'telepon', 'alamat', 'email', 'tanggal_lahir'];

    // relasi tetap
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

