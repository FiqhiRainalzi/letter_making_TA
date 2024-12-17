<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventor extends Model
{
    use HasFactory;

    protected $fillable = ['hki_id', 'nama', 'bidang_studi'];

    public function hki()
    {
        return $this->belongsTo(Hki::class);
    }
}
