<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukMagang extends Model
{
    protected $table = 'produk_magang';

    protected $fillable = [
        'pelajar_id',
        'nama_produk',
        'deskripsi',
        'file_produk'
    ];

    public function pelajar()
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id');
    }
}
