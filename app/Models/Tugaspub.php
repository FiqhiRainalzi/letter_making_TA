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
        'user_id',
        'statusSurat',
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
