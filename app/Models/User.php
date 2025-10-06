<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property \App\Models\Pembimbing $pembimbing
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pelajar()
    {
        return $this->hasOne(Pelajar::class, 'user_id', 'id');
    }


    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function pelajarsBimbingan()
    {
        return $this->hasMany(Pelajar::class, 'pembimbing_id');
    }

    public function pembimbing()
    {
        return $this->hasOne(Pembimbing::class);
    }
}
