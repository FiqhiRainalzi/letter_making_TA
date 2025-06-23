<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];
    protected $table = 'prodi';

    // Relasi ke tabel anggota
    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }

    // Relasi ke tabel tenaga_pembantu
    public function tenagaPembantu()
    {
        return $this->hasMany(TenagaPembantu::class);
    }

    // Relasi ke tabel penulis
    public function penulis()
    {
        return $this->hasMany(Penulis::class);
    }

    // Relasi ke tabel penulis
    public function inventor()
    {
        return $this->hasMany(Inventor::class);
    }
}
