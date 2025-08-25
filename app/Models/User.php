<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'username',
        'nama',      
        'asal_univ',  
        'jurusan',    
        'prodi',      
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
