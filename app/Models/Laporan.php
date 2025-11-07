<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file', 'status', 'file_sertifikat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id');
    }
}
