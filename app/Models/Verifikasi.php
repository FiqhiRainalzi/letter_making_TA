<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;
    protected $table = 'verifikasi_surat';
    protected $fillable = ['petugas_id', 'ajuan_surat_id', 'catatan'];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function ajuanSurat()
    {
        return $this->belongsTo(AjuanSurat::class);
    }
}
