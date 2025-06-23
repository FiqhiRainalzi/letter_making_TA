<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';
    protected $fillable = ['nama', 'penelitian_id', 'prodi_id', 'pkm_id'];

    // Relasi ke tabel penelitian
    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class);
    }
    // Relasi ke tabel pkm
    public function pkm()
    {
        return $this->belongsTo(Pkm::class);
    }

    // Relasi ke tabel prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
