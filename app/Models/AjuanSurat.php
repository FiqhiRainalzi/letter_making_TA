<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanSurat extends Model
{
    use HasFactory;

    protected $table = 'ajuan_surat';
    protected $fillable = ['user_id', 'jenis_surat', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class);
    }

    public function hkis()
    {
        return $this->hasOne(Hki::class);
    }

    public function penelitians()
    {
        return $this->hasOne(Penelitian::class);
    }

    public function pkms()
    {
        return $this->hasOne(Pkm::class);
    }

    public function tugaspubs()
    {
        return $this->hasOne(Tugaspub::class);
    }

    public function ketpubs()
    {
        return $this->hasOne(Ketpub::class);
    }

    public function getSurat()
    {
        switch ($this->jenis_surat) {
            case 'tugaspub':
                return $this->tugaspubs;
            case 'hki':
                return $this->hkis;
            case 'penelitian':
                return $this->penelitians;
            case 'ketpub':
                return $this->ketpubs;
            case 'pkm':
                return $this->pkms;
            default:
                return null;
        }
    }
}
