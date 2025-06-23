<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;
    protected $table = 'penelitian';

    protected $fillable = [
        'ajuan_surat_id',
        'nomorSurat',
        'namaKetua',
        'nipNidn',
        'jabatanAkademik',
        'jurusanProdi',
        'judul',
        'skim',
        'dasarPelaksanaan',
        'lokasi',
        'bulanPelaksanaan',
        'bulanAkhirPelaksanaan',
        'tanggal',
        'user_id',
        'kode_surat_id',
    ];

    // Relasi ke tabel anggota
    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }

    public function ajuanSurat()
    {
        return $this->belongsTo(AjuanSurat::class);
    }

    // Relasi ke tabel tenaga_pembantu
    public function tenagaPembantu()
    {
        return $this->hasMany(TenagaPembantu::class);
    }

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }
}
