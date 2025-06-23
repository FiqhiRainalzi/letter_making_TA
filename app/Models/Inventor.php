<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventor extends Model
{
    use HasFactory;
    protected $table = 'inventor';
    protected $fillable = ['nama', 'prodi_id', 'hki_id'];

    // Relasi ke tabel hki
    public function hki()
    {
        return $this->belongsTo(hki::class);
    }

    // Relasi ke tabel prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
