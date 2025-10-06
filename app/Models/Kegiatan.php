<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';

    protected $fillable = [
        'user_id',
        'tanggal',
        'nama_kegiatan',
        'deskripsi',
        'volume',
        'satuan',
        'durasi',
        'pemberi_tugas',
        'tim_kerja',
        'status_penyelesaian',
        'bukti_dukung'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'durasi' => 'integer',
    ];

    /**
     * Relasi ke model Pelajar
     */
    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id', 'id');
    }

    /**
     * Accessor untuk format tanggal Indonesia
     */
    public function getTanggalFormattedAttribute()
    {
        return Carbon::parse($this->tanggal)->format('d-m-Y');
    }

    /**
     * Accessor untuk format tanggal lengkap
     */
    public function getTanggalLengkapAttribute()
    {
        return Carbon::parse($this->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    /**
     * Accessor untuk durasi dalam format jam dan menit
     */
    public function getDurasiFormattedAttribute()
    {
        if (!$this->durasi) return '-';

        $jam = floor($this->durasi / 60);
        $menit = $this->durasi % 60;

        if ($jam > 0 && $menit > 0) {
            return "{$jam} jam {$menit} menit";
        } elseif ($jam > 0) {
            return "{$jam} jam";
        } else {
            return "{$menit} menit";
        }
    }

    /**
     * Scope untuk kegiatan hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    /**
     * Scope untuk kegiatan bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }

    /**
     * Scope untuk kegiatan berdasarkan pelajar
     */
    public function scopeByPelajar($query, $pelajarId)
    {
        return $query->where('pelajar_id', $pelajarId);
    }

    /**
     * Scope untuk kegiatan berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_penyelesaian', $status);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
