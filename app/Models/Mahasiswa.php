<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $lable = "mahasiswas";
    protected $fillable = ['nama', 'nim', 'telpon', 'alamat'];
}
