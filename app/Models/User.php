<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nomor_telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ajuanSurats()
    {
        return $this->hasMany(AjuanSurat::class);
    }

    public function riwayats()
    {
        return $this->hasMany(Riwayat::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function ketua()
    {
        return $this->hasOne(Ketua::class);
    }
    public function petugas()
    {
        return $this->hasOne(Petugas::class);
    }
}
