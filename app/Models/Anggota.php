<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = ['pkm_id', 'penelitian_id', 'nama', 'prodi'];
    protected $table = 'anggota';
    public function pkm()
    {
        return $this->belongsTo(Pkm::class);
    }

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class);
    }
}
