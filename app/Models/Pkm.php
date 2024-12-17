<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pkm extends Model
{
    use HasFactory;
    protected $table = 'pkm';
    protected $fillable = [
        'namaKetua',
        'user_id',
        'nomorSurat',
        'statusSurat',
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
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }

    public function tenagaPembantu()
    {
        return $this->hasMany(TenagaPembantu::class);
    }
}
