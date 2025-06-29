<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tugaspub extends Model
{
    use HasFactory;
    protected $table = 'tugaspub';
    protected $fillable = [
        'nomorSurat',
        'kategori_jurnal',
        'user_id',
        'namaPublikasi',
        'penerbit',
        'alamat',
        'link',
        'volume',
        'nomor',
        'bulan',
        'akreditas',
        'issn',
        'judul',
        'ajuan_surat_id',
        'tanggal',
        'kode_surat_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ajuanSurat()
    {
        return $this->belongsTo(AjuanSurat::class);
    }

    public function penulis()
    {
        return $this->hasMany(Penulis::class);
    }
    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }
}
