<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use HasFactory;

    protected $table = 'hki';

    protected $fillable = [
        'ajuan_surat_id',
        'user_id',
        'kode_surat_id',
        'namaPemegang',
        'alamat',
        'judul',
        'tanggal',
        'nomorSurat',
    ];
    // Relasi ke tabel inventor
    public function inventor()
    {
        return $this->hasMany(Inventor::class);
    }

    public function ajuanSurat()
    {
        return $this->belongsTo(AjuanSurat::class);
    }

    // Lewat relasi ini kamu juga bisa akses user:
    public function user()
    {
        return $this->ajuanSurat->user();
    }
    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }
}
