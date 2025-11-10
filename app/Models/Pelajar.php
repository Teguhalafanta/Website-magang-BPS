<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pelajar extends Model
{
    use HasFactory;

    protected $table = 'pelajars';   // nama tabel
    protected $primaryKey = 'id'; // primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'pembimbing_id',
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
        'surat_penerimaan',
        'status',
        'alasan',
    ];

    // relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // relasi ke Presensi
    public function presensis()
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
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }


    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'pelajar_id');
    }

    // Accessor: menghitung jumlah hari aktif magang
    public function getHariAktifMagangAttribute()
    {
        if ($this->status !== 'disetujui' || !$this->rencana_mulai) {
            return 0;
        }

        $mulai = Carbon::parse($this->rencana_mulai);
        $akhir = $this->status_magang === 'selesai' 
            ? Carbon::parse($this->rencana_selesai)
            : Carbon::today();

        if ($mulai->gt($akhir)) {
            return 0;
        }

        return $mulai->diffInDays($akhir) + 1;
    }

    // Accessor: status magang otomatis untuk tampilan
    public function getStatusMagangOtomatisAttribute()
    {
        $status = $this->status ?? 'belum ditentukan';

        // ubah 'menunggu' jadi 'diajukan'
        if ($status === 'menunggu') {
            $status = 'diajukan';
        }

        // hanya ubah ke aktif/selesai jika admin sudah menyetujui
        if ($status === 'disetujui') {
            $today = Carbon::today();
            $mulai = $this->rencana_mulai ? Carbon::parse($this->rencana_mulai) : null;
            $selesai = $this->rencana_selesai ? Carbon::parse($this->rencana_selesai) : null;

            if ($mulai && $today->gte($mulai) && (!$selesai || $today->lte($selesai))) {
                $status = 'aktif';
            }

            if ($selesai && $today->gt($selesai)) {
                $status = 'selesai';
            }
        }

        return $status;
    }

    // Accessor: badge class otomatis
    public function getBadgeClassAttribute()
    {
        $status = $this->statusMagangOtomatis;

        return [
            'diajukan' => 'bg-warning-subtle text-warning fw-semibold px-3 py-1 rounded-pill',
            'disetujui' => 'bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill',
            'aktif' => 'bg-success-subtle text-success fw-semibold px-3 py-1 rounded-pill',
            'selesai' => 'bg-info-subtle text-info fw-semibold px-3 py-1 rounded-pill',
            'ditolak' => 'bg-danger-subtle text-danger fw-semibold px-3 py-1 rounded-pill',
            'belum ditentukan' => 'bg-secondary-subtle text-secondary fw-semibold px-3 py-1 rounded-pill',
        ][$status] ?? 'bg-secondary-subtle text-secondary fw-semibold px-3 py-1 rounded-pill';
    }
}
