<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Penelitian extends Model
{
    use HasFactory;
    protected $table = 'penelitian';
    protected $fillable = [
        'namaKetua', 'nipNidn', 'jabatanAkademik', 'jurusanProdi', 'judul', 'skim','statusSurat','nomorSurat', 'dasarPelaksanaan', 'lokasi', 'bulanPelaksanaan', 'bulanAkhirPelaksanaan', 'tanggal','user_id'
    ];

    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }

    public function tenagaPembantu()
    {
        return $this->hasMany(TenagaPembantu::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
