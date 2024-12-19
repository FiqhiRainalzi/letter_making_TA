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
        'user_id',
        'nomorSurat',
        'kategori_publikasi',
        'statusSurat',
        'namaPenerbit',
        'penerbit',
        'jilid',
        'edisi',
        'bulan',
        'issn',
        'tahun',
        'tanggal',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function penulis()
    {
        return $this->hasMany(Penulis::class);
    }
}
