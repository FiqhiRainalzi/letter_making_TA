<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';

    protected $fillable = [
        'ajuan_surat_id',
        'user_id',
        'aksi',
        'diubah_oleh',
        'catatan',
        'waktu_perubahan'
    ];

    public function ajuanSurat()
    {
        return $this->belongsTo(AjuanSurat::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
