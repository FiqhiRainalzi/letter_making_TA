<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ketpub extends Model
{
    use HasFactory;
    protected $table = 'ketpub';
    protected $fillable = [
        'judul',
        'ajuan_surat_id',
        'user_id',
        'nomorSurat',
        'kategori_publikasi',
        'namaPenerbit',
        'penerbit',
        'jilid',
        'edisi',
        'bulan',
        'issn',
        'tahun',
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
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
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
