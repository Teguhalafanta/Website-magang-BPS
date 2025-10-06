<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajar extends Model
{
    use HasFactory;

    protected $table = 'pelajars';   // nama tabel
    protected $primaryKey = 'id'; // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',   // 
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'email',
        'nim_nisn',
        'asal_institusi',
        'fakultas',
        'jurusan',
        'rencana_mulai',
        'rencana_selesai',
        'proposal',
        'surat_pengajuan',
        'status',
        'alasan',
    ];

    // relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // relasi ke Absensi
    public function absensis()
    {
        return $this->hasMany(Presensi::class, 'pelajar_id', 'id');
    }

    // accessor untuk format tanggal lahir
    public function getTanggalLahirFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal_lahir)->translatedFormat('d F Y');
    }

    //relasi ke pembimbing
    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }

    // Relasi ke presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
