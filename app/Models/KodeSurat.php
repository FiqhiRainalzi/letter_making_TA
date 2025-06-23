<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeSurat extends Model
{
    use HasFactory;

    protected $table = 'kode_surat';

    protected $fillable = ['kode_instansi', 'kode_layanan'];

    // Relasi ke semua jenis surat
    public function hki()
    {
        return $this->hasMany(Hki::class);
    }
    public function penelitian()
    {
        return $this->hasMany(Penelitian::class);
    }
    public function pkm()
    {
        return $this->hasMany(Pkm::class);
    }
    public function tugaspub()
    {
        return $this->hasMany(TugasPub::class);
    }
    public function ketpub()
    {
        return $this->hasMany(KetPub::class);
    }
}
