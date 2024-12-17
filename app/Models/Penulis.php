<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $fillable = ['ketpub_id', 'tugaspub_id', 'nama', 'jurusan_prodi'];

    public function ketpub()
    {
        return $this->belongsTo(Ketpub::class);
    }

    public function tugaspub()
    {
        return $this->belongsTo(Tugaspub::class);
    }
}
